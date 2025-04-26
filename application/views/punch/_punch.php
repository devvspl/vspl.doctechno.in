<style>
   .form-group {
   margin-bottom: 4px;
   }
   th {
   text-align: center;
   }
   .form-control-sm {
   display: inline-block;
   height: auto;
   font-size: 10pt;
   line-height: 1.42857143;
   color: #555;
   background-color: #fff;
   background-image: none;
   border: 1px solid #ccc;
   }
   .tabs-container{margin-bottom: 10px;}
   .d-none {
   display: none !important;
   }
   .tab-content {
   display: none;
   }
   .active {
   display: block; 
   }
   #rows_container .form-row:nth-child(odd) {
   background-color: #f0f0f0; 
   }
   #rows_container .form-row:nth-child(even) {
   background-color: #d0d0d0; 
   }
   .tabs {
   cursor: pointer;
   padding: 10px;
   display: inline-block;
   background-color: #425458a6;
   border: 1px solid #ccc;
   color: #fff;
   }
   .tabs.active-tab {
   background-color: #3a495e; 
   }
   .ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
    padding: 5px;
}
.ui-widget.ui-widget-content li{
	margin-bottom: 5px;
}
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Punch File - <?= $doc_type_name; ?>
                            <?php if (!empty($document_name)) : ?>
                                - (<?= $document_name; ?>)
                            <?php endif; ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url('punch'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-long-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <?php if (!empty($doc_config['view'])): ?>
                        <?php $this->load->view('punch/' . $doc_config['view']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
