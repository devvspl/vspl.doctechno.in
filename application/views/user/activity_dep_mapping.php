<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-solid1 box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-lock"></i> Department Activity Mapping</h3>
                  <div class="box-tools pull-right">
                     <a href="<?= base_url(); ?>user" class="btn btn-primary btn-sm">
                     <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  </div>
               </div>
               <div class="box-body">
                  <?php if ($this->session->flashdata('msg')): ?>
                  <div class="alert alert-success"><?= $this->session->flashdata('msg'); ?></div>
                  <?php endif; ?>
                  <div class="table-responsive">
                     <table id="mappingTable" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>S No.</th>
                              <th>Activity</th>
                              <?php foreach ($departments as $dept): ?>
                              <th><?= $dept['department_name']; ?></th>
                              <?php endforeach; ?>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sno = 1; ?>
                           <?php foreach ($activities as $act): ?>
                           <tr>
                              <td><?= $sno++; ?></td>
                              <td><?= $act['activity_name']; ?></td>
                              <?php foreach ($departments as $dept): ?>
                              <?php
                                 $is_mapped = false;
                                 foreach ($mappings as $map) {
                                     if ($map['department_id'] == $dept['api_id'] && $map['activity_id'] == $act['api_id']) {
                                         $is_mapped = true;
                                         break;
                                     }
                                 }
                                 ?>
                              <td>
                                 <input type="checkbox" 
                                    class="mapping-checkbox" 
                                    data-department-id="<?= $dept['api_id']; ?>" 
                                    data-activity-id="<?= $act['api_id']; ?>" 
                                    <?= $is_mapped ? 'checked' : ''; ?>>
                              </td>
                              <?php endforeach; ?>
                           </tr>
                           <?php endforeach; ?>
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
   $(document).ready(function() {
       $('#mappingTable').DataTable({
           "pageLength": 10,
           "ordering": false,
           "searching": true,
           "paging": true,
           "info": true,
             fixedColumns: {
                leftColumns: 2 
        }
       });
       $('.mapping-checkbox').on('change', function() {
           var departmentId = $(this).data('department-id');
           var activityId = $(this).data('activity-id');
           var checked = $(this).is(':checked');
   
           $.ajax({
               url: "<?= base_url('master/UserController/updateMapping'); ?>",
               type: "POST",
               data: {
                   department_id: departmentId,
                   activity_id: activityId,
                   checked: checked
               },
               dataType: "json",
               success: function(response) {
                   if (response.status === 'success') {
                      alert('Mapping updated successfully');
                   } else {
                       alert('Failed to update mapping');
                   }
               },
               error: function() {
                   alert('Error occurred while updating mapping');
               }
           });
       });
   });
</script>