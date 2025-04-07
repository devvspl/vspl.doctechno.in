<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Company-Vendor-Farmer</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>master/FirmController/create" id="firm_form" name="firm_form" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Firm Name</label>
                                <input autofocus="" id="firm_name" name="firm_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('firm_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('firm_name'); ?></span>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Firm Type</label>
                                    <select name="firm_type" id="firm_type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Company">Company</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Farmer">Farmer</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('firm_type'); ?></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Firm Code</label>
                                    <input autofocus="" id="firm_code" name="firm_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('firm_code'); ?>" />
                                    <span class="text-danger"><?php echo form_error('firm_code'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">GST</label>
                                <input autofocus="" id="gst" name="gst" placeholder="" type="text" class="form-control" value="<?php echo set_value('gst'); ?>" />
                                <span class="text-danger"><?php echo form_error('gst'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <input autofocus="" id="address" name="address" placeholder="" type="text" class="form-control" value="<?php echo set_value('address'); ?>" />
                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($countrylist as $key => $value) {
                                            echo "<option value='" . $value['country_id'] . "'>" . $value['country_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('country_id'); ?></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">State</label>
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($statelist as $key => $value) {
                                            echo "<option value='" . $value['state_id'] . "'>" . $value['state_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">City</label>
                                    <input autofocus="" id="city_name" name="city_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('city_name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('city_name'); ?></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Pin Code</label>
                                    <input autofocus="" id="pin_code" name="pin_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('pin_code'); ?>" />
                                    <span class="text-danger"><?php echo form_error('pin_code'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="A">Active</option>
                                    <option value="D">Deactive</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-9">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Firm List</h3>
                        <div class="box-tools pull-right">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-sm" style="margin-right: 15px;"
                                    data-toggle="modal" data-target="#importModal"><i class="fa fa-file-excel-o"></i>
                                    Import
                                </button>


                            </div>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Firm List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Focus ID</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>GST</th>
                                        <th>Address</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Pin</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($firmlist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($firmlist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php
                                                    echo $row['firm_id'];
                                                    ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    echo $row['focus_id'];
                                                    ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    echo $row['firm_type'];
                                                    ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['firm_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['firm_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['gst'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['address'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['country_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['state_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['city_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['pin_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                              
                                                    <td class="mailbox-date pull-right no-print">

                                                        <a href="<?= base_url(); ?>firm/edit/<?php echo $row['firm_id'] ?>" class="btn btn-default btn-xs">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>firm/delete/<?php echo $row['firm_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>

                                                    </td>
                                              
                                            </tr>
                                    <?php
                                        }
                                        $count++;
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div> <!-- right column -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>
<div class="modal fade" id="importModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_title">   <a href="<?= base_url() ?>/assets/import_sample/firm.csv" download="">
                                    <button class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Sample
                                        Import File</button>
                                </a></h4>
            </div>
            <form action="<?= base_url() ?>firm_import" id="importform" name="importform" method="post"
                enctype="multipart/form-data">
                <div class="modal-body pb0">
                    <div class="form-horizontal balanceformpopup">
                        <div class="box-body">
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="file">Select CSV File</label><small class="req"> *</small>
                                    <input class="filestyle form-control" type='file' name='file' id="file"
                                        accept=".csv" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancle</button>
                    <input type="submit" class="btn btn-info pull-right" name="importSubmit" value="Import">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('change', '#country_id', function() {
        var country_id = $(this).val();
        $.ajax({
            url: '<?= base_url(); ?>State/get_state_by_country_id',
            type: 'POST',
            data: {
                country_id: country_id
            },
            dataType: 'json',
            success: function(res) {
                $('#state_id').empty();
                $('#state_id').append('<option value="">Select</option>');
                $.each(res, function(i, item) {
                    $('#state_id').append($('<option>', {
                        value: item.state_id,
                        text: item.state_name
                    }));
                });
            }
        });
    });
</script>