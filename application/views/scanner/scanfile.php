<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-4">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">Scan File</h3>
               </div>
               <form id="form1" action="<?= base_url('upload_main'); ?>" id="scan_main" name="scan_main" method="post"
                  accept-charset="utf-8" enctype="multipart/form-data">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                        <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group mt-3" style="margin-top: 8px;">
                        <input class="filestyle form-control" type='file' name='main_file' id="main_file"
                           accept="image/*,application/pdf">
                     </div>
                  </div>
                  <div class="box-footer">
                     <button type="submit" id="upload_main" class="btn btn-info pull-right">Save</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-md-8">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Latest Scan File</h3>
                  <div class="box-tools pull-right">
                     <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm">Get Report</button>
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                           style="padding-bottom: 11px;" data-toggle="dropdown">
                           <span class="caret"></span>
                           <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                           <li> <a href="<?= base_url('scanner/export/csv') . '?' . http_build_query([
                              'status' => $status,
                              'document_name' => $document_name,
                              'from_date' => $from_date,
                              'to_date' => $to_date
                           ]) ?>">Export CSV</a></li>
                           <li> <a href="<?= base_url('scanner/export/pdf') . '?' . http_build_query([
                              'status' => $status,
                              'document_name' => $document_name,
                              'from_date' => $from_date,
                              'to_date' => $to_date
                           ]) ?>">Export PDF</a></li>

                        </ul>
                     </div>
                  </div>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Latest Scan File</div>
                     <form method="get" action="<?= base_url('scanner') ?>" class="form-inline mb-3">
                        <div class="form-group mr-2">
                           <label for="status" class="sr-only">Status</label>
                           <select name="status" id="status" class="form-control">
                              <option value="all" <?= $status == 'all' || $status == '' ? 'selected' : '' ?>>All</option>
                              <option value="submitted" <?= $status == 'submitted' ? 'selected' : '' ?>>Submitted</option>
                              <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                              <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                              <option value="deleted" <?= $status == 'deleted' ? 'selected' : '' ?>>Deleted</option>
                           </select>
                        </div>
                        <div class="form-group mr-2">
                           <label for="document_name" class="sr-only">Document Name</label>
                           <input type="text" name="document_name" id="document_name" class="form-control"
                              placeholder="Search Document Name" value="<?= $document_name ?>">
                        </div>
                        <div class="form-group mr-2">
                           <label for="from_date" class="sr-only">From Date</label>
                           <input type="date" name="from_date" id="from_date" class="form-control"
                              value="<?= $from_date ?>">
                        </div>
                        <div class="form-group mr-2">
                           <label for="to_date" class="sr-only">To Date</label>
                           <input type="date" name="to_date" id="to_date" class="form-control" value="<?= $to_date ?>">
                        </div>
                        <div class="form-group mr-2">
                           <select class="form-control" onchange="location.href=this.value">
                              <option
                                 value="<?= current_url() ?>?<?= http_build_query(array_merge($_GET, ['per_page' => 2])) ?>"
                                 <?= (isset($_GET['per_page']) && $_GET['per_page'] != 'all') ? 'selected' : '' ?>>
                                 Paginated
                              </option>
                              <option
                                 value="<?= current_url() ?>?<?= http_build_query(array_merge($_GET, ['per_page' => 'all'])) ?>"
                                 <?= (isset($_GET['per_page']) && $_GET['per_page'] == 'all') ? 'selected' : '' ?>>Show All
                              </option>
                           </select>
                        </div>
                        <div class="form-group mr-2">
                           <button type="submit" class="btn btn-sm btn-primary">Search</button>
                           <a href="<?= base_url('scan/temp_scan') ?>" class="btn btn-sm btn-default ml-2">Reset</a>
                        </div>
                     </form>
                     <table class="table table-striped table-bordered table-hover mt-3">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>File</th>
                              <th>Document Name</th>
                              <th>Scan Date</th>
                              <th>Final Submit</th>
                              <?php if ($status == 'rejected') { ?>
                                 <th>Reject Remark</th>
                              <?php } ?>
                              <th class="text-right no-print">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($my_lastest_scan)) { ?>
                              <tr>
                                 <td colspan="<?= $status == 'rejected' ? 7 : 6 ?>" class="text-center">No Record Found
                                 </td>
                              </tr>
                           <?php } else {
                              $count = 1;
                              foreach ($my_lastest_scan as $index => $row) { ?>
                                 <tr class="text-center">
                                    <td><?= $offset + $index + 1 ?></td>
                                    <td>
                                       <a href="javascript:void(0);"
                                          onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                                          <?= $row['file_name'] ?>
                                       </a>
                                    </td>
                                    <td><?= $row['document_name'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['temp_scan_date'])); ?></td>
                                    <td>
                                       <?php if ($row['is_final_submitted'] == 'Y') { ?>
                                          <span class="label label-success">Yes</span>
                                       <?php } else { ?>
                                          <span class="label label-danger">No</span>
                                       <?php } ?>
                                    </td>
                                    <?php if ($status == 'rejected') { ?>
                                       <td>
                                          <input class="form-control" readonly style="background-color: #e9ecef40;"
                                             value="<?= $row['temp_scan_reject_remark'] ?>">
                                       </td>
                                    <?php } ?>
                                    <td class="text-right">
                                       <?php if ($row['is_final_submitted'] != 'Y' || $row['is_temp_scan_rejected'] === 'Y') { ?>

                                          <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1): ?>
                                             <a data-toggle="collapse" href="#detail<?= $row['scan_id'] ?>"
                                                class="btn btn-info btn-xs" title="View Support Files">
                                                <i class="fa fa-eye"></i>
                                             </a>
                                          <?php endif; ?>
                                          <?php
                                          $is_final_submitted = $row['is_final_submitted'];
                                          $is_temp_scan_rejected = $row['is_temp_scan_rejected'];
                                          $is_deleted = $row['is_deleted'];
                                          $showEditDelete = false;
                                          if ($is_deleted !== 'Y') {
                                             if ($is_temp_scan_rejected === 'Y') {
                                                $showEditDelete = true;
                                             } elseif ($is_final_submitted === 'N') {
                                                $showEditDelete = true;
                                             }
                                          }
                                          if ($showEditDelete): ?>
                                             <a href="<?= base_url('temp-supporting/' . $row['scan_id']); ?>"
                                                class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                             </a>
                                             <a href="javascript:void(0);" data-scan_id="<?= $row['scan_id']; ?>"
                                                class="btn btn-danger btn-xs delete-scan" title="Delete">
                                                <i class="fa fa-remove"></i>
                                             </a>
                                          <?php endif; ?>

                                       <?php } ?>
                                    </td>
                                 </tr>
                                 <tr id="detail<?= $row['scan_id'] ?>" class="collapse" style="background-color: #FEF9E7;">
                                    <td colspan="<?= $status == 'rejected' ? 7 : 6 ?>">
                                       <table class="table table-bordered mytable1"
                                          style="background-color:#FEF9E7;margin-bottom:0;">
                                          <tbody>
                                             <?php
                                             $support = $this->db->query("SELECT * FROM support_file WHERE scan_id = ?", [$row['scan_id']])->result_array();
                                             foreach ($support as $rec) { ?>
                                                <tr>
                                                   <td>
                                                      <a href="javascript:void(0);"
                                                         onclick="window.open('<?= $rec['file_path'] ?>','popup','width=600,height=600');">
                                                         <?= $rec['file_name'] ?>
                                                      </a>
                                                   </td>
                                                </tr>
                                             <?php } ?>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                              <?php }
                           } ?>
                        </tbody>
                     </table>
                     <div class="mt-3">
                        <?= $pagination ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script type="text/javascript">
   $(document).ready(function () {
      $(document).on('click', '.delete-scan', function () {
         var scan_id = $(this).data('scan_id');
         var url = '<?= base_url() ?>Scan/delete_all';
         if (confirm('Are you sure to delete all ?')) {
            $.ajax({
               url: url,
               type: 'POST',
               data: {
                  'scan_id': scan_id
               },
               dataType: 'json',
               success: function (data) {
                  if (data.status == 200) {
                     window.location.href = '<?= base_url('scanner?status=rejected') ?>';
                  }
               }
            });
         }
      });
   });
</script>