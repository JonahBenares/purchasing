function additemdr(baseurl,drid) {
    window.open(baseurl+"index.php/dr/additemdr/"+drid, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=450,width=500,height=550");
}

function chooseSupplier(){
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+'dr/getSupplierItems';
    var supplier = document.getElementById("supplier").value;
    $.ajax({
            type: 'POST',
            url: redirect,
            data: 'supplier='+supplier,
            success: function(data){
                $("#items").html(data);
           }
    }); 
}
