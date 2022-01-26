<?php

  error_reporting(0);
  ini_set("log_errors", 1);
  ini_set("error_log", "php-error.log");

  require_once ("localfunctions.php");
  require_once ("pushfunctions.php");

  //header("HTTP/1.1 200 OK");
  
  $file_contents = file_get_contents ('php://input');
  $logmessage = "";
  $logmessage .= "HTTP_AUTHORIZATION: " . $_SERVER["HTTP_AUTHORIZATION"] . "\n";
  $logmessage .= "REQUEST_TIME: " . date ('Y-m-d H:i:s', $_SERVER["REQUEST_TIME"]) . "\n";
  $logmessage .= "REMOTE_ADDR: " . $_SERVER["REMOTE_ADDR"] ."\n";
  $logmessage .= "REMOTE_PORT: " . $_SERVER["REMOTE_PORT"] ."\n";
  $logmessage .= $file_contents . "\n"; 

  if (! isAuthenticHash ($_SERVER["HTTP_AUTHORIZATION"], $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"], $file_contents)) {
    $logmessage .= "Hash error!\n"; 
    logFile ("Error_" . date ('Ymd_His', $_SERVER["REQUEST_TIME"]) . ".txt", $logmessage);
    exit ();
  }

//  $logmessage .= $_SERVER['QUERY_STRING'] . "\n");
//  foreach (getallheaders() as $name => $value) $logmessage .= "$name: $value\n";
//  $logmessage .= "\n";
//  foreach ($_SERVER as $key_name => $key_value) $logmessage .= "$key_name = $key_value\n";
//  $logmessage .= "\n";




  if (json_decode ($file_contents, true)["data"]["status"] == "READY_TO_SHIP") {
    
    $order_sn = json_decode ($file_contents, true)["data"]["ordersn"];

    $logmessage .= "Order_exist: " . orderExists ($order_sn) . "\n";   

    if (! orderExists ($order_sn)) {
      $logmessage .= "Order: $order_sn\n";
          
      $refresh_token_response = refreshToken ();
      if (json_decode ($refresh_token_response, true)["error"] == "") {
        $logmessage .= $refresh_token_response;
        sleep (1);
              
        $all_responses = "";
        include ("automation.php");
              
        updateDB (array ("order_sn"=>$order_sn, "http_authorization"=>$_SERVER["HTTP_AUTHORIZATION"], "request_time"=>date ('Y-m-d H:i:s', $_SERVER["REQUEST_TIME"]), "remote_addr"=>$_SERVER["REMOTE_ADDR"], "remote_port"=>$_SERVER["REMOTE_PORT"], "payload"=>$file_contents));
      }
      else {
        $all_responses = "Error with Refresh Token<br>" . getConstant ("time_created") . $refresh_token_response;
        exit (); 
      }
              
      sendemail ($order_sn, $all_responses);
            
         
    }

    logFile ("order_" . $order_sn . "_" . date ('Ymd_His', $_SERVER["REQUEST_TIME"]) . ".txt", $logmessage);

  }


  if (json_decode ($file_contents, true)["code"] == 0) logFile ("pushverify_" . date ('Ymd_His', $_SERVER["REQUEST_TIME"]) . ".txt", $logmessage);

  ob_clean ();

?>