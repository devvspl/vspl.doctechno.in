<div class="content-wrapper" style="min-height: 946px;">
   <style>
      .pagination{
      display: flex;
      gap: 10px;
      text-align: center;
      justify-content: center;
      }
   </style>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">My Approved Files</h3>
               </div>
               <form role="form" action="<?= base_url(); ?>Approve/my_approved_file" method="get" id="filterForm">
                  <div class="box-body row">
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="groupSelect">Group</label>
                           <select style="height: 25px;" name="Group" id="groupSelect" class="form-control" onchange="submitFilterForm()">
                              <option value="">Select Group</option>
                              <?php foreach ($grouplist as $group): ?>
                              <option value="<?php echo $group['group_id']; ?>" <?php echo (isset($_GET['Group']) && $_GET['Group'] == $group['group_id']) ? 'selected' : ''; ?>>
                                 <?php echo $group['group_name']; ?>
                              </option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="doctypeSelect">Doc Type</label>
                           <select style="height: 25px;" name="Doctype" id="doctypeSelect" class="form-control" onchange="submitFilterForm()">
                              <option value="">Select Doctype</option>
                              <?php foreach ($doctypelist as $doctype): ?>
                              <option value="<?php echo $doctype['type_id']; ?>" <?php echo (isset($_GET['Doctype']) && $_GET['Doctype'] == $doctype['type_id']) ? 'selected' : ''; ?>>
                                 <?php echo $doctype['file_type']; ?>
                              </option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="fromDate">From Date</label>
                           <input style="height: 25px;" type="date" name="from_date" id="fromDate" class="form-control"
                              value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>" onchange="submitFilterForm()">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="toDate">To Date</label>
                           <input style="height: 25px;" type="date" name="to_date" id="toDate" class="form-control"
                              value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>" onchange="submitFilterForm()">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="searchInput">Search</label>
                           <input style="height: 25px;" type="text" name="search" placeholder="Search..." id="searchInput" class="form-control"
                              value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" onchange="submitFilterForm()">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <button type="button" id="reset" style="margin-top: 20px;" name="reset" onclick="window.location.href = '<?= base_url('Approve/my_approved_file') ?>';" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                     </div>
                  </div>
               </form>
               <script>
                  function submitFilterForm() {
                     document.getElementById('filterForm').submit();
                  }
               </script>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">My Punched Files</div>
                     <table class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>master/GroupController/Company</th>
                              <th>Document Name</th>
                              <th>Document Type</th>
                              <th>File</th>
                              <th>Punch Date</th>
                              <th>Approve Date</th>
                              <th class="text-right no-print">Support File</th>
                              <th class="text-right no-print">view</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($approve_file_list)) {
                              ?>
                           <?php
                              } else {
                                  $count = 1;
                                  foreach ($approve_file_list as $row) {
                                  ?>
                           <tr>
                              <td><?php echo $count++; ?></td>
                              <td class="mailbox-name">
                                 <?php echo $row['group_name']; ?>
                              </td>
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
                                 <?php echo date('d-m-Y', strtotime($row['punched_date'])) ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['approved_date'])) ?>
                              </td>
                              <td class="mailbox-date text-center no-print">
                                 <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                 <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                              <td>
                                 <a href="<?php echo base_url(); ?>file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                              </td>
                              <?php
                                 }
                                 $count++;
                                 }
                                 ?>
                        </tbody>
                     </table>
                     <div class="pagination">
                        <?= $pagination; ?>
                     </div>
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
       window.location.href = "<?php echo base_url(); ?>my_approved_file";
   }
</script>