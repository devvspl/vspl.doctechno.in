<div class="content-wrapper" >
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">Scanned File Pending for Approval</h3>
                  <div class="box-tools pull-right">
                     <a href="<?= base_url(); ?>dashboard" class="btn btn-primary btn-sm"><i
                           class="fa fa-long-arrow-left"></i> Back</a>
                  </div>
                  <?php if ($this->session->flashdata('message')) { ?>
                     <?php echo $this->session->flashdata('message') ?>
                  <?php } ?>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Scanned File Pending for Approval</div>
                     <table class="table table-striped table-bordered table-hover example">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Location</th>
                              <th>Document Name</th>
                              <th>Bill</th>
                              <th>Scan By</th>
                              <th>Scan Date</th>
                              <th class="no-print">Support</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($bill_list)) {
                              ?>
                              <?php
                           } else {
                              $count = 1;
                              foreach ($bill_list

                                 as $row) {
                                 ?>
                                 <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td class="mailbox-name">
                                       <?php echo $row['location_name']; ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['document_name']; ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <a href="javascript:void(0);" target="popup"
                                          onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                                          <?php echo $row['file_name'] ?></a>
                                    </td>
                                    <?php
                                    if ($row['is_temp_scan'] === 'Y') { ?>
                                       <td class="mailbox-name">
                                          <?php echo $this->customlib->get_Name($row['temp_scan_by']); ?>
                                       </td>
                                       <td class="mailbox-name">
                                          <?php echo date('d-m-Y', strtotime($row['temp_scan_date'])) ?>
                                       </td>
                                    <?php } else { ?>
                                       <td class="mailbox-name">
                                          <?php echo $this->customlib->get_Name($row['scanned_by']); ?>
                                       </td>
                                       <td class="mailbox-name">
                                          <?php echo date('d-m-Y', strtotime($row['scan_date'])) ?>
                                       </td>
                                    <?php } ?>
                                    <td class="mailbox-name text-center no-print">
                                       <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                          <a href="javascript:void(0);" class="btn btn-link btn-xs"
                                             onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                       <?php } ?>
                                    </td>
                                    <td>
                                       <a href="<?php echo base_url('bill_detail/' . $row['scan_id']); ?>"
                                          class="btn btn-primary btn-xs" data-toggle="tooltip" title=""
                                          data-original-title="View"><i class="fa fa-eye"></i> View</a>
                                    </td>
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
         success: function (response) {
            if (response.status == 200) {
               var x = '';
               $.each(response.data, function (index, value) {
                  x += '<object data="' + value.file_path + '" type="application/pdf" width="100%" height="500px"></object>';
               });
               $('#detail').html(x);
               $('#SupportFileView').modal('show');
            }
         }
      });
   }
</script>