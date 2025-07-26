<div class="content-wrapper" >
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Role List</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Role List</div>
                     <table id="rolesTable" class="table">
                        <thead>
                           <tr>
                              <th style="text-align: left;">Role ID</th>
                              <th style="text-align: left;">Role Name</th>
                              <th style="text-align: left;">Description</th>
                              <th style="text-align: center;">Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($roles)) { ?>
                              <tr>
                                 <td colspan="7">No records found</td>
                              </tr>
                           <?php } else {
                              foreach ($roles as $row) { ?>
                                 <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['role_name']; ?></td>
                                    <td><?= $row['description']; ?></td>
                                    <td style="text-align: center;"><?= $row['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
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
      $('#rolesTable').DataTable({
         "pageLength": 10,
         "ordering": false,
         "columnDefs": [
            { "orderable": false, "targets": 1 }
         ],
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Role_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]

      });
   });
</script>