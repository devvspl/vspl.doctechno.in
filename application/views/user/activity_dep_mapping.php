<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-solid1 box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-lock"></i> Department Activity Mapping</h3>
               </div>
               <div class="box-body">
                  <?php if ($this->session->flashdata('msg')): ?>
                     <div class="alert alert-success"><?= $this->session->flashdata('msg'); ?></div>
                  <?php endif; ?>
                  <div class="table-responsive">
                     <table id="mappingTable" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th style="text-align:left">S No.</th>
                              <th style="text-align:left;width:200px">Activity</th>
                              <?php foreach ($departments as $dept): ?>
                                 <th style="text-align:center; cursor:pointer"
                                    title="<?= htmlspecialchars($dept['department_name']) ?>">
                                    <?= htmlspecialchars($dept['department_code']) ?>
                                 </th>
                              <?php endforeach; ?>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sno = 1; ?>
                           <?php foreach ($activities as $act): ?>
                              <tr>
                                 <td><?= $sno++; ?></td>
                                 <td><?= $act['activity_name']; ?></td>
                                 <?php foreach ($departments as $dept): ?>
                                    <?php
                                    $is_mapped = false;
                                    foreach ($mappings as $map) {
                                       if ($map['department_id'] == $dept['api_id'] && $map['activity_id'] == $act['api_id']) {
                                          $is_mapped = true;
                                          break;
                                       }
                                    }
                                    ?>
                                    <td style="text-align:center">
                                       <input type="checkbox" class="mapping-checkbox"
                                          data-department-id="<?= $dept['api_id']; ?>"
                                          data-activity-id="<?= $act['api_id']; ?>" <?= $is_mapped ? 'checked' : ''; ?>>
                                    </td>
                                 <?php endforeach; ?>
                              </tr>
                           <?php endforeach; ?>
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
      $('#mappingTable').DataTable({
         "ordering": false,
         "columnDefs": [
            { "orderable": false, "targets": 1 }
         ],
         dom: 'Bfrtip',
         pageLength: 10,
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Department_Activity_Mapping_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  orthogonal: 'display'
               },
               customize: function (csv) {
                  var table = $('#mappingTable').DataTable();
                  var newCsv = '';
                  var headerRow = [];
                  table.columns().every(function () {
                     headerRow.push($(this.header()).text().trim());
                  });
                  newCsv += headerRow.join(',') + '\n';
                  table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                     var $row = $(table.row(rowIdx).node());
                     var rowData = [];
                     rowData.push($row.find('td').eq(0).text().trim());
                     rowData.push($row.find('td').eq(1).text().trim());
                     $row.find('input.mapping-checkbox').each(function (idx) {
                        rowData.push($(this).is(':checked') ? 'Yes' : 'No');
                     });
                     newCsv += rowData.join(',') + '\n';
                  });
                  return newCsv;
               }
            }
         ]
      });
   });
</script>