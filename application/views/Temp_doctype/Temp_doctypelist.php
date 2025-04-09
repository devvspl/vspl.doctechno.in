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
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Temp Doctype</h3>
                    </div>
                    
                    <form id="form1" action="<?= base_url(); ?>Temp_doctype/create" id="Temp_doctypeform" name="Temp_doctypeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Temp Doctype Name</label>
                                <input autofocus="" id="file_type" name="file_type" placeholder="" type="text" class="form-control" value="<?php echo set_value('file_type'); ?>" />
                                <span class="text-danger"><?php echo form_error('file_type'); ?></span>
                            </div>
                         
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">

                                    <option value="A">Active</option>
                                    <option value="D">Deactive</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <div class="col-md-9">
                
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Temp Doctype List</h3>
                    </div>
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Temp Doctype List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Temp Doctype Id</th>
                                        <th>Temp Doctype</th>
                                   
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($Temp_doctypelist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($Temp_doctypelist as $row) {
                                        ?>
                                            <tr>
                                            <td class="mailbox-name">
                                                    <?php echo $row['type_id']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['file_type']; ?>
                                                </td>
                                                
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                                    <a href="<?= base_url(); ?>Temp_doctype/edit/<?php echo $row['type_id'] ?>" class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>Temp_doctype/delete/<?php echo $row['type_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </section>
</div>