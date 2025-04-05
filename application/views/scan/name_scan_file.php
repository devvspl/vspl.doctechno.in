<?php
   $Scan_Id = $this->uri->segment(2);
   $rec = $this->customlib->getScanData($Scan_Id);
   $group_id = $rec->Group_Id;
   $Bill_Approver = $rec->Bill_Approver;
   
   ?>
   <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css" />
<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="box-body">
         <div class="row">
            <div class="col-md-8">
               <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id; ?>">
               <?php if ($rec->File_Ext == 'pdf') { ?>
               <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
               <?php } else { ?>
               <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
               <div id="imageViewerContainer" style=" width: 450px; height:490px; border:2px solid #3a495e; border:2px solid #3a495e;"></div>
               <script>
                  var curect_file_path = $('#image').val();
                  $("#imageViewerContainer").verySimpleImageViewer({
                  	imageSource: curect_file_path,
                  	frame: ['100%', '100%'],
                  	maxZoom: '900%',
                  	zoomFactor: '10%',
                  	mouse: true,
                  	keyboard: true,
                  	toolbar: true,
                  	rotateToolbar: true
                  });
               </script>
               <?php } ?>
            </div>
            <div class="col-md-4">
               <form style="background-color: #fff;" id="form1" action="<?= base_url('Scan/update_document_name/').$rec->Scan_Id; ?>"  method="post" accept-charset="utf-8" enctype="multipart/form-data">
                  <input type="hidden" name="group_id" id="group_id" value="<?= $group_id; ?>">   
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                     <?= $this->session->flashdata('message') ?>
                     <?php } ?>
							<?php if ($this->session->flashdata('error')) { ?>
							<?php echo $this->session->flashdata('error') ?>
						<?php } ?>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="doctype">Document Type<i style="color: red;">*</i></label>
                              <select name="doctype_id" id="doctype" class="form-control" required>
                                 <option value="">Select Document Type</option>
                                 <?php
                                    $selected = $rec->scan_doctype_id;
                                    foreach ($doctypeList as $value) { ?>
                                 <option value="<?= $value['type_id'] ?>" <?= ($value['type_id'] == $selected) ? 'selected' : ''; ?>>
                                    <?= $value['file_type'] ?>
                                 </option>
                                 <?php } ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('doctype'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="location">Location<i style="color: red;">*</i></label>
                              <select name="location" id="location" class="form-control" required>
                                 <option value="">Select Location</option>
                                 <?php
                                    $selected = $rec->Location;
                                    foreach ($locationlist as $value) { ?>
                                 <option value="<?= $value['location_id'] ?>" <?= ($value['location_id'] == $selected) ? 'selected' : ''; ?>><?= $value['location_name'] ?></option>
                                 <?php } ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('location'); ?></span>
                           </div>
                        </div>
                        <?php 
                           if($rec->Bill_Approved == 'N') {
								
										
                           ?>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="department">Department</label>
                              <select name="department_id" id="department" class="form-control" >
											<option value="">Select Department</option>
											<?php

												$selected_department = !empty($rec->department_id) ? $rec->department_id : $getdepartments;

												foreach ($departmentList as $value) { ?>
														<option value="<?= $value['department_id'] ?>" 
															<?= ($value['department_id'] == $selected_department) ? 'selected' : ''; ?>>
															<?= $value['department_name'] ?>
														</option>
											<?php } ?>
											<span class="text-danger"><?php echo form_error('department'); ?></span>
										</select>

                           </div>
                        </div>
                        <?php 
                           }
                           
                           ?>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="firm">Vendor Name/Exp Head</label>
                              <select name="firm_id" id="firm" class="form-control" >
                                 <option value="">Select Vendor Name / Exp Head</option>
                                 <?php foreach ($firmList as $value) { ?>
                                 <option value="<?= $value['firm_id'] ?>" data-name="<?= $value['firm_name'] ?>"><?= $value['firm_name'] ?></option>
                                 <?php } ?>
                                 <span class="text-danger"><?php echo form_error('firm'); ?></span>
                              </select>
                           </div>
                        </div>
                        <?php 
                           $Bill_Approver = $rec->Bill_Approver;
                           if ($rec->Bill_Approved == 'N') {
                           ?>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="bill_approver">Bill Approver<i style="color: red;">*</i></label>
                              <select name="bill_approver" id="bill_approver" class="form-control" required>
                                 <option value="">Select Approver</option>
                              </select>
                              <span class="text-danger"><?php echo form_error('bill_approver'); ?></span>
                           </div>
                        </div>
                        <?php 
                           }
                           
                           ?>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="bill_voucher_date">Bill Date/Voucher Date</label>
                              <input type="text" placeholder="MM/DD/YYYY" name="bill_voucher_date" id="bill_voucher_date" class="form-control"  />
                              <span class="text-danger"><?php echo form_error('bill_voucher_date'); ?></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="bill_no_voucher_no">Bill No/Voucher No</label>
                              <input type="text" placeholder="Enter Bill No or Voucher No" name="bill_no_voucher_no" id="bill_no_voucher_no" class="form-control"  />
                              <span class="text-danger"><?php echo form_error('bill_no_voucher_no'); ?></span>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="document_name">Document Name<i style="color: red;">*</i></label>
                        <input type="text" name="document_name" id="document_name" class="form-control" required />
                        <span class="text-danger"><?php echo form_error('document_name'); ?></span>
                     </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
				    <button type="button" class="btn btn-sm btn-danger" id="reject" style="float: left;margin-top:10px;">Reject</button>
                     <button type="submit" id="upload_main" class="btn btn-info pull-right">Save</button>
                  </div>
               </form>
               <!-- <label for="">Document Name:</label>
                  <input type="text" id="document_name" name="document_name" class="form-control form-control-sm">
                  <button type="button" class="btn btn-sm btn-danger" id="reject" style="float: left;margin-top:10px;">Reject</button>
                  <button type="button" class="btn btn-sm btn-success" id="save" style="float: right;margin-top:10px;">Save</button> -->
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   $(document).on('click', '#save', function() {
   	var Scan_Id = $("#Scan_Id").val();
   	var Document_Name = $("#document_name").val();
   	if (Document_Name == null || Document_Name == '') {
   		alert("Please Enter Document Name");
   		return false;
   	} else {
   		$.ajax({
   			url: '<?php echo base_url(); ?>update_document_name/' + Scan_Id,
   			type: 'POST',
   			data: {
   				Document_Name: Document_Name
   			},
   			dataType: 'json',
   			success: function(response) {
   				if (response.status == 200) {
   					alert('Document Name Changed Successfully');
   					setTimeout(() => {
   						window.opener.location.reload(true);
   						window.close();
   					}, 1000);
   
   				}
   			}
   		});
   	}
   });
   
   $(document).on('click', '#reject', function() {
    var Scan_Id = $("#Scan_Id").val();
    var remark = prompt("Please enter reject remark");
   
    if (remark !== null && remark !== '') {
      $.ajax({
        url: '<?php echo base_url(); ?>reject_temp_scan/' + Scan_Id,
        type: 'POST',
        data: { remark: remark },
        dataType: 'json',
        success: function(response) {
          if (response.status == 200) {
            alert("Document Rejected Successfully");
            setTimeout(function() {
              window.opener.location.reload(true);
              window.close();
            }, 1000);
          }
        }
      });
    }else{
        alert("Please enter remark...")
    }
   });
</script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script>
var groupId = "<?php echo $group_id;?>" ;
var bill_approver = "<?php echo $Bill_Approver;?>" ;

var defaultApproverId = 115;
var approversByLocation = <?= json_encode($approversByLocation ?? []) ?>;
var approversByDepartment = <?= json_encode($approversByDepartment ?? []) ?>;

function populateApproversByLocation(locationId, defaultApproverId) {
   var $approverSelect = $('#bill_approver');
   $approverSelect.empty().append('<option value="">Select Approver</option>');

   if (approversByLocation[locationId]) {
      $.each(approversByLocation[locationId], function (index, approver) {
         var selected = (approver.user_id == bill_approver) ? 'selected' : '';
         $approverSelect.append('<option value="' + approver.user_id + '" ' + selected + '>' + approver.first_name + ' ' + approver.last_name + '</option>');
      });
   }

   appendDefaultApprover($approverSelect, bill_approver);
}

function populateApproversByDepartment(departmentId, defaultApproverId) {
   var $approverSelect = $('#bill_approver');
   $approverSelect.empty().append('<option value="">Select Approver</option>');

   if (approversByDepartment[departmentId]) {
      $.each(approversByDepartment[departmentId], function (index, approver) {
         var selected = (approver.user_id == bill_approver) ? 'selected' : '';
         $approverSelect.append('<option value="' + approver.user_id + '" ' + selected + '>' + approver.first_name + ' ' + approver.last_name + '</option>');
      });
   }

   appendDefaultApprover($approverSelect, bill_approver);
}

function appendDefaultApprover($approverSelect, defaultApproverId) {
   var defaultApproverExists = false;

   $approverSelect.find('option').each(function () {
      if ($(this).val() == defaultApproverId) {
         defaultApproverExists = true;
         $(this).attr('selected', 'selected'); // Select the default approver if it matches
         return false;
      }
   });

   if (!defaultApproverExists && (defaultApproverId !== null && defaultApproverId !== '')) {
      $approverSelect.append('<option value="' + defaultApproverId + '" selected>Auto Approver</option>');
   }
}

function generateDocumentName(format) {
   // Get the date value and format it
   var dateVal = $('#bill_voucher_date').val();
   var formattedDate = '';
   if (dateVal) {
      var dateParts = dateVal.split('/');
      if (dateParts.length === 3) {
         formattedDate = dateParts[1] + dateParts[0] + dateParts[2].substr(-2);
      }
   }

   // Trim the docType value to remove leading and trailing spaces
   var docType = $('#doctype option:selected').text().trim();

   // Initialize vendorName based on the docType
   var vendorName = '';
   if (docType === 'Labour Payment') {
      vendorName = (docType + ' ' + ($('#location option:selected').text() || '')).toUpperCase();
   } else {
      vendorName = (($('#firm option:selected').data('name') || docType) || '').toUpperCase();
   }

   // Get the bill number or voucher number
   var billNoVoucherNo = $('#bill_no_voucher_no').val() || '';

   // Generate the document name based on the selected format
   var documentName = '';
   if (format === 'underscore') {
      documentName = formattedDate + '_' + vendorName + '_' + billNoVoucherNo;
   } else if (format === 'slash') {
      documentName = formattedDate + '/' + vendorName + '/' + billNoVoucherNo;
   }

   // Set the generated document name in the input field
   $('#document_name').val(documentName);
}



$(document).ready(function () {

   if (!bill_approver) {
      bill_approver = '';
   }

   $("#location, #doctype, #department, #firm, #bill_approver").select2();
   $("#bill_voucher_date").datepicker();


   $('#bill_voucher_date, #bill_no_voucher_no, #location, #doctype, #firm').on('change keyup', function () {
      generateDocumentName('underscore');
   });


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


   $(document).on('click', '#delete_all', function () {
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
                  window.location.href = '<?= base_url() ?>Scan';
               }
            }
         });
      }
   });

   var initialLocationId = $('#location').val();
   var initialDepartmentId = $('#department').val();

   if (groupId == 16) {
      if (initialDepartmentId) {
         populateApproversByDepartment(initialDepartmentId, defaultApproverId);
      }

      $('#department').on('change', function () {
         var departmentId = $(this).val();
         if (departmentId) {
            populateApproversByDepartment(departmentId, defaultApproverId);
         }
      });
   } else {
      if (initialLocationId) {
         populateApproversByLocation(initialLocationId, defaultApproverId);
      }

      $('#location').on('change', function () {
         var locationId = $(this).val();
         if (locationId) {
            populateApproversByLocation(locationId, defaultApproverId);
         }
      });
   }
});

</script>
