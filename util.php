<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['url'])) {
    function randtext($length)
    {
        #generate random code
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
        #append data to data.json
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

    function search(...$s){
        #check if data be used
        $data = json_decode(file_get_contents("data.json"), 1);
        foreach ($s as $k => $v) {
            if (isset($data[$k][$v])){
                if($k===1){
                    return $v;
                }
                return $data[$k][$v];
            }
        }
        return 0;
    }
} else {
    header("HTTP/1.1 403 Forbidden");
}

