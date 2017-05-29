<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unit List</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <style> 
.box-shadow {
    box-shadow: 3px 3px 10px grey;
}
</style>
    </head> 
<body>
    <BR><br><BR>
    <div class="container box-shadow">
        

        <h3>District</h3>
        <br />
        <button class="btn btn-success" onclick="add_district()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>District ID</th>
                    <th>Pratham District Name</th>
                    <th>Census District Name</th>
                    <th>DISE District Name</th>
                    <th>District Code</th>
                    <th>State Name</th>
                    <th style="width:125px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

        </table>
    </div>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('district/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_district()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New District'); // Set Title to Bootstrap modal title
}

function edit_district(DistrictId)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('district/ajax_edit/')?>/" + DistrictId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="DistrictId"]').val(data.DistrictId);
            $('[name="PrathamDistrictName"]').val(data.PrathamDistrictName);

            $('[name="IsFoundInCensusList"]').val(data.IsFoundInCensusList);
            $('[name="CensusDistrictName"]').val(data.CensusDistrictName);
  
            $('[name="IsFoundInDISEList"]').val(data.IsFoundInDISEList);
            $('[name="DISEDistrictName"]').val(data.DISEDistrictName);
            
            $('[name="DistrictCode"]').val(data.DistrictCode);
           

            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit District Name'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

//function to get data for update to be delete row's
function edit_del(DistrictId)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('district/ajax_edit/')?>/" + DistrictId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="DistrictId"]').val(data.DistrictId);
            $('[name="PrathamDistrictName"]').val(data.PrathamDistrictName);
            $('[name="CensusDistrictName"]').val(data.CensusDistrictName);
            $('[name="DISEDistrictName"]').val(data.DISEDistrictName);
            $('[name="IsDeleted"]').val(data.IsDeleted);
            $('#delete_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Delete District Name'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('district/ajax_add')?>";
    } else {
        url = "<?php echo site_url('district/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                { 
                    $('[PrathamDistrictName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[PrathamDistrictName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                 
                    if(save_method == 'add') { 
                        $('[CreatedBy="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[CreatedBy="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        } else {
                        $('[LastUpdatedBy="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[LastUpdatedBy="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        $('[LastUpdatedOn="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[LastUpdatedOn="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                               
                        }


                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}
// save deleted item
function save_del()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
        url = "<?php echo site_url('district/ajax_update_del')?>";
 
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_del').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#delete_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                { 
                    $('[PrathamDistrictName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[PrathamDistrictName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    $('[CensusDistrictName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[CensusDistrictName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    $('[DISEDistrictName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[DISEDistrictName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    
                    $('[IsDeleted="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[IsDeleted="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    $('[LastUpdatedBy="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[LastUpdatedBy="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    $('[LastUpdatedOn="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[LastUpdatedOn="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string

                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error delete data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add District</h3>
            </div>




            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="DistrictId"/> 
                    <input type="hidden" value="Vikas Saini" name="CreatedBy"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>
                    <div class="form-body">

                        <div class="form-group">
                         <label class="control-label col-md-3">State Name</label>
                            <div class="col-md-9">
                                    <select id = "StateId" name="StateId" class="form-control" placeholder="Select State Name">
                                    <?php
                                    foreach ($fetch_statename as $row){
                                        echo "<option value='".$row['StateId']."'>".$row['StateName']."</option>";
                                    }
                                    ?>


                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Pratham District Name</label>
                            <div class="col-md-9">
                                <input name="PrathamDistrictName" placeholder="Pratham District Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Is Found In Census List</label>
                            <div class="col-md-9">
                                <select name="IsFoundInCensusList" placeholder="IsFoundInCensusList" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Census District Name</label>
                            <div class="col-md-9">
                                <input name="CensusDistrictName" placeholder="Census District Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3">Is Found In DISE List</label>
                        <div class="col-md-9">
                            <select name="IsFoundInDISEList" placeholder="IsFoundInDISEList" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">DISE District Name</label>
                            <div class="col-md-9">
                                <input name="DISEDistrictName" placeholder="DISE District Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <input type="hidden" value="<?php
                                    $below10 = 9;
                                    $below100 = 99;
                                    $below1000 = 100;
                                    foreach ($getLastInserted as $row){

                                        $lastid = $row['DistrictId'];
                                        
                                        if($lastid < $below10) { 
                                            echo "D00",$row['DistrictId']+1;

                                        } elseif($lastid < $below100) { 
                                            echo "D0",$row['DistrictId']+1;

                                         } elseif($lastid > $below100){

                                            echo "D",$row['DistrictId']+1;
                                         }
                                    }
                                    
                                    ?>" name="DistrictCode"/>
                    
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Bootstrap Delete modal -->
<div class="modal fade" id="delete_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">District</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_del" class="form-horizontal">
                    <input type="hidden" value="" name="DistrictId"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Pratham District Name</label>
                            <div class="col-md-9">
                                <input name="PrathamDistrictName" placeholder="Pratham District Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Census District Name</label>
                            <div class="col-md-9">
                                <input name="CensusDistrictName" placeholder="Census District Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">DISE District Name</label>
                            <div class="col-md-9">
                                <input name="DISEDistrictName" placeholder="DISE District Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">want to Delete</label>
                            <div class="col-md-9">
                                <select name="IsDeleted" placeholder="want to delete" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_del()" class="btn btn-primary">Delete</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /. Delete modal -->
<!-- End Bootstrap modal -->
<BR><BR>
</body>
</html>