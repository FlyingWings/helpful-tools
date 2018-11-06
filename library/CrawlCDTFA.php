<?php
/**
 * Created by PhpStorm.
 * User=> alex
 * Date: 18-7-9
 * Time: 下午1:46
 */

class CrawlCDTFA
{
    public $fwdc_data;

    public function __construct(){
        if(file_exists("cookie.txt") && file_exists("option.txt")){
            $this->fwdc_data = unserialize(file_get_contents("option.txt"));
            $this->client = new GuzzleHttp\Client(['cookies'=>unserialize(file_get_contents("cookie.txt"))]);
        }else{
            $this->_init();
        }


    }


    public function _init(){
        $this->fwdc_data = [];
        $this->fwdc_data['FAST_CLIENT_WINDOW__'] = "FWDC.WND-0000-0000-0000";
        $this->fwdc_data['FAST_CLIENT_AJAX_ID__'] = 0;
        $this->client = new \GuzzleHttp\Client(['cookies'=>true]);
    }

    public function _set_cookie(){
        $cookie_file = "cookie.txt";
        $options_file = "option.txt";
        file_put_contents($cookie_file, serialize($this->client->getConfig("cookies")));
        file_put_contents($options_file, serialize($this->fwdc_data));
    }

    public function before_request(){
        $this->fwdc_data['FAST_CLIENT_WHEN__'] = strval(floor(microtime(1) * 1000));
        $this->fwdc_data['FAST_CLIENT_AJAX_ID__'] += 1;
    }

    public function after_request($response=null){
        try{
            $this->fwdc_data['FAST_VERLAST__'] = $response->getHeader("Fast-Ver-Last")[0];
            $this->fwdc_data['FAST_VERLAST_SOURCE__'] = $response->getHeader('Fast-Ver-Source')[0];
        }catch (Exception $e){
            print($e->getMessage());
        }
    }

    public function get($params=[]){
        $this->before_request();
        $params= array_merge($params, $this->fwdc_data);
        $uri = $params['uri'];
        unset($params['uri']);

        $r = $this->client->get($uri, [
            'query'=>$params
        ]);
        $this->after_request($r);
        $this->_set_cookie();
        return $r;
    }

    public function post($params=[], $type="form_params"){
        $this->before_request();
        $params= array_merge($params, $this->fwdc_data);
        $uri = $params['uri'];
        unset($params['uri']);

        $r= $this->client->post($uri, [
            $type=>$params
        ]);
        $this->after_request($r);
        $this->_set_cookie();
        return $r;
    }



    public function load_front_page(){
        $this->get(['uri'=>"https://onlineservices.cdtfa.ca.gov/_/"]);
        return $this->get(['uri'=>"https://onlineservices.cdtfa.ca.gov/_/", 'Load'=>"1"]);
    }

    public function click_lookup_status(){
        $data =[
            'DOC_MODAL_ID__'=> "0",
            'EVENT__'=> "l-1-4",
            'TYPE__'=> "0",
            'CLOSECONFIRMED__'=> "false",
            'uri'=>"https://onlineservices.cdtfa.ca.gov/_/EventOccurred"
        ];
        return $this->post($data);
    }

    public function select_radio_button($field_id=""){
        $data = [
            $field_id=>"SUTSITE",
            "DOC_MODAL_ID__"=>"0",
            'uri'=>"https://onlineservices.cdtfa.ca.gov/_/Recalc"
        ];
        return $this->post($data);
    }

    public function enter_text_field($field_id="", $number=""){
        $data = [
            $field_id=>$number,
            "DOC_MODAL_ID__"=>"0",
            'uri'=>"https://onlineservices.cdtfa.ca.gov/_/Recalc"
        ];
        return $this->post($data);
    }

    public function get_table_value($number = ""){
        $data = [
            'd-3'=>"SUTSITE",
            'd-4'=>$number,
            'DOC_MODAL_ID__'=> "0",
            'EVENT__'=> "d-5",
            'TYPE__'=> "0",
            'CLOSECONFIRMED__'=> "false",
            "uri"=>"https://onlineservices.cdtfa.ca.gov/_/EventOccurred"
        ];
        return $this->post($data);
    }

    public function parse_response($content=""){
        $regex= [
            'owner_name'=>'/name=\\\"f-4\\\".*?value =\\\"(.*?)\\\"/',
            'dba_name'  =>'/name=\\\"f-5\\\".*?value =\\\"(.*?)\\\"/',
            'address'   =>'/name=\\\"f-6\\\".*?>(.*?)</',
            'start_date'=>'/name=\\\"f-7\\\".*?value =\\\"(.*?)\\\"/'
        ];
        $result = [];
        foreach($regex as $key=>$re){
            preg_match($re, $content, $match);
            $result[$key]= $match[1];
        }

        if(count(array_filter($result)) < 4){
            throw new Exception("This seller's permit does not exist.", 400);
        }
        else{
            return $result;
        }
    }


    public function fetch_info($number = ""){
        try{
            $contents = $this->get_table_value($number)->getBody()->getContents();
            return $this->parse_response($contents);
        }catch (Exception $e){
            if($e->getCode() != 200){
                $this->_init();
                $this->load_front_page();

                $this->click_lookup_status();
                $this->select_radio_button("d-3");


                $this->enter_text_field("d-4", $number);
                $r = $this->get_table_value($number)->getBody()->getContents();

                return $this->parse_response($r);
            }else{
                throw new Exception($e->getMessage(),  $e->getCode());
            }
        }
    }

}


function get_desc($number=""){
    $c = new CrawlCDTFA();
    var_dump($c->fetch_info($number));
}