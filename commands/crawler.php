<?php
use GuzzleHttp\Client;
use library\HTTP_client;

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

    $client = new HTTP_client( "https://www.zhihu.com", true);
    $response = ($client->client->request("GET", "https://www.zhihu.com/", ['cookies'=>$client->cookies])->getBody()->getContents());
    if(!is_dir(DATA."/log")){
        mkdir(DATA."/log");
    }
    file_put_contents(DATA."/log/zhihu.com", $response);
}


function cli_crawl_diyidan(){

    $url = "https://www.diyidan.com/main/post/6294360860179266447/detail/1";
    $base_url = "https://www.diyidan.com/";

    $client = new HTTP_client($base_url);
    $client->crawl_images($url);

    dd(1);
    $params = func_get_args()[0];

    $page = @$params[0]?: 10;
    $time_gap = 1800;
    $flag = false;
    if(time()- filemtime(DATA."/log/diyidan_cos_".($page-1)) > $time_gap){
        //未过期，处理本地
        $flag = true;
    }
//    dd($flag);
    $base_url = "https://www.diyidan.com/";
    if($flag){
        $site = "main/area/104003/%d";//COS区
        $client = new HTTP_client($base_url);
        for($i=1; $i< $page; $i++){
            $site_to_crawl = sprintf($site, $i);
            $content = $client->get($site_to_crawl);

            file_put_contents( DATA."/log/diyidan_cos_".$i,$content);
            usleep(100);
        }
    }
    $crawled = new UniqueSet();
    for($i=1; $i< $page; $i++){
        $content = file_get_contents(DATA."/log/diyidan_cos_".$i);
        $topic_regex = "/main\/post\/\d+\/.+?'/";
        preg_match_all($topic_regex, $content, $all);
//        dd($all);
        foreach($all[0] as $url){
            $real_url = $base_url.$url;
            $crawled->add($real_url);
        }
    }
    file_put_contents(DATA."/log/diyidan_result.log", implode("\n", $crawled->getArray()));

//    foreach($)


}


function cli_get_product_size(){

    $pic_base_url = "https://www.b2sign.com/econo-feather-flag-p-%s.html";
    $base_url = "https://www.diyidan.com/";
    $client = new HTTP_client($base_url);

    $item_raw = file_get_contents(DATA."/item_size.txt");
    $item_raw = explode("\n", $item_raw);
    $item_size = [];
    $item_size = array_map(function($i)use(&$item_size){$temp = explode(",", $i);$item_size[$temp[0]] = $item_size[1];  }, $item_size);
    $input = DATA."/best_2_sellers.txt";
    @mkdir(DATA."/output");



    $connections = [];
    if(file_exists($input)){
        $m = file_get_contents($input);
        $m = array_filter(explode("\n", $m));
        foreach($m as $conn){
            $item = explode(",", $conn);
            if(@$item_size[$item[0]]){
                var_dump(@$item_size[$item[0]]);
            }elseif(file_exists(DATA."/output/".$item[0]."html")){
                $size_regex = "/(?<=<h1>)(.+?)(?=<\/h1>)/";
                $content = file_get_contents(DATA."/output/".$item[0]."html");
                preg_match($size_regex, $content, $matches);
                $item_size[$item[0]] = $matches[0];


            }else{
                dd($item_size);
                $content = $client->get(sprintf($pic_base_url, $item[0]));

                file_put_contents(DATA."/output/".$item[0]."html", $content);
            }

            if(@$item_size[$item[1]]){
                var_dump(@$item_size[$item[1]]);

            }elseif(file_exists(DATA."/output/".$item[1]."html")){
                $size_regex = "/(?<=<h1>)(.+?)(?=<\/h1>)/";
                $content = file_get_contents(DATA."/output/".$item[1]."html");
                preg_match($size_regex, $content, $matches);
                $item_size[$item[1]] = $matches[1];


            }else{
                dd($item_size);

                $content = $client->get(sprintf($pic_base_url, $item[1]));
                file_put_contents(DATA."/output/".$item[1]."html", $content);

            }
        }
    }
}