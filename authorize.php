<!DOCTYPE html>
<html>
  <head>
     <script> 
       ajax = async (form) => { 
        let response = await fetch (form.action, { 
          method: form.method,
          body: new URLSearchParams (new FormData (form))
        });
        let text = await response.text ();
        document.getElementById ("result").innerHTML = text; 
      } 
     </script>
    </head>

  <body>

<?php

  require ("shopee.php");

  $api_path = "/api/v2/shop/auth_partner"; //without the host     

  $params = array ();
  $params["partner_id"] = "";
  $params["timestamp"] = "";
  $params["sign"] = "";
  $params["redirect"] = constant ("redirect");

  echo "FUNCTION auth link<br>";
  echo getResponse ($api_path, $params, "get");

?>

  </body>
</html>