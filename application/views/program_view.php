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
        

        <h3>Program Name</h3>
        <br />
        <button class="btn btn-success" onclick="add_program()"><i class="glyphicon glyphicon-plus"></i> Add New</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Program ID</th>
                    <th>Program Name</th>
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
            "url": "<?php echo site_url('program/ajax_list')?>",
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



function add_program()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Program'); // Set Title to Bootstrap modal title
}

function edit_program(ProgramId)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('program/ajax_edit/')?>/" + ProgramId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="ProgramId"]').val(data.ProgramId);
            $('[name="Program"]').val(data.Program);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Program'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

//function to get data for update to be delete row's
function edit_del(ProgramId)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals    
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('program/ajax_edit/')?>/" + ProgramId,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="ProgramId"]').val(data.ProgramId);
            $('[name="Program"]').val(data.Program);
            $('[name="IsDeleted"]').val(data.IsDeleted);
            $('#delete_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Delete Program'); // Set title to Bootstrap modal title

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
        url = "<?php echo site_url('program/ajax_add')?>";
    } else {
        url = "<?php echo site_url('program/ajax_update')?>";
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
                    $('[Program="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[Program="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                 
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
        url = "<?php echo site_url('program/ajax_update_del')?>";
 
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
                    $('[Program="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[Program="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
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

//saved deleted item

/*function delete_pbucket(ProgramBucketId) {

    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('pbucket/ajax_delete')?>/"+ProgramBucketId,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}
*/
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add Program Name</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="ProgramId"/> 
                    <input type="hidden" value="Vikas Saini" name="CreatedBy"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Program Name</label>
                            <div class="col-md-9">
                                <input name="Program" placeholder="Program Name" class="form-control" type="text">
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
                <h3 class="modal-title">Program </h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_del" class="form-horizontal">
                    <input type="hidden" value="" name="ProgramId"/> 
                    <input type="hidden" value="Vikas Saini" name="LastUpdatedBy"/> 
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s",time()); ?>" name="LastUpdatedOn"/>                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Program Name</label>
                            <div class="col-md-9">
                                <input name="Program" placeholder="Program Name" class="form-control" type="text">
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