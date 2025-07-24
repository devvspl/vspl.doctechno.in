<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Ledger List</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <table id="ledgerTable" class="table">
                        <thead>
                           <tr>
                              <th style="text-align:center">S No.</th>
                              <th style="text-align:left">Ledger</th>
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
      if (!$.fn.DataTable.isDataTable('#ledgerTable')) {
         $('#ledgerTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            pageLength: 10,
            ajax: {
               url: '<?= base_url('ledger') ?>',
               type: 'POST',
               data: function (d) {
                 
                  d.group = '';
               },
               dataSrc: function (json) {
                  console.log('AJAX response:', json);
                  if (!json || typeof json !== 'object' || !Array.isArray(json.data)) {
                     console.error("Invalid data format from server.");
                     alert('Failed to load data.');
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
               { data: 'id', className: 'text-center' },
               { data: 'account_name', className: 'text-left' },
               { data: 'ledger_type', className: 'text-center' },
               { data: 'focus_code', className: 'text-center' }
            ],
            paging: true,
            searching: true
         });
      }
   });
</script>