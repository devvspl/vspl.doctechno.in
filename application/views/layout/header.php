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
         <nav class="navbar navbar-static-top">
            <style>
               .dropdown-menu {
                     width: 200px;
               }

               .dropdown-menu .dropdown-menu {
                  position: absolute;
                  top: 0;
                  left: 100%;
                  
                  margin-top: -1px;
                  
                  display: none;
                  
               }

               .navbar-nav>.dropdown:hover>.dropdown-menu {
                  display: block;
                  
               }

               .dropdown-submenu:hover>.dropdown-menu {
                  display: block;
                  
               }

               .nav .open>a,
               .nav .open>a:focus,
               .nav .open>a:hover {
                  background-color: #f5f5f5;
                  outline: none;
               }

               .navbar-custom-menu {
                  display: inline-block;
                  vertical-align: middle;
                  margin-left: 20px;
                  
               }

               .headertopmenu {
                  display: flex;
                  align-items: center;
                  
                  
               }

               .dropdown-user {
                  min-width: 200px;
                  right: 0;
                  left: auto;
               }

               .caret {
                  float: right;
                  margin-top: 8px;
               }
            </style>
            <div class="collapse navbar-collapse" id="navbar-collapse">
               <ul class="nav navbar-nav">
                  <?php
                  $user_id = $this->session->userdata('user_id');
                  $role_id = $this->session->userdata('role_id');
                  $this->load->helper('menu');
                  $menu = get_menu($user_id, $role_id);
                  ?>
                  <?= $menu ?>
               </ul>
               <div class="navbar-custom-menu">
                  <ul class="nav navbar-nav headertopmenu">
                     <!-- User Menu Dropdown -->
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
                                 <div class="sspass" style="display: flex; gap: 10px; flex-direction: row;justify-content: space-between;">
                                    <a href="#" data-toggle="tooltip" title="My Profile">
                                       <i class="fa fa-user"></i> Profile
                                    </a>
                                    <a href="<?= base_url(); ?>changepass" data-toggle="tooltip"
                                       title="Change Password">
                                       <i class="fa fa-key"></i> Password
                                    </a>
                                    <a class="pull-right" href="<?= base_url(); ?>logout">
                                       <i class="fa fa-sign-out fa-fw"></i> Logout
                                    </a>
                                 </div>
                              </div>
                           </li>
                        </ul>
                     </li>

                     <!-- Back Button -->
                     <li>
                        <a href="javascript:history.back()"
                           style="display: flex; gap: 8px; background-color: #ffffff; align-items: center; color: #1b98ae;"
                           data-toggle="tooltip" title="Go Back">
                           <i class="fa fa-arrow-left"></i> <span>Back</span>
                        </a>
                     </li>
                  </ul>
               </div>

            </div>
         </nav>

         <script>
            $(document).ready(function () {
               // Enable dropdown for nested levels in Bootstrap 3
               $('.dropdown-submenu a.dropdown-toggle').on('click', function (e) {
                  var $this = $(this);
                  if ($this.next().hasClass('dropdown-menu')) {
                     e.preventDefault();
                     e.stopPropagation();
                     $this.parent().toggleClass('open');
                     // Close other open submenus at the same level
                     $this.parent().siblings().removeClass('open');
                  }
               });

               // Ensure top-level dropdown opens on hover
               $('.navbar-nav > .dropdown').hover(
                  function () {
                     $(this).addClass('open');
                  },
                  function () {
                     $(this).removeClass('open');
                  }
               );
            });
         </script>


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