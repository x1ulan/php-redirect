<?php
include ("util.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  # register data to json
  if (@isset ($_POST["url"])) {
    $data = json_decode(file_get_contents("data.json"), 1);
    $url = $_POST["url"];
    $s = search($url);
    if ($s !== 0) {
      $msg = "{\"status\":\"isset\",\"url\":\"$s\"}";
    } else {
      $p1 = stripos($url,"http");
      $p2 = stripos($url,"://");
      $p3 = stripos($url,".");
      if ($p1 !== false && $p2 !== false && $p3 !== false &&
              $p1 < $p2 && $p2 < $p3){
        $act = AppendData($url);
        $msg = "{\"status\":\"success\",\"url\":\"$act\"}";
      }else{
        $msg = "{\"status\":\"error\",\"url\":\"url must be available\"}";
      }
    }
  }
}
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
  <title>Centered Input</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container d-flex h-100 align-items-center justify-content-center">
    <div class="col-md-auto">
      <form action="." method="POST">
        <div class="input-group mb-3">
          <input name="url" type="text" class="form-control" placeholder="Enter url here">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVFQWjxhGqHJ5y8qC0PVzpoGDnwVtEU8IqVxLBXn$$4HcwSXzEjq"
    crossorigin="anonymous"></script>
  <?php
  if (isset ($msg)) {
    $msg = json_decode($msg, 1);
    $title = $msg["status"];
    $protocol = ((!empty ($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $http = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    switch ($title) {
      case "success":
        $icon = "success";
        $cont = 1;
        break;
      case "isset":
        $icon = "info";
        $cont = 1;
        break;
      case "error":
        $icon = "error";
        $cont = 0;
        break;
    }
    if($cont){
      $html = "url:<br>" . "<code>" . $protocol.rtrim($http,"/")."?".$msg["url"] . "</code>";
    }else{
      $html = "<code>" . $msg["url"] . "</code>";
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