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
                  <form method="post" action="<?= base_url('master/UserController/saveMenuMapping'); ?>">
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
                                          <input type="checkbox" name="permissions[<?= $menu['id']; ?>][]" value="<?= $perm['permission_id']; ?>"
                                             <?= in_array($perm['permission_id'], $menu_permissions) ? 'checked' : ''; ?>>
                                          <?= $perm['permission_name']; ?>
                                       </label>
                                    <?php endforeach; ?>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                     <br>
                     <button style="float: right;" type="submit" class="btn btn-success">Save Mapping</button>
                  </form>
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
           "ordering": false, // Disable ordering if needed
           "columnDefs": [
               { "orderable": false, "targets": 1 } // Disable ordering on permissions column
           ]
       });
   });
</script>