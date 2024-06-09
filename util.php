<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['url'])) {
    function randtext($length)
    {
        $password_len = $length;
        $password = "";
        $word = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";   //亂數內容
        $len = strlen($word);
        for ($i = 0; $i < $password_len; $i++) {
            $password .= $word[rand() % $len];
        }
        return $password;
    }

    function AppendData($url,$custom="")
    {
        $data = json_decode(file_get_contents("data.json"), 1);
        if($custom){
            $id = $custom;
        }else{
            $id = randtext(6);
        }
        $data[0][$url] = $id;
        $data[1][$id] = $url;
        file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT));
        return $id;
    }

    function search($s){
        $data = json_decode(file_get_contents("data.json"), 1);
        if (isset($data[0][$s])){
            return $data[0][$s];
        }else{
            return 0;
        }
    }
} else {
    header("HTTP/1.1 403 Forbidden");
}

