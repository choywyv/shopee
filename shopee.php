<?php

  require_once ("config.php");
  require_once ("accesstoken.php");

  function getResponse ($api_path, $params, $method) {
  
    return execCurl (constant ("environment") . formURL ($api_path, $params), $method, "");

  }



  function getResponseWithBody ($api_path, $params, $body) {

    return execCurl (constant ("environment") . formURL ($api_path, $params), "post", $body);

  }



  function getFile ($order_sn) {
  
    $params = array ();
    $params["partner_id"] = "";
    $params["timestamp"] = "";
    $params["access_token"] = "";
    $params["shop_id"] = "";
    $params["sign"] = "";

    $order["order_sn"] = $order_sn;
    //  $order["package_number"] = "";
    $order_list = array ($order);

    $body = array();
    $body["shipping_document_type"] = "NORMAL_AIR_WAYBILL";
    $body["order_list"] = $order_list;

    $filename = "pdf/$order_sn.pdf";
    $fwrite = fopen ($filename, 'w');

    $connection = curl_init();
    curl_setopt ($connection, CURLOPT_URL, constant ("environment") . formURL ("/api/v2/logistics/download_shipping_document", $params));
    curl_setopt ($connection, CURLOPT_HTTPHEADER, array ('Content-Type: application/json'));
    curl_setopt ($connection, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($connection, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($connection, CURLOPT_POST, 1);
    curl_setopt ($connection, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt ($connection, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($connection, CURLOPT_FILE, $fwrite);

    curl_exec ($connection);
    fclose ($fwrite);
    curl_close ($connection);

    return filesize ($filename); // file_get_contents('php://input');

  }


  function execCurl ($url, $method, $body) {  
    $connection = curl_init();
    curl_setopt  ($connection, CURLOPT_URL, $url);
    curl_setopt  ($connection, CURLOPT_HTTPHEADER, array ("Content-Type: application/json"));
    curl_setopt  ($connection, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt  ($connection, CURLOPT_SSL_VERIFYHOST, 0);
    if ($method == "post") {
      curl_setopt($connection, CURLOPT_POST, 1);
      curl_setopt ($connection, CURLOPT_POSTFIELDS, json_encode ($body));
    }
    curl_setopt  ($connection, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec ($connection);
    curl_close ($connection);

    return $response;
  }


  function formURL ($api_path, $params) {

    $timestamp = (new DateTime ())->getTimestamp ();

    $url_params = "$api_path?";
    foreach ($params as $key=>$value) {
      if ($key == "access_token") $url_params .= "access_token=" . constant ("const_access_token") . "&";
      else if ($key == "partner_id") $url_params .= "partner_id=" . constant ("partner_id") . "&";
      else if ($key == "shop_id") $url_params .= "shop_id=" . constant ("shop_id") . "&";
      else if ($key == "timestamp") $url_params .= "timestamp=" . $timestamp . "&";
      else if ($key == "sign") $url_params .= "sign=". getSign ($api_path, $params, $timestamp) . "&";
      else $url_params .= "$key=$value&"; 
    }

    return substr ($url_params, 0, -1);

  }


  function getSign ($api_path, $params, $timestamp) {

    $hash_data = constant ("partner_id") . $api_path . $timestamp;  

    if (array_key_exists ("access_token", $params)) $hash_data .= constant ("const_access_token");
    if (array_key_exists ("shop_id", $params)) $hash_data .= constant ("shop_id");

    return hash_hmac ("sha256", $hash_data, constant ("secret_key"), false);

  }


  function get_order_list ($fromdate, $todate) {

//  function get_order_list () {
    $api_path = "/api/v2/order/get_order_list";

    $params = array ();
    $params["time_range_field"] = "create_time";
    $params["time_from"] = strtotime ($fromdate);
         // (new DateTime ())->modify('-15 day')->getTimestamp ();
    $params["time_to"] = strtotime ($todate); 
         // (new DateTime ())->getTimestamp ();
    $params["page_size"] = 100;
    $params["order_status"] = "READY_TO_SHIP";
    $params["response_optional_fields"] = "order_status";
    $params["shop_id"] = "";
    $params["partner_id"] = "";
    $params["access_token"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    //echo "From " . (new DateTime ())->modify('-15 day')->format ("Y-m-d") . " To " . (new DateTime ())->format ("Y-m-d") . "<br>";
 
    return getResponse ("/api/v2/order/get_order_list", $params, "get");

  }



  function get_shipping_parameter ($order_sn) {

    $api_path = "/api/v2/logistics/get_shipping_parameter";

    $params = array ();
    $params["order_sn"] = $order_sn;
    $params["shop_id"] = "";
    $params["partner_id"] = "";
    $params["access_token"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    $response = getResponse ("/api/v2/logistics/get_shipping_parameter", $params, "get");
/*
    $address_id = (json_decode ($response, true)["response"]["pickup"]["address_list"][0]["address_id"]);
    $time_slot_list = (json_decode ($response, true)["response"]["pickup"]["address_list"][0]["time_slot_list"]);
    $pickup_time_id = $time_slot_list[0]["date"] . "_4";

    $shipping_parameter["address_id"] = $address_id;
    $shipping_parameter["pickup_time_id"] = $pickup_time_id;
    
    return $shipping_parameter;
*/

    return $response;
  }


  function get_tracking_number ($order_sn) {

    $api_path = "/api/v2/logistics/get_tracking_number";

    $params = array ();
    $params["order_sn"] = $order_sn;
    $params["shop_id"] = "";
    $params["partner_id"] = "";
    $params["access_token"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    $response = getResponse ($api_path, $params, "get");
    return (json_decode ($response, true))["response"]["tracking_number"];

  }


  function get_shipping_document_result ($order_sn) {

    $params = array ();
    $params["partner_id"] = "";
    $params["timestamp"] = "";
    $params["access_token"] = "";
    $params["shop_id"] = "";
    $params["sign"] = "";

    $order_list = array ();
    $order["order_sn"] = $order_sn;
    $order["shipping_document_type"] = "NORMAL_AIR_WAYBILL";
    array_push ($order_list, $order);
   
    $body = array();
    $body["order_list"] = $order_list;

    return getResponseWithBody ("/api/v2/logistics/get_shipping_document_result", $params, $body);

  }


  function ship_order ($order_sn, $address_id, $pickup_time_id) {

    $api_path = "/api/v2/logistics/ship_order";

    $params = array ();
    $params["shop_id"] = "";
    $params["partner_id"] = "";
    $params["access_token"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    $pickup["address_id"] = (int)$address_id;
    $pickup["pickup_time_id"] = $pickup_time_id;

    $order["order_sn"] = $order_sn;
    $order["pickup"] = $pickup;

//  echo "<pre>" . json_encode ($body) . "</pre>";

    return getResponseWithBody ($api_path, $params, $order);

  }



  function get_shipping_document_parameter ($order_sn) {

    $api_path = "/api/v2/logistics/get_shipping_document_parameter";

    $params = array ();
    $params["shop_id"] = "";
    $params["partner_id"] = "";
    $params["access_token"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    $order["order_sn"] = $order_sn;
    $order_list = array ($order);
    $body["order_list"] = $order_list;

    return getResponseWithBody ($api_path, $params, $body);

  }



  function create_shipping_document ($order_sn, $tracking_number) {

    $api_path = "/api/v2/logistics/create_shipping_document";

    $params = array ();
    $params["shop_id"] = "";
    $params["partner_id"] = "";
    $params["access_token"] = "";
    $params["sign"] = "";
    $params["timestamp"] = "";

    $order["order_sn"] = $order_sn;
    $order["tracking_number"] = $tracking_number;
    $order["shipping_document_type"] = "NORMAL_AIR_WAYBILL";
    $order_list = array ($order);
    $body["order_list"] = $order_list;

//echo "<pre>" . json_encode ($body) . "</pre>";

    return getResponseWithBody ($api_path, $params, $body);
 
  }

?>