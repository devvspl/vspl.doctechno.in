<?php

$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);


?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="text-center">
                            <h3>Under Construction</h3>
                            <p>This section is currently being developed. Please check back later.</p>
                        </div>
                    <!-- <div class="box-header with-border">
                        <h3 class="box-title">Record Detail - <?php echo $this->customlib->getDocType($doc_type_id); ?>
                            <?php
                            $document_name = $this->customlib->getDocumentName($scan_id);
                            if (!empty($document_name) || $document_name != null) {
                                echo " - (" . $document_name . ")";
                            }
                            ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="javascript:void(0);" onclick="window.close();" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <?php
                    $this->load->view('records/view_detail');
                    ?> -->
                </div>
            </div>
        </div>
    </section>
</div>