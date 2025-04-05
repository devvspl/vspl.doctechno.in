<style>
   .loader {
   width: 14px;
   height: 14px;
   border: 3px solid #FFF;
   border-bottom-color: #FF3D00;
   border-radius: 50%;
   display: inline-block;
   box-sizing: border-box;
   animation: rotation 1s linear infinite;
   display: none; /* Hide loader initially */
   margin-left: 10px;
   }
   @keyframes rotation {
   0% {
   transform: rotate(0deg);
   }
   100% {
   transform: rotate(360deg);
   }
   }
</style>
<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Scan Punch Report:</h3>
               </div>
     
               <form method="get" action="<?php echo site_url('All_report/index'); ?>" style="padding: 10px;" id="searchForm">
                  <select style="height: 25px;" name="Group" id="groupSelect">
                     <option value="">Select Group</option>
                     <?php foreach ($grouplist as $group): ?>
                     <option value="<?php echo $group['group_id']; ?>" <?php echo (isset($_GET['Group']) && $_GET['Group'] == $group['group_id']) ? 'selected' : ''; ?>>
                           <?php echo $group['group_name']; ?>
                     </option>
                     <?php endforeach; ?>
                  </select>

                  <label>From:</label>
                  <input style="height: 25px;" type="date" name="from_date" id="fromDate" value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>">
                  <label>To:</label>
                  <input style="height: 25px;" type="date" name="to_date" id="toDate" value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>">
                  <label>Key:</label>
                  <input style="height: 25px;" type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" id="searchInput">

               </form>

               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Scan Punch Report</div>
                     <table class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Document Name</th>
                              <th>File</th>
                              <th>Temp Scan By</th>
                              <th>Temp Scan Date</th>
                              <th>Scan/Naming By</th>
                              <th>Scan/Naming Date</th>
                              <th>Bill Approve Date</th>
                              <th>Punch By</th>
                              <th>Punch Date</th>
                              <th>Days to Punch</th>
                              <th>Approve By</th>
                              <th>Approve Date</th>
                              <th>Days to Approve</th>
                              <th>PO No</th>
                              <th>PO Date</th>
                              <td class="text-right no-print"><b>view</b></td>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($record_list)) {
                              ?>
                           <?php
                              } else {
                                  $count = $start_count;
                                  foreach ($record_list as $row) {
                                  ?>
                           <tr>
                              <td><?php echo $count++; ?></td>
                              <td class="mailbox-name">
                                 <?php echo $row['Document_Name']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location']  ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['Temp_Scan_By']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($row['Temp_Scan_Date']) ? date('d-m-Y', strtotime($row['Temp_Scan_Date'])) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['Scan_By']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($row['Scan_Date']) ? date('d-m-Y', strtotime($row['Scan_Date'])) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?= !empty($row['Bill_Approver_Date']) ? date('d-m-Y', strtotime($row['Bill_Approver_Date'])) : ''; ?>
                              </td>
                              <?php if ($row['File_Punched'] == 'Y') { ?>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['Punch_By']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['Punch_Date'])) ?>
                              </td>
                              <td class="mailbox-name text-center">
                                 <?php echo $this->customlib->dateDiff($row['Punch_Date'], $row['Scan_Date']); ?>
                              </td>
                              <?php } else { ?>
                              <td></td>
                              <td></td>
                              <td></td>
                              <?php } ?>
                              <?php if ($row['File_Approved'] == 'Y') { ?>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['Approve_By']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['Approve_Date'])) ?>
                              </td>
                              <td class="mailbox-name text-center">
                                 <?php echo $this->customlib->dateDiff($row['Approve_Date'], $row['Punch_Date']); ?>
                              </td>
                              <?php } else { ?>
                              <td></td>
                              <td></td>
                              <td></td>
                              <?php } ?>
                              <!--  <td class="mailbox-date text-center no-print">
                                 <?php if ($this->customlib->haveSupportFile($row['Scan_Id']) == 1) { ?>
                                     <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['Scan_Id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                                 </td> -->
                              <td><?= $row['ServiceNo'] ?></td>
                              <td class="mailbox-name">
                                 <?= !empty($row['BookingDate']) ? date('d-m-Y', strtotime($row['BookingDate'])) : ''; ?>
                              </td>
                              <td class="no-print">
                                 <?php if ($row['File_Punched'] == 'Y') { ?>
                                 <a href="<?php echo base_url(); ?>file_detail/<?= $row['Scan_Id'] ?>/<?= $row['DocType_Id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                              <?php
                                 }
                                 $count++;
                                 }
                                 ?>
                        </tbody>
                     </table>
                     <!-- /.table -->
                     <div class="pagination" style="display: flex;justify-content: center;gap: 10px;">
                        <?php echo $pagination; ?>
                     </div>
                  </div>
                  <!-- /.mail-box-messages -->
               </div>
               <!-- /.box-body -->
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
      const formFields = ['searchInput', 'groupSelect', 'fromDate', 'toDate'];

formFields.forEach(function(fieldId) {
    document.getElementById(fieldId).addEventListener('change', function() {
        document.getElementById('searchForm').submit(); 
    });
    document.getElementById(fieldId).addEventListener('focusout', function() {
        document.getElementById('searchForm').submit(); 
    });
});
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
                       /*  x += '<div class="col-md-4">';
                        x += '<div class="form-group">';
                        x += '<a href="javascript:void(0);" target="popup" onclick="window.open(\'' + value.File_Location + '\',\'popup\',\'width=600,height=600\');">' + value.File + '</a>';
                        x += '</div>';
                        x += '</div>'; */
                       x += '<object data="' + value.File_Location + '" type="application/pdf" width="100%" height="500px"></object>';
   
                   });
                   $('#detail').html(x);
                   $('#SupportFileView').modal('show');
               }
   
   
           }
       });
   }
   
   function reloadPage() {
       window.location.href = "<?php echo base_url(); ?>all_report";
   }
</script>
<script>
   document.getElementById('search_form').addEventListener('submit', function() {
   	document.querySelector('.loader').style.display = 'inline-block';
   	document.getElementById('search').disabled = true;
   });
</script>