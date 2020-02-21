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
               document.getElementById("contact_person").innerHTML  = response.contact_person;
            
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
  /* var price = document.getElementById("unit_cost").value;
   var qty = document.getElementById("quantity").value;*/
   var sum_cost =document.getElementById("sum_cost").value;


  var less_percent = document.getElementById("less_percent").value;
   var less = less_percent/100;
   var less_amount = parseFloat(sum_cost) * parseFloat(less);

   var net =  parseFloat(sum_cost) - parseFloat(less_amount);
   document.getElementById("less_amount").value  =less_amount.toFixed(2);
   document.getElementById("net").value  =net;


  document.getElementById("gtotal").innerHTML  = net.toFixed(2);
 
}

function changePrice_JO(count){
  //alert(count);
   var price = document.getElementById("price"+count).value;

   var qty = document.getElementById("quantity"+count).value;

   var tprice = parseFloat(price) * parseFloat(qty);

   document.getElementById("tprice"+count).value  =tprice;

    /*var total_pr=0;
    $(".tprice").each(function(){
          total_pr += parseFloat($(this).val());
    });*/

   //  document.getElementById("total_pr"+countPR).value  =total_pr;
    var grandtotal=0;
    $(".tprice").each(function(){
          var p = $(this).val().replace(",", "");
          grandtotal += parseFloat(p);
    });

    var grandtotal1=0;
    $(".tprice").each(function(){
          var p1 = $(this).val().replace(",", "");
          grandtotal1 += parseFloat(p1);
    });
   
     document.getElementById("grandtotal").innerHTML  =grandtotal;
     document.getElementById("grandtotal1").innerHTML  =grandtotal1;
}

$(document).on("click", ".approverev", function () {
     var jo_id = $(this).data('id');
     $(".modal #jo_id").val(jo_id);
  
});

function viewHistory(baseurl,id,cenjo_no,jo_no) {
    window.open(baseurl+"jo/view_history/"+id+"/"+cenjo_no+"/"+jo_no, "_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=500,height=500");
}

$(document).on("click", ".cancelJO", function () {
     var jo_id = $(this).data('id');
     $(".modal #jo_id").val(jo_id);
  
});

