<div class="content-wrapper" >
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">
                     <?= !empty($business_entity['business_entity_id']) ? 'Edit Business Entity' : 'Add Business Entity'; ?>
                  </h3>
               </div>
               <form id="form1"
                  action="<?= base_url('save_business_entity' . (!empty($business_entity['business_entity_id']) ? '/' . $business_entity['business_entity_id'] : '')) ?>"
                  name="business_entityform" method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')): ?>
                        <?= $this->session->flashdata('message'); ?>
                     <?php endif; ?>
                     <div class="form-group">
                        <label for="business_entity_name">Name</label>
                        <input type="text" id="business_entity_name" name="business_entity_name" class="form-control"
                           value="<?= set_value('business_entity_name', isset($business_entity['business_entity_name']) ? $business_entity['business_entity_name'] : ''); ?>" />
                        <span class="text-danger"><?= form_error('business_entity_name'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="focus_code">Code</label>
                        <input type="text" id="focus_code" name="focus_code" class="form-control"
                           value="<?= set_value('focus_code', isset($business_entity['focus_code']) ? $business_entity['focus_code'] : ''); ?>" />
                        <span class="text-danger"><?= form_error('focus_code'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                           <option value="A" <?= set_select('status', 'A', (isset($business_entity['status']) && $business_entity['status'] === 'A')); ?>>Active</option>
                           <option value="D" <?= set_select('status', 'D', (isset($business_entity['status']) && $business_entity['status'] === 'D')); ?>>Deactive</option>
                        </select>
                        <span class="text-danger"><?= form_error('status'); ?></span>
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
                  <h3 class="box-title titlefix">Business Entity List</h3>
               </div>
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Business Entity List</div>
                     <table id="businessEntityTable" class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th style="text-align: center;">Id</th>
                              <th style="text-align: left;">Name</th>
                              <th style="text-align: center;">Code</th>
                              <th style="text-align: center;">Status</th>
                              <th style="text-align: center;">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($business_entity_list)) {
                              ?>
                              <?php
                           } else {
                              $count = 1;
                              foreach ($business_entity_list as $row) {
                                 ?>
                                 <tr>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?php echo $row['business_entity_id']; ?>
                                    </td>
                                    <td style="text-align: left;" class="mailbox-name">
                                       <?php echo $row['business_entity_name']; ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?php echo $row['focus_code'] ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-date pull-right no-print">
                                       <a href="<?= base_url('business_entity/') . $row['business_entity_id']; ?>"
                                          class="btn btn-default btn-xs">
                                          <i class="fa fa-pencil"></i>
                                       </a>
                                       <a href="<?= base_url('delete_business_entity/') . $row['business_entity_id']; ?>"
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
      $('#business_entity_group').select2();
      $("#businessEntityTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Business_Entity_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
   });
</script>