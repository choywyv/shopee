<!DOCTYPE html>
<html>
  <head>
    <style>

    span { margin-left: 20px; margin-right: 20px; }

.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  margin-bottom: 10vh;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
     </style>

  </head>

  <body>

<?php

  require_once ("shopee.php");

  $response = //get_order_list (); 
get_order_list ($_POST["fromdate"], $_POST["todate"]);

  $arr = json_decode ($response, true)["response"]["order_list"];

  if (count ($arr) == 0) echo "No READY_TO_SHIP";
  else {

    echo "<table border=\"1\" id=\"table1\">";
    echo "  <thead>    <tr>";

    $order_attrib = array_keys ($arr[0]); 
    for ($i = 0; $i < count ($order_attrib); $i++) echo "<th>". ($order_attrib[$i]) . "</th>";
    echo "<th>Shipping Parameter</th>";
    echo "<th>Tracking Number</th>";
    echo "<th>Shipping Document Result</th>";
    echo "    </tr>  </thead>";
    echo "  <tbody>";
    for ($i = 0; $i < count ($arr); $i++) { 
      echo "<tr>";
      $order_attrib = array_values ($arr[$i]); 
      for ($j = 0; $j < count ($order_attrib); $j++) echo "<td>" . (($j == 0) ? "<a href=\"javascript:;\" onclick=\"linkClicked ('$order_attrib[$j]'); \">" : "") . ($order_attrib[$j]) . (($j == 1) ? "</a><br>" : "") . "</td>";      
      echo "    <td></td>    <td></td>    <td></td>  </tr>";
    }
    echo "  </tbody></table>"; 

//    echo "<pre>". json_encode (json_decode ($response), JSON_PRETTY_PRINT) . "</pre>";
  }

?>


<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="alert ('clicked');">&times;</span>
    <div id="details">
      <span id="orderid"></span><br><br>
      <span><a href="javascript:;" onclick="ajax1 ('http://10.1.20.98/Shopee/getorderdetail.php?order_sn=' + document.getElementById ('order_sn').value, 'details'); ">Order Details</a></span>
      <span><a href="javascript:;" onclick="ajax1 ('http://10.1.20.98/Shopee/processorder.php?order_sn=' + document.getElementById ('order_sn').value, 'details'); ">Process Order</a></span>
    </div>
  </div>

</div>

    <script>
      var modal = document.getElementById("myModal"), span = document.getElementsByClassName("close")[0], orders;

      populateCell = async (url, row, cell) => { 
        let response = await fetch (url, { 
          //method: form.method         
        });
        let text = await response.text ();
        document.getElementById ("table1").tBodies[0].rows[row].cells[cell].innerHTML = text; 
      }

      ajax1 = async (url, target) => { 
        document.getElementById (target).innerHTML = "Please wait..."; 
        let response = await fetch (url, {         });
        let text = await response.text ();
        document.getElementById (target).innerHTML = text; 
      }

      linkClicked = (val) => { 
        document.getElementById ("order_sn").value = val; 
        document.getElementById ("orderid").innerHTML = val;
        modal.style.display = "block";
      }

      span.onclick = () => {
        modal.style.display = "none";
        orders = "";
        document.getElementById ("form1").action = "getorderlist.php";
        ajax (document.getElementById ("form1"), document.getElementById ("result"));
        document.getElementById ("orderid").innerHTML = "";

      }

      window.onclick = (event) => {
        if (event.target == modal) modal.style.display = "none";
      }

      
      <?php

        $orders = "";
        for ($i = 0; $i < count ($arr); $i++) {
          $orders .= "'" .array_values ($arr[$i])[0] . "'";
          if ($i < count ($arr) - 1) $orders .= ", "; 
        }
        echo "orders = [$orders];";

      ?>

      for (var i = 0; i < orders.length; i++) {
        populateCell ("http://10.1.20.98/Shopee/echo.php?funct=getshippingparameter&order_sn=" + orders[i], i, 4);      
        populateCell ("http://10.1.20.98/Shopee/echo.php?funct=gettrackingnumber&order_sn=" + orders[i], i, 3);      
        populateCell ("http://10.1.20.98/Shopee/echo.php?funct=getshippingdocumentresult&order_sn=" + orders[i], i, 2);      
      }
    </script>

  </body>
</html>