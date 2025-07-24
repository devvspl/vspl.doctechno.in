<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title"><?= !empty($vendor['firm_id']) ? 'Edit Vendor' : 'Add Vendor' ?></h3>
               </div>
               <form id="form1" action="<?= base_url('save_vendor' . (!empty($vendor['firm_id']) ? '/' . $vendor['firm_id'] : '')) ?>" 
                  name="firm_form" method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                     <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <label for="firm_name">Vendor Name</label>
                        <input autofocus id="firm_name" name="firm_name" type="text" class="form-control"
                           value="<?= set_value('firm_name', !empty($vendor['firm_name']) ? $vendor['firm_name'] : '') ?>" />
                        <span class="text-danger"><?php echo form_error('firm_name'); ?></span>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="firm_type">Vendor Type</label>
                           <select name="firm_type" id="firm_type" class="form-control">
                              <option value="">Select</option>
                              <option value="Vendor" selected>Vendor</option>
                           </select>
                           <span class="text-danger"><?php echo form_error('firm_type'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="focus_code">Focus Code</label>
                           <input id="focus_code" name="focus_code" type="text" class="form-control"
                              value="<?= set_value('focus_code', !empty($vendor['focus_code']) ? $vendor['focus_code'] : '') ?>" />
                           <span class="text-danger"><?php echo form_error('focus_code'); ?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="gst">GST</label>
                        <input id="gst" name="gst" type="text" class="form-control"
                           value="<?= set_value('gst', !empty($vendor['gst']) ? $vendor['gst'] : '') ?>" />
                        <span class="text-danger"><?php echo form_error('gst'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="address">Address</label>
                        <input id="address" name="address" type="text" class="form-control"
                           value="<?= set_value('address', !empty($vendor['address']) ? $vendor['address'] : '') ?>" />
                        <span class="text-danger"><?php echo form_error('address'); ?></span>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="country_id">Country</label>
                           <select name="country_id" id="country_id" class="form-control">
                              <option value="">Select</option>
                              <?php foreach ($countries as $country): ?>
                              <option value="<?= $country['country_id'] ?>" 
                                 <?= set_select('country_id', $country['country_id'], !empty($vendor['country_id']) && $vendor['country_id'] == $country['country_id']) ?>>
                                 <?= htmlspecialchars($country['country_name']) ?>
                              </option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('country_id'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="state_id">State</label>
                           <select name="state_id" id="state_id" class="form-control">
                              <option value="">Select</option>
                              <?php foreach ($states as $state): ?>
                              <option value="<?= $state['state_id'] ?>" 
                                 <?= set_select('state_id', $state['state_id'], !empty($vendor['state_id']) && $vendor['state_id'] == $state['state_id']) ?>>
                                 <?= htmlspecialchars($state['state_name']) ?>
                              </option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="city_name">City</label>
                           <input id="city_name" name="city_name" type="text" class="form-control"
                              value="<?= set_value('city_name', !empty($vendor['city_name']) ? $vendor['city_name'] : '') ?>" />
                           <span class="text-danger"><?php echo form_error('city_name'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="pin_code">Pin Code</label>
                           <input id="pin_code" name="pin_code" type="text" class="form-control"
                              value="<?= set_value('pin_code', !empty($vendor['pin_code']) ? $vendor['pin_code'] : '') ?>" />
                           <span class="text-danger"><?php echo form_error('pin_code'); ?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                           <option value="A" <?= set_select('status', 'A', !empty($vendor['status']) && $vendor['status'] == 'A') ?>>Active</option>
                           <option value="D" <?= set_select('status', 'D', !empty($vendor['status']) && $vendor['status'] == 'D') ?>>Deactive</option>
                        </select>
                        <span class="text-danger"><?php echo form_error('status'); ?></span>
                     </div>
                  </div>
                  <div class="box-footer">
                     <button type="submit" class="btn btn-info pull-right">Save</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-md-9">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Vendor List</h3>
                  <div class="box-tools pull-right">
                     <div class="btn-group pull-right">
                     </div>
                  </div>
               </div>
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Vendor List</div>
                     <table id="vendorTable" class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Focus Code</th>
                              <th>Status</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($vendor_list)) {
                              foreach ($vendor_list as $row) { ?>
                           <tr>
                              <td><?php echo $row['firm_id']; ?></td>
                              <td><?php echo $row['firm_name']; ?></td>
                              <td><?php echo $row['focus_code']; ?></td>
                              <td><?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive'; ?></td>
                              <td style="text-align:center;">
                               <a href="javascript:void(0)" class="btn btn-xs btn-default view-vendor" data-id="<?= $row['firm_id'] ?>">
                                       <i class="fa fa-eye" aria-hidden="true"></i>
                                 </a>
                                 <a href="<?= base_url(); ?>vendor/<?php echo $row['firm_id'] ?>"
                                    class="btn btn-default btn-xs">
                                 <i class="fa fa-pencil"></i>
                                 </a>
                                 <a href="<?= base_url(); ?>delete_vendor/<?php echo $row['firm_id'] ?>"
                                    class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
                                 <i class="fa fa-remove"></i>
                                 </a>
                              </td>
                           </tr>
                           <?php }
                              } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<!-- Modal for Vendor Details -->
<div class="modal fade" id="vendorModal" tabindex="-1" role="dialog" aria-labelledby="vendorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorModalLabel">Vendor Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><td style="width: 25%; text-align: left;">Vendor Name</td><td style="width:75%" id="modal_firm_name"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">Vendor Type</td><td style="width:75%" id="modal_firm_type"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">Focus Code</td><td style="width:75%" id="modal_focus_code"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">GST</td><td style="width:75%" id="modal_gst"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">Address</td><td style="width:75%" id="modal_address"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">Country</td><td style="width:75%" id="modal_country_name"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">State</td><td style="width:75%" id="modal_state_name"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">City</td><td style="width:75%" id="modal_city_name"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">Pin Code</td><td style="width:75%" id="modal_pin_code"></td></tr>
                    <tr><td style="width: 25%; text-align: left;">Status</td><td style="width:75%" id="modal_status_label"></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
   $(document).ready(function () {
     var country_id = "<?= !empty($vendor['country_id']) ? addslashes($vendor['country_id']) : '' ?>";
     var state_id = "<?= !empty($vendor['state_id']) ? addslashes($vendor['state_id']) : '' ?>";
     $("#vendorTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
             {
                 extend: 'csv',
                 text: '<i class="fa fa-file-text-o"></i> Export',
                 title: 'Vendor_List_' + new Date().toISOString().slice(0, 10),
                 className: 'btn btn-primary btn-sm',
                 exportOptions: {
                     columns: ':not(:last-child)'
                 }
             }
         ]
     });
     getCountry(country_id, state_id);
     $('#country_id').on('change', function () {
         let countryId = $(this).val();
         if (countryId) {
             getState(countryId, '');
         } else {
             $('#state_id').html('<option value="">-- Select State --</option>');
         }
     });
     $(document).on('click', '.view-vendor' , function () {
        var firmId = $(this).data('id');
        $.ajax({
            url: '<?= base_url("get_vendor_details/") ?>' + firmId,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                // Populate modal fields
                $('#modal_firm_name').text(response.firm_name || 'N/A');
                $('#modal_firm_type').text(response.firm_type || 'N/A');
                $('#modal_focus_code').text(response.focus_code || 'N/A');
                $('#modal_gst').text(response.gst || 'N/A');
                $('#modal_address').text(response.address || 'N/A');
                $('#modal_country_name').text(response.country_name || 'N/A');
                $('#modal_state_name').text(response.state_name || 'N/A');
                $('#modal_city_name').text(response.city_name || 'N/A');
                $('#modal_pin_code').text(response.pin_code || 'N/A');
                $('#modal_status_label').text(response.status_label || 'N/A');
                // Show modal
                $('#vendorModal').modal('show');
            },
            error: function () {
                alert('Error loading vendor details.');
            }
        });
    });

   });
   function getCountry(selectedCountryId, selectedStateId) {
     $.ajax({
         url: '<?= base_url("get-country") ?>',
         type: 'GET',
         dataType: 'json',
         success: function (response) {
             let options = '<option value="">-- Select Country --</option>';
             $.each(response, function (index, country) {
                 let selected = (country.api_id == selectedCountryId) ? 'selected' : '';
                 options += `<option value="${country.api_id}" ${selected}>${country.country_name} (${country.country_code})</option>`;
             });
             $('#country_id').html(options);
   
             
             if (selectedCountryId && selectedStateId) {
                 getState(selectedCountryId, selectedStateId);
             }
         },
         error: function () {
             $('#country_id').html('<option value="">Error loading countries</option>');
         }
     });
   }
   
   function getState(countryId, selectedStateId) {
     $.ajax({
         url: '<?= base_url("get-state") ?>',
         type: 'POST',
         data: { 
             country_id: countryId,
             <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>' 
         },
         dataType: 'json',
         success: function (response) {
             let options = '<option value="">-- Select State --</option>';
             $.each(response, function (index, state) {
                 let selected = (state.api_id == selectedStateId) ? 'selected' : '';
                 options += `<option value="${state.api_id}" ${selected}>${state.state_name} (${state.short_code})</option>`;
             });
             $('#state_id').html(options);
         },
         error: function () {
             $('#state_id').html('<option value="">Error loading states</option>');
         }
     });
   }
</script>