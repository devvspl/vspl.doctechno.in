<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-lock"></i> Menu Role Mapping Management</h3>
               </div>
               <div class="box-body">
                  <table id="menuTable" class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th style="text-align: center;">S No.</th>
                           <th style="text-align: left;">Menu Name</th>
                           <th style="text-align: center;">URL</th>
                           <th style="text-align: center;">Roles</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                        $i = 1;
                        foreach ($menu_list as $menu): ?>
                           <tr>
                              <td style="text-align: center;"><?= $i++; ?></td>
                              <td style="width: text-align: left;"><?= $menu['name']; ?></td>
                              <td style="text-align: center;"><?= $menu['url']; ?></td>
                              <td style="text-align: center;">
                                 <?php
                                 $menu_permissions = json_decode($menu['permission_ids'] ?? '[]', true);
                                 if (!is_array($menu_permissions))
                                    $menu_permissions = [];
                                 ?>
                                 <?php foreach ($permission_list as $perm): ?>
                                    <label style="margin-right:10px;">
                                       <input type="checkbox" class="permission-checkbox" data-menu-id="<?= $menu['id']; ?>"
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
   $(document).ready(function () {
      $('#menuTable').DataTable({
         "pageLength": 10,
         "ordering": false,
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Menu_mapping_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
      $(document).on('change', '.permission-checkbox', function () {
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
            success: function (response) {
               if (response.status === 'success') {
               } else {
                  alert('Failed to update permission');
               }
            },
            error: function () {
               alert('Error occurred while updating permission');
            }
         });
      });
   });
</script>