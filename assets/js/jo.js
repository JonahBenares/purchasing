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
   var mat_sum_cost =document.getElementById("mat_sum_cost").value;

    var vat_percent = document.getElementById("vat_percent").value;
    var vat = vat_percent/100;
    var total= parseFloat(sum_cost)+parseFloat(mat_sum_cost);
    var sumvat=parseFloat(total)*parseFloat(vat);
    //var vat_amount = parseFloat(sum_cost) * parseFloat(vat);
    var vat_amount = total * parseFloat(sumvat);
    //document.getElementById("vat_amount").value  =vat_amount.toFixed(2);;
    document.getElementById("vat_amount").value  =sumvat.toFixed(2);
    var subtotal = parseFloat(sum_cost) + parseFloat(mat_sum_cost) + parseFloat(sumvat);
    document.getElementById("subtotal").value  =subtotal.toFixed(2);

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
    if(parseFloat(sum_cost)<10000){
      $("#hide_work").show();
      $("#hide_input").show();
      $("#border_work").show();
    }else{
      $('#hide_work').hide();
      $('#hide_input').hide();
      $('#border_work').hide();
    }
}

function changePrice_rfd(){
    var gtotal = document.getElementById("total_amount").value;
    var mtotal = document.getElementById("mtotal_amount").value;
    var sum_amount = document.getElementById("sum_amount").value;
    var payment_amount = document.getElementById("payment_amount").value;
    var discount = document.getElementById("discount_deduct").value;
    if(sum_amount!=''){
      var totalamdue =  parseFloat(gtotal) + parseFloat(mtotal) - parseFloat(sum_amount) - parseFloat(discount);
      var balnet =  parseFloat(totalamdue) - parseFloat(payment_amount);
      //var balnet =  parseFloat(gtotal) + parseFloat(mtotal) - parseFloat(payment_amount) - parseFloat(sum_amount);
    }else{
      var totalamdue =  parseFloat(gtotal) + parseFloat(mtotal) - parseFloat(discount);
      var balnet =  parseFloat(totalamdue) - parseFloat(payment_amount);
      //var balnet =  parseFloat(gtotal) + parseFloat(mtotal) - parseFloat(payment_amount);
    }


    document.getElementById("balaft").innerHTML  = balnet.toFixed(2);
    //document.getElementById("totalamdue").innerHTML  = totalamdue.toFixed(2);
}

