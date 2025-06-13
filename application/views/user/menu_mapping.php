<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-solid1 box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-lock"></i> Menu Mapping</h3>
                  <div class="box-tools pull-right">
                     <a href="<?= base_url(); ?>user" class="btn btn-primary btn-sm">
                        <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  </div>
               </div>
               <div class="box-body">
                  <table id="menuTable" class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th>Menu Name</th>
                           <th>Permissions</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($menu_list as $menu): ?>
                           <tr>
                              <td><?= $menu['name']; ?></td>
                              <td>
                                 <?php
                                    $menu_permissions = json_decode($menu['permission_ids'], true);
                                    if(!is_array($menu_permissions)) $menu_permissions = [];
                                 ?>
                                 <?php foreach($permission_list as $perm): ?>
                                    <label style="margin-right:10px;">
                                       <input type="checkbox" 
                                              class="permission-checkbox" 
                                              data-menu-id="<?= $menu['id']; ?>" 
                                              data-permission-id="<?= $perm['permission_id']; ?>" 
                                              <?= in_array($perm['permission_id'], $menu_permissions) ? 'checked' : ''; ?>>
                                       <?= $perm['permission_name']; ?>
                                    </label>
                                 <?php endforeach; ?>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
$(document).ready(function() {
    $('#menuTable').DataTable({
        "pageLength": 10,
        "ordering": false,
        "columnDefs": [
            { "orderable": false, "targets": 1 }
        ]
    });

    // Handle checkbox change
    $(document).on('change', '.permission-checkbox', function() {
        var menuId = $(this).data('menu-id');
        var permissionId = $(this).data('permission-id');
        var checked = $(this).is(':checked');

        $.ajax({
            url: "<?= base_url('master/UserController/updateMenuPermission'); ?>",
            type: "POST",
            data: {
                menu_id: menuId,
                permission_id: permissionId,
                checked: checked
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    alert('Permission updated successfully');
                } else {
                    alert('Failed to update permission');
                }
            },
            error: function() {
                alert('Error occurred while updating permission');
            }
        });
    });
});
</script>