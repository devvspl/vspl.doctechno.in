<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
               <?php echo $this->session->flashdata('success'); ?>
            </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
               <?php echo $this->session->flashdata('error'); ?>
            </div>
            <?php endif; ?>
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">Punched Files</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Punched Files</div>
                     <table class="table table-striped table-bordered table-hover example">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Document Name</th>
                              <th>Document Type</th>
                              <th>File</th>
                              <th>Scan By</th>
                              <th>Scan Date</th>
                              <th>Bill Approve Date</th>
                              <th>Punch Date</th>
                              <th>Approve By</th>
                              <th>Approve Date</th>
                              <th class="text-right no-print">Support File</th>
                              <th class="text-right no-print">view</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($my_finance_punched_file)) {
                              ?>
                           <?php
                              } else {
                                  $count = 1;
                                  foreach ($my_finance_punched_file as $row) {
                                  ?>
                           <tr>
                              <td><?php echo $count++; ?></td>
                              <td class="mailbox-name">
                                 <?php echo $row['document_name']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $row['doc_type']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                              </td>
                              <td class="mailbox-name">
                                 <?php
                                    if($row['is_temp_scan']=='Y'){
                                    	$scan_by = $row['temp_scan_by'];
                                    	$scan_date = $row['temp_scan_date'];
                                    }else{
                                    	$scan_by = $row['scanned_by'];
                                    	$scan_date = $row['scan_date'];
                                    }
                                    ?>
                                 <?php echo $this->customlib->get_Name($scan_by); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($scan_date) ? date('d-m-Y', strtotime($scan_date)) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($row['bill_approved_date']) ? date('d-m-Y', strtotime($row['bill_approved_date'])) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($row['punched_date']) ? date('d-m-Y', strtotime($row['punched_date'])) : ''; ?>
                              </td>
                              <?php if ($row['is_file_approved'] == 'Y') { ?>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['approved_by']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['approved_date'])) ?>
                              </td>
                              <?php } else { ?>
                              <td class="mailbox-name">
                                 <?php echo "Not Approved"; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo "Not Approved"; ?>
                              </td>
                              <?php } ?>
                              <td class="mailbox-date text-center no-print">
                                 <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                 <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                              <td>
                                 <a href="<?php echo base_url(); ?>vspl_file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-info btn-xs" target="_blank">
                                 <i class="fa fa-eye"></i>
                                 </a>
                              </td>
                              <td>
                                 <?php if ($row['finance_punch_action_status'] == 'N'): ?>
                                 
                                 <a href="<?php echo base_url(); ?>approve_file/<?= $row['scan_id'] ?>" class="btn btn-success btn-xs" onclick="return confirm('Are you sure you want to approve this file?')">
                                 <i class="fa fa-check"></i> Approve
                                 </a>
                                 
                                 <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#rejectModal<?= $row['scan_id'] ?>">
                                 <i class="fa fa-times"></i> Reject
                                 </button>
                                 <?php else: ?>
                                 
                                 <span class="badge badge-<?php echo ($row['finance_punch_action_status'] == 'Y') ? 'success' : 'secondary'; ?>">
                                 <?php echo ($row['finance_punch_action_status'] == 'Y') ? 'Approved' : 'Rejected'; ?>
                                 </span>
                                 <?php endif; ?>
                              </td>
                              
                              <div class="modal fade" id="rejectModal<?= $row['scan_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                 <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <h5 class="modal-title" id="rejectModalLabel">Reject File</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                          </button>
                                       </div>
                                       <form action="<?php echo base_url(); ?>reject_file/<?= $row['scan_id'] ?>" method="POST">
                                          <div class="modal-body">
                                             <div class="form-group">
                                                <label for="remark">Reason for Rejection:</label>
                                                <textarea class="form-control" id="remark" name="punch_reject_remark" rows="4" required></textarea>
                                             </div>
                                          </div>
                                          <div class="modal-footer">
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                             <button type="submit" class="btn btn-danger">Reject</button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                              </div>
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
<div id="SupportFileView" class="modal fade" role="dialog" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modalwrapwidth">
      <div class="modal-content">
         <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
         <div class="scroll-area">
            <div class="modal-body paddbtop">
               <div id="detail">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   function getSupportFile(scan_id) {
       $.ajax({
           url: '<?php echo base_url(); ?>Punch/getSupportFile',
           type: 'POST',
           data: {
               scan_id: scan_id
           },
           dataType: 'json',
           success: function(response) {
               if (response.status == 200) {
                   var x = '';
                   $.each(response.data, function(index, value) {
                       x += '<object data="' + value.file_path + '" type="application/pdf" width="100%" height="500px"></object>';
   
                   });
                   $('#detail').html(x);
                   $('#SupportFileView').modal('show');
               }
   
   
           }
       });
   }
   function reloadPage() {
      window.location.href = "<?php echo base_url(); ?>punch/my-punched-file/all";
   }
</script>