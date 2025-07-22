<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 style="float: left;margin-top: 7px;" class="box-title">My Punched Files</h3>
                  <div style="float: right;">
                     <form id="punchedForm" role="form" action="<?= base_url('punched_files'); ?>" method="get">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <input style="width: 130px;" type="date" autocomplete="off" name="from_date"
                                    id="from_date" class="form-control"
                                    value="<?= isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d') ?>">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <input style="width: 130px;" type="date" autocomplete="off" name="to_date" id="to_date"
                                    class="form-control"
                                    value="<?= isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d') ?>">
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">My Punched Files</div>
                     <table class="table table-striped table-bordered table-hover" id="punchedFilesTable">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Document Name</th>
                              <th style="text-align: center;">File</th>
                              <th style="text-align: center;">Document Type</th>

                              <th style="text-align: center;">Scan By</th>
                              <th style="text-align: center;">Scan Date</th>
                              <th style="text-align: center;">Punch Date</th>
                              <th style="text-align: center;" class="text-right no-print">View</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($my_punched_files)) {
                              ?>
                              <?php
                           } else {
                              $count = 1;
                              foreach ($my_punched_files as $row) {
                                 ?>
                                 <tr>
                                    <td style="text-align: center;"><?php echo $count++; ?></td>
                                    <td style="text-align: left;" class="mailbox-name">
                                       <?php echo $row['document_name']; ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <a href="javascript:void(0);" target="popup"
                                          onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                                          <?php echo $row['file_name'] ?></a>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?php echo $row['doc_type']; ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?php echo $this->customlib->get_Name($row['temp_scan_by']); ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?= !empty($row['temp_scan_date']) ? date('d-m-Y', strtotime($row['temp_scan_date'])) : ''; ?>
                                    </td>
                                    <td style="text-align: center;" class="mailbox-name">
                                       <?= !empty($row['punched_date']) ? date('d-m-Y', strtotime($row['punched_date'])) : ''; ?>
                                    </td>
                                    <td style="text-align: center;">
                                       <a href="<?php echo base_url(); ?>file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>"
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
<script>
   $(document).ready(function () {
      $("#punchedFilesTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         pageLength: 25,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Punched_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
   });
   document.getElementById('from_date').addEventListener('change', function () {
      document.getElementById('punchedForm').submit();
   });
   document.getElementById('to_date').addEventListener('change', function () {
      document.getElementById('punchedForm').submit();
   });
   document.getElementById('from_date').addEventListener('focus', function () {
      this.showPicker && this.showPicker();
   });
   document.getElementById('to_date').addEventListener('focus', function () {
      this.showPicker && this.showPicker();
   });
</script>