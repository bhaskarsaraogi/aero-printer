<?php
  //start a session -- needed for Securimage Captcha check
  session_start();


  /**
   * Sets error header and json error message response.
   *
   * @param  String $messsage error message of response
   * @return void
   */
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
  //do some simple validation. this should have been validated on the client-side also
  if (empty($email)) {
  	errorResponse('Email or message is empty.');
  }

  //do Captcha check, make sure the submitter is not a robot:)...
  include_once './vender/securimage/securimage.php';
  $securimage = new Securimage();
  if (!$securimage->check($_POST['captcha_code'])) {
    errorResponse('Invalid Security Code');
  }

  //try to send the message
  // if(mail(MY_EMAIL, EMAIL_SUBJECT, setMessageBody($fields_req), "From: $email")) {
  // 	echo json_encode(array('message' => 'Your message was successfully submitted.'));
  // } else {
  // 	header('HTTP/1.1 500 Internal Server Error');
  // 	echo json_encode(array('message' => 'Unexpected error while attempting to send e-mail.'));
  // }
  // $query = "INSERT INTO info VALUES(".$name.','.$sid.','.$company.','.$phone.','.$email.','.$upfile.','.$message.');';
  // $conn = mysql_connect($config['DB_HOST'],$config['DB_USER'],$config['DB_PASS']);
  //         mysql_select_db($config['DB_NAME'],$conn);
  // $result = mysql_query($query,$conn);           
  // if($result) {
  //   move_uploaded_file($_FILE['upfile']['tmp_name'], '/home/bhaskar/'.$upfile);
  // }  else {
  //  header('HTTP/1.1 500 Internal Server Error');
  //  echo json_encode(array('message' => 'Unexpected error while attempting to send e-mail.'));
  // }

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

