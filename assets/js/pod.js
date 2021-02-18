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
    var vat = document.getElementById("vat_percent").value;
    var percent=vat/100;
    var new_vat = parseFloat(percent)*parseFloat(grandtotal);
    $("#vat").val(new_vat);
    document.getElementById("grandtotal").innerHTML  =grandtotal;
    document.getElementById("orig_amount").value  =grandtotal;
    document.getElementById("grandtotal").innerHTML  =grandtotal+new_vat;
}

function additionalCost(){
  var total = document.getElementById("orig_amount").value;

   var shipping = document.getElementById("shipping").value;
    var discount = document.getElementById("discount").value;
    var packing = document.getElementById("packing").value;
    var vat = document.getElementById("vat").value;

   var new_total = (parseFloat(total)+parseFloat(shipping)+parseFloat(packing)+parseFloat(vat))-parseFloat(discount);
 
    document.getElementById("grandtotal").innerHTML  =new_total;
}

$(document).ready(function(){
    $('#vat_percent').keyup( function(){
        var vat = document.getElementById("vat_percent").value;
        var total = document.getElementById("orig_amount").value;
        var percent=vat/100;
        var new_vat = parseFloat(percent)*parseFloat(total);
        $("#vat").val(new_vat);
        var new_total=parseFloat(total)+parseFloat(new_vat);
        document.getElementById("grandtotal").innerHTML  =new_total;
    });
});

window.onload=function() {
    var vat = document.getElementById("vat").value;
    var total = document.getElementById("orig_amount").value;
    var new_total = (parseFloat(total)+parseFloat(vat));
    document.getElementById("grandtotal").innerHTML=new_total;
};

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

$(document).on("click", "#edits", function () {
    var tc_id = $(this).attr("data-id");
    var notes = $(this).attr("data-name");
    $("#tc1_id").val(tc_id);
    $("#notes").val(notes);
  
});