<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Role List</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Role List</div>
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Role ID</th>
                              <th>Role Name</th>
                              <th>Description</th>
                              <th>Status</th>
                              <th>Created At</th>
                              <th>Updated At</th>

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
                                    <td><?= $row['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                                    <td><?= $row['created_at']; ?></td>
                                    <td><?= $row['updated_at']; ?></td>
                                   
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