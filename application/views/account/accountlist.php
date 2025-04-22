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
                  <h3 class="box-title">Add Account</h3>
               </div>
               <form id="form1" action="<?= base_url(); ?>create_account" id="accountform" name="accountform" method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                     <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Account Name</label>
                        <input autofocus="" id="account_name" name="account_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('account_name'); ?>" />
                        <span class="text-danger"><?php echo form_error('account_name'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Account Code</label>
                        <input autofocus="" id="account_code" name="account_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('account_code'); ?>" />
                        <span class="text-danger"><?php echo form_error('account_code'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Account Group</label>
                        <select name="account_group" id="account_group" class="form-control">
                           <option value="">Select</option>
                           <?php
                              foreach ($getGroupedData as $key => $value) {
                                 echo "<option value='".$value['account_group']."'>".$value['account_group']."</option>";
                              }
                              ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('account_group'); ?></span>
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
                  <h3 class="box-title titlefix">Account List</h3>
               </div>
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Account List</div>
                     <!-- <form method="get" action="<?= base_url('account/index') ?>" class="form-inline" style="margin-bottom: 15px;">
                        <div class="form-group">
                           <select name="group" id="groupFilter" class="form-control" onchange="this.form.submit()">
                              <option value="">All Groups</option>
                              <?php foreach ($getGroupedData as $group) { ?>
                                 <option value="<?= $group['account_group'] ?>" <?= ($selected_group == $group['account_group']) ? 'selected' : '' ?>>
                                    <?= $group['account_group'] ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                        <div class="form-group" style="margin-left: 10px;">
                           <input type="text" name="search" id="searchInput" class="form-control" placeholder="Account name or code" value="<?= isset($search) ? htmlspecialchars($search) : '' ?>" />
                        </div>
                        <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
                     </form> -->
                     <table class="table table-striped table-bordered table-hover example1">
                        <thead>
                           <tr>
                              <th>Account Id</th>
                              <th>Account</th>
                              <th>Code</th>
                              <th>Group</th>
                              <th>Status</th>
                              <th class="text-right no-print">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($accountlist)) { ?>
                           <tr>
                              <td colspan="6">No records found</td>
                           </tr>
                           <?php } else {
                              foreach ($accountlist as $row) { ?>
                           <tr>
                              <td><?= $row['id']; ?></td>
                              <td><?= $row['account_name']; ?></td>
                              <td><?= $row['account_code'] ?></td>
                              <td><?= $row['account_group'] ?></td>
                              <td><?= ($row['status'] == 'A') ? 'Active' : 'Deactive' ?></td>
                              <td class="text-right no-print">
                                 <a href="<?= base_url('account/edit/' . $row['id']) ?>" class="btn btn-default btn-xs">
                                 <i class="fa fa-pencil"></i>
                                 </a>
                                 <a href="<?= base_url('account/delete/' . $row['id']) ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
                                 <i class="fa fa-remove"></i>
                                 </a>
                              </td>
                           </tr>
                           <?php }
                              } ?>
                        </tbody>
                     </table>
                     <!-- <div class="pagination" style="display: flex;justify-content: center;gap: 10px;">
                        <?= isset($pagination); ?>
                     </div> -->
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   $(document).ready(function () {
      $('#account_group').select2();
   });
</script>
