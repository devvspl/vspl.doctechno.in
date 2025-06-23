<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Extraction Queue List</h3>
                  <div class="box-tools pull-right">
                     <button id="processQueueBtn" style="margin-bottom: 5px;" type="button" class="btn btn-primary"
                        onclick="processQueue()">
                     <i class="fa fa-play"></i> Process Queue
                     </button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <?php if ($this->session->flashdata('message')): ?>
                     <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message'); ?>
                     </div>
                     <?php endif; ?>
                     <table id="queueTable" class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th class="text-center">S No.</th>
                              <th class="text-center">Document Name</th>
                              <th class="text-center">Document Type</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Created At</th>
                              <th class="text-center">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($queues)): ?>
                           <?php $i = 1;
                              foreach ($queues as $queue): ?>
                           <tr>
                              <td class="text-center"><?= $i++ ?></td>
                              <td class="text-center"><?= htmlspecialchars($queue->document_name); ?></td>
                              <td class="text-center"><?= htmlspecialchars($queue->file_type); ?></td>
                              <td class="text-center">
                                 <span class="label label-<?php
                                    echo $queue->status == 'pending' ? 'warning' :
                                       ($queue->status == 'processing' ? 'info' :
                                          ($queue->status == 'completed' ? 'success' : 'danger'));
                                    ?>">
                                 <?php echo ucfirst($queue->status); ?>
                                 </span>
                              </td>
                              <td class="text-center"><?= date('Y-m-d H:i:s', strtotime($queue->created_at)); ?></td>
                              <td class="text-center">
                                 <button class="btn btn-primary btn-sm run-queue" data-queue-id="<?= $queue->id; ?>"
                                    title="Run Queue">
                                 <i class="fa fa-play"></i>
                                 </button>
                                 <button class="btn btn-danger btn-sm remove-queue" data-queue-id="<?= $queue->id; ?>"
                                    title="Remove">
                                 <i class="fa fa-trash"></i>
                                 </button>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                           <?php else: ?>
                           <tr>
                              <td colspan="6" class="text-center">No records found in queue</td>
                           </tr>
                           <?php endif; ?>
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
   function processQueue() {
      var $btn = $('#processQueueBtn');
   
      $.ajax({
         url: '<?php echo base_url("cron-process-queue"); ?>',
         type: 'POST',
         dataType: 'json',
         beforeSend: function () {
   
            $btn.prop('disabled', true).html('Please wait...');
         },
         success: function (response) {
            if (response.status === 'success') {
               alert(response.message);
               location.reload();
            } else {
               alert('Error: ' + response.message);
            }
         },
         error: function () {
            alert('Error processing queue');
         },
         complete: function () {
            $btn.prop('disabled', false).html('Process Queue');
         }
      });
   }
   $(document).ready(function () {
      $("#queueTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Queue_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
      $(document).on('click', '.remove-queue', function () {
         if (confirm('Are you sure you want to remove this item from the queue?')) {
            var queueId = $(this).data('queue-id');
            $.ajax({
               url: '<?php echo base_url("extract/ExtractorController/removeFromQueue"); ?>',
               type: 'POST',
               data: { queue_id: queueId },
               dataType: 'json',
               success: function (response) {
                  if (response.status === 'success') {
                     alert(response.message);
                     location.reload();
                  } else {
                     alert('Error: ' + response.message);
                  }
               },
               error: function () {
                  alert('Error removing from queue');
               }
            });
         }
      });
      $(document).on('click', '.run-queue', function (e) {
         e.preventDefault();
   
         if (confirm('Are you sure you want to run this queue?')) {
            var queueId = $(this).data("queue-id");
            var $btn = $(this);
   
            $.ajax({
               url: '<?= base_url("process-queue"); ?>',
               type: 'POST',
               dataType: 'json',
               data: { queue_id: queueId },
               beforeSend: function () {
                  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
               },
               success: function (response) {
                  alert(response.message);
                  location.reload();
               },
               error: function () {
                  alert('Error processing queue');
               },
               complete: function () {
                  $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Run Queue');
               }
            });
         }
      });
   
   
   });
</script>