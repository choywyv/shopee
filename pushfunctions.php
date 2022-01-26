<?php

  require_once ("config.php");
  require_once ("shopee.php");
  require_once ("localfunctions.php");

  function refreshToken () {

    $params = array ();
    $params["partner_id"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    $response = getResponseWithBody ("/api/v2/auth/access_token/get", $params, array ("refresh_token"=>constant ("const_refresh_token"), "partner_id"=>constant ("partner_id"), "shop_id"=>constant ("shop_id")));
/*
  echo $response;
  echo "<br>";
  echo ("Expire in: " . json_decode($response, true)["expire_in"]);
*/

    $access_token = json_decode($response, true)["access_token"];   
    $refresh_token = json_decode($response, true)["refresh_token"];

    $token_file = "<?php\n";
    $token_file .= "  define ('time_created', '" . (new DateTime())->format ("Y-m-d H:i:s") . "');\n";
    $token_file .= "  define ('const_access_token', '$access_token');\n";
    $token_file .= "  define ('const_refresh_token', '$refresh_token');\n";
    $token_file .= "?>";

    logFile ("accesstoken.php", $token_file); 

    return $response;

  }


  function getConstant ($const) {

    return constant ($const);

  }


?>