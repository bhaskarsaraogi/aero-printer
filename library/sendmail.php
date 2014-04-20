<?php
  
  session_start();

  function errorResponse ($messsage) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(array('message' => $messsage)));
  }

  $name = $_POST['name'];
  $sid = $_POST['sid'];
  $company = $_POST['company'];
  $phone = $_POST['phone'];
  $email = $_POST['email']; 
  $message = $_POST['message'];
  $upfile = $_FILE['upfile']['name'];

  // $name = "jhg";
  // $sid = "4567nm";
  // $company = "hgjhkkjh";
  // $phone = 0000000000;
  // $email = "a@b.c"; 
  // $message = "test";
  // $upfile = "hgk";

  header('Content-type: application/json');
  
  if (empty($email)) {
  	errorResponse('Email or message is empty.');
  }

  
  include_once './vender/securimage/securimage.php';
  $securimage = new Securimage();
  if (!$securimage->check($_POST['captcha_code'])) {
    errorResponse('Invalid Security Code');
  }

   $config = array('DB_NAME' => 'root',
         'DB_PASS' => 'q');

   try {
    $conn = new PDO('mysql:host=localhost;dbname=print3D',$config['DB_NAME'],$config['DB_PASS']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "INSERT INTO info VALUES(:name,:sid,:company,:phone,:email,:upfile,:message)";

    $stmt = $conn->prepare($sql);

    $stmt->execute(array('name' => $name,
                         'sid' => $sid,
                         'company' => $company,
                         'phone' => $phone,
                         'email' => $email,
                         'upfile' => $upfile,
                         'message' => $message));
    echo json_encode(array('message' => $_FILES['upfile']['name']));
    // echo json_encode(array('message' => $_POST['name']));
    // move_uploaded_file($_FILE['upfile']['tmp_name'], '/home/bhaskar/'.$upfile);
 //   while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //  print_r($row);
  // }
} catch(PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo $name;
    echo json_encode(array('message' => $_POST['name']));
}

