function chooseSupplierPR(){
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+'po/getsupplierPR';
    var supplier = document.getElementById("supplier").value;
    $.ajax({
            type: 'POST',
            url: redirect,
            data: 'supplier='+supplier,
            success: function(data){
                $("#prno").html(data);
           }
    }); 
}

function choosePR()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'po/getPRinformation';
    var prid = document.getElementById("prno").value;

      $.ajax({
            type: 'POST',
            url: redirect,
            data: 'prid='+prid,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("purpose").innerHTML  = response.purpose;
               document.getElementById("enduse").innerHTML  = response.enduse;
               document.getElementById("requestor").innerHTML  = response.requestor;
               document.getElementById("aoq_id").value  = response.aoq_id;
            
           }
        }); 
}

function chooseSupplier()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'po/getsupplier';
    var supplier = document.getElementById("supplier").value;

      $.ajax({
            type: 'POST',
            url: redirect,
            data: 'supplier='+supplier,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("address").innerHTML  = response.address;
               document.getElementById("phone").innerHTML  = response.phone;
               document.getElementById("contact").innerHTML  = response.contact;
            
           }
        }); 
}

function chooseSupplierrep()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'index.php/po/getsupplier';
    var supplier = document.getElementById("supplierrep").value;

      $.ajax({
            type: 'POST',
            url: redirect,
            data: 'supplier='+supplier,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("addressrep").innerHTML  = response.address;
               document.getElementById("phonerep").innerHTML  = response.phone;
               document.getElementById("contactrep").innerHTML  = response.contact;
            
           }
        }); 
}
function getPRInfo()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'index.php/po/getpr';
   var pr = document.getElementById("pr").value;
   
      $.ajax({
            type: 'POST',
            url: redirect,
            data: 'pr='+pr,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("purpose").value  = response.purpose;
               document.getElementById("enduse").value  = response.enduse;
               document.getElementById("requestor").value  = response.requestor;
            
           }
        }); 
}
$(document).on("click", ".addPR", function () {
     var po_id = $(this).data('id');
     $(".modal-body #po_id").val(po_id);
  
});

$(document).on("click", ".approverev", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});

$(document).on("click", ".cancelPO", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});

$(document).on("click", ".cancelDuplicatePO", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});

$(document).on("click", ".deliverpo", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});

$(document).on("click", "#updateTerm", function () {
    var tc_id = $(this).attr("data-id");
    var terms = $(this).attr("data-name");
    $("#tc_id").val(tc_id);
    $("#terms").val(terms);
  
});

$(document).on("click", "#updateTermRep", function () {
    var tc_id = $(this).attr("data-id");
    var terms = $(this).attr("data-name");
    $("#tc_id").val(tc_id);
    $("#termsrep").val(terms);
  
});

$(document).on("click", "#edits", function () {
    var tc_id = $(this).attr("data-id");
    var notes = $(this).attr("data-name");
    $("#tc1_id").val(tc_id);
    $("#notes").val(notes);
  
});


function addItemPo(baseurl,pr) {
    window.open(baseurl+"index.php/po/add_itempo/"+pr, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=800,height=500");
}

function changePrice(count){
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
    var vat = document.getElementById("vat_percent").value;
    var percent=vat/100;
    var new_vat = parseFloat(percent)*parseFloat(grandtotal);
    $("#vat").val(new_vat);
    document.getElementById("grandtotal").innerHTML  =grandtotal+new_vat;
    document.getElementById("orig_amount").value  =grandtotal;
    document.getElementById("grandtotal").innerHTML  =grandtotal;
}

function additionalCost(){
  var total = document.getElementById("orig_amount").value;
//var total = document.getElementById("it_").value;

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

/*function deductCost(){
  var total = document.getElementById("orig_amount").value;
   var discount = document.getElementById("discount").value;

   var new_total = parseFloat(total)-parseFloat(discount);
 
    document.getElementById("grandtotal").innerHTML  =new_total;
}*/
function addPo(baseurl,po_id,vendor_id,pr_id,group_id) {
    window.open(baseurl+"po/addPo/"+vendor_id+'/'+po_id+'/'+pr_id+'/'+group_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=45,left=25,width=1300,height=600");
}

function generatePO(baseurl,vendor_id,po_id,pr_id,group_id,oldid){
  
  window.location.href=baseurl+'po/addPo/'+vendor_id+'/'+po_id+'/'+pr_id+'/'+group_id+'/'+oldid;
}

$(document).on("click", "#polink_button", function () {
     var poid = $(this).attr("data-id");
     $("#poid").val(poid);
});


function viewHistory(baseurl,id,po_no) {
    window.open(baseurl+"po/view_history/"+id+"/"+po_no, "_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=500,height=500");
}

function deliver_po(baseurl,jo_id, dr_id) {
    window.open(baseurl+"po/deliver_po/"+jo_id+"/"+dr_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=45,left=25,width=1300,height=600");
}