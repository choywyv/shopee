<?php

  require_once ("config.php");
  require_once ("shopee.php");
  
  echo "FUNCTION get token<br>";

  $api_path = "/api/v2/auth/token/get"; //without the host   

  $params = array ();
  $params["partner_id"] = "";
  $params["sign"] = "";
  $params["timestamp"] = "";

  $response = getResponseWithBody ($api_path, $params, array("code"=>$_GET["code"],  "partner_id"=>constant ("partner_id"), "shop_id"=>constant ("shop_id")));
  echo $response . "<br><br>";

  $access_token = json_decode($response, true)["access_token"];   
  $refresh_token = json_decode($response, true)["refresh_token"];

  $myfilename = "accesstoken.php";
  if (file_exists ($myfilename)) unlink ($myfilename);

  $myfile = fopen ($myfilename, "w");
  fwrite ($myfile, "<?php\n"); 
  fwrite ($myfile, "  define ('time_created', '" . (new DateTime())->format ("Y-m-d H:i:s") . "');\n");
  fwrite ($myfile, "  define ('const_access_token', '$access_token');\n");
  fwrite ($myfile, "  define ('const_refresh_token', '$refresh_token');\n");
  fwrite ($myfile, "?>");
  fclose ($myfile);

//  require_once ($myfilename);
//  echo constant ("const_refresh_token");

?>

<button type="bytton" onclick="window.location.href='index.php'">Query Form</button>

