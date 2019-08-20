function chooseVendor()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'jo/getVendorInformation';
    var vendor = document.getElementById("vendor").value;

      $.ajax({
            type: 'POST',
            url: redirect,
            data: 'vendor='+vendor,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("address").innerHTML  = response.address;
               document.getElementById("phone").innerHTML  = response.phone;
            
           }
        }); 
}

function getJO(){
  var date_prepared = document.getElementById("date_prepared").value;
  var res = date_prepared.split("-");
  var year = res[0];
  var loc= document.getElementById("baseurl").value;
  var redirect = loc+'jo/getJoNo';
   $.ajax({
            type: 'POST',
            url: redirect,
            data: 'year='+year,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("jo_no").value  = response.jo_no;
            
           }
        }); 
}

function changePrice(){
   var price = document.getElementById("unit_cost").value;
   var qty = document.getElementById("quantity").value;
   var tprice = parseFloat(price) * parseFloat(qty);

   document.getElementById("total_cost").value  =tprice;

    var total_cost = document.getElementById("total_cost").value;
  var less_percent = document.getElementById("less_percent").value;
   var less = less_percent/100;
   var less_amount = parseFloat(total_cost) * parseFloat(less);

   var net =  parseFloat(total_cost) - parseFloat(less_amount);
   document.getElementById("less_amount").value  =less_amount.toFixed(2);
   document.getElementById("net").value  =net;


  document.getElementById("gtotal").innerHTML  = net.toFixed(2);
 
}

