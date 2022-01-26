<?php

  require_once ("shopee.php");

  $api_path = "/api/v2/order/get_shipment_list";

  $params = array ();
  $params["page_size"] = 20;
  $params["shop_id"] = "";
  $params["partner_id"] = "";
  $params["access_token"] = "";
  $params["sign"] = "";
  $params["timestamp"] = "";


  $response = getResponse ($api_path, $params, "get");

  $arr = json_decode ($response, true)["response"];

  echo "<table border=\"1\">";

  echo "  <tr>";

  $order_attrib = array_keys ($arr["order_list"][0]); 
  for ($i = 0; $i < count ($order_attrib); $i++) echo "<th>". ($order_attrib[$i]) . "</th>";
  echo "  </tr>";


  for ($i = 0; $i < count ($arr["order_list"]); $i++) { 

    echo "<tr>";
    $order_attrib = array_values ($arr["order_list"][$i]); 
    for ($j = 0; $j < count ($order_attrib); $j++) {
      echo "<td>" . (($j == 0) ? "<a href=\"javascript:;\" onclick=\"document.getElementById ('form1').action = 'getorderdetail.php'; document.getElementById ('order_sn').value = '" . ($order_attrib[$j]) . "'; ajax (document.getElementById ('form1')); document.getElementById ('form1').action = 'getorderlist.php'; \">" : "") . ($order_attrib[$j]) . (($j == 1) ? "</a>" : "") . "</td>";
    }
    echo "</tr>";
  }

  echo "</table>"; 

  echo "<pre>". json_encode (json_decode ($response), JSON_PRETTY_PRINT) . "</pre>";

?>


