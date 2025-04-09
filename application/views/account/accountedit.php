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
                  <h3 class="box-title">Update Account</h3>
               </div>
               <!-- /.box-header -->
               <!-- form start -->
               <form id="form1" action="<?= base_url(); ?>Account/update/<?= $account_id ?>" id="accountform" name="accountform" method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                     <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Account Name</label>
                        <input autofocus="" id="account_name" name="account_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('account_name', $account[0]['account_name']); ?>" />
                        <span class="text-danger"><?php echo form_error('account_name'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Account Code</label>
                        <input autofocus="" id="account_code" name="account_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('account_code', $account[0]['account_code']); ?>" />
                        <span class="text-danger"><?php echo form_error('account_code'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Account Group</label>
                        <select name="account_group" id="account_group" class="form-control">
                           <option value="">Select</option>
                           <?php
                              $selectedGroup = $account[0]['account_group'];
                                   foreach ($getGroupedData as $key => $value) {
                                       $isSelected = ($selectedGroup == $value['account_group']) ? 'selected' : '';
                                      echo "<option value='".$value['account_group']."' $isSelected >".$value['account_group']."</option>";
                                   }
                              ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('account_group'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Status</label>
                        <select name="status" id="status" class="form-control">
                           <option value="">Select</option>
                           <option value="A" <?php if (set_value('status', $account[0]['status']) == 'A') {
                              echo "selected";
                              } ?>>Active</option>
                           <option value="D" <?php if (set_value('status', $account[0]['status']) == 'D') {
                              echo "selected";
                              } ?>>Deactive</option>
                        </select>
                        <span class="text-danger"><?php echo form_error('status'); ?></span>
                     </div>
                  </div>
                  <!-- /.box-body -->
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
                  <h3 class="box-title titlefix">Account List</h3>
                  <div class="box-tools pull-right">
                     <a href="<?= base_url(); ?>account" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                  </div>
               </div>
               <!-- /.box-header -->
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Account List</div>
                     <table class="table table-striped table-bordered table-hover example">
                        <thead>
                           <tr>
                              <th>Account</th>
                              <th>Code</th>
                              <th>Status</th>
                              <th class="text-right no-print">Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($accountlist)) {
                              ?>
                           <?php
                              } else {
                                  $count = 1;
                                  foreach ($accountlist as $row) {
                                  ?>
                           <tr>
                              <td class="mailbox-name">
                                 <?php echo $row['account_name']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $row['account_code'] ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                              </td>
                              <td class="mailbox-date pull-right no-print">
                                 <a href="<?= base_url(); ?>account/delete/<?php echo $row['account_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
                     <!-- /.table -->
                  </div>
                  <!-- /.mail-box-messages -->
               </div>
               <!-- /.box-body -->
            </div>
         </div>
         <!-- right column -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>
<script>
   $(document).ready(function () {
      $('#account_group').select2();
   });
</script>
