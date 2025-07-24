<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">
                     <?= !empty($location['location_id']) ? 'Edit Location' : 'Add Location'; ?>
                  </h3>
               </div>
               <form id="form1"
                  action="<?= base_url('save_location' . (!empty($location['location_id']) ? '/' . $location['location_id'] : '')) ?>"
                  name="locationform" method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                        <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <label for="location_name">Location Name</label>
                        <input autofocus id="location_name" name="location_name" type="text" class="form-control"
                           value="<?= set_value('location_name', isset($location['location_name']) ? $location['location_name'] : '') ?>" />
                        <span class="text-danger"><?php echo form_error('location_name'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="focus_code">Focus Code</label>
                        <input id="focus_code" name="focus_code" type="text" class="form-control"
                           value="<?= set_value('focus_code', isset($location['focus_code']) ? $location['focus_code'] : '') ?>" />
                        <span class="text-danger"><?php echo form_error('focus_code'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                           <option value="A" <?= set_select('status', 'A', (isset($location['status']) && $location['status'] == 'A')); ?>>Active</option>
                           <option value="D" <?= set_select('status', 'D', (isset($location['status']) && $location['status'] == 'D')); ?>>Deactive</option>
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
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Location List</h3>
               </div>
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Location List</div>
                     <table id="LocationListTable" class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>Location</th>
                              <th>Location Code</th>
                              <th style="text-align:center">Status</th>
                              <th style="text-align:center">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($location_list)) {
                              ?>
                              <?php
                           } else {
                              $count = 1;
                              foreach ($location_list as $row) {
                                 ?>
                                 <tr>
                                    <td class="mailbox-name">
                                       <?php echo $row['location_name']; ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['focus_code']; ?>
                                    </td>
                                    <td class="mailbox-name" style="text-align:center">
                                       <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-date pull-right no-print">
                                       <a href="<?= base_url('location/') . $row['location_id']; ?>"
                                          class="btn btn-default btn-xs">
                                          <i class="fa fa-pencil"></i>
                                       </a>
                                       <a href="<?= base_url('delete_location/') . $row['location_id']; ?>"
                                          class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
<script>
   $(document).ready(function () {
      $("#LocationListTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Location_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
   });
</script>