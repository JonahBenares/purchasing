<div class="modal-header" id='search'>
                                        
    <div class="row">
        <div class="col-lg-6">
            <h4 class="modal-title" id="exampleModalLabel" style="font-size: 16px;font-weight: 600">
                <select class="form-control" id = "getCal">
                    <option value="" selected>Choose PR</option>
                    <?php foreach($prs AS $p){ ?>
                    <option value="<?php echo $p['pr_id']; ?>"><?php echo $p['pr_no']; ?></option>
                    <?php } ?>
                </select>                                                    
            </h4>
        </div>
        <div class="col-lg-6">
            <input type="hidden" id="proj_act_id" value="<?php echo $proj_act_id; ?>">
            <input type="hidden" id="baseurl" value="<?php echo base_url(); ?>">
            <input type="hidden" id="year" value="<?php echo $year; ?>">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>                                        
</div>
<div class="modal-body" id ="shows"></div>
<script type="">
    $(document).on('change', '#getCal', function(e){
        e.preventDefault();     
        var getCal= document.getElementById("getCal").value;
        var year= document.getElementById("year").value;
        var proj_act_id= document.getElementById("proj_act_id").value;
        var loc= document.getElementById("baseurl").value;
        var redirect1=loc+'reports/getCalendar_disp';
        $.ajax({
              url: redirect1,
              type: 'POST',
              data: 'id='+proj_act_id+'&pr_id='+getCal+'&year'+year,
            beforeSend:function(){
                $("#shows").html('Please wait ..');
            },
            success:function(data){
               $("#shows").html(data);
            },
        })
    });
</script>
