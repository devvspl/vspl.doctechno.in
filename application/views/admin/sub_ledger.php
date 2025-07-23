<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box" id="exphead">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix">Sub Ledger List</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <table id="SubLedgerTable" class="table">
                        <thead>
                           <tr>
                              <th style="text-align:center">S No.</th>
                              <th style="text-align:left">Sub Ledger</th>
                              <th style="text-align:center">Focus Code</th>
                              <th style="text-align:left">Ledger Name</th>
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
      if (!$.fn.DataTable.isDataTable('#SubLedgerTable')) {
         $('#SubLedgerTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
               url: '<?= base_url('sub_ledger') ?>',
               type: 'POST',
               data: function (d) {
                  d.search = { value: d.search.value };
                  d.group = '';
               },
               dataSrc: function (json) {
                  console.log('AJAX response:', json);
                  if (!json || !Array.isArray(json.data)) {
                     console.error("Invalid data format from server.");
                     alert('Failed to load table data. Please try again or contact support.');
                     return [];
                  }
                  return json.data;
               },
               error: function (xhr, error, thrown) {
                  console.error('DataTable AJAX error:', error, thrown);
                  alert('Error loading table data: ' + thrown + '. Please refresh the page.');
               }
            },
            columns: [
               { data: 'id', className: 'text-center' },
               { data: 'name', className: 'text-left' },
               {
                  data: 'focus_code',
                  className: 'text-center editable',
                  render: function (data, type, row) {
                     return `<span title="Click to edit focus code">${data ? data : 'N/A'}</span>`;
                  }
               },
               {
                  data: 'ledger_name',
                  className: 'text-left editable',
                  render: function (data, type, row) {
                     return `<span title="Click to edit ledger name">${data ? data : 'N/A'}</span>`;
                  }
               },
            ],
            paging: true,
            searching: true,
            ordering: true,
            drawCallback: function () {
               $('#SubLedgerTable .editable span').tooltip();
            }
         });

         $('#SubLedgerTable .editable span').tooltip();
      } 
      
      $('#SubLedgerTable tbody').on('click', 'td.editable', function () {
         let cell = $(this);
         let columnIndex = cell.index();
         let rowData = $('#SubLedgerTable').DataTable().row(cell.closest('tr')).data();

         // Function to handle cell cleanup
         function cleanupCell(cell, value) {
            cell.removeClass('editing').html(`<span title="Click to edit ${columnIndex === 2 ? 'focus code' : 'ledger name'}">${value ? value : (columnIndex === 2 ? 'N/A' : 'N/A')}</span>`);
            cell.find('span').tooltip();
         }

         if (columnIndex === 2) { // Focus code column
            if (!cell.hasClass('editing')) {
               cell.addClass('editing');
               let currentVal = rowData.focus_code || '';
               cell.html(`<input type="text" class="form-control input-sm focus-code-input" value="${currentVal}" placeholder="Enter focus code" />`);
               let input = cell.find('input');

               input.focus().on('blur', function () {
                  let newVal = $(this).val().trim();
                  if (newVal === '' && currentVal !== '') {
                     // Show message if attempting to save an empty value
                     alert('Focus code cannot be empty. Please enter a value.');
                     cleanupCell(cell, currentVal);
                  } else if (newVal !== currentVal) {
                     // Only update if value changed and not empty
                     if (confirm('Do you want to save the new focus code: "' + newVal + '"?')) {
                        $.post('<?= base_url('update_sub_ledger') ?>', {
                           id: rowData.id,
                           focus_code: newVal
                        }, function (response) {
                           try {
                              let parsedResponse = JSON.parse(response);
                              cleanupCell(cell, newVal);
                              rowData.focus_code = newVal;
                              alert(parsedResponse.status === "success" ?
                                 'Focus code updated successfully!' :
                                 'Failed to update focus code. Please try again.');
                           } catch (e) {
                              console.error('JSON parse error:', e, response);
                              cleanupCell(cell, currentVal);
                              alert('Error processing server response: ' + e.message + '. Changes not saved.');
                           }
                        }).fail(function (xhr, error, thrown) {
                           console.error('Update failed:', error, thrown);
                           cleanupCell(cell, currentVal);
                           alert('Error updating focus code: ' + thrown + '. Changes not saved.');
                        });
                     } else {
                        cleanupCell(cell, currentVal); // Revert if user cancels
                     }
                  } else {
                     cleanupCell(cell, currentVal); // Revert if no change
                  }
               });
            }
         }

         if (columnIndex === 3) { // Ledger name column
            if (!cell.hasClass('editing')) {
               cell.addClass('editing');
               let currentVal = rowData.ledger_name || '';
               cell.html(`
               <input type="hidden" class="ledger-id" value="${rowData.ledger_id || ''}" />
               <input type="text" class="form-control input-sm ledger-name-input" value="${currentVal}" placeholder="Search or select ledger" />
            `);
               let input = cell.find('.ledger-name-input');

               input.autocomplete({
                  source: function (request, response) {
                     $.ajax({
                        url: '<?= base_url('get-ledger') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: { term: request.term },
                        success: function (data) {
                           response(data);
                        },
                        error: function (xhr, error, thrown) {
                           console.error('Autocomplete error:', error, thrown);
                           cleanupCell(cell, currentVal);
                           alert('Error loading ledger options: ' + thrown + '. Please try again.');
                        }
                     });
                  },
                  minLength: 0,
                  select: function (event, ui) {
                     if (ui.item.label.trim() === '') {
                        // Show message if selected value is empty
                        alert('Ledger name cannot be empty. Please select a valid ledger.');
                        cleanupCell(cell, currentVal);
                        return false;
                     }
                     if (confirm('Do you want to save the new ledger: "' + ui.item.label + '"?')) {
                        rowData.ledger_id = ui.item.id;
                        rowData.ledger_name = ui.item.label;
                        cleanupCell(cell, ui.item.label);

                        $.post('<?= base_url('update_sub_ledger') ?>', {
                           id: rowData.id,
                           parent_id: ui.item.value,
                           parent_name: ui.item.label,
                           focus_code: rowData.focus_code || ''
                        }, function (response) {
                           try {
                              let parsedResponse = JSON.parse(response);
                              alert(parsedResponse.status === "success" ?
                                 'Ledger updated successfully!' :
                                 'Failed to update ledger. Please try again.');
                           } catch (e) {
                              console.error('JSON parse error:', e, response);
                              cleanupCell(cell, currentVal);
                              alert('Error processing server response: ' + e.message + '. Changes not saved.');
                           }
                        }).fail(function (xhr, error, thrown) {
                           console.error('Update failed:', error, thrown);
                           cleanupCell(cell, currentVal);
                           alert('Error updating ledger: ' + thrown + '. Changes not saved.');
                        });
                     } else {
                        cleanupCell(cell, currentVal); // Revert if user cancels
                     }
                     return false;
                  }
               }).focus(function () {
                  $(this).autocomplete('search', '');
               }).on('blur', function () {
                  // Delay to allow autocomplete select to process first
                  setTimeout(() => {
                     if (!rowData.ledger_name || rowData.ledger_name.trim() === '') {
                        alert('Ledger name cannot be empty. Please select a valid ledger.');
                        cleanupCell(cell, currentVal);
                     }
                  }, 200);
               });
            }
         }
      });
   });

</script>