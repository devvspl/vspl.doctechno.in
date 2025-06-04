<div class="content-wrapper py-4">
   <section class="content">
      <div class="row">
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
               <div class="inner">
                  <h3><?= $total_count ?></h3>
                  <p>Total Pending Punch</p>
               </div>
               <div class="icon">
                  <i class="fa fa-file"></i>
               </div>
               <a href="<?php echo base_url('punch')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
               <div class="inner">
                  <h3><?= $user_count ?></h3>
                  <p>Total Punched</p>
               </div>
               <div class="icon">
                  <i class="fa fa-check-circle"></i>
               </div>
               <a href="<?php echo base_url('punch/my-punched-file/all')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
      </div>
   </section>
</div>