<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Account List</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Account List</div>
                     <table id="accountTable" class="table">
                        <thead>
                           <tr>
                              <th style="text-align:left">Account Id</th>
                              <th style="text-align:left">Account</th>
                              <th style="text-align:center">Ledger Type</th>
                              <th style="text-align:center">Focus Code</th>

                           </tr>
                        </thead>
                        <tbody>
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
   $(document).ready(function () {
      if (!$.fn.DataTable.isDataTable('#accountTable')) {
         $('#accountTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
               url: '<?= base_url('account') ?>',
               type: 'POST',
               data: function (d) {
                  d.length = 10;
                  d.search = { value: d.search.value };
                  d.group = '';
               },
               dataSrc: function (json) {
                  console.log('AJAX response:', json);
                  if (!json || !Array.isArray(json.data)) {
                     console.error("Invalid data format from server.");
                     return [];
                  }
                  return json.data;
               },
               error: function (xhr, error, thrown) {
                  console.error('DataTable AJAX error:', error, thrown);
                  alert('Error loading data: ' + thrown);
               }
            },
            columns: [
               { data: 'id', className: 'text-left' },
               { data: 'account_name', className: 'text-left' },
               { data: 'ledger_type', className: 'text-center' },
               { data: 'focus_code', className: 'text-center' }
            ],
            paging: true,
            searching: true,
            ordering: true,
            dom: 'Bfrtip',
            buttons: [
               {
                  extend: 'csv',
                  text: '<i class="fa fa-file-text-o"></i> Export',
                  title: 'Account_List_' + new Date().toISOString().slice(0, 10),
                  className: 'btn btn-primary btn-sm',
                  exportOptions: {
                     columns: ':not(:last-child)'
                  }
               }
            ]
         });
      }
   });
</script>