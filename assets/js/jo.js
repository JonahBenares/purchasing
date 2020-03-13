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
               document.getElementById("fax").innerHTML  = response.fax;
            
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

    var vat_percent = document.getElementById("vat_percent").value;
    var vat = vat_percent/100;
    var vat_amount = parseFloat(sum_cost) * parseFloat(vat);
    document.getElementById("vat_amount").value  =vat_amount.toFixed(2);;
     var subtotal = parseFloat(sum_cost) + parseFloat(vat_amount);
       document.getElementById("subtotal").value  =subtotal.toFixed(2);;

  /*var less_percent = document.getElementById("less_percent").value;
   var less = less_percent/100;*/
   var less =document.getElementById("less_amount").value;
   /*var less_amount = parseFloat(subtotal) - parseFloat(less);*/

   var net =  parseFloat(subtotal) - parseFloat(less);
   /*document.getElementById("less_amount").value  =less.toFixed(2);*/
   document.getElementById("net").value  =net.toFixed(2);


  /*document.getElementById("gtotal").innerHTML  = net.toFixed(2);*/
    document.getElementById("gtotal").innerHTML  = net.toFixed(2);
   //document.getElementById("grandtotal1").innerHTML  = net.toFixed(2);
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
   
    document.getElementById("sum_cost").value  =grandtotal1;

     var sum_cost =document.getElementById("sum_cost").value;

    var vat_percent = document.getElementById("vat_percent").value;
    var vat = vat_percent/100;
    var vat_amount = parseFloat(sum_cost) * parseFloat(vat);
    document.getElementById("vat_amount").value  =vat_amount.toFixed(2);;
     var subtotal = parseFloat(sum_cost) + parseFloat(vat_amount);
       document.getElementById("subtotal").value  =subtotal.toFixed(2);;

       var less =document.getElementById("less_amount").value;
   /*var less_amount = parseFloat(subtotal) - parseFloat(less);*/

   var net =  parseFloat(subtotal) - parseFloat(less);

  /*  document.getElementById("grandtotal1").innerHTML  = net.toFixed(2);
     document.getElementById("net").value  =net;*/
  document.getElementById("net").value  =net.toFixed(2);;


  /*document.getElementById("gtotal").innerHTML  = net.toFixed(2);*/
    document.getElementById("gtotal").innerHTML  = net.toFixed(2);

     /*document.getElementById("grandtotal").innerHTML  =grandtotal;
     document.getElementById("grandtotal1").innerHTML  =grandtotal1;*/
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

