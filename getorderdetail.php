<?php

  require_once ("shopee.php");

  $api_path = "/api/v2/order/get_order_detail";

  $params = array ();
  $params["shop_id"] = "";
  $params["partner_id"] = "";
  $params["access_token"] = "";
  $params["sign"] = "";
  $params["timestamp"] = "";
  $params["order_sn_list"] = isset ($_GET["order_sn"]) ? $_GET["order_sn"] : "";
  $params["order_sn_list"] = isset ($_POST["order_sn"]) ? $_POST["order_sn"] : $_GET["order_sn"];
  $params["response_optional_fields"] = "buyer_user_id,buyer_username,estimated_shipping_fee,recipient_address,actual_shipping_fee,goods_to_declare,note,note_update_time,item_list,pay_time,dropshipper,dropshipper_phone,split_up,buyer_cancel_reason,cancel_by,cancel_reason,actual_shipping_fee_confirmed,buyer_cpf_id,fulfillment_flag,pickup_done_time,package_list,shipping_carrier,payment_method,total_amount,buyer_username,invoice_data,checkout_shipping_carrier,reverse_shipping_fee,cod,create_time1632973421,currency,days_to_ship,message_to_seller,order_sn,order_status,region,ship_by_date,update_time";

  echo "FUNCTION $api_path<br>";
  print_r ($params["order_sn_list"]);


//  echo  isset ($_GET["order_sn"]) ? $_GET["order_sn"] : "No";
//  echo  isset ($_POST["order_sn"]) ?  $_POST["order_sn"] : "No";

  echo "<pre>". json_encode (json_decode (getResponse ($api_path, $params, "get")), JSON_PRETTY_PRINT) . "</pre>";

?>