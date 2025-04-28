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

            <div class="box box-primary">
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
                                 <?php echo $row['Document_Name']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $row['Doc_Type']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location']  ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
                              </td>
                              <td class="mailbox-name">
                                 <?php
                                    if($row['Temp_Scan']=='Y'){
                                    	$scan_by = $row['Temp_Scan_By'];
                                    	$scan_date = $row['Temp_Scan_Date'];
                                    }else{
                                    	$scan_by = $row['Scan_By'];
                                    	$scan_date = $row['Scan_Date'];
                                    }
                                    ?>
                                 <?php echo $this->customlib->get_Name($scan_by); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($scan_date) ? date('d-m-Y', strtotime($scan_date)) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($row['Bill_Approver_Date']) ? date('d-m-Y', strtotime($row['Bill_Approver_Date'])) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['Punch_Date'])) ?>
                              </td>
                              <?php if ($row['File_Approved'] == 'Y') { ?>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['Approve_By']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['Approve_Date'])) ?>
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
                                 <?php if ($this->customlib->haveSupportFile($row['Scan_Id']) == 1) { ?>
                                 <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['Scan_Id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                              <td>
                                 <a href="<?php echo base_url(); ?>file_detail/<?= $row['Scan_Id'] ?>/<?= $row['DocType_Id'] ?>" class="btn btn-info btn-xs" target="_blank">
                                    <i class="fa fa-eye"></i>
                                 </a>
                              </td>
                              <td>
        <?php if ($row['finance_punch_status'] == 'N'): ?>
            <!-- Approve Button -->
            <a href="<?php echo base_url(); ?>approve_file/<?= $row['Scan_Id'] ?>" class="btn btn-success btn-xs" onclick="return confirm('Are you sure you want to approve this file?')">
                <i class="fa fa-check"></i> Approve
            </a>
            <!-- Reject Button -->
            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#rejectModal<?= $row['Scan_Id'] ?>">
                <i class="fa fa-times"></i> Reject
            </button>
        <?php else: ?>
            <!-- Badge for other statuses -->
            <span class="badge badge-<?php echo ($row['finance_punch_status'] == 'Y') ? 'success' : 'secondary'; ?>">
                <?php echo ($row['finance_punch_status'] == 'Y') ? 'Approved' : 'Rejected'; ?>
            </span>
        <?php endif; ?>
    </td>

    <!-- Modal for Reject Remark -->
    <div class="modal fade" id="rejectModal<?= $row['Scan_Id'] ?>" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo base_url(); ?>reject_file/<?= $row['Scan_Id'] ?>" method="POST">
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
   function getSupportFile(Scan_Id) {
       $.ajax({
           url: '<?php echo base_url(); ?>Punch/getSupportFile',
           type: 'POST',
           data: {
               Scan_Id: Scan_Id
           },
           dataType: 'json',
           success: function(response) {
               if (response.status == 200) {
                   var x = '';
                   $.each(response.data, function(index, value) {
                       x += '<object data="' + value.File_Location + '" type="application/pdf" width="100%" height="500px"></object>';
   
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