function chooseSupplier()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'index.php/po/getsupplier';
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

$(document).on("click", ".cancelPO", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});

$(document).on("click", ".cancelDuplicatePO", function () {
     var po_id = $(this).data('id');
     $(".modal #po_id").val(po_id);
  
});


function addItemPo(baseurl,pr) {
    window.open(baseurl+"index.php/po/add_itempo/"+pr, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=800,height=500");
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
}

function addPo(baseurl,poid,supplier) {
    window.open(baseurl+"po/addPo/"+poid+"/"+supplier, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=45,left=25,width=1300,height=600");
}

function generatePO(po,poid,supplier,baseurl){
  
  window.location.href=baseurl+'po/addPo/'+poid+'/'+supplier+'/'+po;
}

$(document).on("click", "#polink_button", function () {
     var poid = $(this).attr("data-id");
     $("#poid").val(poid);
});


function viewHistory(baseurl,id,po_no) {
    window.open(baseurl+"index.php/po/view_history/"+id+"/"+po_no, "_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=500,height=500");
}