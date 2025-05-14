<style type="text/css">
   .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
   cursor: default;
   padding-left: 15px !important;
   padding-right: 5px !important;
   }
   .select2-container--default .select2-selection--multiple .select2-selection__choice {
   color: #0c0c0c !important;
   }
</style>
<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Update Bill Approver</h3>
               </div>
               <form id="form1" action="<?= base_url(); ?>bill_approver/update/<?= $id ?>" id="userform" name="userform" method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                     <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <label for="exampleInputEmail1">First Name</label>
                        <input autofocus="" id="first_name" name="first_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('first_name', $user['first_name']); ?>" />
                        <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Last Name</label>
                        <input autofocus="" id="last_name" name="last_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('last_name', $user['last_name']); ?>" />
                        <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Company</label>
                        <select name="firm[]" id="firm" class="form-control" multiple="multiple">
                           <?php 
                              $firm_ids = !is_null($user['firm_id']) ? $user['firm_id'] : '';
                              $firm_ids_array = explode(',', $firm_ids);
                              
                              foreach ($companylist as $key => $value) { 
                              ?>
                           <option value="<?= $value['firm_id'] ?>" <?php echo in_array($value['firm_id'], $firm_ids_array) ? 'selected' : ''; ?>>
                              <?= $value['firm_name'] ?>
                           </option>
                           <?php 
                              } 
                              ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('firm[]'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Department</label>
                        
                        <select name="department[]" id="department" class="form-control" multiple="multiple">
                           <?php 
                              $department_ids = !is_null($user['department_id']) ? $user['department_id'] : '';
                              $department_ids_array = explode(',', $department_ids);
                              
                              foreach ($departmentlist as $key => $value) { 
                              ?>
                           <option value="<?= $value['api_id'] ?>" <?php echo in_array($value['department_id'], $department_ids_array) ? 'selected' : ''; ?>>
                              <?= $value['department_name'] ?>
                           </option>
                           <?php 
                              } 
                              ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('department[]'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Location</label>
                        <select name="location[]" id="location" class="form-control" multiple="multiple">
                           <?php 
                              $user_location_ids = !empty($user['location_id']) ? explode(',', $user['location_id']) : [];
                              
                              foreach ($locationlist as $key => $value) { ?>
                           <option value="<?= $value['location_id'] ?>" <?php echo in_array($value['location_id'], $user_location_ids) ? 'selected' : ''; ?>>
                              <?= $value['location_name'] ?>
                           </option>
                           <?php } ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('location[]'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input id="username" name="username" placeholder="" type="text" class="form-control" value="<?php echo set_value('username', $user['username']); ?>" />
                        <span class="text-danger"><?php echo form_error('username'); ?></span>
                     </div>
                  </div>
                  <div class="box-footer">
                     <button type="submit" class="btn btn-info pull-right">Save</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-md-9">
            <div class="box box-primary" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Bill Approver List</h3>
               </div>
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Bill Approver List</div>
                     <table class="table table-striped table-bordered table-hover example">
                        <thead>
                           <tr>
                              <th>Name</th>
                              <th>UserName</th>
                              <th>Location</th>
                              <th class="text-right no-print">Action</th>
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
                              <td class="mailbox-name">
                                 <?php echo $row['first_name'] . ' ' . $row['last_name'] ?>
                              </td>
                              <td class="mailbox-name">
                                 <?php echo $row['username'] ?>
                              </td>
                              <td>
                                 <?php 
                                    if (!empty($row['location_id'])) {
                                    	$locationIds = explode(',', $row['location_id']);
                                    	
                                    
                                    	$locations = $this->db->select('location_name')
                                    						->where_in('location_id', $locationIds)
                                    						->get('master_work_location')
                                    						->result_array();
                                    
                                    
                                    	foreach ($locations as $location) {
                                    		echo $location['location_name'] . ', &nbsp;';
                                    	}
                                    } 
                                    ?>
                              </td>
                              <td class="mailbox-date pull-right no-print">
                                 <a href="<?= base_url(); ?>bill_approver/edit/<?php echo $row['user_id'] ?>" class="btn btn-default btn-xs">
                                 <i class="fa fa-pencil"></i>
                                 </a>
                                 <a href="<?= base_url(); ?>bill_approver/delete/<?php echo $row['user_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
                                 <i class="fa fa-remove"></i>
                                 </a>
                              </td>
                           </tr>
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
   $(document).ready(function() {
   	$("#location").select2({
   		multiple:true
   	});
       $("#firm").select2({
   		multiple:true
   	});
       $("#department").select2({
   		multiple:true
   	});
       $('.example1').DataTable({
           destroy: true,
           pageLength: 12,
       });
       $('#firm').on('change', function() {
       var selectedCompanies = $(this).val();
       
       $.ajax({
           url: '<?= site_url('master/BillApproverController/get_departments') ?>',
           type: 'POST',
           dataType: 'json',
           data: { company_ids: selectedCompanies },
           success: function(data) {
               var $departmentSelect = $('#department');
               $departmentSelect.empty();
               
               $.each(data, function(index, department) {
                   $departmentSelect.append(
                       $('<option></option>').val(department.department_id).text(department.department_name)
                   );
               });
           },
           error: function() {
               alert('Failed to retrieve departments.');
           }
       });
   });
   });
</script>