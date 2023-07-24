<?php include "simple_html_dom.php";

@$get_number = filter_var($_GET['number'], FILTER_SANITIZE_STRING);

if($get_number != ""){
    $html = file_get_html("https://receive-smss.com/sms/$get_number/");

    foreach($html->find('tr') as $element){
        
        $arr['number'] = $get_number;
        $arr['sender'] = $element->find('td', 3)->plaintext ?? "null";
        
        foreach($element->find('td') as $el){
           foreach($el->find('p') as $el){
                $arr['message'] = trim($el->plaintext) ?? "null";
            }
            
            foreach($el->find('span') as $el){
                $arr['time'] = trim($el->plaintext) ?? "null";
            }
        }
        
        
        $arr1['number'][] = array_filter($arr);
    }
    
    unset($arr1['numbers'][0]);
    
    echo json_encode($arr1, true);

}else{
    $html = file_get_html("https://receive-smss.com");
    
    foreach($html->find('.number-boxes-item') as $element){
        foreach($element->find('a') as $el){
            $ex = explode("/sms/", $el->href);
            
            $arr['number'] = "+" . end(str_replace("/", "", $ex));
            $arr['messages'] = "https://sa.omdda.com/receive-sms?number=" . end(str_replace("/", "", $ex));
    
            foreach($element->find('img') as $el){
                $ex = explode("https", $el->src);
                $arr['icon'] = "https" . $ex[1];
            }
        }
    
        foreach($element->find('h5') as $el){
            $arr['country'] =  trim($el->plaintext);
        }
        
        $arr1["numbers"][] = array_filter($arr);
    }
    
    echo json_encode($arr1, true);
}