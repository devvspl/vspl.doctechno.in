<style>
   .form-control-sm {
   display: inline-block;
   width: 70%;
   font-size: 10pt;
   line-height: 1.42857143;
   color: #555;
   background-color: #fff;
   background-image: none;
   border: 1px solid #ccc;
   }
</style>
<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Punch File</h3>
               </div>
               <div class="box-body">
                  <?php if ($this->session->flashdata('message')) { ?>
                  <?php echo $this->session->flashdata('message') ?>
                  <?php } ?>
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Latest Scan File</div>
                     <table class="table table-striped table-bordered table-hover example">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Location</th>
                              <th>Document Name</th>
                              <th>File</th>
                              <th>Scanned By</th>
                              <th>Scan Date</th>
                              <th>Bill Approve Date</th>
                              <th>Punched By</th>
                              <th>Punch Date</th>
                              <th>Support File</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($scanfile_list)) {
                              ?>
                           <?php
                              } else {
                                  $count = 1;
                                  foreach ($scanfile_list as $row) {
                              
                                  ?>
                           <tr>
                              <td><?php echo $count++; ?></td>
                              <td><?= $this->customlib->get_Location_Name($row['Location'])?></td>
                              <td class="mailbox-name">
                                 <?php echo $row['Document_Name']; ?>
                                 <span class="fa fa-pencil edit_doc_name" style="cursor: pointer; display: none;" data-id="<?= $row['Scan_Id'] ?>" data-val="<?= $row['Document_Name']; ?>"></span>
                              </td>
                              <td class="mailbox-name">
                                 <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
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
                                 <?php
                                    $punch_by = isset($row['Punch_By']) ? $row['Punch_By'] : '';
                                    
                                    if (!empty($punch_by)) {
                                    
                                        $punch_by = (int) $punch_by;
                                    
                                        $q = $this->db->query("SELECT * FROM users WHERE user_id = $punch_by")->row(); if ($q) { echo $q->first_name. ' '.$q->last_name; } } ?>
                              </td>
                              <td class="mailbox-name">
                                 <?=  !empty($row['Punch_Date']) ? date('d-m-Y', strtotime($row['Punch_Date'])) : ''; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php if ($this->customlib->haveSupportFile($row['Scan_Id']) == 1) { ?>
                                 <a href="#" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['Scan_Id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                              <td class="mailbox-name">
                              <?php if ($row['DocType_Id'] != 0) { ?>
                                 <?php if ($row['DocType_Id'] == 57) { ?>
                                       <a href="<?php echo base_url(); ?>file_entry/<?= $row['Scan_Id'] ?>/<?= $row['DocType_Id'] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" title="Punch File">
                                          <i class="fa fa-pencil"></i> Punch
                                       </a>
                                 <?php } else { ?>
                                       <a href="<?php echo base_url(); ?>vspl_file_entry/<?= $row['Scan_Id'] ?>/<?= $row['DocType_Id'] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" title="Punch File">
                                          <i class="fa fa-pencil"></i> Punch
                                       </a>
                                 <?php } ?>
                                 
                              <?php } ?>
                           </td>

                              <?php
                                 }
                                 $count++;
                                 }
                                 ?>
                           </tr>
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
               <div id="detail"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   function getSupportFile(Scan_Id) {
       $.ajax({
           url: "<?php echo base_url(); ?>Punch/getSupportFile",
           type: "POST",
           data: {
               Scan_Id: Scan_Id,
           },
           dataType: "json",
           success: function (response) {
               if (response.status == 200) {
                   var x = "";
                   $.each(response.data, function (index, value) {
                       x += '<object data="' + value.File_Location + '" type="application/pdf" width="100%" height="500px"></object>';
                   });
                   $("#detail").html(x);
                   $("#SupportFileView").modal("show");
               }
           },
       });
   }
   $(document).on('click', '#resend_scan', function() {
        var Scan_Id = $(this).data('id');
        var Remark = prompt("Please enter remark to resend this file");
        if (Remark == null) {
            window.location.reload();
        } else {
            $.ajax({
                url: '<?php echo base_url(); ?>finance_resend_scan/' + Scan_Id,
                type: 'POST',
                data: {
                    Remark: Remark
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        alert('Record Resend Successfully');
                        location.reload();
                    }
                }
            });
        }
    });
</script>
