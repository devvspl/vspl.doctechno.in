<div class="content-wrapper">
   <style>
      .content-wrapper {
      padding: 20px;
      }
      .info-box {
      background-color: #f4f6f9;
      border-radius: 8px;
      transition: box-shadow 0.3s ease;
      }
      .info-box:hover {
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      }
      .info-box-content {
      padding: 15px;
      text-align: center;
      }
      .info-box-text {
      font-size: 1.1rem;
      font-weight: 600;
      margin-top: 5px;
      }
      .text-decoration-none {
      text-decoration: none;
      }
      .text-dark {
      color: #343a40;
      }
      .shadow-sm {
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .info-box-content {
      padding: 5px 10px;
      margin-left: 0;
      }
      .detail-row table {
         background-color: #f2f2f2 !important;
      }
      .detail-row{
         background-color: #f2f2f2;
      }
   </style>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Scan Punch Report:</h3>
               </div>
               <form method="get" action="<?php echo site_url('VSPL_Punch/focus_exports'); ?>" id="searchForm" class="row" style="padding: 10px;">
                  <div class="col-md-3">
                     <label for="doctype">Document Type</label>
                     <select name="doctype" id="doctype" class="form-control select2">
                        <option value="">Select Doc Type</option>
                        <?php foreach ($doctype as $group): ?>
                        <option value="<?php echo $group['type_id']; ?>" <?php echo (isset($_GET['doctype']) && $_GET['doctype'] == $group['type_id']) ? 'selected' : ''; ?>>
                           <?php echo $group['file_type']; ?>
                        </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-3">
                     <label for="from_date">From Date</label>
                     <input type="date" name="from_date" class="form-control" value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>">
                  </div>
                  <div class="col-md-3">
                     <label for="to_date">To Date</label>
                     <input type="date" name="to_date" class="form-control" value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>">
                  </div>
                  <div class="col-md-3 align-self-end">
                     <button type="submit" class="btn btn-primary btn-sm mt-3" style="margin-top: 23px;">Search</button>
                     <a href="<?php echo site_url('VSPL_Punch/export_cash_payment?' . http_build_query($_GET)); ?>" class="btn btn-primary btn-sm mt-3" style="margin-top: 23px;">Export CSV</a>
                  </div>
               </form>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Scan Punch Report</div>
                     <table class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>Doc No</th>
                              <th>Date</th>
                              <th>Cash Bank AC</th>
                              <th>Business Entity</th>
                              <th>Cost Center</th>
                              <th>Location</th>
                              <th>Crop</th>
                              <th>Activity</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($record_list)) : ?>
                           <?php foreach ($record_list as $record) : ?>
                           <tr class="main-row" style="cursor: pointer;">
                              <td><?= $record['DocNo'] ?></td>
                              <td><?= $record['Date'] ?></td>
                              <td><?= $record['CashBankAC'] ?></td>
                              <td><?= $record['BusinessEntity'] ?></td>
                              <td><?= $record['CostCenter'] ?></td>
                              <td><?= $record['Location'] ?></td>
                              <td><?= $record['Crop'] ?></td>
                              <td><?= $record['Activity'] ?></td>
                           </tr>
                           <tr class="detail-row" style="display:none;">
                              <td colspan="8">
                                 <table class="table table-bordered">
                                    <tr>
                                       <th>Narration</th>
                                       <td><?= $record['sNarration'] ?></td>
                                       <th>Favouring</th>
                                       <td><?= $record['Favouring'] ?></td>
                                    </tr>
                                    <tr>
                                       <th>TDS JV No</th>
                                       <td><?= $record['TDSJVNo'] ?></td>
                                       <th>State</th>
                                       <td><?= $record['State'] ?></td>
                                       <th>Category</th>
                                       <td><?= $record['Category'] ?></td>
                                    </tr>
                                    <tr>
                                       
                                       <th>Region</th>
                                       <td><?= $record['Region'] ?></td>
                                       <th>Department</th>
                                       <td><?= $record['Department'] ?></td>
                                       <th>PMT Category</th>
                                       <td><?= $record['PMTCategory'] ?></td>
                                    </tr>
                                    <tr>
                                       
                                    </tr>
                                    <tr>
                                       <th>Business Unit</th>
                                       <td><?= $record['BusinessUnit'] ?></td>
                                       <th>Account</th>
                                       <td><?= $record['Account'] ?></td>
                                       <th>Total Amount</th>
                                       <td><?= $record['TotalAmount'] ?></td>
                                    </tr>
                                    <tr>
                                      
                                       <th>Reference</th>
                                       <td><?= $record['Reference'] ?></td>
                                       <th>Remarks</th>
                                       <td><?= $record['sRemarks'] ?></td>
                                       <th>TDS</th>
                                       <td><?= $record['TDS'] ?></td>
                                    </tr>
                                    <tr>
                                      
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                           <?php else : ?>
                           <tr>
                              <td colspan="8" class="text-center">No records found</td>
                           </tr>
                           <?php endif; ?>
                        </tbody>
                     </table>
                     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                     <script>
                        $(document).ready(function() {
                            $('.main-row').on('click', function() {
                                var detailRow = $(this).next('.detail-row');
                                detailRow.toggle(); 
                                
                                
                                if (detailRow.is(':visible')) {
                                    $(this).css('background-color', '#f2f2f2'); 
                                } else {
                                    $(this).css('background-color', ''); 
                                }
                                
                                
                                $('.main-row').not(this).css('background-color', ''); 
                                $('.detail-row:visible').not(detailRow).hide(); 
                                $('#doctype').select2();

                            });
                        });
                     </script>
                     <div class="pagination" style="display: flex;justify-content: center;gap: 10px;">
                        <?php echo $pagination; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>