<?php
include ("util.php");
#post handle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  echo $_POST["custom-url"];
  # register data to json
  if (@isset ($_POST["url"])) {
    $data = json_decode(file_get_contents("data.json"), 1);
    $url = $_POST["url"];
    $custom = $_POST["custom-url"];
    #check if url be used
    $s = search($url);
    if ($s !== 0) {
      $msg = "{\"Status\":\"Isset\",\"Url\":\"$s\"}";
    } else {
      #make sure the url is legal
      $p1 = stripos($url,"http");
      $p2 = stripos($url,"://");
      if ($p1 !== false && $p2 !== false && $p1 < $p2){
        $act = AppendData($url,$custom);
        $msg = "{\"Status\":\"Success\",\"Url\":\"$act\"}";
      }else{
        $msg = "{\"Status\":\"Error\",\"Url\":\"Url must be available\"}";
      }
    }
  }
}
#get handle
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET !== []) {
  $data = json_decode(file_get_contents("data.json"), 1);
  foreach ($_GET as $key => $value) {
      @$url = $data[1][$key];
      if (isset ($url)) {
          header("HTTP/1.1 301 Moved Permanently");
          header("Location: $url");
          exit();
      } else {
          die("No such a value as $key");
      }
  }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shorturl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <script>
        function SwitchToCustom(){
            let ele = document.querySelector(".custom-url");
            ele.style = "display:display";
        }
    </script>
    <div class="container d-flex h-100 align-items-center justify-content-center">
        <div class="col-md-3">
            <form action="." method="POST">
                <div class="input-group mb-3">
                    <input name="url" type="text" class="form-control" placeholder="Enter your url here...">
                </div>
                <div>
                    <a href="#" onclick="(function(f){f.style.display='none';SwitchToCustom()})(this)" style="text-decoration:none">custom url</a>
                    <input style="display:none" name="custom-url" type="text" class="form-control custom-url" placeholder="Enter your custom here...">    
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVFQWjxhGqHJ5y8qC0PVzpoGDnwVtEU8IqVxLBXn$$4HcwSXzEjq"
        crossorigin="anonymous"></script>
    <?php
    if (isset ($msg)) {
      $msg = json_decode($msg, 1);
      $title = $msg["Status"];
      $protocol = ((!empty ($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      $http = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      switch ($title) {
        case "Success":
          $icon = "success";
          $cont = 1;
          break;
        case "Isset":
          $icon = "info";
          $cont = 1;
          break;
        case "Error":
          $icon = "error";
          $cont = 0;
          break;
      }
      if($cont){
        $html = "Url:<br>" . "<code>" . $protocol.rtrim($http,"/")."?".$msg["Url"] . "</code>";
      }else{
        $html = "<code>" . $msg["Url"] . "</code>";
      }
      echo <<<SCRIPT
    <script>
    Swal.fire({
      title: "$title",
      html: "$html",
      icon: "$icon",
      toast:true,
      timer: 10000
    });
    </script>
SCRIPT;
    }
    ?>
</body>
</html>