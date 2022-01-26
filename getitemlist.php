<?php

  require_once ("shopee.php");

  echo "FUNCTION get_item_list<br>";
  
  $params = array ();
  $params["offset"] = 0;
  $params["page_size"] = 20;
  $params["item_status"] = "NORMAL";
  $params["shop_id"] = "";
  $params["partner_id"] = "";
  $params["access_token"] = "";
  $params["sign"] = "";
  $params["timestamp"] = "";

  $response = getResponse ("/api/v2/product/get_item_list", $params, "get");
  echo "<pre>". json_encode (json_decode ($response), JSON_PRETTY_PRINT) . "</pre><br><br>";


  echo "FUNCTION get_item_base_info<br>";

  $item_id_list = "";
  $params1 = array ();
  foreach (json_decode ($response, true)["response"]["item"] as $item) array_push ($params1, $item["item_id"]);
  for ($i = 0; $i < count ($params1); $i++) {
    $item_id_list .= $params1[$i];
    if ($i < count ($params1) - 1) $item_id_list .= ",";
  }

//echo $item_id_list;

  $params["item_id_list"] = $item_id_list;

  echo "<pre>". json_encode (json_decode (getResponse ("/api/v2/product/get_item_base_info", $params, "get")), JSON_PRETTY_PRINT) . "</pre>";

?>