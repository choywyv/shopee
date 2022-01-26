<?php

  require_once ("shopee.php");

//  if ($_GET["funct"] == "getshippingparameter") echo "<pre>". json_encode (get_shipping_parameter ($_GET["order_sn"]), JSON_PRETTY_PRINT) . "</pre>";
  if ($_GET["funct"] == "getshippingparameter") echo "<pre>". json_encode (json_decode (get_shipping_parameter ($_GET["order_sn"]), true)["response"]["pickup"]["address_list"][0]["time_slot_list"], JSON_PRETTY_PRINT) . "</pre>";
  if ($_GET["funct"] == "gettrackingnumber") echo get_tracking_number ($_GET["order_sn"]);
  if ($_GET["funct"] == "getshippingdocumentresult") echo "<pre>". json_encode (json_decode (get_shipping_document_result ($_GET["order_sn"]), true), JSON_PRETTY_PRINT) . "</pre>";


?>