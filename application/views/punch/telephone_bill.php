<div id="invoice-details" class="tab-content active">
<form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="BillForm" name="BillForm" method="post" accept-charset="utf-8">
    <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
    <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="bill_invoice_date">Bill / Invoice Date:</label>
            <input type="date" name="bill_invoice_date" id="bill_invoice_date" class="form-control"
                   value="<?= (isset($punch_detail->bill_invoice_date)) ? date('Y-m-d', strtotime($punch_detail->bill_invoice_date)) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="invoice_bill_no">Invoice / Bill No:</label>
            <input type="text" name="invoice_bill_no" id="invoice_bill_no" class="form-control"
                   value="<?= (isset($punch_detail->invoice_bill_no)) ? htmlspecialchars($punch_detail->invoice_bill_no) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="biller_name">Biller Name:</label>
            <input type="text" name="biller_name" id="biller_name" class="form-control"
                   value="<?= (isset($punch_detail->biller_name)) ? htmlspecialchars($punch_detail->biller_name) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="telephone_no">Telephone No:</label>
            <input type="text" name="telephone_no" id="telephone_no" class="form-control"
                   value="<?= (isset($punch_detail->telephone_no)) ? htmlspecialchars($punch_detail->telephone_no) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="invoice_period">Invoice Period:</label>
            <input type="text" name="invoice_period" id="invoice_period" class="form-control"
                   value="<?= (isset($punch_detail->invoice_period)) ? htmlspecialchars($punch_detail->invoice_period) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="invoice_taxable_value">Invoice Taxable Value:</label>
            <input type="number" step="0.01" name="invoice_taxable_value" id="invoice_taxable_value" class="form-control"
                   value="<?= (isset($punch_detail->invoice_taxable_value)) ? htmlspecialchars($punch_detail->invoice_taxable_value) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="cgst">CGST:</label>
            <input type="number" step="0.01" name="cgst" id="cgst" class="form-control"
                   value="<?= (isset($punch_detail->cgst)) ? htmlspecialchars($punch_detail->cgst) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="sgst">SGST:</label>
            <input type="number" step="0.01" name="sgst" id="sgst" class="form-control"
                   value="<?= (isset($punch_detail->sgst)) ? htmlspecialchars($punch_detail->sgst) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="igst">IGST:</label>
            <input type="number" step="0.01" name="igst" id="igst" class="form-control"
                   value="<?= (isset($punch_detail->igst)) ? htmlspecialchars($punch_detail->igst) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="total_amount_due">Total Amount Due:</label>
            <input type="number" step="0.01" name="total_amount_due" id="total_amount_due" class="form-control"
                   value="<?= (isset($punch_detail->total_amount_due)) ? htmlspecialchars($punch_detail->total_amount_due) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="total_amount_outstanding">Total Amount Outstanding:</label>
            <input type="number" step="0.01" name="total_amount_outstanding" id="total_amount_outstanding" class="form-control final_amount_column"
                   value="<?= (isset($punch_detail->total_amount_outstanding)) ? htmlspecialchars($punch_detail->total_amount_outstanding) : '' ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="last_payment_date">Last Payment Date:</label>
            <input type="date" name="last_payment_date" id="last_payment_date" class="form-control"
                   value="<?= (isset($punch_detail->last_payment_date)) ? date('Y-m-d', strtotime($punch_detail->last_payment_date)) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="remark_comment">Remark / Comment:</label>
            <textarea name="remark_comment" id="remark_comment" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->remark_comment)) ? htmlspecialchars($punch_detail->remark_comment) : '' ?></textarea>
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-danger">Reset</button>
        <?php if (!empty($user_permission) && $user_permission == 'N'): ?>
            <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
        <?php endif; ?>
        <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')): ?>
            <input type="submit" class="btn btn-info pull-right" name="save_as_draft" value="Save as Draft"></input>
        <?php endif; ?>
    </div>
    <?php if ($this->customlib->haveSupportFile($scan_id) == 1): ?>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <label for="supporting_file">Supporting File:</label>
                <div class="form-group">
                    <?php
                    $support_file = $this->customlib->getSupportFile($scan_id);
                    foreach ($support_file as $row) {
                    ?>
                        <div class="col-md-3">
                            <a href="javascript:void(0);" target="popup"
                               onclick="window.open('<?= htmlspecialchars($row['file_path']) ?>','popup','width=600,height=600');">
                                <?= htmlspecialchars($row['file_name']) ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</form>
</div>
<script> 
 $(document).ready(function () {
   $("#invoice-tab").click(function () {
        $("#additional-info").removeClass("active");
        $("#invoice-details").addClass("active");
        $(".tabs").removeClass("active-tab");
        $(this).addClass("active-tab");
    });

    $("#additional-info-tab").click(function () {
        $("#invoice-details").removeClass("active");
        $("#additional-info").addClass("active");
        $(".tabs").removeClass("active-tab");
        $(this).addClass("active-tab");
    });
});
</script>