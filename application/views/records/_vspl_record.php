<?php

$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);


?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Record Detail - <?php echo $this->customlib->getDocType($doc_type_id); ?>
                            <?php
                            $document_name = $this->customlib->getDocumentName($scan_id);
                            if (!empty($document_name)) {
                                echo " - (" . $document_name . ")";
                            }
                            ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="javascript:void(0);" onclick="window.close();" class="btn btn-primary btn-sm">
                                <i class="fa fa-long-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <!-- Under Construction -->
                        <div class="alert alert-warning">
                            <strong>Note:</strong> This section is currently under development.
                        </div>

                        <?php
                        // To be loaded once developed
                        // $this->load->view('records/vspl_view_detail');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>