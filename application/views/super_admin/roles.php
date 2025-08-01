<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Role Permission Mapping</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Role Permission Mapping</div>
                     <table id="rolePermissionTable" class="table table-bordered">
                        <thead>
                           <tr>
                              <th style="text-align: left;">Role Name</th>
                              <?php foreach ($permissions as $permission) { ?>
                              <th style="text-align: center;"><?= $permission['permission_name']; ?></th>
                              <?php } ?>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($roles)) { ?>
                           <tr>
                              <td colspan="<?= count($permissions) + 1; ?>">No records found</td>
                           </tr>
                           <?php } else {
                              foreach ($roles as $role) { ?>
                           <tr>
                              <td><?= $role['role_name']; ?></td>
                              <?php foreach ($permissions as $permission) { ?>
                              <td style="text-align: center;">
                                 <input type="checkbox" 
                                    class="permission-checkbox" 
                                    data-role-id="<?= $role['id']; ?>" 
                                    data-permission-id="<?= $permission['permission_id']; ?>" 
                                    <?= in_array($permission['permission_id'], array_column($role_permissions[$role['id']] ?? [], 'permission_id')) ? 'checked' : ''; ?>>
                              </td>
                              <?php } ?>
                           </tr>
                           <?php }
                              } ?>
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
      $('#rolePermissionTable').DataTable({
         "pageLength": 10,
         "ordering": false,
         "searching": false,
         "dom": 'Bfrtip',
         "buttons": []
      });
   
      $('.permission-checkbox').on('change', function () {
         var roleId = $(this).data('role-id');
         var permissionId = $(this).data('permission-id');
         var isChecked = $(this).is(':checked');
         var action = isChecked ? 'assign' : 'remove';
   
         $.ajax({
            url: '<?= base_url('assign_permission'); ?>',
            type: 'POST',
            data: {
               role_id: roleId,
               permission_id: permissionId,
               action: action,
             
            },
            dataType: 'json',
            success: function (response) {
               if (response.status === 'success') {
                  alert(response.message);
               } else {
                  alert('Error: ' + response.message);
                  
                  $(this).prop('checked', !isChecked);
               }
            },
            error: function () {
               alert('An error occurred while processing the request.');
               
               $(this).prop('checked', !isChecked);
            }
         });
      });
   });
</script>