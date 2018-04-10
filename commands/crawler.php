<?php
use GuzzleHttp\Client;


/**
 * 获取当前汇率
 * A:B
 */
function cli_get_currency(){

    //中行的货币代号

    $codes = [
        'BR'=>1314,
        "HK"=>1315,
        "US"=>1316,
        "JP"=>1323,
        "EU"=>1326
    ];

    //代号不符时，默认获取美元
    $params = count(func_get_args()[0]) == 1 && in_array(func_get_args()[0][0], array_keys($codes))? func_get_args()[0] : ['US'];

    $client = new Client([
        'base_uri'=> 'http://srh.bankofchina.com/',
        'timeout'=> 2.0
    ]);

    $currency= $codes[$params[0]];

    $response = $client->request("POST", "search/whpj/search.jsp",[
        'form_params' => [
            'pjname' => $currency,
        ]
    ]);

    $content =  ($response->getBody());

    @mkdir(ROOT."/data/log");

    $log_postion = sprintf(ROOT."/data/log/temp_currency_%s.log",$params[0]);

    file_put_contents($log_postion, "");

    $logger = new \Monolog\Logger('name');

    $logger->pushHandler(new Monolog\Handler\StreamHandler($log_postion, Monolog\Logger::INFO));

    $logger->addInfo($content);

}

/**
 * 处理爬虫获得的汇率结果
 */
function cli_parse_currency_result(){
    $codes = [
        'BR'=>1314,
        "HK"=>1315,
        "US"=>1316,
        "JP"=>1323,
        "EU"=>1326
    ];

    //代号不符时，默认获取美元
    $params = count(func_get_args()[0]) == 1 && in_array(func_get_args()[0][0], array_keys($codes))? func_get_args()[0] : ['US'];

    $log_postion = sprintf(ROOT."/data/log/temp_currency_%s.log", $params[0]);

    $content = file_get_contents($log_postion);

    preg_match_all("/<tr>.*?<\/tr>/", $content, $matches);
    $format = "/".str_replace(".", "\.", date("Y.m.d"))."/";

    $symbol = [
        "币种"=>"type",
        "汇买"=>"cur_buy",
        "钞买"=>"money_buy",
        "汇卖"=>"cur_sell",
        "钞卖"=>"money_sell",
        "中间"=>"middle",
        "中行"=>"bc_price",
        "记录时间"=>"record_time"
    ];

    printf("%s\n", implode("\t", array_keys($symbol)));

    foreach($matches[0] as $result){
        if(preg_match($format, $result)){
            $data = array_map("trim",explode("</td>",preg_replace("/\<tr\>|\<td\>/", "", $result)));
            array_pop($data);
            printf("%s\n", implode("\t", $data));
            $temp = array_flip($symbol);$pos = 0;
            foreach($temp as $k=>$v){
                $temp[$k] = $data[$pos];
                $pos++;
            }
            $temp["type"] = $params[0];
            $temp["record_time"] = date("Y-m-d H:i:s",strtotime(str_replace(".","-",$temp["record_time"])));
            try{
                $currency = new Currency($temp);
                $currency->save();
            }catch (Exception $e){
//                print($e->getMessage());
            }

        }
    }
}

function cli_crawl_get_state_US(){

    $log_postion = sprintf(ROOT."/data/log/state.log");
    $content = file_get_contents($log_postion);
    if(empty($content)){
        $client = new Client([
            'base_uri'=> 'http://www.fltacn.com/',
            'timeout'=> 10.0
        ]);

        $reponse = $client->request("GET", "article_177.html");

        $content = $reponse->getBody();

        file_put_contents($log_postion, $content);
    }

    preg_match_all("/<tr>.*?<\/tr>/", $content, $matches);
    $a = new State();
    $key_mapping = array_keys($a->attributes());
    array_shift($key_mapping);



    foreach($matches[0] as $match){

        $data = array_map("trim",explode("</td>",preg_replace("/\<tr\>|\<td\>/", "", $match)));
        array_pop($data);
        if(preg_match("/^\w{2}$/", $data[2]) == true){
            $data = array_map(function($i){return preg_replace("/<.*?>|&nbsp;/", " ", $i);}, $data);

            $attributes = array_combine($key_mapping, $data);


            if( !empty($object = State::find_by_state_abbr($data[2]) )){
                array_map(function($i) use ($object, $attributes){ $object->assign_attribute($i, $attributes[$i]);}, array_keys($attributes));
                $object->save();
            }else{
                $state = new State($attributes);
                $state->save();
            }

        }else{
            continue;
        }

    }


}


/**
 * 获取知乎首页信息（在有cookie的基础上）
 */
function cli_get_zhihu(){

    $client = new HTTP_client(1, "https://www.zhihu.com");
    $response = ($client->client->request("GET", "https://www.zhihu.com/", ['cookies'=>$client->cookies])->getBody()->getContents());
    if(!is_dir(DATA."/log")){
        mkdir(DATA."/log");
    }
    file_put_contents(DATA."/log/zhihu.com", $response);
}
