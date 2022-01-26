<!DOCTYPE html>
<html>
  <head>
    <script> 
      ajax = async (form, target) => { 
       target.innerHTML = "Retrieving data..."; 
       let response = await fetch (form.action, { 
         method: form.method,
         body: new URLSearchParams (new FormData (form))
       });
       let text = await response.text ();
       target.innerHTML = text; 

       for (var i = 0, scripts = document.getElementById ("result").getElementsByTagName ("script"); i < scripts.length; i++) eval (scripts[i].innerText);

      } 

      showForm = (val) => {
        document.getElementById ("result").innerHTML = "";
        document.getElementById ("div1").style.display = (val == "getshippingdocumentresult.php") ? "block" :
                                                         (val == "sendmail.php") ? "block" :
                                                         (val == "gettrackinginfo.php") ? "block" :
                                                         (val == "downloadfile.php") ? "block" :
                                                         (val == "getorderdetail.php") ? "block" : "none";
        var ele, date = new Date ();
        ydate = date.setDate(date.getDate() - 1);

        if (val == "getorderlist.php") {
          br = document.createElement ("br");
          document.getElementById ("select1").after (br);

          ele = document.createElement ("input");
          ele.setAttribute ("id", "fromdate");
          ele.setAttribute ("name", "fromdate");
          ele.setAttribute ("type", "date");
          ele.setAttribute ("placeholder", "From Date");

          document.getElementById ("btn1").before (ele);

          ele = document.createElement ("label");
          ele.innerHTML = "From Date";
          document.getElementById ("fromdate").before (ele);

          br = document.createElement ("br");
          document.getElementById ("fromdate").after (br);

          ele = document.createElement ("input");
          ele.setAttribute ("id", "todate");
          ele.setAttribute ("name", "todate");
          ele.setAttribute ("type", "date");
          ele.setAttribute ("placeholder", "To Date");
          document.getElementById ("btn1").before (ele);

          ele = document.createElement ("label");
          ele.innerHTML = "To Date";
          document.getElementById ("todate").before (ele);

          br = document.createElement ("br");
          document.getElementById ("btn1").before (br);

          document.getElementById ("fromdate").value = new Date (ydate).toISOString().slice(0, 10);
          document.getElementById ("todate").value = new Date().toISOString().slice(0, 10);

       } 
/*
        if (val == "sendmail.php") {
          document.getElementById ("order_sn").value = "";          
          document.getElementById ("order_sn").placeholder = "Send email to";          
        }
*/
      }

    </script>

     <style>
       body { width: 80%; margin: 5% 5% 5% 5%;}
       select, input, button { font-size: 120%;     }
       input { width: 400px;  }
       input[type="date"] { width: 150px; }
       label {  display:inline-block; width: 100px; }

    

      </style>
    </head>

    <body>

      <form id="form1" name="form1" method="post">       
        <select id="select1" onchange="this.form.action=this.value; showForm (this.value);">
          <option value="" selected>Select option</option>
          <option value="getitemlist.php">Item List</option>
          <option value="getorderlist.php">Order List</option>
          <option value="getshipmentlist.php">Shipment List</option>
          <option value="getpushconfig.php">Push Config</option>
          <option value="getshippingdocumentresult.php">Shipping Document Result</option>
          <option value="gettrackinginfo.php">Tracking Info</option>
        </select> 
             
        <div id="div1" style="display: none;"><input type="text" name="order_sn" id="order_sn" placeholder="order_sn" value="2201052262MB8D"></div>

        <button id="btn1" type="button" onclick="ajax (this.form, document.getElementById ('result'));">Submit</button>

      </form>
      
      <br>
      <div id="result"></div>


      <script>
        for (var i = 0, x = document.getElementsByClassName ("myform"); i < x.length; i++) x[i].style.display = "none";

      </script>
  </body>
</html>