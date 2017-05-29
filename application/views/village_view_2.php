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
.margin-bottom {
margin-bottom:0px;}
</style>
    </head> 
<body>
    <BR><br><BR>
    <div class="container box-shadow" style="overflow-x: auto">
        

        <h3>VIllage</h3>
        <br />
        <button class="btn btn-success" onclick="add_village()"><i class="glyphicon glyphicon-plus"></i>Add New</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i>Reload</button>
           <!--  <div class="col-md-2">
                <select id = "StateId" name="StateId" class="form-control c_state" placeholder="Select State Name">
                    <option value="">--Select--</option> 
                </select>
                <span class="help-block"></span>
            </div>
            <div class="col-md-2">
                <select id = "DistrictId" name="DistrictId" class="form-control c_district" placeholder="Select District Name">
                    <option value="">--Select--</option>                                   
                </select>
                <span class="help-block"></span>
            </div>
            <div class="col-md-2">
                <select id = "BlockId" name="BlockId" class="form-control c_block" placeholder="Select Block Name">
                    <option value="">--Select--</option> 
                </select>
                <span class="help-block"></span>
            </div> -->
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" style="width:auto">
            <thead>
                <tr>
                    <th>Village Id</th>
                    <th>Village Name</th>
                    <th>Village Code</th>
                    <th>Village Type</th>
                    <th>Block</th>
                    <th>District</th>
                    <th>State</th>
                    <th>Library</th>
                    <th>Read India</th>
                    <th>Balwadi</th>
                    <th>Balwachan</th>
                    <th>Upper Primary</th>
                    <th>H Learning</th>
                    <th>Other</th>
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
            "url": "<?php echo site_url('Village/ajax_list')?>",
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

/*function add_unit()
{
    save_method = 'add';
    $('#form_add_unit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_unit').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Unit'); // Set Title to Bootstrap modal title
}*/

function add_village()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Village'); // Set Title to Bootstrap modal title
}

