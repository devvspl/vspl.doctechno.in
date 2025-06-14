<div class="content-wrapper" style="min-height: 946px;">
   <style>
      label {
         display: inline-block;
         max-width: 100%;
         margin-bottom: 5px;
         font-weight: 100;
         font-size: 10px;
      }

      .form-group {
         margin-bottom: 0 !important
      }

      .form-control {
         font-size: 10px;
      }

      .small,
      small {
         font-size: 10px;
      }

      td,
      b {
         font-size: 10px !important;
      }

      .select2-container .select2-selection--single {
         box-sizing: border-box;
         cursor: pointer;
         display: block;
         height: 22px !important;
         user-select: none;
         -webkit-user-select: none;
      }

      .select2-container--default .select2-selection--single .select2-selection__rendered {
         color: #444;
         line-height: 17px !important;
         font-size: 10px !important;
      }

      .table>thead>tr>th {
         background: #fff;
         vertical-align: top;
         color: #444;
         font-family: "Roboto-Bold";
         font-size: 10px !important;
      }

      .form-control-sm {
         display: inline-block;
         height: auto;
         font-size: 10px !important;
         line-height: 1.42857143;
         color: #555;
         background-color: #fff;
         background-image: none;
         border: 1px solid #ccc;
      }


      .fixed-section {
         position: fixed;
         z-index: 1000;
         background: #fff;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }


      .placeholder {
         display: none;
      }

      .placeholder.active {
         display: block;
      }


      .main-header>.navbar {
         -webkit-transition: margin-left 0.3s ease-in-out;
         -o-transition: margin-left 0.3s ease-in-out;
         transition: margin-left 0.3s ease-in-out;
         margin-bottom: 0;
         margin-left: 100px;
         border: none;
         min-height: 50px;
         border-radius: 0;
      }

      .fixed .main-header {
         position: fixed;
         top: 0;
         right: 0;
         left: 0;
         box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
         z-index: 1030;
      }

      body.fixed {
         padding-top: 50px;
      }


      .col-md-8 {
         position: relative;
      }

      .tabs-container {
         position: sticky;
         top: 0;
         background: #fff;
         z-index: 1010;

         padding: 10px 0;
         border-bottom: 1px solid #ddd;
      }

      .tabs-container .tabs {
         display: inline-block;
         padding: 5px 15px;
         cursor: pointer;
         border-bottom: 2px solid transparent;
      }

      .tabs-container .tabs.active-tab {
         border-bottom: 2px solid #00c0ef;
         color: rgb(255, 255, 255);
      }
      .scrollable-content {
         max-height: 490px;
         overflow-y: auto;
         padding: 0 0;
      }
      .table>tbody>tr>td,
      .table>tbody>tr>th,
      .table>tfoot>tr>td,
      .table>tfoot>tr>th,
      .table>thead>tr>td,
      .table>thead>tr>th {
         padding: 1px !important;
        
      }
   </style>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">
                     Punch File - <?= $doc_type_name; ?>
                     <?php if (!empty($document_name)): ?>
                        - (<?= $document_name; ?>)
                     <?php endif; ?>
                  </h3>
                  <div class="box-tools pull-right">
                     <?php if (!empty($user_permission) && $user_permission == 'N'): ?>
                        <a href="<?= base_url('punch'); ?>" class="btn btn-primary btn-sm">
                           <i class="fa fa-long-arrow-left"></i> Back
                        </a>
                     <?php endif; ?>
                     <?php if (!empty($user_permission) && $user_permission == 'Y'): ?>
                        <a href="<?= base_url('finance_punch'); ?>" class="btn btn-primary btn-sm">
                           <i class="fa fa-long-arrow-left"></i> Back
                        </a>
                     <?php endif; ?>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">

                     <div class="col-md-4 section-wrapper">
                        <div class="col-md-12 section-content">
                           <?php if ($rec->file_extension == 'pdf'): ?>
                              <object data="<?= $rec->file_path ?>" type="" height="490px" width="100%;"></object>
                           <?php else: ?>
                              <input type="hidden" name="image" id="image" value="<?= $rec->file_path ?>">
                              <div id="imageViewerContainer" style="width: 400px; height:490px;"></div>
                              <script>
                                 var curect_file_path = $('#image').val();
                                 $("#imageViewerContainer").verySimpleImageViewer({
                                    imageSource: curect_file_path,
                                    frame: ['100%', '100%'],
                                    maxZoom: '900%',
                                    zoomFactor: '10%',
                                    mouse: true,
                                    keyboard: true,
                                    toolbar: true,
                                    rotateToolbar: true
                                 });
                              </script>
                           <?php endif; ?>
                        </div>
                        <div class="placeholder" style="height: 490px; width: 100%;"></div>
                     </div>

                     <div class="col-md-8">
                        <?php if ($this->session->flashdata('message')): ?>
                           <div class="custom-alert alert-<?= $this->session->flashdata('alert_type') ?>">
                              <?= $this->session->flashdata('message') ?>
                              <span class="custom-alert-close" onclick="this.parentElement.style.display='none';">×</span>
                           </div>
                        <?php endif; ?>

                        <?php if (!empty($user_permission) && $user_permission == 'Y'): ?>
                           <div class="tabs-container">
                              <div class="tabs active-tab" id="invoice-tab">Basic Details</div>
                              <div class="tabs" id="additional-info-tab">Additional Information</div>
                           </div>
                        <?php endif; ?>

                        <div class="scrollable-content">
                           <?php if (!empty($doc_config['view'])): ?>
                              <?php $this->load->view('punch/' . $doc_config['view']); ?>
                           <?php endif; ?>
                           <?php if (!empty($user_permission) && $user_permission == 'Y'): ?>
                              <?php $this->load->view('punch/additional', $main_record); ?>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <script>
      $(document).ready(function () {
         // Sticky Header Logic
         function toggleFixedHeader() {
            if ($(window).scrollTop() > 0) {
               $('body').addClass('fixed');
            } else {
               $('body').removeClass('fixed');
            }
         }

         // Fixed Section Logic for col-md-4
         var $wrapper = $('.section-wrapper');
         var $section = $wrapper.find('.section-content');
         var $placeholder = $wrapper.find('.placeholder');
         var sectionOffsetTop;
         var sectionWidth;
         var headerHeight;

         function updateSectionState() {
            headerHeight = $('.main-header').outerHeight() || 50;
            sectionOffsetTop = $wrapper.offset().top;
            sectionWidth = $wrapper.width();
            var scrollTop = $(window).scrollTop();

            var fixedTop = headerHeight;

            if (scrollTop >= sectionOffsetTop - headerHeight) {
               $section.addClass('fixed-section');
               $section.css({
                  'width': sectionWidth,
                  'top': fixedTop + 'px'
               });
               $placeholder.addClass('active');
            } else {
               $section.removeClass('fixed-section');
               $section.css({
                  'width': '',
                  'top': ''
               });
               $placeholder.removeClass('active');
            }
         }

         // Run on page load and after content is fully loaded
         $(window).on('load', function () {
            toggleFixedHeader();
            updateSectionState();
         });

         // Run immediately in case 'load' event has already fired
         toggleFixedHeader();
         updateSectionState();

         // Run on scroll
         $(window).on('scroll', function () {
            toggleFixedHeader();
            updateSectionState();
         });

         // Handle window resize to update width
         $(window).on('resize', function () {
            sectionWidth = $wrapper.width();
            if ($section.hasClass('fixed-section')) {
               $section.css('width', sectionWidth);
            }
         });

         // Tab Switching Logic (if needed)
         $('.tabs').on('click', function () {
            $('.tabs').removeClass('active-tab');
            $(this).addClass('active-tab');
            // Add logic to show/hide content based on tab if needed
         });
      });
   </script>
</div>