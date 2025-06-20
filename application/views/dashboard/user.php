<div class="content-wrapper py-4">
   <section class="content">
      <div class="row">
         <div class="col-lg-2 col-xs-3">
            <div class="small-box bg-aqua" onclick="window.location.href='<?php echo base_url('temp_scan?status=all')?>'" style="cursor: pointer;">
               <div class="inner">
                  <h3><?= $total_scans ?></h3>
                  <p>Total Scanned Files</p>
               </div>
               <div class="icon">
                  <i class="fa fa-file"></i>
               </div>
            </div>
         </div>
         <div class="col-lg-2 col-xs-3">
            <div class="small-box bg-green" onclick="window.location.href='<?php echo base_url('temp_scan?status=submitted')?>'" style="cursor: pointer;">
               <div class="inner">
                  <h3><?= $final_submitted ?></h3>
                  <p>Final Submitted</p>
               </div>
               <div class="icon">
                  <i class="fa fa-check-circle"></i>
               </div>
            </div>
         </div>
         <div class="col-lg-2 col-xs-3">
            <div class="small-box bg-yellow" onclick="window.location.href='<?php echo base_url('temp_scan?status=pending')?>'" style="cursor: pointer;">
               <div class="inner">
                  <h3><?= $pending_submission ?></h3>
                  <p>Pending Submission</p>
               </div>
               <div class="icon">
                  <i class="fa fa-hourglass-half"></i>
               </div>
            </div>
         </div>
         <div class="col-lg-2 col-xs-3">
            <div class="small-box bg-light-blue" onclick="window.location.href='<?php echo base_url('temp_scan?status=rejected')?>'" style="cursor: pointer;">
               <div class="inner">
                  <h3><?= $rejected_scans ?></h3>
                  <p>Rejected Scans</p>
               </div>
               <div class="icon">
                  <i class="fa fa-times-circle"></i>
               </div>
            </div>
         </div>
         <div class="col-lg-2 col-xs-3">
            <div class="small-box bg-red" onclick="window.location.href='<?php echo base_url('temp_scan?status=deleted')?>'" style="cursor: pointer;">
               <div class="inner">
                  <h3><?= $deleted_scans ?></h3>
                  <p>Deleted Scans</p>
               </div>
               <div class="icon">
                  <i class="fa fa-trash-o"></i>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>