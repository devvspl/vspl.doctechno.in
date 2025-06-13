<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-solid1 box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">
                     <i class="fa fa-lock"></i> Set Permission - <?= $user['first_name'] . ' ' . $user['last_name']; ?>
                  </h3>
                  <div class="box-tools pull-right">
                     <a href="<?= base_url(); ?>user" class="btn btn-primary btn-sm">
                     <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  </div>
               </div>
               <div class="box-body">
                  <?php if ($this->session->flashdata('message')): ?>
                  <div class="alert alert-info">
                     <?= $this->session->flashdata('message'); ?>
                  </div>
                  <?php endif; ?>
                  <ul class="nav nav-tabs" role="tablist">
                     <li class="active"><a href="#permission" role="tab" data-toggle="tab">Permission</a></li>
                     <li><a href="#document" role="tab" data-toggle="tab">Document</a></li>
                     <li><a href="#department" role="tab" data-toggle="tab">Department</a></li>
                     <li><a href="#location" role="tab" data-toggle="tab">Location</a></li>
                  </ul>
                  <div class="tab-content" style="margin-top: 20px;display:block">
                     <div class="tab-pane active" id="permission">
                        <form id="permissionForm">
                           <div class="form-group">
                              <input type="text" class="form-control" style="width: 300px;" id="permissionSearch" placeholder="Search Permissions">
                           </div>
                           <div id="permission_list" class="row"></div>
                           <button type="button" class="btn btn-primary" onclick="savePermissions()">Save Permissions</button>
                        </form>
                     </div>
                     <div class="tab-pane" id="document">
                        <form id="documentForm">
                           <div class="form-group">
                              <input type="text" class="form-control" style="width: 300px;" id="documentSearch" placeholder="Search Documents">
                           </div>
                           <div id="document_list" class="row"></div>
                           <button type="button" class="btn btn-primary" onclick="savePermissions()">Save Permissions</button>
                        </form>
                     </div>
                     <div class="tab-pane" id="department">
                        <form id="departmentForm">
                           <div class="form-group">
                              <input type="text" class="form-control" style="width: 300px;" id="departmentSearch" placeholder="Search Departments">
                           </div>
                           <div id="department_list" class="row"></div>
                           <button type="button" class="btn btn-primary" onclick="savePermissions()">Save Permissions</button>
                        </form>
                     </div>
                     <div class="tab-pane" id="location">
                        <form id="locationForm">
                           <div class="form-group">
                              <input type="text" class="form-control" style="width: 300px;" id="locationSearch" placeholder="Search Locations">
                           </div>
                           <div id="location_list" class="row"></div>
                           <button type="button" class="btn btn-primary" onclick="savePermissions()">Save Permissions</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   $(document).ready(function () {
       const userId = <?= $id ?>;
   
       
       let originalPermissions = '';
       let originalDocuments = '';
       let originalDepartments = '';
       let originalLocations = '';
   
       
       $.ajax({
           url: '<?= base_url(); ?>master/UserController/get_permissions_data/' + userId,
           type: 'GET',
           dataType: 'json',
           success: function (response) {
               
               let permissionHtml = '';
               if (response.permissions && response.permissions.length > 0) {
                   response.permissions.forEach(function (perm, index) {
                       let checked = response.user_permissions.some(up =>
                           up.permission_type === 'Permission' && up.permission_value == perm.permission_id
                       ) ? 'checked' : '';
                       permissionHtml += `
                           <div class="col-md-2 col-sm-6 col-xs-12">
                               <div class="checkbox">
                                   <label>
                                       <input type="checkbox" name="permissions[]" value="${perm.permission_id}" ${checked}>
                                       ${perm.permission_name}
                                   </label>
                               </div>
                           </div>`;
                   });
               } else {
                   permissionHtml = '<div class="col-md-12"><p>No permissions available.</p></div>';
               }
               $('#permission_list').html(permissionHtml);
               originalPermissions = permissionHtml;
   
               
               let documentHtml = '';
               if (response.documents && response.documents.length > 0) {
                   response.documents.forEach(function (doc, index) {
                       let checked = response.user_permissions.some(up =>
                           up.permission_type === 'Document' && up.permission_value == doc.type_id
                       ) ? 'checked' : '';
                       documentHtml += `
                           <div class="col-md-2 col-sm-6 col-xs-12">
                               <div class="checkbox">
                                   <label>
                                       <input type="checkbox" name="documents[]" value="${doc.type_id}" ${checked}>
                                       ${doc.file_type}
                                   </label>
                               </div>
                           </div>`;
                   });
               } else {
                   documentHtml = '<div class="col-md-12"><p>No documents available.</p></div>';
               }
               $('#document_list').html(documentHtml);
               originalDocuments = documentHtml;
   
               
               let departmentHtml = '';
               if (response.departments && response.departments.length > 0) {
                   response.departments.forEach(function (dept, index) {
                       let checked = response.user_permissions.some(up =>
                           up.permission_type === 'Department' && up.permission_value == dept.api_id
                       ) ? 'checked' : '';
                       departmentHtml += `
                           <div class="col-md-2 col-sm-6 col-xs-12">
                               <div class="checkbox">
                                   <label>
                                       <input type="checkbox" name="departments[]" value="${dept.api_id}" ${checked}>
                                       ${dept.department_name} (${dept.department_code})
                                   </label>
                               </div>
                           </div>`;
                   });
               } else {
                   departmentHtml = '<div class="col-md-12"><p>No departments available.</p></div>';
               }
               $('#department_list').html(departmentHtml);
               originalDepartments = departmentHtml;
   
               
               let locationHtml = '';
               if (response.locations && response.locations.length > 0) {
                   response.locations.forEach(function (loc, index) {
                       let checked = response.user_permissions.some(up =>
                           up.permission_type === 'Location' && up.permission_value == loc.location_id
                       ) ? 'checked' : '';
                       locationHtml += `
                           <div class="col-md-2 col-sm-6 col-xs-12">
                               <div class="checkbox">
                                   <label>
                                       <input type="checkbox" name="locations[]" value="${loc.location_id}" ${checked}>
                                       ${loc.location_name}
                                   </label>
                               </div>
                           </div>`;
                   });
               } else {
                   locationHtml = '<div class="col-md-12"><p>No locations available.</p></div>';
               }
               $('#location_list').html(locationHtml);
               originalLocations = locationHtml;
   
               
               $('.nav-tabs a').tab('show');
   
               
               $('#permissionSearch').on('input', function () {
                   const searchTerm = $(this).val().toLowerCase();
                   if (searchTerm === '') {
                       $('#permission_list').html(originalPermissions);
                   } else {
                       const filteredHtml = $(originalPermissions).filter(function () {
                           return $(this).text().toLowerCase().includes(searchTerm);
                       });
                       $('#permission_list').html(filteredHtml.length > 0 ? filteredHtml : '<div class="col-md-12"><p>No matching permissions found.</p></div>');
                   }
               });
   
               
               $('#documentSearch').on('input', function () {
                   const searchTerm = $(this).val().toLowerCase();
                   if (searchTerm === '') {
                       $('#document_list').html(originalDocuments);
                   } else {
                       const filteredHtml = $(originalDocuments).filter(function () {
                           return $(this).text().toLowerCase().includes(searchTerm);
                       });
                       $('#document_list').html(filteredHtml.length > 0 ? filteredHtml : '<div class="col-md-12"><p>No matching documents found.</p></div>');
                   }
               });
   
               
               $('#departmentSearch').on('input', function () {
                   const searchTerm = $(this).val().toLowerCase();
                   if (searchTerm === '') {
                       $('#department_list').html(originalDepartments);
                   } else {
                       const filteredHtml = $(originalDepartments).filter(function () {
                           return $(this).text().toLowerCase().includes(searchTerm);
                       });
                       $('#department_list').html(filteredHtml.length > 0 ? filteredHtml : '<div class="col-md-12"><p>No matching departments found.</p></div>');
                   }
               });
   
               
               $('#locationSearch').on('input', function () {
                   const searchTerm = $(this).val().toLowerCase();
                   if (searchTerm === '') {
                       $('#location_list').html(originalLocations);
                   } else {
                       const filteredHtml = $(originalLocations).filter(function () {
                           return $(this).text().toLowerCase().includes(searchTerm);
                       });
                       $('#location_list').html(filteredHtml.length > 0 ? filteredHtml : '<div class="col-md-12"><p>No matching locations found.</p></div>');
                   }
               });
           },
           error: function (xhr, status, error) {
               console.error('Error fetching permissions data:', xhr.responseText);
               alert('Error fetching permissions data. Check console for details.');
           }
       });
   
       
       $('.nav-tabs a').on('shown.bs.tab', function (e) {
           const targetTab = $(e.target).attr('href');
           console.log('Switched to tab:', targetTab);
       });
   });
   function savePermissions() {
       const userId = <?= $id ?>;
       const permissions = $('input[name="permissions[]"]:checked').map(function () { return this.value; }).get();
       const documents = $('input[name="documents[]"]:checked').map(function () { return this.value; }).get();
       const departments = $('input[name="departments[]"]:checked').map(function () { return this.value; }).get();
       const locations = $('input[name="locations[]"]:checked').map(function () { return this.value; }).get();
   
       $.ajax({
           url: '<?= base_url(); ?>master/UserController/save_permissions',
           type: 'POST',
           data: {
               user_id: userId,
               permissions: permissions,
               documents: documents,
               departments: departments,
               locations: locations,
               <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>' 
           },
           dataType: 'json',
           success: function (response) {
               alert(response.message);
           },
           error: function (xhr, status, error) {
               console.error('Error saving permissions:', xhr.responseText);
               alert('Error saving permissions. Check console for details.');
           }
       });
   }
</script>
<style>
   .checkbox {
   margin-bottom: 10px;
   }
   .checkbox label {
   width: 100%;
   overflow: hidden;
   text-overflow: ellipsis;
   white-space: nowrap;
   }
</style>    