function check_rfd(){
    var payment_amount = document.getElementById("payment_amount").value;
    var rows_rfd = document.getElementById("rows_rfd").value;
    if(payment_amount==0 && rows_rfd !=0){
      alert('Sorry Payment Amount cannot be zero!');
      $("#submit").attr('disabled','disabled');
    }else{
      $('#submit').removeAttr('disabled');
    }
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
     var mat_sum_cost =document.getElementById("mat_sum_cost").value;

    var vat_percent = document.getElementById("vat_percent").value;
    var vat = vat_percent/100;
    var total=parseFloat(sum_cost)+parseFloat(mat_sum_cost);
    var sumvat=total*vat;
    var vat_amount = parseFloat(total) * parseFloat(sumvat);
    document.getElementById("vat_amount").value  =sumvat.toFixed(2);;
     var subtotal = parseFloat(sum_cost)  + parseFloat(mat_sum_cost) + parseFloat(sumvat);
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

function changematerialsPrice_JO(count,countb){
  //alert(count);
   var price = document.getElementById("materials_price"+count+"_"+countb).value;

   var qty = document.getElementById("materials_qty"+count+"_"+countb).value;

   var tprice = parseFloat(price) * parseFloat(qty);

   document.getElementById("materials_tprice"+count+"_"+countb).value  =tprice;

    /*var total_pr=0;
    $(".tprice").each(function(){
          total_pr += parseFloat($(this).val());
    });*/

   //  document.getElementById("total_pr"+countPR).value  =total_pr;
    var grandtotal=0;
    $(".materials_tprice").each(function(){
          var p = $(this).val().replace(",", "");
          grandtotal += parseFloat(p);
    });

    var grandtotal1=0;
    $(".materials_tprice").each(function(){
          var p1 = $(this).val().replace(",", "");
          grandtotal1 += parseFloat(p1);
    });
   
    document.getElementById("mat_sum_cost").value  =grandtotal1;

     var mat_sum_cost =document.getElementById("mat_sum_cost").value;
     var sum_cost =document.getElementById("sum_cost").value;

    var vat_percent = document.getElementById("vat_percent").value;
    var vat = vat_percent/100;
    var vat_amount = parseFloat(mat_sum_cost) * parseFloat(vat);
    document.getElementById("vat_amount").value  =vat_amount.toFixed(2);;
     var subtotal = parseFloat(sum_cost) + parseFloat(mat_sum_cost) + parseFloat(vat_amount);
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

function changesinglePrice_JO(){
  //alert(count);
   var price = document.getElementById("materials_price").value;

   var qty = document.getElementById("materials_qty").value;

   var tprice = parseFloat(price) * parseFloat(qty);

   document.getElementById("materials_tprice").value  =tprice;

    /*var total_pr=0;
    $(".tprice").each(function(){
          total_pr += parseFloat($(this).val());
    });*/

   //  document.getElementById("total_pr"+countPR).value  =total_pr;
    var grandtotal=0;
    $(".materials_tprice").each(function(){
          var p = $(this).val().replace(",", "");
          grandtotal += parseFloat(p);
    });

    var grandtotal1=0;
    $(".materials_tprice").each(function(){
          var p1 = $(this).val().replace(",", "");
          grandtotal1 += parseFloat(p1);
    });
   
    document.getElementById("mat_sum_cost").value  =grandtotal1;

     var mat_sum_cost =document.getElementById("mat_sum_cost").value;

    var vat_percent = document.getElementById("vat_percent").value;
    var vat = vat_percent/100;
    var vat_amount = parseFloat(mat_sum_cost) * parseFloat(vat);
    document.getElementById("vat_amount").value  =vat_amount.toFixed(2);
     var subtotal = parseFloat(sum_cost) + parseFloat(mat_sum_cost) + parseFloat(vat_amount);
       document.getElementById("subtotal").value  =subtotal.toFixed(2);
  var less =document.getElementById("less_amount").value;
  var net =  parseFloat(subtotal) - parseFloat(less);
  document.getElementById("net").value  =net.toFixed(2);;
  document.getElementById("gtotal").innerHTML  = net.toFixed(2);
}

$(document).on("click", ".approverev", function () {
     var jo_id = $(this).data('id');
     $(".modal #jo_id").val(jo_id);
  
});

function viewHistory(baseurl,id,cenjo_no,jo_no) {
    window.open(baseurl+"jo/view_history/"+id+"/"+cenjo_no+"/"+jo_no, "_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=500,height=500");
}

function viewHistoryjoi(baseurl,id,cenjo_no,jo_no) {
    window.open(baseurl+"joi/view_history/"+id+"/"+cenjo_no+"/"+jo_no, "_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=500,height=500");
}
$(document).on("click", ".cancelJO", function () {
     var jo_id = $(this).data('id');
     $(".modal #jo_id").val(jo_id);
  
});

function chooseSupplierJO(){
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+'joi/getsupplierJOI';
    var supplier = document.getElementById("supplier").value;
    $.ajax({
        type: 'POST',
        url: redirect,
        data: 'supplier='+supplier,
        success: function(data){
            $("#jo_no").html(data);
        }
    }); 
    var redirect1 = loc+'joi/getsupplier';
    $.ajax({
        type: 'POST',
        url: redirect1,
        data: 'supplier='+supplier,
        dataType: 'json',
        success: function(response){
            document.getElementById("address").innerHTML  = response.address;
            document.getElementById("phone").innerHTML  = response.phone;
        }
    });
}

function chooseSupplierJOD(){
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+'jod/getsupplierJOD';
    var supplier = document.getElementById("supplier").value;
    $.ajax({
        type: 'POST',
        url: redirect,
        data: 'supplier='+supplier,
        success: function(data){
            $("#jo_no").html(data);
        }
    }); 
    var redirect1 = loc+'jod/getsupplier';
    $.ajax({
        type: 'POST',
        url: redirect1,
        data: 'supplier='+supplier,
        dataType: 'json',
        success: function(response){
            document.getElementById("address").innerHTML  = response.address;
            document.getElementById("phone").innerHTML  = response.phone;
        }
    });
}

function chooseJO(){
  var loc= document.getElementById("baseurl").value;
  var redirect = loc+'joi/getJOinformation';
  var jor_id = document.getElementById("jo_no").value;
  $.ajax({
      type: 'POST',
      url: redirect,
      data: 'jor_id='+jor_id,
      dataType: 'json',
      success: function(response){
        document.getElementById("project_title").value  = response.purpose;
        document.getElementById("date_prepared").value  = response.date_prepared;
        document.getElementById("user_jo_no").value  = response.user_jo_no;
        document.getElementById("jor_aoq_id").value  = response.jor_aoq_id;
        document.getElementById("general_desc").value  = response.general_desc;
      }
  }); 
}

function chooseJOD(){
  var loc= document.getElementById("baseurl").value;
  var redirect = loc+'jod/getJODinformation';
  var jor_id = document.getElementById("jo_no").value;
  $.ajax({
      type: 'POST',
      url: redirect,
      data: 'jor_id='+jor_id,
      dataType: 'json',
      success: function(response){
        document.getElementById("project_title").value  = response.purpose;
        document.getElementById("date_prepared").value  = response.date_prepared;
        document.getElementById("user_jo_no").value  = response.user_jo_no;
        document.getElementById("jor_aoq_id").value  = response.jor_aoq_id;
        document.getElementById("general_desc").value  = response.general_desc;
      }
  }); 
}

function chooseSupplier(){
  var loc= document.getElementById("baseurl").value;
  alert(loc);
  var redirect = loc+'joi/getsupplier';
  var supplier = document.getElementById("supplier").value;
  $.ajax({
    type: 'POST',
    url: redirect,
    data: 'supplier='+supplier,
    dataType: 'json',
    success: function(response){
      document.getElementById("address").innerHTML  = response.address;
      document.getElementById("phone").innerHTML  = response.phone;
    }
  }); 
}

function chooseSupplierJOD(){
  var loc= document.getElementById("baseurl").value;
  alert(loc);
  var redirect = loc+'jod/getsupplier';
  var supplier = document.getElementById("supplier").value;
  $.ajax({
    type: 'POST',
    url: redirect,
    data: 'supplier='+supplier,
    dataType: 'json',
    success: function(response){
      document.getElementById("address").innerHTML  = response.address;
      document.getElementById("phone").innerHTML  = response.phone;
    }
  }); 
}

$(document).on("click", "#updateTerm", function () {
    var tc_id = $(this).attr("data-id");
    var terms = $(this).attr("data-name");
    $("#tc_id").val(tc_id);
    $("#terms").val(terms);
  
});


$(document).on("click", "#jo", function () {
    var joi_id1 = $(this).attr("data-id");
    $("#joi_id1").val(joi_id1);
});

$(document).on("click", "#prnt_btn", function () {
    var payment = $(this).attr("data-payment");
    var warranty = $(this).attr("data-warranty");
    var delivery = $(this).attr("data-delivery");
    var freight = $(this).attr("data-freight");
    $("#payment").val(payment);
    $("#warranty").val(warranty);
    $("#delivery").val(delivery);
    $("#freight").val(freight);
});

function minmax(value, min, max) {
  if(parseFloat(value) < min || isNaN(parseFloat(value))){ 
    return 0;
  } else if(parseFloat(value) > max) {
    alert("JOI Quantity is more than JOR Quantity!");
    return max; 
  }else{
    return value;
  }
}

function deliver_jo(baseurl,joi_id, joi_dr_id) {
    window.open(baseurl+"joi/deliver_jo/"+joi_id+"/"+joi_dr_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=45,left=25,width=1300,height=600");
}

function deliver_jod(baseurl,joi_id, joi_dr_id) {
    window.open(baseurl+"jod/deliver_jod/"+joi_id+"/"+joi_dr_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=45,left=25,width=1300,height=600");
}


function chooseEmpchecked(){
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+'index.php/joi/getEmpChecked';
    var checked = document.getElementById("checked").value;
    document.getElementById('altss').innerHTML='<b>Please wait, Loading data...</b>'; 
    $.ajax({
        type: 'POST',
        url: redirect,
        data: 'employee_id='+checked,
        dataType: 'json',
        success: function(response){
            $("#altss").hide();
            $("#positionchecked").html(response.position);
        }
    }); 
}

function chooseEmpapprove(){
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+'index.php/joi/getEmpChecked';
    var approved = document.getElementById("approved").value;
    document.getElementById('altsss').innerHTML='<b>Please wait, Loading data...</b>'; 
    $.ajax({
        type: 'POST',
        url: redirect,
        data: 'employee_id='+approved,
        dataType: 'json',
        success: function(response){
            $("#altsss").hide();
            $("#positionapproved").html(response.position);
        }
    }); 
}