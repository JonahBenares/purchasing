function choosePR()
{

   var loc= document.getElementById("baseurl").value;
   var redirect = loc+'index.php/aoq/getpr';
    var pr = document.getElementById("pr").value;

      $.ajax({
            type: 'POST',
            url: redirect,
            data: 'pr='+pr,
            dataType: 'json',
            success: function(response){
              
               document.getElementById("purpose_name").innerHTML  = response.purpose;
               document.getElementById("enduse_name").innerHTML  = response.enduse;
               document.getElementById("department_name").innerHTML  = response.department;
               document.getElementById("requested_name").innerHTML  = response.requestor;
               document.getElementById("purpose").value =  response.purpose_id;
               document.getElementById("enduse").value =  response.enduse_id;
               document.getElementById("department").value =  response.department_id;
               document.getElementById("requested_by").value =  response.requestor_id;
            
           }
        }); 
}

$(document).on("click", ".cancelAOQ", function () {
    var aoq_id = $(this).data('id');
    $(".modal #aoq_id").val(aoq_id);
});