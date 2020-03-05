<?php

    //Teste de chamada da API teste.php desta mesma pasta

    //URL da API
    $url = 'http://localhost/staffast/api/historicoPonto.php';
    
    //Cria instância da cURL
    $ch = curl_init($url);

    //Cria objeto a ser enviado
    $data = array(
        "email" => 'leonardosoareszuin@gmail.com',
        "token" => "c0a5fb68a6e5b84b58287ceff6d3e251"
    );

    $json = json_encode($data);

    //Anexa o JSON ao POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    //Setando o conteúdo para JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    //Retorna resposta ao invés de imprimir
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    var_dump($result);

    curl_close($ch);
?>