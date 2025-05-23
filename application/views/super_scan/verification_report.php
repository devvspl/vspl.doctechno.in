<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Document Verification Report</h3>
                  <?php if ($this->session->flashdata('message')) { ?>
                  <?php echo $this->session->flashdata('message') ?>
                  <?php } ?>
               </div>
               <form role="form" action="<?= base_url(); ?>verification" method="post">
                  <div class="box-body row">
                     <div class="col-sm-2 col-md-2">
                        <div class="form-group">
                           <label>From Date :</label>
                           <input type="date" autocomplete="off" name="from_date" id="from_date" class="form-control" value="<?= set_value('from_date') ?>">
                        </div>
                     </div>
                     <div class="col-sm-2 col-md-2">
                        <div class="form-group">
                           <label>To Date :</label>
                           <input type="date" autocomplete="off" name="to_date" id="to_date" class="form-control" value="<?= set_value('to_date') ?>">
                        </div>
                     </div>
                     <div class="col-sm-3 col-md-3">
                        <div class="form-group">
                           <label> Group:</label>
                           <select name="Group" id="Group" class="form-control form-control-sm">
                              <option value="">Select</option>
                              <?php foreach ($grouplist as $key => $value) { ?>
                              <option value="<?= $value['group_id']; ?>" <?php if (set_value('Group') == $value['group_id']) {
                                 echo "selected";
                                 } ?>><?= $value['group_name'] ?></option>
                              <?php } ?>
                           </select>
                        </div>
                        <span class="text-danger"><?php echo form_error('Group'); ?></span>
                     </div>
					 <div class="col-sm-2 col-md-2">
                        <div class="form-group">
                           <label> File Type:</label>
                           <select name="DocType_Id" id="DocType_Id" class="form-control form-control-sm">
                              <option value="">Select</option>
                              <?php foreach ($getFileType as $key => $value) { ?>
                              <option value="<?= $value['type_id']; ?>" <?php if (set_value('doc_type_id') == $value['type_id']) {
                                 echo "selected";
                                 } ?>><?= $value['file_type'] ?></option>
                              <?php } ?>
                           </select>
                        </div>
                        <span class="text-danger"><?php echo form_error('doc_type_id'); ?></span>
                     </div>
                     <div class="col-sm-3 col-md-3">
                        <div class="form-group" style="margin-top: 22px;">
                           <button type="submit" id="search" name="search" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-search"></i> Search</button>
                           <button type="button" id="reset" name="reset" onclick="reloadPage();" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                     </div>
                  </div>
               </form>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Resend Scan Files</div>
                     <table class="table table-bordered table-hover example">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Document Name</th>
                              <th>File</th>
                              <th>Scan By</th>
                              <th>Scan Date</th>
                              <th>Naming By</th>
                              <th>Naming Date</th>
                              <th>Verify By</th>
                              <th>Verify Date</th>
                              <th class="no-print">Support</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($scan_list_for_verification)) {
                              ?>
                           <?php
                              } else {
                              	$count = 1;
                              	foreach ($scan_list_for_verification as $row) {
                              	?>
                           <tr>
                              <td><?php echo $count++; ?></td>
                              <td class="mailbox-name">
                                 <?php echo $row['document_name']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['temp_scan_by']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['temp_scan_date'])) ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['scanned_by']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['scan_date'])) ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Name($row['verified_by']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo date('d-m-Y', strtotime($row['verified_date'])) ?>
                              </td>
                              <td class="mailbox-name text-center no-print">
                                 <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                 <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                           </tr>
                           <?php
                              }
                              $count++;
                              }
                              ?>
                        </tbody>
                     </table>
                     <!-- /.table -->
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
   
   					x += '<object data="' + value.File_Location +
   						'" type="application/pdf" width="100%" height="500px"></object>';
   
   				});
   				$('#detail').html(x);
   				$('#SupportFileView').modal('show');
   			}
   
   
   		}
   	});
   }
   
   function reloadPage() {
   	window.location.href = "<?php echo base_url(); ?>verification";
   }
</script>
