<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Edit Bill Approver Name</h3>
                  <?php if ($this->session->flashdata('message')) { ?>
                  <?php echo $this->session->flashdata('message') ?>
                  <?php } ?>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Edit Bill Approver Name</div>
                     <table class="table table-striped table-bordered table-hover example">
                        <thead>
                           <tr class="text-center">
                              <th>S.No</th>
                              <th>Document Name</th>
                              <th>Location</th>
                              <th>File</th>
                              <th class="no-print">Support</th>
                              <th class="text-center">Bill Approver</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($list)) {
                              ?>
                           <?php
                              } else {
                              	$count = 1;
                              	foreach ($list as $row) {
                              	?>
                           <tr>
                              <td><?php echo $count++; ?></td>
                              <td class="mailbox-name">
                                 <?php echo $row['document_name']; ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $this->customlib->get_Location_Name($row['location_id']); ?>
                              </td>
                              <td class="mailbox-name">
                                 <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                              </td>
                              <td class="mailbox-name text-center no-print">
                                 <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                 <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                 <?php } ?>
                              </td>
                              <td class="mailbox-name no-print" style="display: flex;gap: 10px;align-items: center;">
                                 <div class="w-100">
                                    <select
                                       name="Scan_id"
                                       id="Scan_id_<?= $row['scan_id'] ?>"
                                       class="form-control-sm bill_approver"
                                       disabled
                                       onchange="changeBillApprover(<?= $row['scan_id'] ?>, this.value)"
                                       style="min-width: 150px;"
                                       >
                                       <option value="0">Select</option>
                                       <?php
                                          foreach ($bill_approver_list as $value) {
                                              $selected = ($value['user_id'] == $row['bill_approver_id']) ? 'selected' : '';
                                              echo "<option value='" . $value['user_id'] . "' $selected>" . $value['first_name'] . ' ' . $value['last_name'] . "</option>";
                                          }
                                          ?>
                                    </select>
                                 </div>
                                 <div>
                                    <i
                                       class="fa fa-pencil-square-o text-primary"
                                       aria-hidden="true"
                                       id="doctype_edit_<?= $row['scan_id'] ?>"
                                       onclick="editDocType(<?= $row['scan_id'] ?>, this)"
                                       style="font-size: 18px; cursor: pointer;"
                                       title="Edit Approver"
                                       ></i>
                                 </div>
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
   $(".bill_approver").select2({
      minimumResultsForSearch: 5,
      disabled: true,
      width: "resolve",
   });
   
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
   
   function editDocType(scan_id, th) {
      let $select = $("#Scan_id_" + scan_id);
      $select.prop("disabled", false);
      $select.select2("destroy").select2({
          minimumResultsForSearch: 5,
          width: "resolve",
      });
      $select.select2("open");
      $(th).hide();
   }
   
   function changeBillApprover(scan_id, bill_approver_id) {
      showLoader();
      $.ajax({
          url: "<?php echo base_url(); ?>Scan/changeBillApprover",
          type: "POST",
          data: { scan_id: scan_id, bill_approver_id: bill_approver_id },
          dataType: "json",
          success: function (response) {
              hideLoader();
              if (response.status == 200) {
                  showToast("success", "Bill approver updated successfully.");
              } else if (response.status == 400) {
                  showToast("error", "Invalid request.");
              } else {
                  showToast("error", "Something went wrong.");
              }
              setTimeout(() => window.location.reload(), 1500);
          },
          error: function () {
              hideLoader();
              showToast("error", "Request failed.");
          },
      });
   }
</script>