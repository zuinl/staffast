<?php

    //Teste de chamada da API teste.php desta mesma pasta

    $data = array("email" => 'leonardosoareszuin@gmail.com', "token" => 'c0a5fb68a6e5b84b58287ceff6d3e251');                                                                    
    $data_string = json_encode($data);                                                                                   
                                                                                                                        
    $ch = curl_init('http://localhost/staffast/api/documento.php');                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );                                                                                                                   
                                                                                                                        
    $result = curl_exec($ch);
    var_dump($result);
?>