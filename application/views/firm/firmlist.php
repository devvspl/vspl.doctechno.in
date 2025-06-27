<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Add Company-Vendor-Farmer</h3>
               </div>
               <form id="form1" action="<?= base_url(); ?>master/FirmController/create" id="firm_form" name="firm_form"
                  method="post" accept-charset="utf-8">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                        <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Firm Name</label>
                        <input autofocus="" id="firm_name" name="firm_name" placeholder="" type="text"
                           class="form-control" value="<?php echo set_value('firm_name'); ?>" />
                        <span class="text-danger"><?php echo form_error('firm_name'); ?></span>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="exampleInputEmail1">Firm Type</label>
                           <select name="firm_type" id="firm_type" class="form-control">
                              <option value="">Select</option>
                              <option value="Company">Company</option>
                              <option value="Vendor">Vendor</option>
                              <option value="Farmer">Farmer</option>
                           </select>
                           <span class="text-danger"><?php echo form_error('firm_type'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="exampleInputEmail1">Firm Code</label>
                           <input autofocus="" id="firm_code" name="firm_code" placeholder="" type="text"
                              class="form-control" value="<?php echo set_value('firm_code'); ?>" />
                           <span class="text-danger"><?php echo form_error('firm_code'); ?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">GST</label>
                        <input autofocus="" id="gst" name="gst" placeholder="" type="text" class="form-control"
                           value="<?php echo set_value('gst'); ?>" />
                        <span class="text-danger"><?php echo form_error('gst'); ?></span>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Address</label>
                        <input autofocus="" id="address" name="address" placeholder="" type="text" class="form-control"
                           value="<?php echo set_value('address'); ?>" />
                        <span class="text-danger"><?php echo form_error('address'); ?></span>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="exampleInputEmail1">Country</label>
                           <select name="country_id" id="country_id" class="form-control">
                           </select>
                           <span class="text-danger"><?php echo form_error('country_id'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="exampleInputEmail1">State</label>
                           <select name="state_id" id="state_id" class="form-control">
                           </select>
                           <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="exampleInputEmail1">City</label>
                           <input autofocus="" id="city_name" name="city_name" placeholder="" type="text"
                              class="form-control" value="<?php echo set_value('city_name'); ?>" />
                           <span class="text-danger"><?php echo form_error('city_name'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="exampleInputEmail1">Pin Code</label>
                           <input autofocus="" id="pin_code" name="pin_code" placeholder="" type="text"
                              class="form-control" value="<?php echo set_value('pin_code'); ?>" />
                           <span class="text-danger"><?php echo form_error('pin_code'); ?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Status</label>
                        <select name="status" id="status" class="form-control">
                           <option value="A">Active</option>
                           <option value="D">Deactive</option>
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
            <div class="box box-primary" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Firm List</h3>
                  <div class="box-tools pull-right">
                     <div class="btn-group pull-right">
                      
                     </div>
                  </div>
               </div>
               <div class="box-body  ">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Firm List</div>
                     <table id="firmTable" class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th>Focus Code</th>
                              <th>Type</th>
                              <th>Name</th>
                              <th>Code</th>
                              <th>GST</th>
                              <th>Address</th>
                              <th>Country</th>
                              <th>State</th>
                              <th>City</th>
                              <th>Pin</th>
                              <th>Status</th>
                              <th class="text-right no-print">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (empty($firmlist)) {
                              ?>
                              <?php
                           } else {
                              $count = 1;
                              foreach ($firmlist as $row) {
                                 ?>
                                 <tr>
                                    <td class="mailbox-name">
                                       <?php
                                       echo $row['firm_id'];
                                       ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php
                                       echo $row['focus_id'];
                                       ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php
                                       echo $row['firm_type'];
                                       ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['firm_name'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['firm_code'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['gst'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['address'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['country_name'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['short_code'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['city_name'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo $row['pin_code'] ?>
                                    </td>
                                    <td class="mailbox-name">
                                       <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                    </td>
                                    <td class="mailbox-date pull-right no-print">
                                       <a href="<?= base_url(); ?>master/FirmController/edit/<?php echo $row['firm_id'] ?>"
                                          class="btn btn-default btn-xs">
                                          <i class="fa fa-pencil"></i>
                                       </a>
                                       <a href="<?= base_url(); ?>master/FirmController/delete/<?php echo $row['firm_id'] ?>"
                                          class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
<div class="modal fade" id="importModal" role="dialog" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title title text-center fees_title"> <a
                  href="<?= base_url() ?>/assets/import_sample/firm.csv" download="">
                  <button class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Sample
                     Import File</button>
               </a>
            </h4>
         </div>
         <form action="<?= base_url() ?>firm_import" id="importform" name="importform" method="post"
            enctype="multipart/form-data">
            <div class="modal-body pb0">
               <div class="form-horizontal balanceformpopup">
                  <div class="box-body">
                     <div class="row">
                        <div class="col-md-12">
                           <label for="file">Select CSV File</label><small class="req"> *</small>
                           <input class="filestyle form-control" type='file' name='file' id="file" accept=".csv" />
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancle</button>
               <input type="submit" class="btn btn-info pull-right" name="importSubmit" value="Import">
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function () {
      getCountry();
      $("#firmTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Location_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
      $('#country_id').on('change', function () {
         let countryId = $(this).val();
         if (countryId) {
            getState(countryId);
         } else {
            $('#state_id').html('<option value="">-- Select State --</option>');
         }
      });
   });
   function getCountry() {
      $.ajax({
         url: '<?= base_url("get-country") ?>',
         type: 'GET',
         dataType: 'json',
         success: function (response) {
            let options = '<option value="">-- Select Country --</option>';
            $.each(response, function (index, country) {
               options += `<option value="${country.api_id}">${country.country_name} (${country.country_code})</option>`;
            });
            $('#country_id').html(options);
         }
      });
   }

   function getState(countryId) {
      $.ajax({
         url: '<?= base_url("get-state") ?>',
         type: 'POST',
         data: { country_id: countryId },
         dataType: 'json',
         success: function (response) {
            let options = '<option value="">-- Select State --</option>';
            $.each(response, function (index, state) {
               options += `<option value="${state.api_id}">${state.state_name} (${state.short_code})</option>`;
            });
            $('#state_id').html(options);
         }
      });
   }
</script>