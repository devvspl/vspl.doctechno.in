<?php $this->load->library('Customlib'); ?>

<body class="hold-transition skin-blue fixed sidebar-mini">
   <script>
      function collapseSidebar() {
         if (Boolean(sessionStorage.getItem("sidebar-toggle-collapsed"))) {
            sessionStorage.setItem("sidebar-toggle-collapsed", "");
         } else {
            sessionStorage.setItem("sidebar-toggle-collapsed", "1");
         }
      }

      function checksidebar() {
         if (Boolean(sessionStorage.getItem("sidebar-toggle-collapsed"))) {
            var body = document.getElementsByTagName("body")[0];
            body.className = body.className + " sidebar-collapse";
         }
      }

      checksidebar();
   </script>
   <div class="wrapper">
      <header class="main-header" id="alert">
         <a href="#" class="logo" style="background-color: #ffff;">
            <span class="logo-mini">
               <img style="width: 27px;" src="<?= base_url(); ?>assets/images/logo_small.png" alt="SnapDoc" />
            </span>
            <span class="logo-lg">
               <img style="width: 85px;" src="<?= base_url(); ?>assets/images/scan-ocr-logo.png" alt="SnapDoc" />
            </span>
         </a>
         <nav class="navbar navbar-static-top" role="navigation">
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
               <aside class="main-sidebar" id="alert2"
                  style="position:unset;width:auto;padding-top:0px;background-color: transparent;box-shadow: 0 0 0;height:50px;">
                  <section class="sidebar" id="sibe-box" style="height:50px;">
                     <ul class="sidebar-menu">
                        <li class="treeview <?= set_Topmenu('dashboard'); ?>">
                           <a href="<?= base_url(); ?>"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a>
                        </li>
                        <?php if ($_SESSION['role'] == 'super_admin') { ?>
                           <li class="treeview <?= set_Topmenu('master'); ?>">
                              <a href="#"> <i class="fa fa-home ftlayer"></i> <span>Master</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <!-- <li class="<?php echo set_Submenu('account'); ?>">
                                    <a href="<?= base_url(); ?>account"><i class="fa fa-angle-double-right"></i>Account</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('activity'); ?>">
                                    <a href="<?= base_url(); ?>activity"><i
                                          class="fa fa-angle-double-right"></i>Activity</a>
                                 </li> -->
                                 <li class="<?php echo set_Submenu('account'); ?>">
                                    <a href="<?= base_url(); ?>account"><i class="fa fa-angle-double-right"></i>Account</a>
                                 </li>

                                 <li class="<?php echo set_Submenu('bill_approver'); ?>">
                                    <a href="<?= base_url(); ?>bill_approver"><i class="fa fa-angle-double-right"></i>Bill
                                       Approver</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('business_entity'); ?>">
                                    <a href="<?= base_url(); ?>business_entity"><i
                                          class="fa fa-angle-double-right"></i>Business Entity</a>
                                 </li>
                                 <!--  <li class="<?php echo set_Submenu('business_unit'); ?>">
                                    <a href="<?= base_url(); ?>business_unit"><i
                                          class="fa fa-angle-double-right"></i>Business Unit</a>
                                 </li> -->
                                 <li class="<?php echo set_Submenu('firm'); ?>">
                                    <a href="<?= base_url(); ?>firm"><i class="fa fa-angle-double-right"></i>Company /
                                       Vendor</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('location'); ?>"><a href="<?= base_url(); ?>location"><i
                                          class="fa fa-angle-double-right"></i>Location</a></li>
                                 <li class="<?php echo set_Submenu('core-apis'); ?>">
                                    <a href="<?= base_url(); ?>core-apis"><i class="fa fa-angle-double-right"></i>Core
                                       API's</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('employee'); ?>">
                                    <a href="<?= base_url(); ?>employee"><i
                                          class="fa fa-angle-double-right"></i>Employee</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('file'); ?>">
                                    <a href="<?= base_url(); ?>file"><i class="fa fa-angle-double-right"></i>File</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('group'); ?>">
                                    <a href="<?= base_url(); ?>group"><i class="fa fa-angle-double-right"></i>Group</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('hotel'); ?>">
                                    <a href="<?= base_url(); ?>hotel"><i class="fa fa-angle-double-right"></i>Hotel</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('item'); ?>">
                                    <a href="<?= base_url(); ?>item"><i class="fa fa-angle-double-right"></i>Item</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('ledger'); ?>">
                                    <a href="<?= base_url(); ?>ledger"><i class="fa fa-angle-double-right"></i>Ledger</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('rj_reason'); ?>">
                                    <a href="<?= base_url(); ?>rejection_reason"><i
                                          class="fa fa-angle-double-right"></i>Rejection List</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('unit'); ?>">
                                    <a href="<?= base_url(); ?>unit"><i class="fa fa-angle-double-right"></i>Unit</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('user'); ?>">
                                    <a href="<?= base_url(); ?>user"><i class="fa fa-angle-double-right"></i>User</a>
                                 </li>
                              </ul>
                           </li>

                           <!-- <li class="treeview <?= set_Topmenu('reports'); ?>">
                              <a href="#"> <i class="fa fa-file"></i> <span>Reports</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="treeview <?= set_Topmenu('report'); ?>">
                                    <a href="<?= base_url(); ?>all_report"> <i class="fa fa-angle-double-right"></i>
                                       <span>Report</span> </a>
                                 </li>
                                 <li class="treeview <?= set_Topmenu('focus_exports'); ?>">
                                    <a href="<?= base_url(); ?>focus_exports"> <i class="fa fa-angle-double-right"></i>
                                       <span>Focus Exports</span> </a>
                                 </li>
                                 <li class="treeview <?= set_Topmenu('reject_list'); ?>">
                                    <a href="<?= base_url(); ?>reject_list"> <i class="fa fa-angle-double-right"></i>
                                       <span>Rejected Record</span> </a>
                                 </li>
                                 <li class="treeview <?= set_Topmenu('bill_approval_report'); ?>">
                                    <a href="<?= base_url(); ?>bill_approval_report"> <i
                                          class="fa fa-angle-double-right"></i> <span>Bill Approval Report</span> </a>
                                 </li>
                                 <li class="treeview <?= set_Topmenu('ledger_wise_report'); ?>">
                                    <a href="<?= base_url(); ?>ledger_wise_report"> <i
                                          class="fa fa-angle-double-right"></i> <span>Ledger Wise Report</span> </a>
                                 </li>
                                 <?php if ($_SESSION['role'] == 'super_scan' || $_SESSION['role'] == 'super_admin') { ?>
                                    <li class="treeview <?= set_Topmenu('verification'); ?>">
                                       <a href="<?= base_url(); ?>verification"> <i class="fa fa-angle-double-right"></i>
                                          <span>Verification Report</span> </a>
                                    </li>
                                 <?php } ?>
                              </ul>
                           </li> -->
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'super_admin' || $_SESSION['role'] == 'scan_admin') { ?>
                           <li class="treeview <?= set_Topmenu('search_master'); ?>">
                              <a href="#"> <i class="fa fa-search"></i> <span>Documents</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('classification'); ?>">
                                    <a href="<?= base_url(); ?>classification"><i
                                          class="fa fa-angle-double-right"></i>Classification</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('processed'); ?>">
                                    <a href="<?= base_url(); ?>processed"><i
                                          class="fa fa-angle-double-right"></i>Processed</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('extraction-queue'); ?>">
                                    <a href="<?= base_url(); ?>extraction-queue"><i
                                          class="fa fa-angle-double-right"></i>Extraction Queue</a>
                                 </li>
                                 <?php
                                 if ($_SESSION['role'] == 'super_admin') {
                                    ?>
                                    <li class="<?php echo set_Submenu('change-request'); ?>">
                                       <a href="<?= base_url(); ?>change-request"><i
                                             class="fa fa-angle-double-right"></i>Change Request</a>
                                    </li>
                                    <?php
                                 }
                                 ?>

                              </ul>
                           </li>
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                           <li class="treeview <?= set_Topmenu('master'); ?>">
                              <a href="#"> <i class="fa fa-home ftlayer"></i> <span>Master</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('user'); ?>">
                                    <a href="<?= base_url(); ?>user"><i class="fa fa-angle-double-right"></i>Users</a>
                                 </li>
                              </ul>
                           </li>
                           <li class="treeview <?= set_Topmenu('report'); ?>">
                              <a href="<?= base_url(); ?>report"> <i class="fa fa-file"></i> <span>Report</span> </a>
                           <?php } ?>
                           <?php if ($this->customlib->has_permission('Scan')) { ?>
                           </li>
                           <li class="treeview <?= set_Topmenu('scan_master'); ?>">
                              <a href="#"> <i class="fa fa-barcode"></i> <span>Scan Document</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('scan'); ?>">
                                    <a href="<?= base_url(); ?>scan"><i class="fa fa-angle-double-right"></i>New Scan</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('myscannedfiles'); ?>">
                                    <a href="<?= base_url(); ?>myscannedfiles"><i class="fa fa-angle-double-right"></i>My
                                       Scanned Files</a>
                                 </li>

                                 <li class="<?php echo set_Submenu('scan'); ?>">
                                    <a href="<?= base_url(); ?>bill_trashed"><i
                                          class="fa fa-angle-double-right"></i>Trashed Bills</a>
                                 </li>
                              </ul>
                           </li>
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'bill_approver') { ?>
                           <li class="treeview <?= set_Topmenu('pending_bill_approve'); ?>">
                              <a href="<?php echo base_url('pending_bill_approve') ?>"> <i class="fa fa-barcode"></i>
                                 <span>Pending Bills</span></a>
                           </li>
                           <li class="treeview <?= set_Topmenu('reject_bill_approve'); ?>">
                              <a href="<?php echo base_url('reject_bill_approve') ?>"> <i class="fa fa-barcode"></i>
                                 <span>Rejected Bills</span></a>
                           </li>
                           <li class="treeview <?= set_Topmenu('approved_bill_approve'); ?>">
                              <a href="<?php echo base_url('approved_bill_approve') ?>"> <i class="fa fa-barcode"></i>
                                 <span>Approved Bills</span></a>
                           </li>
                        <?php } ?>
                        <?php if ($this->customlib->has_permission('Temporary Scan')) { ?>

                           <li class="treeview <?= set_Topmenu('scan_master'); ?>">
                              <a href="#"> <i class="fa fa-barcode"></i> <span>Scan Document</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('scan'); ?>">
                                    <a href="<?= base_url(); ?>temp_scan"><i class="fa fa-angle-double-right"></i>New
                                       Scan</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('scan'); ?>">
                                    <a href="<?= base_url(); ?>rejected_temp_scan"><i
                                          class="fa fa-angle-double-right"></i>Rejected
                                       Scan</a>
                                 </li>

                              </ul>
                           </li>
                        <?php } ?>
                        <?php if ($this->customlib->has_permission('Punch')) { ?>
                           <li class="treeview <?= set_Topmenu('punch_master'); ?>">
                              <a href="#"> <i class="fa fa-pencil-square-o"></i> <span>Punch Document</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('punch'); ?>">
                                    <a href="<?= base_url(); ?>punch"><i class="fa fa-angle-double-right"></i>Punch
                                       File</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('my_punched_file'); ?>">
                                    <a href="<?= base_url(); ?>punch/my-punched-file/all"><i
                                          class="fa fa-angle-double-right"></i>My Punched Files</a>
                                 </li>
                              </ul>
                           </li>
                        <?php } ?>
                        <?php if ($this->customlib->has_permission('Finance')) { ?>
                           <li class="treeview <?= set_Topmenu('punch_master'); ?>">
                              <a href="#"> <i class="fa fa-pencil-square-o"></i> <span>Punch Document</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('punch'); ?>">
                                    <a href="<?= base_url(); ?>finance_punch"><i class="fa fa-angle-double-right"></i>Punch
                                       File</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('my_punched_file'); ?>">
                                    <a href="<?= base_url(); ?>finance/my-punched-file/all"><i
                                          class="fa fa-angle-double-right"></i>My Punched Files</a>
                                 </li>
                              </ul>
                           </li>
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'vspl_bill_approver') { ?>
                           <li class="treeview <?= set_Topmenu('punch_master'); ?>">
                              <a href="#"> <i class="fa fa-pencil-square-o"></i> <span>Bill Approval</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('finance/bill-approval/pending'); ?>">
                                    <a href="<?= base_url(); ?>finance/bill-approval/N"><i
                                          class="fa fa-angle-double-right"></i>Pending</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('finance/bill-approval/approved'); ?>">
                                    <a href="<?= base_url(); ?>finance/bill-approval/Y"><i
                                          class="fa fa-angle-double-right"></i>Approved</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('finance/bill-approval/approved'); ?>">
                                    <a href="<?= base_url(); ?>finance/bill-approval/R"><i
                                          class="fa fa-angle-double-right"></i>Rejected</a>
                                 </li>
                                
                              </ul>
                           </li>
                            <li class="treeview">
                                    <a href="<?php echo base_url('focus_exports')?>"> <i class="fa fa-cogs" aria-hidden="true"></i>
                                       <span>Focus Export</span> </a>
                                 </li>
                        <?php } ?>

                        <?php if ($this->customlib->has_permission('Approve')) { ?>
                           <li class="treeview <?= set_Topmenu('approve_master'); ?>">
                              <a href="#"> <i class="fa fa-ils"></i> <span>Approve Document</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('approve'); ?>">
                                    <a href="<?= base_url(); ?>approve"><i class="fa fa-angle-double-right"></i>Approve
                                       File</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('my_approved_file'); ?>">
                                    <a href="<?= base_url(); ?>my_approved_file"><i class="fa fa-angle-double-right"></i>My
                                       Approved Files</a>
                                 </li>
                              </ul>
                           </li>
                        <?php } ?>
                        <?php if ($this->customlib->has_permission('Search Report') || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'super_admin') { ?>
                           <!-- <li class="treeview <?= set_Topmenu('search_master'); ?>">
                              <a href="#"> <i class="fa fa-search"></i> <span>Search Record</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('search_with_filter'); ?>">
                                    <a href="<?= base_url(); ?>search_with_filter"><i
                                          class="fa fa-angle-double-right"></i>Search with Filter</a>
                                 </li>
                                 <li class="<?php echo set_Submenu('search_punch_records'); ?>">
                                    <a href="<?= base_url(); ?>search_with_filter_status"><i
                                          class="fa fa-angle-double-right"></i>Search Punch Records</a>
                                 </li>
                              </ul>
                           </li> -->
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                           <li class="treeview <?= set_Topmenu('verification'); ?>">
                              <a href="<?= base_url(); ?>bill_trashed"> <i class="fa fa-trash"></i> <span>Trashed
                                    Bills</span> </a>
                           </li>
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'super_scan') { ?>
                           <li class="treeview <?= set_Topmenu('verification'); ?>">
                              <a href="<?= base_url(); ?>bill_trashed"> <i class="fa fa-trash"></i> <span>Trashed
                                    Bills</span> </a>
                           </li>
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'super_scan' || $_SESSION['role'] == 'super_admin') { ?>
                           <li class="treeview <?= set_Topmenu('verification'); ?>">
                              <a href="<?= base_url(); ?>all_trashed_bill"> <i class="fa fa-trash"></i> <span>All Trashed
                                    Bills</span> </a>
                           </li>
                           <li class="treeview <?= set_Topmenu('verification'); ?>">
                              <a href="<?= base_url(); ?>temp-files"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                 <span>Temp Files</span> </a>
                           </li>
                        <?php } ?>
                        <?php if ($_SESSION['role'] == 'super_scan' || $_SESSION['role'] == 'super_admin') { ?>
                           <li class="treeview <?= set_Topmenu('search_master'); ?>">
                              <a href="#">
                                 <i class="fa fa-search"></i> <span>Request</span>
                                 <i class="fa fa-angle-left pull-right"></i>
                              </a>
                              <ul class="treeview-menu">
                                 <li class="<?php echo set_Submenu('vendor-request'); ?>">
                                    <a href="<?= base_url(); ?>vendor-request">
                                       <i class="fa fa-angle-double-right"></i>Vendor Request
                                    </a>
                                 </li>

                              <?php } ?>

                           </ul>
                        </li>
                     </ul>
                  </section>
               </aside>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
               <div class="pull-right">
                  <div class="navbar-custom-menu">
                     <ul class="nav navbar-nav headertopmenu">
                        <li class="dropdown user-menu">
                           <a class="dropdown-toggle" style="padding: 15px 13px;" data-toggle="dropdown" href="#"
                              aria-expanded="false">
                              <img src="<?= base_url(); ?>assets/images/default_male.jpg" class="topuser-image"
                                 alt="User Image" />
                           </a>
                           <ul class="dropdown-menu dropdown-user menuboxshadow">
                              <li>
                                 <div class="sstopuser">
                                    <div class="sstopuser-test">
                                       <h4 class="text-capitalize"><?= $_SESSION['name']; ?></h4>
                                       <h5><?= $_SESSION['role']; ?></h5>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="sspass">
                                       <a href="#" data-toggle="tooltip" title="" data-original-title="My Profile"><i
                                             class="fa fa-user"></i>Profile </a>
                                       <a class="pl25" href="<?= base_url(); ?>changepass" data-toggle="tooltip"
                                          title="" data-original-title="Change Password"><i
                                             class="fa fa-key"></i>Password</a>
                                       <a class="pull-right" href="<?= base_url() ?>logout"><i
                                             class="fa fa-sign-out fa-fw"></i>Logout</a>
                                    </div>
                                 </div>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </nav>
      </header>
      <script src="<?= base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
   </div>
</body>
<script>
   $(document).ready(function () {
      $("body").click(function () {
         $(".treeview").removeClass("active");
         $(".treeview-menu")
            .removeClass("menu-open")
            .css("display", "none");
      });

      $(".treeview-menu").click(function (e) {
         e.stopPropagation();
      });
   });
</script>