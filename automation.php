<?php

/*
1. get shipping parameter to retrieve address_id and pickup timing
get_shipping_parameter ($order_sn) 

2. ship order using address_id and pickup_time_id
ship_order ($_GET["order_sn"], $_GET["address_id"], $_GET["pickup_time_id"])

3. tracking number will be generated 
get_tracking_number ($order_sn)

4. create shipping document
create_shipping_document ($_GET["order_sn"], $_GET["tracking_number"])

5. download shipping document
*/

  if (!isset ($order_sn)) exit ("No order_sn");

  require_once ("shopee.php");
  require_once ("localfunctions.php");

  $all_responses = "";
  $function_response = "";

  sleep (3);

  $function_response = get_shipping_parameter ($order_sn);
  $gsp = print_r ($function_response , true);
  $all_responses .= $gsp . "\n";


  $address_id = (json_decode ($function_response, true)["response"]["pickup"]["address_list"][0]["address_id"]);
  $time_slot_list = (json_decode ($function_response, true)["response"]["pickup"]["address_list"][0]["time_slot_list"]);
  $pickup_time_id = $time_slot_list[0]["date"] . "_4";
  sleep (3);


//  $function_response = ship_order ($order_sn, $shipping_parameter["address_id"], $shipping_parameter["pickup_time_id"]);
  $function_response = ship_order ($order_sn, $address_id, $pickup_time_id);
  sleep (3);
  //echo "Ship order: $function_response<br><br>";
  $all_responses .= $function_response . "\n";


  $function_response = get_tracking_number ($order_sn);
  sleep (3);
  //echo "Tracking number: $function_response<br><br>";
  $all_responses .= $function_response . "\n";
  $tracking_number = $function_response;


  $function_response = create_shipping_document ($order_sn, $tracking_number);
  sleep (3);
  //echo "Create shipping document: $function_response<br><br>";
  $all_responses .= $function_response . "\n";

 
  $function_response = getFile ($order_sn);
  sleep (5);
  //echo "File size: $function_response<br><br>";
  $all_responses .= "File size: $function_response\n";
  $filesize = $function_response;


//  $function_response = print_to_printer ("\\\\10.1.20.98\\Level1_BroHL6200", $order_sn);  
  $function_response = print_to_printer ("\\\\10.1.20.98\\BrotherHL6200", $order_sn);  // lvl1
  //echo "$function_response<br><br>";
  $all_responses .= $function_response . "\n";

  logFile ("automation_" . $order_sn . "_" . date ('Ymd_His', $_SERVER["REQUEST_TIME"]) . ".txt", $all_responses);


?>


