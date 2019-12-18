function additempod(baseurl,po_id,supplier_id) {
    window.open(baseurl+"index.php/pod/additempod/"+po_id+"/"+supplier_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=45,left=75,width=1200,height=600");
}

function changePrice(count){
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
   
     document.getElementById("grandtotal").innerHTML  =grandtotal;
       document.getElementById("orig_amount").value  =grandtotal;
}

function additionalCost(){
  var total = document.getElementById("orig_amount").value;

   var shipping = document.getElementById("shipping").value;
    var discount = document.getElementById("discount").value;

   var new_total = (parseFloat(total)+parseFloat(shipping))-parseFloat(discount);
 
    document.getElementById("grandtotal").innerHTML  =new_total;
}

$(document).on("click", ".cancelPO", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});

$(document).on("click", "#updateTerm", function () {
    var tc_id = $(this).attr("data-id");
    var terms = $(this).attr("data-name");
    $("#tc_id").val(tc_id);
    $("#terms").val(terms);
  
});