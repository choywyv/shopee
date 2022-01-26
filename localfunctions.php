<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  require_once ("config.php");

  function sendemail ($order_sn, $all_responses) {

    try {

      $mail = new PHPMailer;
      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
      $mail->isSMTP();                                           //Send using SMTP
      $mail->Host       = 'smtp.office365.com';                  //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                  //Enable SMTP authentication
      $mail->Username   = constant ("email_user");               //SMTP username
      $mail->Password   = constant ("email_pass");               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
      $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
      $mail->setFrom('eyss.it@euyansang.com', 'Mailer');
//    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
      $mail->addAddress("victor.choy@euyansang.com");               //Name is optional
      $mail->addAddress("garnett.ho@euyansang.com");               //Name is optional
      $mail->addReplyTo('eyss.it@euyansang.com', 'Information');


    //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//	$mail->addAttachment($_POST["attm"]);
    //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = "New Shopee Order: " . $order_sn;
//      $mail->Body    = "New order " . "<b>" . $order_sn . "</b><br>";
      $mail->Body    = ($all_responses == "")  ? " " : $all_responses;
      $mail->AltBody = ($all_responses == "")  ? " " : $all_responses;

      $mail->send();
//      echo 'Message has been sent';
    } 
    catch (Exception $e) {
//      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

  }



  function logFile ($filename, $logmessage) {
    if (file_exists ($filename)) unlink ($filename);
    $myfile = fopen ($filename, "w");
    fwrite ($myfile, $logmessage);
    fclose ($myfile);
  }


 
  function isAuthenticHash ($hash, $host, $uri, $body) {

    return (strcmp (trim (hash_hmac ("sha256",  "http://$host$uri|$body", constant ("secret_key"), false)), trim ($hash)) == 0);

  }


  function orderExists ($order_sn) {
    $result = "";
   
    $conn = sqlsrv_connect ("10.1.20.101", ["Database" => constant ("db"), "UID" => constant ("db_user"), "PWD" => constant ("db_pass")]);

    if ($conn === false) $result = sqlsrv_errors();
    else {
      $sql = "SELECT COUNT (*) AS Total FROM PushNotification WHERE [ORDER_SN] = ?";
      $values = array ($order_sn);
    
      $stmt = sqlsrv_query ($conn, $sql, $values);

      if ($stmt === false) $result = sqlsrv_errors();
      else while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $result = $row["Total"];
    }
 
    sqlsrv_close ($conn);
      
    return ($result >= 1);
  }



  function updateDB ($params) {
    $result = "";
   
    $conn = sqlsrv_connect ("10.1.20.101", ["Database" => constant ("db"), "UID" => constant ("db_user"), "PWD" => constant ("db_pass")]);

    if ($conn === false) $result = sqlsrv_errors();
    else {
      $sql = "INSERT INTO PushNotification ([ORDER_SN], [HTTP_AUTHORIZATION], [REQUEST_TIME], [REMOTE_ADDR], [REMOTE_PORT], [PAYLOAD]) VALUES (?, ?, ?, ?, ?, ?)";
      $values = array ($params["order_sn"], $params["http_authorization"], $params["request_time"], $params["remote_addr"], $params["remote_port"], $params["payload"]);
    
      $stmt = sqlsrv_query ($conn, $sql, $values);

      if ($stmt === false) $result = sqlsrv_errors();
       
      sqlsrv_close ($conn);
      $result = "Done";
    }
    
    //return $result;
  }


  function print_to_printer ($printer, $order_sn) {

    $result = shell_exec ("print /d:$printer D:\\Shopee\\pdf\\$order_sn.pdf");

  } 

?>