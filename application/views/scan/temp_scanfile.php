<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-4">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Scan File</h3>
               </div>
               <form id="form1" action="<?= base_url(); ?>Scan/temp_upload_main" id="scan_main" name="scan_main"
                  method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
            <div class="box box-primary" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Latest Scan File
                  </h3>
                  <div class="box-tools pull-right">
                     <a href="<?php echo base_url('dashboard')?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  </div>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Latest Scan File</div>
                     <!-- Filter Dropdown -->
                     <div class="mb-5">
                        <a href="?status=all"
                           class="btn btn-sm <?= $status == 'all' || $status == '' ? 'btn-primary' : 'btn-default' ?>">All</a>
                        <a href="?status=submitted"
                           class="btn btn-sm <?= $status == 'submitted' ? 'btn-success' : 'btn-default' ?>">Submitted</a>
                        <a href="?status=pending"
                           class="btn btn-sm <?= $status == 'pending' ? 'btn-warning' : 'btn-default' ?>">Pending</a>
                        <a href="?status=rejected"
                           class="btn btn-sm <?= $status == 'rejected' ? 'btn-danger' : 'btn-default' ?>">Rejected</a>
                        <a href="?status=deleted"
                           class="btn btn-sm <?= $status == 'deleted' ? 'btn-danger' : 'btn-default' ?>">Deleted</a>
                     </div>

                     <!-- DataTable -->
                     <table class="table table-striped table-bordered table-hover mt-3" id="scanTable">
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
                              foreach ($my_lastest_scan as $row) { ?>
                                 <tr class="text-center">
                                    <td><?= $count++; ?></td>
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
                                       <?php if ($row['is_final_submitted'] != 'Y' || $row['is_temp_scan_rejected'] = 'Y') { ?>
                                          <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1): ?>
                                             <a data-toggle="collapse" href="#detail<?= $row['scan_id'] ?>"
                                                class="btn btn-info btn-xs" title="View Support Files">
                                                <i class="fa fa-eye"></i>
                                             </a>
                                          <?php endif; ?>
                                          <a href="<?= base_url('temp-supporting/' . $row['scan_id']); ?>"
                                             class="btn btn-warning btn-xs" title="Edit">
                                             <i class="fa fa-pencil"></i>
                                          </a>
                                          <a href="javascript:void(0);" data-scan_id="<?= $row['scan_id']; ?>"
                                             class="btn btn-danger btn-xs delete-scan" title="Delete">
                                             <i class="fa fa-remove"></i>
                                          </a>
                                       <?php } ?>
                                    </td>
                                 </tr>

                                 <!-- Support Files Collapse -->
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
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script type="text/javascript">
   $('#scan_form').on('submit', function (e) {
      e.preventDefault();
      var form = this;
      $.ajax({
         url: $(form).attr('action'),
         method: $(form).attr('method'),
         data: new FormData(form),
         processData: false,
         dataType: 'json',
         contentType: false,
         success: function (data) {
            alert(data.message);
         }
      });
   });
   $(document).ready(function () {
      $("#location").select2();
      $("#bill_approver").select2();
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
                     window.location.href = '<?= base_url() ?>temp_scan';
                  }
               }
            });
         }
      });
      $('#location').change(function () {
         var location_id = $(this).val();
         if (location_id !== '') {
            $.ajax({
               url: '<?= site_url('Scan/get_bill_approvers') ?>',
               method: 'POST',
               data: { location_id: location_id },
               dataType: 'json',
               success: function (response) {

                  $('#bill_approver').empty();
                  $('#bill_approver').append('<option value="">Select Approver</option>');


                  $.each(response, function (key, value) {
                     $('#bill_approver').append('<option value="' + value.user_id + '">' + value.first_name + ' ' + value.last_name + '</option>');
                  });
               },
               error: function () {
                  alert('Error fetching Bill Approvers.');
               }
            });
         } else {

            $('#bill_approver').empty();
            $('#bill_approver').append('<option value="">Select Approver</option>');
         }
      });
   });
</script>