function edit_village(VillageId)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('village/ajax_edit/')?>/" + VillageId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="e_StateName"]').val(data.StateName);
            $('[name="e_DistrictName"]').val(data.PrathamDistrictName);
            $('[name="e_BlockName"]').val(data.PrathamBlockName);
            $('[name="VillageId"]').val(data.VillageId);
            $('[name="VillageName"]').val(data.VillageName); 
            $('[name="VillageType"]').val(data.VillageType);          
            $('[name="VillageCode"]').val(data.VIllageCode);
            $('[name="DidLibraryEverHappen"]').val(data.DidLibraryEverHappen);
            $('[name="DidRIorRIPlusEverHappen"]').val(data.DidRIorRIPlusEverHappen);
            $('[name="DidBalwadiEverHappen"]').val(data.DidBalwadiEverHappen);
            $('[name="DidBalwachanEverHappen"]').val(data.DidBalwachanEverHappen);
            $('[name="DidUpperPrimaryEverHappen"]').val(data.DidUpperPrimaryEverHappen);
            $('[name="DidHLearningEverHappen"]').val(data.DidHLearningEverHappen);
            $('[name="DidAnyOtherInterventionsHappen"]').val(data.DidAnyOtherInterventionsHappen);
            $('#modal_form_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Village Name'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

// add unit form data start here
    function add_unit(VillageId)
{
    save_method = 'add_unit';
    $('#form_add_unit')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('village/ajax_edit_unit/')?>/" + VillageId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="VillageName"]').val(data.VillageName);
            $('[name="VillageId"]').val(data.VillageId);
            $('[name="PrathamBlockName"]').val(data.PrathamBlockName); 
            $('[name="BlockId"]').val(data.BlockId);
            $('[name="PrathamDistrictName"]').val(data.PrathamDistrictName);
            $('[name="DistrictId"]').val(data.DistrictId);            
            $('[name="StateName"]').val(data.StateName);          
            $('[name="StateId"]').val(data.StateId);
            $('#modal_form_unit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Add Unit Name'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

// add unit form data end here


//function to get data for update to be delete row's
function edit_del(VillageId)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('village/ajax_edit/')?>/" + VillageId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="VillageId"]').val(data.VillageId);
            $('[name="VillageName"]').val(data.VillageName);
            $('[name="IsDeleted"]').val(data.IsDeleted);
            $('#delete_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Delete Village'); // Set title to Bootstrap modal title

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
        url = "<?php echo site_url('village/ajax_add')?>";
    } else {
        url = "<?php echo site_url('village/ajax_update')?>";
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
                    $('[VillageName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[VillageName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                 
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
        url = "<?php echo site_url('village/ajax_update_del')?>";
 
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
                    $('[VillageName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[VillageName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
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

// Save Unit Begin Here
function save_unit()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
        url = "<?php echo site_url('village/ajax_add_unit')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_add_unit').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_unit').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                { 
                    $('[UnitName="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[UnitName="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                 
                    if(save_method == 'add_unit') { 
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

//Unit Save End Here


</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header margin-bottom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add Village</h3>
            </div>
            <div class="modal-body form margin-bottom">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="VillageId"/> 
                    <input type="hidden" value="Vikas Saini" name="CreatedBy"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>
                    <div class="form-body">
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">State</label>
                            <div class="col-md-9">

                                    <select id = "StateId" name="StateId" class="form-control c_state" placeholder="Select State Name">
                                    <option value="">--Select--</option> 
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">District</label>
                            <div class="col-md-9">

                                    <select id = "DistrictId" name="DistrictId" class="form-control c_district" placeholder="Select District Name">
                                    <option value="">--Select--</option> 
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">Block</label>
                            <div class="col-md-9">

                                    <select id = "BlockId" name="BlockId" class="form-control c_block" placeholder="Select Block Name">
                                    <option value="">--Select--</option> 
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Village Name</label>
                            <div class="col-md-9">
                                <input name="VillageName" placeholder="Village Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Village Type</label>
                            <div class="col-md-9">
                                <select name="VillageType" placeholder="Village Type" class="form-control">
                                    <option value="Rural">Rural</option>
                                    <option value="Urban">Urban</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <input type="hidden" value="<?php
                                    $below10 = 9;
                                    $below100 = 99;
                                    $below1000 = 100;
                                    foreach ($getLastInserted as $row){

                                        $lastid = $row['VillageId'];
                                        
                                        if($lastid < $below10) { 
                                            echo "B00",$row['VillageId']+1;

                                        } elseif($lastid < $below100) { 
                                            echo "B0",$row['VillageId']+1;

                                         } elseif($lastid > $below100){

                                            echo "B",$row['VillageId']+1;
                                         }
                                    }
                                    
                                    ?>" name="VillageCode"/>

                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Library Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidLibraryEverHappen" placeholder="Did Library Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did RI or RIPlus Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidRIorRIPlusEverHappen" placeholder="Did RI or RIPlus Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Balwadi Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidBalwadiEverHappen" placeholder="Did Balwadi Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>  
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Balwachan Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidBalwachanEverHappen" placeholder="Did Balwachan Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Upper Primary Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidUpperPrimaryEverHappen" placeholder="Did Upper Primary Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div> 
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did H Learning Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidHLearningEverHappen" placeholder="Did H Learning Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Any Other Interventions Happen</label>
                            <div class="col-md-9">
                                <select name="DidAnyOtherInterventionsHappen" placeholder="Did Any Other Interventions Happen" class="form-control">
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
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header margin-bottom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add Village</h3>
            </div>
            <div class="modal-body form margin-bottom">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="VillageId"/> 
                    <input type="hidden" value="Vikas Saini" name="CreatedBy"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>
                    <div class="form-body">
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">State</label>
                            <div class="col-md-9">

                                <input name="e_StateName" placeholder="State Name" class="form-control" type="text" disabled>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">District</label>
                            <div class="col-md-9">

                                       <input name="e_DistrictName" placeholder="District Name" class="form-control" type="text" disabled>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">Block</label>
                            <div class="col-md-9">

                                       <input name="e_BlockName" placeholder="Block Name" class="form-control" type="text" disabled>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Village Name</label>
                            <div class="col-md-9">
                                <input name="VillageName" placeholder="Village Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Village Type</label>
                            <div class="col-md-9">
                                <select name="VillageType" placeholder="Village Type" class="form-control">
                                    <option value="Rural">Rural</option>
                                    <option value="Urban">Urban</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <input type="hidden" value="<?php
                                    $below10 = 9;
                                    $below100 = 99;
                                    $below1000 = 100;
                                    foreach ($getLastInserted as $row){

                                        $lastid = $row['VillageId'];
                                        
                                        if($lastid < $below10) { 
                                            echo "B00",$row['VillageId']+1;

                                        } elseif($lastid < $below100) { 
                                            echo "B0",$row['VillageId']+1;

                                         } elseif($lastid > $below100){

                                            echo "B",$row['VillageId']+1;
                                         }
                                    }
                                    
                                    ?>" name="VillageCode"/>

                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Library Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidLibraryEverHappen" placeholder="Did Library Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did RI or RIPlus Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidRIorRIPlusEverHappen" placeholder="Did RI or RIPlus Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Balwadi Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidBalwadiEverHappen" placeholder="Did Balwadi Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>  
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Balwachan Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidBalwachanEverHappen" placeholder="Did Balwachan Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Upper Primary Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidUpperPrimaryEverHappen" placeholder="Did Upper Primary Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div> 
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did H Learning Ever Happen</label>
                            <div class="col-md-9">
                                <select name="DidHLearningEverHappen" placeholder="Did H Learning Ever Happen" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Did Any Other Interventions Happen</label>
                            <div class="col-md-9">
                                <select name="DidAnyOtherInterventionsHappen" placeholder="Did Any Other Interventions Happen" class="form-control">
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
                <h3 class="modal-title">Delete Village</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_del" class="form-horizontal">
                    <input type="hidden" value="" name="VillageId"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Village Name</label>
                            <div class="col-md-9">
                                <input name="VillageName" placeholder="Village Name" class="form-control" type="text">
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



<!-- Add Unit modal -->
<div class="modal fade" id="modal_form_unit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header margin-bottom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add Unit</h3>
            </div>
            <div class="modal-body form margin-bottom">
                <form action="#" id="form_add_unit" class="form-horizontal">
                    <input type="hidden" value="" name="UnitId"/>
                    <input type="hidden" value="" name="StateId"/>
                    <input type="hidden" value="" name="DistrictId"/>
                    <input type="hidden" value="" name="BlockId"/>
                    <input type="hidden" value="" name="VillageId"/> 
                    <input type="hidden" value="Vikas Saini" name="CreatedBy"/> 
                    <div class="form-body">
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">State Name</label>
                            <div class="col-md-9">
                                <input name="StateName" placeholder="State Name" class="form-control" type="text" value"" disabled>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">District Name</label>
                            <div class="col-md-9">
                                <input name="PrathamDistrictName" placeholder="District Name" class="form-control" type="text" disabled>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Block Name</label>
                            <div class="col-md-9">
                                <input name="PrathamBlockName" placeholder="Block Name" class="form-control" type="text" disabled>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">Village Name</label>
                            <div class="col-md-9">
                                <input name="VillageName" placeholder="Village Name" class="form-control" type="text" disabled>
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">Program Bucket</label>
                            <div class="col-md-9">

                                    <select id = "ProgramBucketId" name="ProgramBucketId" class="form-control" placeholder="Select Program Bucket Name">
                                    <option value="">--Select--</option> 
                                    <?php
                                    foreach ($fetch_programbucket as $row){
                                        echo "<option value='".$row['ProgramBucketId']."'>".$row['ProgramBucket']."</option>";
                                    }
                                    ?>
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">Unit Type</label>
                            <div class="col-md-9">

                                    <select id = "UnitTypeId" name="UnitTypeId" class="form-control" placeholder="Select Unit Type Name">
                                    <option value="">--Select--</option> 
                                    <?php
                                    foreach ($fetch_unittype as $row){
                                        echo "<option value='".$row['UnitTypeId']."'>".$row['UnitTypeName']."</option>";
                                    }
                                    ?>
                                    </select>
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">Program Name</label>
                            <div class="col-md-9">

                                    <select id = "ProgramId" name="ProgramId" class="form-control" placeholder="Program Name">
                                    <option value="">--Select--</option> 
                                    <?php
                                    foreach ($fetch_program as $row){
                                        echo "<option value='".$row['ProgramId']."'>".$row['Program']."</option>";
                                    }
                                    ?> 
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                         <label class="control-label col-md-3">Donor Name</label>
                            <div class="col-md-9">

                                    <select id = "DonorId" name="DonorId" class="form-control" placeholder="Select Donor Name">
                                    <option value="">--Select--</option> 
                                    <?php
                                    foreach ($fetch_donor as $row){
                                        echo "<option value='".$row['DonorId']."'>".$row['DonorName']."</option>";
                                    }
                                    ?>
                                    </select>
                                    <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                            <label class="control-label col-md-3">UnitName</label>
                            <div class="col-md-9">
                                <input name="UnitName" placeholder="Unit Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group margin-bottom">
                        <label class="control-label col-md-3">Batch No</label>
                        <div class="col-md-9">
                            <select name="BatchNo" placeholder="Batch No" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        </div>
                        <div class="form-group margin-bottom">
                        <label class="control-label col-md-3">No Of Testing Cycles
</label>
                        <div class="col-md-9">
                            <select name="NoOfTestingCycles" placeholder="No Of Testing Cycles" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        </div>
                        
                        <input type="hidden" value="" name="UnitCode"/>
                    
                    </div>
                </form>
            </div>
            <div class="modal-footer margin-bottom">
                <button type="button" id="btnSave" onclick="save_unit()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /Add Unit modal -->
<!-- End Bootstrap modal -->
<BR><BR>
<script type="text/javascript">
    
  $(document).ready(function(){

    /*Get the state list */
      $.ajax({
        type: "GET",
        url: "<?php echo site_url('Village/get_state')?>",
        data:{StateId:$(this).val()}, 
        beforeSend :function(){
      $('.c_state').find("option:eq(0)").html("Please wait..");
        },                         
        success: function (data) {
          /*get response as json */
           $('.c_state').find("option:eq(0)").html("Select State");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('.c_state').append(option);
         });  

          /*ends */
          
        }
      });



    /*Get the state list */


    $('.c_state').change(function(){
      
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Village/get_district')?>",
            data:{DistrictId:$(this).val()}, 
            beforeSend :function(){
          $(".c_district option:gt(0)").remove(); 
          $(".c_block option:gt(0)").remove(); 
          $('.c_district').find("option:eq(0)").html("Please wait..");

            },                         
            success: function (data) {
              /*get response as json */
               $('.c_district').find("option:eq(0)").html("Select district");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
               var option = $('<option />');
               option.attr('value', this.value).text(this.label);           
               $('.c_district').append(option);
             });  

              /*ends */
              
            }

        });
    });



    /*Get the state list */ 


    $('.c_district').change(function(){
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('Village/get_block')?>",
        data:{BlockId:$(this).val()}, 
          beforeSend :function(){
   
      $(".c_block option:gt(0)").remove(); 
      $('.c_block').find("option:eq(0)").html("Please wait..");

        },  

        success: function (data) {
          /*get response as json */
            $('.c_block').find("option:eq(0)").html("Select Block");

          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);
           $('.c_block').append(option);
         });  
          
          /*ends */
          
        }
      });
    });

  });

</script>
</body>
</html>