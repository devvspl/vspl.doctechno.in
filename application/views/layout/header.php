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
                  <?php
                  $user_id = $this->session->userdata('user_id'); // Get logged-in user ID
                  $this->load->helper('menu');
                  $menu = get_menu($user_id);
                  ?>

                  <section class="sidebar" id="sibe-box">
                     <ul class="sidebar-menu">
                        <?= $menu ?>
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
                                       <h5>User Role</h5>
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