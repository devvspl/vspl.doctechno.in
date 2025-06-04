<div class="content-wrapper py-4">
   <section class="content">
      <div class="row">
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
               <div class="inner">
                  <h3><?= $total_scans ?></h3>
                  <p>Total Scanned Files</p>
               </div>
               <div class="icon">
                  <i class="fa fa-file"></i>
               </div>
               <a href="<?php echo base_url('temp_scan?status=all')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
               <div class="inner">
                  <h3><?= $final_submitted ?></h3>
                  <p>Final Submitted</p>
               </div>
               <div class="icon">
                  <i class="fa fa-check-circle"></i>
               </div>
               <a href="<?php echo base_url('temp_scan?status=submitted')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
               <div class="inner">
                  <h3><?= $pending_submission ?></h3>
                  <p>Pending Submission</p>
               </div>
               <div class="icon">
                  <i class="fa fa-hourglass-half"></i>
               </div>
               <a href="<?php echo base_url('temp_scan?status=pending')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-light-blue">
               <div class="inner">
                  <h3><?= $rejected_scans ?></h3>
                  <p>Rejected Scans</p>
               </div>
               <div class="icon">
                  <i class="fa fa-times-circle"></i>
               </div>
               <a href="<?php echo base_url('temp_scan?status=rejected')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
               <div class="inner">
                  <h3><?= $deleted_scans ?></h3>
                  <p>Deleted Scans</p>
               </div>
               <div class="icon">
                  <i class="fa fa-trash-o"></i>
               </div>
               <a href="<?php echo base_url('temp_scan?status=deleted')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
      </div>
   </section>
</div>