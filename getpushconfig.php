<?php

  require_once ("shopee.php");

  $arr = array ();
  $arr["partner_id"] = "";
  $arr["sign"] = "";
  $arr["timestamp"] = "";

  echo getResponse ("/api/v2/push/get_push_config", $arr, array (), "get");

?>