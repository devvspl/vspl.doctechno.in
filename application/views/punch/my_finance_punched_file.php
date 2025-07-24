<div class="content-wrapper" >
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">My Punched Files</h3>
               </div>
               <form role="form" action="<?= base_url(); ?>finance/my-punched-file" method="post">
                  <div class="box-body row">
                     <div class="col-sm-2 col-md-2">
                        <div class="form-group">
                           <label>Punch From Date</label>
                           <input type="date" autocomplete="off" name="from_date" id="from_date" class="form-control"
                              value="<?= set_value('from_date') ?>">
                        </div>
                        <span class="text-danger"><?php echo form_error('from_date'); ?></span>
                     </div>
                     <div class="col-sm-2 col-md-2">
                        <div class="form-group">
                           <label>Punch To Date</label>
                           <input type="date" autocomplete="off" name="to_date" id="to_date" class="form-control"
                              value="<?= set_value('to_date') ?>">
                        </div>
                        <span class="text-danger"><?php echo form_error('to_date'); ?></span>
                     </div>
                     <div class="col-sm-3 col-md-3">
                        <div class="form-group" style="margin-top: 22px;">
                           <button type="submit" id="search" name="search"
                              class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-search"></i>
                              Search</button>
                        </div>
                     </div>
                  </div>
               </form>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">My Punched Files</div>
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
                                       <a href="javascript:void(0);" target="popup"
                                          onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                                          <?php echo $row['file_name'] ?></a>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php
                                       if ($row['is_temp_scan'] == 'Y') {
                                          $scan_by = $row['temp_scan_by'];
                                          $scan_date = $row['temp_scan_date'];
                                       } else {
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
                                       <?php
                                       if (!empty($row['finance_punched_date'])) {
                                          echo date('d-m-Y', strtotime($row['finance_punched_date']));
                                       } else {
                                          echo '-';
                                       }
                                       ?>
                                    </td>
                                    <td class="mailbox-date text-center no-print">
                                       <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                          <a href="javascript:void(0);" class="btn btn-link btn-xs"
                                             onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                       <?php } ?>
                                    </td>
                                    <td>
                                       <a href="<?php echo base_url(); ?>vspl_file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>"
                                          class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
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
   function reloadPage() {
      window.location.href = "<?php echo base_url(); ?>punch/my-punched-file/all";
   }
</script>