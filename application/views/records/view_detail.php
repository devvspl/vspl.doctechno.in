<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$fin_year = $this->customlib->getFinancial_year();
$company_list = $this->customlib->getCompanyList();
$rj_list = $this->customlib->getRejectReason();
function isDateNull($date)
{
   if ($date == null || $date == '0000-00-00' || $date == '1970-01-01') {
      return '';
   } else {
      return date('d-m-Y', strtotime($date));
   }
}
?>
<div class="box-body">
   <div class="row">
      <div class="col-md-4">
         <?php if ($rec->file_extension == 'pdf') { ?>
            <object data="<?= $rec->file_path ?>" type="" height="490px" width="100%;"></object>
         <?php } else { ?>
            <input type="hidden" name="image" id="image" value="<?= $rec->file_path ?>">
            <div id="imageViewerContainer"
               style=" width: 450px; height:490px; border:2px solid #1b98ae; border:2px solid #1b98ae;"></div>
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
         <?php } ?>
      </div>
      <div class="col-md-8">
         <?php
         if ($_SESSION['role'] == 'super_approver' || $_SESSION['role'] == 'approver') {
            if ($file_detail->is_file_punched == 'Y' && $file_detail->is_file_approved == 'N' && $file_detail->is_rejected == 'N') {

               ?>
               <div class="row" style="float: right;">
                  <button class="btn btn-sm btn-success"
                     onclick="approveRecord(<?= $file_detail->scan_id; ?>)">Approve</button>
                  <button class="btn btn-sm btn-danger" onclick="rejectRecord(<?= $file_detail->scan_id; ?>)">Reject</button>
               </div>
            <?php }
         } ?>
         <?php if ($doc_type_id == 4) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account Name</b></td>
                  <td>:&emsp;<?= $file_detail->CustomerName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account No.</b></td>
                  <td>:&emsp;<?= $file_detail->BankAccountNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period</b></td>
                  <td>:&emsp;<?= $file_detail->PeriodDuration ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Financial Year</b></td>
                  <td>:&emsp;<?= $file_detail->FinYear ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 5) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Trip Started On</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Trip Ended On</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey From</b></td>
                  <td>:&emsp;<?= $file_detail->From_Location ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Upto</b></td>
                  <td>:&emsp;<?= $file_detail->To_Location ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 8) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Certificate Name</b></td>
                  <td>:&emsp;<?= $file_detail->CertiType ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Certificate No.</b></td>
                  <td>:&emsp;<?= $file_detail->CertiNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Person</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Certificate Date</b></td>
                  <td>:&emsp;<?= $file_detail->File_Date ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 10) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Record Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Auditor Name</b></td>
                  <td>:&emsp;<?= $file_detail->AuditorName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date of Sign</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->DateofSign)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Financial Year</b></td>
                  <td>:&emsp;<?= $file_detail->FinYear ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 11) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Party Name</b></td>
                  <td>:&emsp;<?= $file_detail->PartyName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period</b></td>
                  <td>:&emsp;<?= $file_detail->PeriodDuration ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date of Confirmation</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->DateOfConfirm)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 18) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Document Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Person</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Document No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Valid From</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ValidFrom)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Valid Upto</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->Validto)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 19) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Document Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Type</b></td>
                  <td>:&emsp;<?= $file_detail->CertiType ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Packing List</b></td>
                  <td>:&emsp;<?= $file_detail->PackingList ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>L/c Advance</b></td>
                  <td>:&emsp;<?= $file_detail->LcAdvance ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 30) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Problem / Issue</b></td>
                  <td>:&emsp;<?= $file_detail->ProblemIssue ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Holder</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period</b></td>
                  <td>:&emsp;<?= $file_detail->PeriodDuration ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hospital</b></td>
                  <td>:&emsp;<?= $file_detail->Hospital ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Doctor</b></td>
                  <td>:&emsp;<?= $file_detail->Doctor ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Medicine</b></td>
                  <td>:&emsp;<?= $file_detail->Medicine ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remedy</b></td>
                  <td>:&emsp;<?= $file_detail->Remedy ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Treatment Taken</b></td>
                  <td>:&emsp;<?= $file_detail->TreatmentTaken ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 31) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['company_text']) ? htmlspecialchars($file_detail['punchdata']['company_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['voucher_no']) ? htmlspecialchars($file_detail['punchdata']['voucher_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['voucher_date']) && $file_detail['punchdata']['voucher_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['voucher_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Particular</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['particular']) ? htmlspecialchars($file_detail['punchdata']['particular']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vendor_text']) ? htmlspecialchars($file_detail['punchdata']['vendor_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? number_format($file_detail['punchdata']['amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 32) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Payment Head</b></td>
                  <td>:&emsp;<?= $file_detail->PaymentHead ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date</b></td>
                  <td>:&emsp;<?= $file_detail->File_Date ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Month</b></td>
                  <td>:&emsp;<?= $file_detail->DocMonth ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>TRRN</b></td>
                  <td>:&emsp;<?= $file_detail->TRRN ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>CRN</b></td>
                  <td>:&emsp;<?= $file_detail->CRN ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>:&emsp;<?= $file_detail->TotalAmount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 35) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Purchase Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Seller Name</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Purchaser Name</b></td>
                  <td>:&emsp;<?= $file_detail->ToName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Purchase Value</b></td>
                  <td>:&emsp;<?= $file_detail->TotalAmount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Market Value</b></td>
                  <td>:&emsp;<?= $file_detail->MarketValue ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Additional Payment</b></td>
                  <td>:&emsp;<?= $file_detail->ExtraCharge ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->FileLoc ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Area</b></td>
                  <td>:&emsp;<?= $file_detail->TotalArea ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>KH No.</b></td>
                  <td>:&emsp;<?= $file_detail->KHNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>PH No.</b></td>
                  <td>:&emsp;<?= $file_detail->PHNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Unit</b></td>
                  <td>:&emsp;<?= $file_detail->Unit ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>RNM/ Ward</b></td>
                  <td>:&emsp;<?= $file_detail->RNM_Ward ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>New Rin Pushtika No.</b></td>
                  <td>:&emsp;<?= $file_detail->RinPushtikaNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>New Khara No.</b></td>
                  <td>:&emsp;<?= $file_detail->KhasraNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Stamp Duty</b></td>
                  <td>:&emsp;<?= $file_detail->Stamp_Duty ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Approved Map / Diversion</b></td>
                  <td>:&emsp;<?= $file_detail->Diversion_Paper ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Map Approval Detail</b></td>
                  <td>:&emsp;<?= $file_detail->Map_Approval ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Additional Exposure</b></td>
                  <td>:&emsp;<?= $file_detail->Additional_Exposure ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 36) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Agency Name</b></td>
                  <td>:&emsp;<?= $file_detail->PartyName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Rating</b></td>
                  <td>:&emsp;<?= $file_detail->Rating ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Valid Upto</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->Validto)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 37) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Certificate Name</b></td>
                  <td>:&emsp;<?= $file_detail->CertiType ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Certificate No.</b></td>
                  <td>:&emsp;<?= $file_detail->CertiNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Certificate Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Department</b></td>
                  <td>:&emsp;<?= $file_detail->Department ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Valid From</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ValidFrom)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Valid Upto</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->Validto)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 41) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Institution Name</b></td>
                  <td>:&emsp;<?= $file_detail->PartyName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Form_No Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Certificate Issue Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Valid Upto</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 45) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Vehicle No.</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle Type</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleType ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle Company</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleCompany ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Registered</b></td>
                  <td>:&emsp;<?= $file_detail->Registered ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Registration Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->RegPurDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Clearance Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ClearanceDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Custody Name</b></td>
                  <td>:&emsp;<?= $file_detail->CustomerName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hypothecation</b></td>
                  <td>:&emsp;<?= $file_detail->Hypothecation ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 1) { ?>

            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee/Payee Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_name']) ? htmlspecialchars($file_detail['punchdata']['emp_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Emp Code</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_code']) ? htmlspecialchars($file_detail['punchdata']['emp_code']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_date']) && $file_detail['punchdata']['bill_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['bill_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_no']) ? htmlspecialchars($file_detail['punchdata']['vehicle_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle Type</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_type']) ? htmlspecialchars($file_detail['punchdata']['vehicle_type']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Rs/Km</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['rs_km']) ? number_format($file_detail['punchdata']['rs_km'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td colspan="2">
                     <table class="table borderless text-center">
                        <thead style="background-color: red; color: white;">
                           <tr>
                              <th>Opening Reading</th>
                              <th>Closing Reading</th>
                              <th>Total Km</th>
                              <th>Amount</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if ($doc_type_id == 1 && !empty($file_detail['punchdata_details'])): ?>
                              <?php foreach ($file_detail['punchdata_details'] as $detail): ?>
                                 <tr>
                                    <td><?= isset($detail['opening_km']) ? number_format($detail['opening_km'], 1) : '0.0' ?>
                                    </td>
                                    <td><?= isset($detail['closing_km']) ? number_format($detail['closing_km'], 1) : '0.0' ?>
                                    </td>
                                    <td><?= isset($detail['total_km']) ? number_format($detail['total_km'], 1) : '0.0' ?></td>
                                    <td><?= isset($detail['amount']) ? number_format($detail['amount'], 2) : '0.00' ?></td>
                                 </tr>
                              <?php endforeach; ?>
                           <?php else: ?>
                              <tr>
                                 <td colspan="4" style="text-align: center;">No Record Found</td>
                              </tr>
                           <?php endif; ?>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total KM</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_run_km']) ? number_format($file_detail['punchdata']['total_run_km'], 0) : '0' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total']) ? number_format($file_detail['punchdata']['total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Round Off</b></td>
                  <td>
                     : (<?= isset($file_detail['punchdata']['round_off_type']) && $file_detail['punchdata']['round_off_type'] === 'Minus' ? '-' : '+' ?>)
                     <?= isset($file_detail['punchdata']['round_off_value']) ? number_format($file_detail['punchdata']['round_off_value'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Grand Total</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['grand_total']) ? number_format($file_detail['punchdata']['grand_total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>

         <?php } elseif ($doc_type_id == 2) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Mode</b></td>
                  <td>:&emsp;<?= $file_detail->TravelMode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Air/Bus/Train Name</b></td>
                  <td>:&emsp;<?= $file_detail->FileName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Quota</b></td>
                  <td>:&emsp;<?= $file_detail->TravelQuota ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Class</b></td>
                  <td>:&emsp;<?= $file_detail->TravelClass ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BookingDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey From</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Upto</b></td>
                  <td>:&emsp;<?= $file_detail->ToName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Passenger Detail</b></td>
                  <td>:&emsp;<?= $file_detail->PassengerDetail ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking Status</b></td>
                  <td>:&emsp;<?= $file_detail->BookingStatus ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Travel Insurance</b></td>
                  <td>:&emsp;<?= $file_detail->TravelInsurance ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 3) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Branch</b></td>
                  <td>:&emsp;<?= $file_detail->BankAddress ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Sanction Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Sanction Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Due Date</b></td>
                  <td>
                     :&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Renewal Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->RenewalDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Type of Document</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Paper Submitted</b></td>
                  <td>:&emsp;<?= $file_detail->PaperSubmitted ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 6) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Type</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['type']) ? htmlspecialchars($file_detail['punchdata']['type']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bank_name']) ? htmlspecialchars($file_detail['punchdata']['bank_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Branch</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['branch']) ? htmlspecialchars($file_detail['punchdata']['branch']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['date']) && $file_detail['punchdata']['date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['account_no']) ? htmlspecialchars($file_detail['punchdata']['account_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Beneficiary Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['beneficiary_name']) ? htmlspecialchars($file_detail['punchdata']['beneficiary_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? number_format(floatval($file_detail['punchdata']['amount']), 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 23) { ?>
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%; line-height: 2;">
                  <tr>
                     <td><b>Invoice No.:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['invoice_no']) ? htmlspecialchars($file_detail['punchdata']['invoice_no']) : '-' ?>
                     </td>
                     <td><b>Invoice Date:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['invoice_date']) ? isDateNull($file_detail['punchdata']['invoice_date']) : '-' ?>
                     </td>
                  </tr>
                  <tr>
                     <td><b>Buyer:</b></td>
                     <td colspan="2">
                        <?= isset($file_detail['punchdata']['buyer_name']) ? htmlspecialchars($file_detail['punchdata']['buyer_name']) : '-' ?>
                     </td>
                     <td><b>Buyer Address:</b></td>
                     <td colspan="2">
                        <?= isset($file_detail['punchdata']['buyer_address']) ? htmlspecialchars($file_detail['punchdata']['buyer_address']) : '-' ?>
                     </td>
                  </tr>
                  <tr>
                     <td><b>Vendor:</b></td>
                     <td colspan="2">
                        <?= isset($file_detail['punchdata']['vendor_name']) ? htmlspecialchars($file_detail['punchdata']['vendor_name']) : '-' ?>
                     </td>
                     <td><b>Vendor Address:</b></td>
                     <td colspan="2">
                        <?= isset($file_detail['punchdata']['vendor_address']) ? htmlspecialchars($file_detail['punchdata']['vendor_address']) : '-' ?>
                     </td>
                  </tr>
                  <tr>
                     <td><b>Purchase Order No.:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['buyers_order_no']) ? htmlspecialchars($file_detail['punchdata']['buyers_order_no']) : '-' ?>
                     </td>
                     <td><b>Purchase Order Date:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['buyers_order_date']) ? isDateNull($file_detail['punchdata']['buyers_order_date']) : '-' ?>
                     </td>
                  </tr>
                  <tr>
                     <td><b>Dispatch Through:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['dispatch_through']) ? htmlspecialchars($file_detail['punchdata']['dispatch_through']) : '-' ?>
                     </td>
                     <td><b>Delivery Note Date:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['delivery_note_date']) ? isDateNull($file_detail['punchdata']['delivery_note_date']) : '-' ?>
                     </td>
                  </tr>
                  <tr>
                     <td><b>LR Number:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['lr_number']) ? htmlspecialchars($file_detail['punchdata']['lr_number']) : '-' ?>
                     </td>
                     <td><b>LR Date:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['lr_date']) ? isDateNull($file_detail['punchdata']['lr_date']) : '-' ?>
                     </td>
                     <td><b>Cartoon Number:</b></td>
                     <td>
                        <?= isset($file_detail['punchdata']['cartoon_number']) ? htmlspecialchars($file_detail['punchdata']['cartoon_number']) : '-' ?>
                     </td>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1" style="margin-top:1px;">
                  <thead class="bg-primary">
                     <th>Particular</th>
                     <th>HSN</th>
                     <th>Qty.</th>
                     <th>Unit</th>
                     <th>MRP</th>
                     <th>Discount in MRP</th>
                     <th>Price</th>
                     <th>Amount</th>
                     <th>GST</th>
                     <th>SGST</th>
                     <th>IGST</th>
                     <th>Cess</th>
                     <th>Total Amount</th>
                  </thead>
                  <tbody>
                     <?php if (isset($file_detail['punchdata_details']) && is_array($file_detail['punchdata_details']) && !empty($file_detail['punchdata_details'])) { ?>
                        <?php foreach ($file_detail['punchdata_details'] as $value) { ?>
                           <tr>
                              <td><?= isset($value['particular']) ? htmlspecialchars($value['particular']) : '-' ?></td>
                              <td><?= isset($value['hsn']) ? htmlspecialchars($value['hsn']) : '-' ?></td>
                              <td><?= isset($value['qty']) ? htmlspecialchars($value['qty']) : '-' ?></td>
                              <td><?= isset($value['unit_name']) ? htmlspecialchars($value['unit_name']) : '-' ?></td>
                              <td><?= isset($value['mrp']) ? htmlspecialchars($value['mrp']) : '-' ?></td>
                              <td><?= isset($value['discount_in_mrp']) ? htmlspecialchars($value['discount_in_mrp']) : '-' ?>
                              </td>
                              <td><?= isset($value['price']) ? htmlspecialchars($value['price']) : '-' ?></td>
                              <td><?= isset($value['amount']) ? htmlspecialchars($value['amount']) : '-' ?></td>
                              <td><?= isset($value['gst']) ? htmlspecialchars($value['gst']) : '-' ?></td>
                              <td><?= isset($value['sgst']) ? htmlspecialchars($value['sgst']) : '-' ?></td>
                              <td><?= isset($value['igst']) ? htmlspecialchars($value['igst']) : '-' ?></td>
                              <td><?= isset($value['cess']) ? htmlspecialchars($value['cess']) : '-' ?></td>
                              <td><?= isset($value['total_amount']) ? htmlspecialchars($value['total_amount']) : '-' ?></td>
                           </tr>
                        <?php } ?>
                     <?php } else { ?>
                        <tr>
                           <td colspan="13" style="text-align: center;">No Record Found</td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <table class="table">
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Sub Total:</b></td>
                     <td style="text-align: right;">
                        <b><?= isset($file_detail['punchdata']['sub_total']) ? htmlspecialchars($file_detail['punchdata']['sub_total']) : '-' ?></b>
                     </td>
                  </tr>
                  <?php if (isset($file_detail['punchdata']['tcs_percent']) && $file_detail['punchdata']['tcs_percent'] != '0.00') { ?>
                     <tr>
                        <td colspan="7" style="text-align: right;"><b>TCS:</b></td>
                        <td style="text-align: right;">
                           <b><?= htmlspecialchars($file_detail['punchdata']['tcs_percent']) ?>%</b>
                        </td>
                     </tr>
                  <?php } ?>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Total:</b></td>
                     <td style="text-align: right;">
                        <b><?= isset($file_detail['punchdata']['total']) ? htmlspecialchars($file_detail['punchdata']['total']) : '-' ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Round Off:</b></td>
                     <td style="text-align: right;">
                        <b>
                           ( <?php
                           if (
                              isset($file_detail['punchdata']['grand_total']) &&
                              isset($file_detail['punchdata']['total']) &&
                              $file_detail['punchdata']['grand_total'] < $file_detail['punchdata']['total']
                           ) {
                              echo '-';
                           } else {
                              echo '+';
                           }
                           ?> )
                           <?= isset($file_detail['punchdata']['round_off']) ? htmlspecialchars($file_detail['punchdata']['round_off']) : '-' ?>
                        </b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Grand Total:</b></td>
                     <td style="text-align: right;">
                        <b><?= isset($file_detail['punchdata']['grand_total']) ? htmlspecialchars($file_detail['punchdata']['grand_total']) : '-' ?></b>
                     </td>
                  </tr>
               </table>
               <table>
                  <tr>
                     <td><b>Remarks:</b></td>
                     <td colspan="6" style="text-align: left;">
                        <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '-' ?>
                     </td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($doc_type_id == 54) { ?>
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td><b>Invoice No.:</b></td>
                     <td><?= $file_detail->File_No ?></td>
                     <td><b>Invoice Date:</b></td>
                     <td><?= isDateNull($file_detail->BillDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Mode of Payment:</b></td>
                     <td><?= $file_detail->NatureOfPayment ?></td>
                     <td><b>Suppliers Ref.:</b></td>
                     <td><?= $file_detail->ReferenceNo ?></td>
                  </tr>
                  <tr>
                     <td><b>Vendor:</b></td>
                     <td colspan="2"><?= $file_detail->FromName ?></td>
                     <td><b>Vendor Address:</b></td>
                     <td colspan="2"><?= $file_detail->Loc_Add ?></td>
                  </tr>
                  <tr>
                     <td><b>Buyer:</b></td>
                     <td colspan="2"><?= $file_detail->ToName ?></td>
                     <td><b>Buyer Address:</b></td>
                     <td colspan="2"><?= $file_detail->AgencyAddress ?></td>
                  </tr>
                  <tr>
                     <td><b>Buyer's Order No.:</b></td>
                     <td><?= $file_detail->ServiceNo ?></td>
                     <td><b>Buyer's Order No. Date:</b></td>
                     <td><?= isDateNull($file_detail->BookingDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Dispatch Through:</b></td>
                     <td><?= $file_detail->Particular ?></td>
                     <td><b>Delivery Note Date:</b></td>
                     <td><?= isDateNull($file_detail->DueDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Department:</b></td>
                     <td><?= $file_detail->Department ?></td>
                     <td><b>Ledger:</b></td>
                     <td><?= $file_detail->Ledger ?></td>
                  </tr>
                  <tr>
                     <td><b>Category:</b></td>
                     <td><?= $file_detail->Category ?></td>
                     <td><b>File:</b></td>
                     <td><?= $file_detail->FileName ?></td>
                  </tr>
                  <tr>
                     <td><b>LR Number:</b></td>
                     <td><?= $file_detail->FDRNo ?></td>
                     <td><b>LR Date:</b></td>
                     <td><?= isDateNull($file_detail->File_Date); ?></td>
                  </tr>
                  <tr>
                     <td><b>Cartoon Number:</b></td>
                     <td><?= $file_detail->RegNo ?></td>
                     <td><b>Consignee Name:</b></td>
                     <td><?= $file_detail->AgentName ?></td>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1" style="margin-top:1px;">
                  <thead class="bg-primary">
                     <th>Particular</th>
                     <th>HSN</th>
                     <th>Qty.</th>
                     <th>Unit</th>
                     <th>MRP</th>
                     <th>Discount in MRP</th>
                     <th>Price</th>
                     <th>Amount</th>
                     <th>GST</th>
                     <th>SGST</th>
                     <th>IGST</th>
                     <th>Cess</th>
                     <th>Total Amount</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($doc_type_id == 54) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where scan_id='$scan_id'")->result();
                        foreach ($get_invoice_detail as $key => $value) {
                           ?>
                           <tr>
                              <td><?= $value->Particular ?></td>
                              <td><?= $value->HSN ?></td>
                              <td><?= $value->Qty ?></td>
                              <td><?= $value->unit_name ?></td>
                              <td><?= $value->MRP ?></td>
                              <td><?= $value->Discount ?></td>
                              <td><?= $value->Price ?></td>
                              <td><?= $value->Amount ?></td>
                              <td><?= $value->GST ?></td>
                              <td><?= $value->SGST ?></td>
                              <td><?= $value->IGST ?></td>
                              <td><?= $value->Cess ?></td>
                              <td><?= $value->Total_Amount ?></td>
                           </tr>
                        <?php } ?>
                     <?php } else { ?>
                        <tr>
                           <td colspan="6" style="text-align: center;">No Record Found</td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <table class="table">
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Sub Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->SubTotal; ?></b></td>
                  </tr>
                  <?php if ($file_detail->TCS != '0.00') { ?>
                     <tr>
                        <td colspan="7" style="text-align: right;"><b>TCS:</b></td>
                        <td style="text-align: right;"><b><?= $file_detail->TCS; ?>%</b></td>
                     </tr>
                  <?php } ?>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Total_Amount; ?></b></td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Round Off:</b></td>
                     <td style="text-align: right;"><b>
                           ( <?php
                           if ($file_detail->Grand_Total < $file_detail->Total_Amount) {
                              echo '-';
                           } else {
                              echo '+';
                           }
                           ?>
                           )
                           <?= $file_detail->Total_Discount; ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Grand Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Grand_Total; ?></b></td>
                  </tr>
               </table>
               <table>
                  <tr>
                     <td><b>Remarks :</b></td>
                     <td colspan="6" style="text-align: left;">&nbsp;&nbsp;<?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($doc_type_id == 44) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vendor_name']) ? htmlspecialchars($file_detail['punchdata']['vendor_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing To</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['billing_to']) ? htmlspecialchars($file_detail['punchdata']['billing_to']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_no']) ? htmlspecialchars($file_detail['punchdata']['vehicle_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_no']) ? htmlspecialchars($file_detail['punchdata']['invoice_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_date']) && $file_detail['punchdata']['invoice_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['invoice_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Work Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td colspan="2">
                     <table class="table borderless text-center">
                        <thead style="background-color: red; color: white;">
                           <tr>
                              <th>Particular</th>
                              <th>HSN</th>
                              <th>Qty.</th>
                              <th>Unit</th>
                              <th>MRP</th>
                              <th>Discount in MRP</th>
                              <th>Price</th>
                              <th>Amount</th>
                              <th>GST</th>
                              <th>SGST</th>
                              <th>IGST</th>
                              <th>Total Amount</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if ($doc_type_id == 44 && !empty($file_detail['punchdata_details'])) { ?>
                              <?php foreach ($file_detail['punchdata_details'] as $value) { ?>
                                 <tr>
                                    <td><?= isset($value['particular']) ? htmlspecialchars($value['particular']) : '' ?></td>
                                    <td><?= isset($value['hsn']) ? htmlspecialchars($value['hsn']) : '' ?></td>
                                    <td><?= isset($value['qty']) ? number_format($value['qty'], 0) : '0' ?></td>
                                    <td><?= isset($value['unit_name']) ? htmlspecialchars($value['unit_name']) : '' ?></td>
                                    <td><?= isset($value['mrp']) ? number_format($value['mrp'], 2) : '0.00' ?></td>
                                    <td>
                                       <?= isset($value['discount_in_mrp']) ? number_format($value['discount_in_mrp'], 2) : '0.00' ?>
                                    </td>
                                    <td><?= isset($value['price']) ? number_format($value['price'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['amount']) ? number_format($value['amount'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['gst']) ? number_format($value['gst'], 2) : '0.00' ?>%</td>
                                    <td><?= isset($value['sgst']) ? number_format($value['sgst'], 2) : '0.00' ?>%</td>
                                    <td><?= isset($value['igst']) ? number_format($value['igst'], 2) : '0.00' ?>%</td>
                                    <td><?= isset($value['total_amount']) ? number_format($value['total_amount'], 2) : '0.00' ?>
                                    </td>
                                 </tr>
                              <?php } ?>
                           <?php } else { ?>
                              <tr>
                                 <td colspan="12" style="text-align: center;">No Record Found</td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Sub Total</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['sub_total']) ? number_format($file_detail['punchdata']['sub_total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total']) ? number_format($file_detail['punchdata']['total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Round Off</b></td>
                  <td>
                     : (<?= isset($file_detail['punchdata']['round_off_type']) && $file_detail['punchdata']['round_off_type'] === 'Minus' ? '-' : '+' ?>)
                     <?= isset($file_detail['punchdata']['round_off_value']) ? number_format($file_detail['punchdata']['round_off_value'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Grand Total</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['grand_total']) ? number_format($file_detail['punchdata']['grand_total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 43) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vendor_name_text']) ? htmlspecialchars($file_detail['punchdata']['vendor_name_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing To</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['billing_to_text']) ? htmlspecialchars($file_detail['punchdata']['billing_to_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Dealer Code</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['dealer_code']) ? htmlspecialchars($file_detail['punchdata']['dealer_code']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_no']) ? htmlspecialchars($file_detail['punchdata']['invoice_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_date']) && $file_detail['punchdata']['invoice_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['invoice_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Due Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['due_date']) && $file_detail['punchdata']['due_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['due_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Work Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_no']) ? htmlspecialchars($file_detail['punchdata']['vehicle_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Description</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['description']) ? htmlspecialchars($file_detail['punchdata']['description']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Liters</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['liters']) ? htmlspecialchars($file_detail['punchdata']['liters']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Rate per Liter</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['per_liter_rate']) ? htmlspecialchars($file_detail['punchdata']['per_liter_rate']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? htmlspecialchars($file_detail['punchdata']['amount']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Round Off</b></td>
                  <td>
                     : (<?= isset($file_detail['punchdata']['round_off_type']) && $file_detail['punchdata']['round_off_type'] == 'Minus' ? '-' : '+' ?>)
                     <?= isset($file_detail['punchdata']['round_off_value']) ? htmlspecialchars($file_detail['punchdata']['round_off_value']) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Grand Total</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['grand_total']) ? htmlspecialchars($file_detail['punchdata']['grand_total']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 42) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_invoice_date']) && $file_detail['punchdata']['bill_invoice_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['bill_invoice_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Biller Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['biller_name']) ? htmlspecialchars($file_detail['punchdata']['biller_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_bill_no']) ? htmlspecialchars($file_detail['punchdata']['invoice_bill_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Telephone No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['telephone_no']) ? htmlspecialchars($file_detail['punchdata']['telephone_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Period</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_period']) ? htmlspecialchars($file_detail['punchdata']['invoice_period']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Taxable Value</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_taxable_value']) ? htmlspecialchars($file_detail['punchdata']['invoice_taxable_value']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>CGST</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['cgst']) ? htmlspecialchars($file_detail['punchdata']['cgst']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>SGST</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['sgst']) ? htmlspecialchars($file_detail['punchdata']['sgst']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>IGST</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['igst']) ? htmlspecialchars($file_detail['punchdata']['igst']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_amount_due']) ? htmlspecialchars($file_detail['punchdata']['total_amount_due']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount Outstanding</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_amount_outstanding']) ? htmlspecialchars($file_detail['punchdata']['total_amount_outstanding']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Last Payment Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['last_payment_date']) && $file_detail['punchdata']['last_payment_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['last_payment_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 40) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Application Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Subsidy Received Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Institution Name</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Benifit Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Branch Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankAddress ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>IFSC Code</b></td>
                  <td>:&emsp;<?= $file_detail->BankIfscCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account No.</b></td>
                  <td>:&emsp;<?= $file_detail->BankAccountNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 39) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Branch Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankAddress ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>IFSC Code</b></td>
                  <td>:&emsp;<?= $file_detail->BankIfscCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account Number</b></td>
                  <td>:&emsp;<?= $file_detail->BankAccountNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Beneficiary Name</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 38) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Crop</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Crop Detail</b></td>
                  <td>:&emsp;<?= $file_detail->CropDetails ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Trial Operation Exp Amount</b></td>
                  <td>:&emsp;<?= $file_detail->MealsAmount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Fertilizer Chemicals Amount</b></td>
                  <td>:&emsp;<?= $file_detail->HallTent_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Consumable Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Gift_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Miscellaneous Amount</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 34) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Booking Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Docket No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Provider Name</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Sender Name</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Receiver Name</b></td>
                  <td>:&emsp;<?= $file_detail->ToName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Sender Address</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Add ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Receiver Address</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Weight Charged</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 33) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Mode</b></td>
                  <td>:&emsp;<?= $file_detail->NatureOfPayment ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Due Date</b></td>
                  <td>
                     :&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Cycle</b></td>
                  <td>:&emsp;<?= $file_detail->BillingCycle ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Person</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Address</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Mobile Service</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Mobile No.</b></td>
                  <td>:&emsp;<?= $file_detail->MobileNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Terrif Plan</b></td>
                  <td>:&emsp;<?= $file_detail->TariffPlan ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Previous Balance</b></td>
                  <td>:&emsp;<?= $file_detail->PreviousBalance ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Charges</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Last Payment Detail</b></td>
                  <td>:&emsp;<?= $file_detail->LastPayement ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 29) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hotel Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['hotel_name_text']) ? htmlspecialchars($file_detail['punchdata']['hotel_name_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hotel Address</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['hotel_address']) ? htmlspecialchars($file_detail['punchdata']['hotel_address']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill No</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_no']) ? htmlspecialchars($file_detail['punchdata']['bill_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_date']) && $file_detail['punchdata']['bill_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['bill_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_name']) ? htmlspecialchars($file_detail['punchdata']['emp_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee Code</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_code']) ? htmlspecialchars($file_detail['punchdata']['emp_code']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? number_format($file_detail['punchdata']['amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Detail</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['detail']) ? htmlspecialchars($file_detail['punchdata']['detail']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 28) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill No.</b></td>
                  <td style="width: 30%;">
                     : <?= isset($file_detail['punchdata']['bill_no']) ? htmlspecialchars($file_detail['punchdata']['bill_no']) : '' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_date']) && $file_detail['punchdata']['bill_date'] !== '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($file_detail['punchdata']['bill_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['company_text']) ? htmlspecialchars($file_detail['punchdata']['company_text']) : '' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Billing Address</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['billing_address']) ? htmlspecialchars($file_detail['punchdata']['billing_address']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hotel Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['hotel_name_text']) ? htmlspecialchars($file_detail['punchdata']['hotel_name_text']) : '' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Hotel Address</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['hotel_address']) ? htmlspecialchars($file_detail['punchdata']['hotel_address']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Check In Date/Time</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['check_in']) && $file_detail['punchdata']['check_in'] !== '0000-00-00 00:00:00' ? date('d-m-Y H:i', strtotime($file_detail['punchdata']['check_in'])) : '' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Check Out Date/Time</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['check_out']) && $file_detail['punchdata']['check_out'] !== '0000-00-00 00:00:00' ? date('d-m-Y H:i', strtotime($file_detail['punchdata']['check_out'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Duration of Stay</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['duration_of_stay']) ? htmlspecialchars($file_detail['punchdata']['duration_of_stay']) : '' ?>
                     days</td>
                  <td class="text-dark" style="width: 20%;"><b>Room Type</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['room_type']) ? htmlspecialchars($file_detail['punchdata']['room_type']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Meal Plan</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['meal_plan']) ? htmlspecialchars($file_detail['punchdata']['meal_plan']) : '' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Billing Instruction</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['billing_instruction']) ? htmlspecialchars($file_detail['punchdata']['billing_instruction']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Room Rate</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['rate']) ? number_format($file_detail['punchdata']['rate'], 2) : '0.00' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? number_format($file_detail['punchdata']['amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other Charges</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['other_charges']) ? number_format($file_detail['punchdata']['other_charges'], 2) : '0.00' ?>
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Discount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['discount']) ? number_format($file_detail['punchdata']['discount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>GST</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['gst']) ? number_format($file_detail['punchdata']['gst'], 2) : '0.00' ?>%
                  </td>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['grand_total']) ? number_format($file_detail['punchdata']['grand_total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td colspan="4">
                     <table class="table borderless text-center">
                        <thead style="background-color: red; color: white;">
                           <tr>
                              <th>Employee Name</th>
                              <th>Emp Code</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if ($doc_type_id == 28 && !empty($punchdata_details)) { ?>
                              <?php foreach ($punchdata_details as $value) { ?>
                                 <tr>
                                    <td><?= htmlspecialchars($value->emp_name) ?></td>
                                    <td><?= htmlspecialchars($value->emp_code) ?></td>
                                 </tr>
                              <?php } ?>
                           <?php } else { ?>
                              <tr>
                                 <td colspan="2" style="text-align: center;">No Record Found</td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 27) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Mode</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['mode']) ? htmlspecialchars($file_detail['punchdata']['mode']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_name']) ? htmlspecialchars($file_detail['punchdata']['emp_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee Code</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_code']) ? htmlspecialchars($file_detail['punchdata']['emp_code']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location_text']) ? htmlspecialchars($file_detail['punchdata']['location_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_no']) ? htmlspecialchars($file_detail['punchdata']['vehicle_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Month</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['month']) ? htmlspecialchars($file_detail['punchdata']['month']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Calculation Base</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['calculation_base']) ? htmlspecialchars($file_detail['punchdata']['calculation_base']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <?php if (isset($file_detail['punchdata']['calculation_base']) && strpos($file_detail['punchdata']['calculation_base'], 'Per KM') !== false) { ?>
                     <td class="text-dark" style="width: 20%;"><b>Per KM Rate</b></td>
                     <td>
                        : <?= isset($file_detail['punchdata']['per_km_rate']) ? number_format($file_detail['punchdata']['per_km_rate'], 2) : '0.00' ?>
                     </td>
                  <?php } else { ?>
                     <td class="text-dark" style="width: 20%;"><b>Fixed Amount</b></td>
                     <td>
                        : <?= isset($file_detail['punchdata']['total']) ? number_format($file_detail['punchdata']['total'], 2) : '0.00' ?>
                     </td>
                  <?php } ?>
               </tr>
               <tr>
                  <td colspan="2">
                     <table class="table borderless text-center">
                        <thead style="background-color: red; color: white;">
                           <tr>
                              <th>Date</th>
                              <th>Opening Reading</th>
                              <th>Closing Reading</th>
                              <th>KM Run</th>
                              <th>Amount</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if ($doc_type_id == 27 && !empty($file_detail['punchdata_details'])) { ?>
                              <?php foreach ($file_detail['punchdata_details'] as $detail) { ?>
                                 <tr>
                                    <td>
                                       <?= isset($detail['travel_date']) && $detail['travel_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($detail['travel_date'])) : '' ?>
                                    </td>
                                    <td>
                                       <?= isset($detail['opening_reading']) ? number_format($detail['opening_reading'], 1) : '0.0' ?>
                                    </td>
                                    <td>
                                       <?= isset($detail['closing_reading']) ? number_format($detail['closing_reading'], 1) : '0.0' ?>
                                    </td>
                                    <td><?= isset($detail['total_km']) ? number_format($detail['total_km'], 1) : '0.0' ?></td>
                                    <td><?= isset($detail['amount']) ? number_format($detail['amount'], 2) : '0.00' ?></td>
                                 </tr>
                              <?php } ?>
                           <?php } else { ?>
                              <tr>
                                 <td colspan="5" style="text-align: center;">No Record Found</td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total KM</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_km']) ? number_format($file_detail['punchdata']['total_km'], 0) : '0' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total']) ? number_format($file_detail['punchdata']['total'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remarks</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 22) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Insurance Type</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['insurance_type']) ? htmlspecialchars($file_detail['punchdata']['insurance_type']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Insurance Company</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['insurance_company']) ? htmlspecialchars($file_detail['punchdata']['insurance_company']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Number</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['policy_number']) ? htmlspecialchars($file_detail['punchdata']['policy_number']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['policy_date']) && $file_detail['punchdata']['policy_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['policy_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>From Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['from_date']) && $file_detail['punchdata']['from_date'] !== '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($file_detail['punchdata']['from_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>To Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['to_date']) && $file_detail['punchdata']['to_date'] !== '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($file_detail['punchdata']['to_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_no']) ? htmlspecialchars($file_detail['punchdata']['vehicle_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Premium Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['premium_amount']) ? number_format($file_detail['punchdata']['premium_amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 26) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Lessor Name</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b> Lessee Name</b></td>
                  <td>:&emsp;<?= $file_detail->ToName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Property Address</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Add ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Propery Area</b></td>
                  <td>:&emsp;<?= $file_detail->PropertyArea ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Other Specification</b></td>
                  <td>:&emsp;<?= $file_detail->OtherSpecif ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Lease Start Period</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Lease End Period</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Payment Frequency</b></td>
                  <td>:&emsp;<?= $file_detail->BillingCycle ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Lease Rent Taxable Value</b></td>
                  <td>:&emsp;<?= $file_detail->SubTotal ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>CGST</b></td>
                  <td>:&emsp;<?= $file_detail->CGST_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>SGST</b></td>
                  <td>:&emsp;<?= $file_detail->SGST_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>IGST</b></td>
                  <td>:&emsp;<?= $file_detail->GST_IGST_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 25) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vegetable</b></td>
                  <td>:&emsp;<?= $file_detail->CropDetails ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>HVC</b></td>
                  <td>:&emsp;<?= $file_detail->HiredVehicle_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>AV</b></td>
                  <td>:&emsp;<?= $file_detail->AVTent_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other Charges</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 24) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Financial Year</b></td>
                  <td>:&emsp;<?= $file_detail->Financial_Year ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Filling Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Acknowledge No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 7) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['company_name_text']) ? htmlspecialchars($file_detail['punchdata']['company_name_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['voucher_no']) ? htmlspecialchars($file_detail['punchdata']['voucher_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['voucher_date']) && $file_detail['punchdata']['voucher_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['voucher_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payee</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['payee']) ? htmlspecialchars($file_detail['punchdata']['payee']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payer</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['payer']) ? htmlspecialchars($file_detail['punchdata']['payer']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Particular</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['particular']) ? htmlspecialchars($file_detail['punchdata']['particular']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? number_format($file_detail['punchdata']['amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 16) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan Serial No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan Purpose</b></td>
                  <td>:&emsp;<?= $file_detail->ChallanPurpose ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period of Payment.</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 30%;"><b>Reference Payment No.</b></td>
                  <td>:&emsp;<?= $file_detail->ServiceNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank BSR Code</b></td>
                  <td>:&emsp;<?= $file_detail->BankBSRCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 9) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Branch Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankAddress ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>IFSC Code</b></td>
                  <td>:&emsp;<?= $file_detail->BankIfscCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account No.</b></td>
                  <td>:&emsp;<?= $file_detail->BankAccountNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Cheque No.</b></td>
                  <td>:&emsp;<?= $file_detail->ChequeNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Cheque Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payee Name</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Cheque Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 12) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Crop</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Crop Details</b></td>
                  <td>:&emsp;<?= $file_detail->CropDetails ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Meals</b></td>
                  <td>:&emsp;<?= $file_detail->MealsAmount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hall/Tent</b></td>
                  <td>:&emsp;<?= $file_detail->HallTent_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Gift</b></td>
                  <td>:&emsp;<?= $file_detail->Gift_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>AV</b></td>
                  <td>:&emsp;<?= $file_detail->AVTent_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 21) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Policy Holder Name</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Number</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Nominee</b></td>
                  <td>:&emsp;<?= $file_detail->NomineeDetails ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Sum Assured</b></td>
                  <td>:&emsp;<?= $file_detail->SumAssured ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Premium Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->PremiumDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Maturity Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->MaturityDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Due Date</b></td>
                  <td>
                     :&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Coverage</b></td>
                  <td>:&emsp;<?= $file_detail->Coverage ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No.</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleRegNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Premium Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agent Branch</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Insured Details</b></td>
                  <td>:&emsp;<?= $file_detail->PassengerDetail ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 13) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['payment_date']) && $file_detail['punchdata']['payment_date'] !== '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($file_detail['punchdata']['payment_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Biller Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['biller_name']) ? htmlspecialchars($file_detail['punchdata']['biller_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Business Partner No (BP No.)</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['business_partner_no']) ? htmlspecialchars($file_detail['punchdata']['business_partner_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Period</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_period']) ? htmlspecialchars($file_detail['punchdata']['bill_period']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Meter Number</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['meter_number']) ? htmlspecialchars($file_detail['punchdata']['meter_number']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_date']) && $file_detail['punchdata']['bill_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['bill_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Number</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_no']) ? htmlspecialchars($file_detail['punchdata']['bill_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Previous Meter Reading</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['previous_meter_reading']) ? htmlspecialchars($file_detail['punchdata']['previous_meter_reading']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Current Meter Reading</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['current_meter_reading']) ? htmlspecialchars($file_detail['punchdata']['current_meter_reading']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Unit Consumed</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['unit_consumed']) ? htmlspecialchars($file_detail['punchdata']['unit_consumed']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Last Date of Payment</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['last_date_of_payment']) && $file_detail['punchdata']['last_date_of_payment'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['last_date_of_payment'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Mode</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['payment_mode']) ? htmlspecialchars($file_detail['punchdata']['payment_mode']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bill_amount']) ? number_format($file_detail['punchdata']['bill_amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['payment_amount']) ? number_format($file_detail['punchdata']['payment_amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 14) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Vegetable</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>No.of Farmers(FMS)</b></td>
                  <td>:&emsp;<?= $file_detail->NoOfFarmers ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Dealers/Trade Partner(DTP)</b></td>
                  <td>:&emsp;<?= $file_detail->Dealers_TradePartners ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hired Vehicle (HVC)</b></td>
                  <td>:&emsp;<?= $file_detail->HiredVehicle_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>AV Tent (AVT)</b></td>
                  <td>:&emsp;<?= $file_detail->AVTent_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Snacks (SNK)</b></td>
                  <td>:&emsp;<?= $file_detail->Snacks_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other (OTH)</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 15) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Deposit Account No.</b></td>
                  <td>:&emsp;<?= $file_detail->DepositAccNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Deposit Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Deposit Rate of Interest</b></td>
                  <td>:&emsp;<?= $file_detail->RateOfInterest ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Maturity Amount</b></td>
                  <td>:&emsp;<?= $file_detail->MaturityAmount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Deposit Start Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Deposit End Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Period</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 20) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Section</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['section']) ? htmlspecialchars($file_detail['punchdata']['section']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['company_text']) ? htmlspecialchars($file_detail['punchdata']['company_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Nature of Payment</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['nature_of_payment']) ? htmlspecialchars($file_detail['punchdata']['nature_of_payment']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Assessment Year</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['assessment_year_text']) ? htmlspecialchars($file_detail['punchdata']['assessment_year_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bank_name']) ? htmlspecialchars($file_detail['punchdata']['bank_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>BSR Code</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bsr_code']) ? htmlspecialchars($file_detail['punchdata']['bsr_code']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['challan_no']) ? htmlspecialchars($file_detail['punchdata']['challan_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['challan_date']) && $file_detail['punchdata']['challan_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['challan_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Reference No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bank_reference_no']) ? htmlspecialchars($file_detail['punchdata']['bank_reference_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['amount']) ? number_format($file_detail['punchdata']['amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 17) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agency Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['agency_name_text']) ? htmlspecialchars($file_detail['punchdata']['agency_name_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agency Address</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['agency_address']) ? htmlspecialchars($file_detail['punchdata']['agency_address']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['billing_name_text']) ? htmlspecialchars($file_detail['punchdata']['billing_name_text']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Address</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['billing_address']) ? htmlspecialchars($file_detail['punchdata']['billing_address']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['location']) ? htmlspecialchars($file_detail['punchdata']['location']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['employee_name']) ? htmlspecialchars($file_detail['punchdata']['employee_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Emp Code</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['emp_code']) ? htmlspecialchars($file_detail['punchdata']['emp_code']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['vehicle_no']) ? htmlspecialchars($file_detail['punchdata']['vehicle_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_no']) ? htmlspecialchars($file_detail['punchdata']['invoice_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['invoice_date']) && $file_detail['punchdata']['invoice_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['invoice_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['booking_date']) && $file_detail['punchdata']['booking_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['booking_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>End Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['end_date']) && $file_detail['punchdata']['end_date'] !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail['punchdata']['end_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Per Km Rate</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['per_km_rate']) ? number_format($file_detail['punchdata']['per_km_rate'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Opening Reading</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['start_reading']) ? htmlspecialchars($file_detail['punchdata']['start_reading']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Closing Reading</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['closing_reading']) ? htmlspecialchars($file_detail['punchdata']['closing_reading']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total KM</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_km']) ? htmlspecialchars($file_detail['punchdata']['total_km']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other Charges</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['other_charges']) ? number_format($file_detail['punchdata']['other_charges'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_amount']) ? number_format($file_detail['punchdata']['total_amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 46) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>CPIN</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['cpin']) ? htmlspecialchars($file_detail['punchdata']['cpin']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Deposit Date</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['deposit_date']) && $file_detail['punchdata']['deposit_date'] !== '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($file_detail['punchdata']['deposit_date'])) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>CIN</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['cin']) ? htmlspecialchars($file_detail['punchdata']['cin']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['bank_name']) ? htmlspecialchars($file_detail['punchdata']['bank_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>BRN</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['brn']) ? htmlspecialchars($file_detail['punchdata']['brn']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>GSTIN</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['gstin']) ? htmlspecialchars($file_detail['punchdata']['gstin']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Email ID</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['email_id']) ? htmlspecialchars($file_detail['punchdata']['email_id']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Mobile No.</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['mobile_no']) ? htmlspecialchars($file_detail['punchdata']['mobile_no']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company Name</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['company_name']) ? htmlspecialchars($file_detail['punchdata']['company_name']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company Address</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['address']) ? htmlspecialchars($file_detail['punchdata']['address']) : '' ?>
                  </td>
               </tr>
               <tr>
                  <td colspan="2">
                     <table class="table borderless text-center">
                        <thead style="background-color: red; color: white;">
                           <tr>
                              <th>Particular</th>
                              <th>Tax (₹)</th>
                              <th>Interest (₹)</th>
                              <th>Penalty (₹)</th>
                              <th>Fees (₹)</th>
                              <th>Other (₹)</th>
                              <th>Total (₹)</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if ($doc_type_id == 46 && !empty($file_detail['punchdata_details'])) { ?>
                              <?php foreach ($file_detail['punchdata_details'] as $value) { ?>
                                 <tr>
                                    <td><?= isset($value['particular']) ? htmlspecialchars($value['particular']) : '' ?></td>
                                    <td><?= isset($value['tax']) ? number_format($value['tax'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['interest']) ? number_format($value['interest'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['penalty']) ? number_format($value['penalty'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['fees']) ? number_format($value['fees'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['other']) ? number_format($value['other'], 2) : '0.00' ?></td>
                                    <td><?= isset($value['total']) ? number_format($value['total'], 2) : '0.00' ?></td>
                                 </tr>
                              <?php } ?>
                           <?php } else { ?>
                              <tr>
                                 <td colspan="7" style="text-align: center;">No Record Found</td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Challan Amount</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['total_challan_amount']) ? number_format($file_detail['punchdata']['total_challan_amount'], 2) : '0.00' ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>
                     : <?= isset($file_detail['punchdata']['remark_comment']) ? htmlspecialchars($file_detail['punchdata']['remark_comment']) : '' ?>
                  </td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 47) { ?>
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td><b>Voucher No:</b></td>
                     <td><?= $file_detail->File_No; ?></td>
                     <td><b>Payment Date:</b></td>
                     <td><?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
                  </tr>
                  <tr>
                     <td><b>Payee:</b></td>
                     <td><?= $file_detail->Related_Person ?></td>
                     <td><b>Location:</b></td>
                     <td><?= $file_detail->Loc_Name ?></td>
                  </tr>
                  <tr>
                     <td><b>Particular:</b></td>
                     <td><?= $file_detail->FileName ?></td>
                     <td><b>Total Amount:</b></td>
                     <td><?= $file_detail->Total_Amount; ?></td>
                  </tr>
                  <tr>
                     <td><b>From Date:</b></td>
                     <td><?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
                     <td><b>To Date:</b></td>
                     <td><?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
                  </tr>
                  <tr>
                     <td><b>Ledger:</b></td>
                     <td><?= $file_detail->Ledger ?></td>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1">
                  <thead class="bg-primary">
                     <th>Head</th>
                     <th>Amount(₹)</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($doc_type_id == 47) {
                        $labour_payment_detail = $this->db->query("select * from labour_payment_detail where scan_id='$scan_id'")->result();

                        foreach ($labour_payment_detail as $key => $value) {
                           ?>
                           <tr>
                              <td><?= $value->Head ?></td>
                              <td><?= $value->Amount ?></td>
                           </tr>
                        <?php } ?>
                     <?php } else { ?>
                        <tr>
                           <td colspan="2" style="text-align: center;">No Record Found</td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <table class="table">
                  <tr>
                     <td style="text-align: center;"><b>Sub Total:</b></td>
                     <td style="text-align: center;"><b><?= $file_detail->Total_Amount; ?></b></td>
                  </tr>
                  <tr>
                     <td>Remarks :</td>
                     <td colspan="2"><?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($doc_type_id == 48) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%"><b>Company Name</b></td>
                  <td>: <?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Receiver Name</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Received From</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Particular</b></td>
                  <td>:&emsp;<?= $file_detail->FileName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 49) { ?>
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td><b>Invoice Date:</b></td>
                     <td><?= $file_detail->BillDate; ?></td>
                     <td><b>Invoice No.:</b></td>
                     <td><?= $file_detail->File_No ?></td>
                     <td><b>Company:</b></td>
                     <td><?= $file_detail->Company ?></td>
                  </tr>
                  <tr>
                     <td><b>Department:</b></td>
                     <td><?= $file_detail->Department ?></td>
                     <td><b>From:</b></td>
                     <td><?= $file_detail->FromName ?></td>
                     <td><b>To:</b></td>
                     <td><?= $file_detail->ToName ?></td>
                  </tr>
                  <tr>
                     <td><b>Work Location:</b></td>
                     <td><?= $file_detail->Loc_Name; ?></td>
                     <td><b>Ledger:</b></td>
                     <td><?= $file_detail->Ledger ?></td>
                     <td><b>File:</b></td>
                     <td><?= $file_detail->FileName ?></td>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1" style="margin-top:1px;">
                  <thead class="bg-primary">
                     <th>Particular</th>
                     <th>HSN</th>
                     <th>Qty.</th>
                     <th>MRP</th>
                     <th>Discount in MRP</th>
                     <th>Price</th>
                     <th>Amount</th>
                     <th>GST</th>
                     <th>SGST</th>
                     <th>IGST</th>
                     <th>Cess</th>
                     <th>Total Amount</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($doc_type_id == 49) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where scan_id='$scan_id'")->result();
                        foreach ($get_invoice_detail as $key => $value) {
                           ?>
                           <tr>
                              <td><?= $value->Particular ?></td>
                              <td><?= $value->HSN ?></td>
                              <td><?= $value->Qty ?></td>
                              <td><?= $value->MRP ?></td>
                              <td><?= $value->Discount ?></td>
                              <td><?= $value->Price ?></td>
                              <td><?= $value->Amount ?></td>
                              <td><?= $value->GST ?></td>
                              <td><?= $value->SGST ?></td>
                              <td><?= $value->IGST ?></td>
                              <td><?= $value->Cess ?></td>
                              <td><?= $value->Total_Amount ?></td>
                           </tr>
                        <?php } ?>
                     <?php } else { ?>
                        <tr>
                           <td colspan="6" style="text-align: center;">No Record Found</td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <table class="table">
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Sub Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->SubTotal; ?></b></td>
                  </tr>
                  <?php if ($file_detail->TCS != '0.00') { ?>
                     <tr>
                        <td colspan="7" style="text-align: right;"><b>TCS:</b></td>
                        <td style="text-align: right;"><b><?= $file_detail->TCS; ?>%</b></td>
                     </tr>
                  <?php } ?>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Total_Amount; ?></b></td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Round Off:</b></td>
                     <td style="text-align: right;"><b>
                           ( <?php
                           if ($file_detail->Grand_Total < $file_detail->Total_Amount) {
                              echo '-';
                           } else {
                              echo '+';
                           }
                           ?>
                           )
                           <?= $file_detail->Total_Discount; ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Grand Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Grand_Total; ?></b></td>
                  </tr>
               </table>
               <tr>
                  <td>Remarks :</td>
                  <td><?= $file_detail->Remark ?></td>
               </tr>
            </div>
         <?php } elseif ($doc_type_id == 50) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%"><b>Company Name</b></td>
                  <td>: <?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company Address</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor Name</b></td>
                  <td>:&emsp;<?= $file_detail->ToName; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor Address</b></td>
                  <td>:&emsp;<?= $file_detail->AgencyAddress ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleRegNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle Type</b></td>
                  <td>:&emsp;<?= $file_detail->Vehicle_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Particular</b></td>
                  <td>:&emsp;<?= $file_detail->Particular ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hour</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Trips</b></td>
                  <td>:&emsp;<?= $file_detail->TotalRunKM ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Rate Per Trip</b></td>
                  <td>:&emsp;<?= $file_detail->RateOfInterest ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 51) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%"><b>Mode</b></td>
                  <td>: <?= $file_detail->TravelMode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agent</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>PNR Number</b></td>
                  <td>:&emsp;<?= $file_detail->ServiceNo; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date of Booking</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BookingDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Air Line</b></td>
                  <td>:&emsp;<?= $file_detail->Airline ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Ticket Number</b></td>
                  <td>:&emsp;<?= $file_detail->File_No; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey From</b></td>
                  <td>:&emsp;<?= $file_detail->TripStarted; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Upto</b></td>
                  <td>:&emsp;<?= $file_detail->TripEnded; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Travel Class</b></td>
                  <td>:&emsp;<?= $file_detail->TravelClass; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Passenger Details</b></td>
                  <td>:&emsp;<?= $file_detail->PassengerDetail; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Base Fare</b></td>
                  <td>:&emsp;<?= $file_detail->Base_Fare; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>GST</b></td>
                  <td>:&emsp;<?= $file_detail->GSTIN; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Fees & Surcharge</b></td>
                  <td>:&emsp;<?= $file_detail->Surcharge; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>CUTE Charge</b></td>
                  <td>:&emsp;<?= $file_detail->Cute_Charge; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Extra Luggage</b></td>
                  <td>:&emsp;<?= $file_detail->Extra_Luggage; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
            <table class="table text-center" border="1" style="margin-top:1px;">
               <thead class="bg-primary">
                  <th>Employee Name</th>
                  <th>Emp Code</th>
               </thead>
               <tbody>
                  <?php
                  if ($doc_type_id == 51) {
                     $emp_detail = $this->db->query("select * from lodging_employee  where scan_id='$scan_id'")->result();
                     foreach ($emp_detail as $key => $value) {
                        ?>
                        <tr>
                           <td><?= $value->emp_name ?></td>
                           <td><?= $value->emp_code ?></td>
                        </tr>
                     <?php } ?>
                  <?php } ?>
               </tbody>
            </table>
         <?php } elseif ($doc_type_id == 52) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%"><b>Mode</b></td>
                  <td>: <?= $file_detail->TravelMode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Train Number</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agent</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>PNR Number</b></td>
                  <td>:&emsp;<?= $file_detail->ServiceNo; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date of Booking</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BookingDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking ID</b></td>
                  <td>:&emsp;<?= $file_detail->FDRNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Transaction ID</b></td>
                  <td>:&emsp;<?= $file_detail->RegNo; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey From</b></td>
                  <td>:&emsp;<?= $file_detail->TripStarted; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Journey Upto</b></td>
                  <td>:&emsp;<?= $file_detail->TripEnded; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Travel Class</b></td>
                  <td>:&emsp;<?= $file_detail->TravelClass; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Travel Quota</b></td>
                  <td>:&emsp;<?= $file_detail->TravelQuota; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Passenger Details</b></td>
                  <td>:&emsp;<?= $file_detail->PassengerDetail; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Base Fare</b></td>
                  <td>:&emsp;<?= $file_detail->Base_Fare; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>GST</b></td>
                  <td>:&emsp;<?= $file_detail->GSTIN; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Fees & Surcharge</b></td>
                  <td>:&emsp;<?= $file_detail->Surcharge; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
            <table class="table text-center" border="1" style="margin-top:1px;">
               <thead class="bg-primary">
                  <th>Employee Name</th>
                  <th>Emp Code</th>
               </thead>
               <tbody>
                  <?php
                  if ($doc_type_id == 52) {
                     $emp_detail = $this->db->query("select * from lodging_employee  where scan_id='$scan_id'")->result();
                     foreach ($emp_detail as $key => $value) {
                        ?>
                        <tr>
                           <td><?= $value->emp_name ?></td>
                           <td><?= $value->emp_code ?></td>
                        </tr>
                     <?php } ?>
                  <?php } ?>
               </tbody>
            </table>
         <?php } elseif ($doc_type_id == 53) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%"><b>Mode</b></td>
                  <td>: <?= $file_detail->TravelMode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Number</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agent</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking ID</b></td>
                  <td>:&emsp;<?= $file_detail->FDRNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date of Booking</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BookingDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Ticket Number</b></td>
                  <td>:&emsp;<?= $file_detail->ServiceNo; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bus Type</b></td>
                  <td>:&emsp;<?= $file_detail->TravelClass; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee</b></td>
                  <td>:&emsp;<?= $file_detail->Employee_Name; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Passenger Details</b></td>
                  <td>:&emsp;<?= $file_detail->PassengerDetail; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Base Fare</b></td>
                  <td>:&emsp;<?= $file_detail->Base_Fare; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>GST</b></td>
                  <td>:&emsp;<?= $file_detail->GSTIN; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Fees & Surcharge</b></td>
                  <td>:&emsp;<?= $file_detail->Surcharge; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Other</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($doc_type_id == 55) { ?>
            <table class="table">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agent Name</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BookingDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Cancelled Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
            </table>
            <br>
            <table class="table text-center" border="1" style="margin-top:1px;">
               <thead class="bg-primary">
                  <th>Employee</th>
                  <th>PNR Number</th>
                  <th>Amount</th>
               </thead>
               <tbody>
                  <?php
                  if ($doc_type_id == 55) {
                     $ticket_detail = $this->db->query("select * from ticket_cancellation where scan_id='$scan_id'")->result();
                     foreach ($ticket_detail as $key => $value) {
                        ?>
                        <tr>
                           <td><?= $value->Emp_Name ?></td>
                           <td><?= $value->PNR ?></td>
                           <td><?= $value->Amount ?></td>
                        </tr>
                     <?php } ?>
                  <?php } else { ?>
                     <tr>
                        <td colspan="6" style="text-align: center;">No Record Found</td>
                     </tr>
                  <?php } ?>
               </tbody>
            </table>
            <table class="table">
               <tr>
                  <td colspan="7" style="text-align: right;"><b>Sub Total:</b></td>
                  <td style="text-align: right;"><b><?= $file_detail->SubTotal; ?></b></td>
               </tr>
               <tr>
                  <td colspan="7" style="text-align: right;"><b>Cancellation Charge:</b></td>
                  <td style="text-align: right;"><b><?= $file_detail->Total_Discount; ?></b></td>
               </tr>
               <tr>
                  <td colspan="7" style="text-align: right;"><b>Other Charge:</b></td>
                  <td style="text-align: right;"><b><?= $file_detail->OthCharge_Amount; ?></b></td>
               </tr>
               <tr>
                  <td colspan="7" style="text-align: right;"><b>Grand Total:</b></td>
                  <td style="text-align: right;"><b><?= $file_detail->Grand_Total; ?></b></td>
               </tr>
            </table>
            <tr>
               <td>Remarks :</td>
               <td><?= $file_detail->Remark ?></td>
            </tr>
         <?php } elseif ($doc_type_id == 56) { ?>
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td><b>Credit Note No.:</b></td>
                     <td><?= $file_detail->CreditNo ?></td>
                     <td><b>Credit Note Date:</b></td>
                     <td><?= isDateNull($file_detail->CreditDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Invoice No.:</b></td>
                     <td><?= $file_detail->File_No ?></td>
                     <td><b>Invoice Date:</b></td>
                     <td><?= isDateNull($file_detail->BillDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Mode of Payment:</b></td>
                     <td><?= $file_detail->NatureOfPayment ?></td>
                     <td><b>Suppliers Ref.:</b></td>
                     <td><?= $file_detail->ReferenceNo ?></td>
                  </tr>
                  <tr>
                     <td><b>Buyer:</b></td>
                     <td colspan="2"><?= $file_detail->FromName ?></td>
                     <td><b>Buyer Address:</b></td>
                     <td colspan="2"><?= $file_detail->Loc_Add ?></td>
                  </tr>
                  <tr>
                     <td><b>Vendor:</b></td>
                     <td colspan="2"><?= $file_detail->ToName ?></td>
                     <td><b>Vendor Address:</b></td>
                     <td colspan="2"><?= $file_detail->AgencyAddress ?></td>
                  </tr>
                  <tr>
                     <td><b>Buyer's Order No.:</b></td>
                     <td><?= $file_detail->ServiceNo ?></td>
                     <td><b>Buyer's Order No. Date:</b></td>
                     <td><?= isDateNull($file_detail->BookingDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Dispatch Through:</b></td>
                     <td><?= $file_detail->Particular ?></td>
                     <td><b>Delivery Note Date:</b></td>
                     <td><?= isDateNull($file_detail->DueDate); ?></td>
                  </tr>
                  <tr>
                     <td><b>Department:</b></td>
                     <td><?= $file_detail->Department ?></td>
                     <td><b>Ledger:</b></td>
                     <td><?= $file_detail->Ledger ?></td>
                  </tr>
                  <tr>
                     <td><b>Category:</b></td>
                     <td><?= $file_detail->Category ?></td>
                     <td><b>File:</b></td>
                     <td><?= $file_detail->FileName ?></td>
                  </tr>
                  <tr>
                     <td><b>Location</b></td>
                     <td><?= $file_detail->Loc_Name ?></td>
                  </tr>
                  <tr>
                     <td><b>LR Number:</b></td>
                     <td><?= $file_detail->FDRNo ?></td>
                     <td><b>LR Date:</b></td>
                     <td><?= isDateNull($file_detail->File_Date); ?></td>
                     <td><b>Cartoon Number:</b></td>
                     <td><?= $file_detail->RegNo ?></td>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1" style="margin-top:1px;">
                  <thead class="bg-primary">
                     <th>Particular</th>
                     <th>HSN</th>
                     <th>Qty.</th>
                     <th>Unit</th>
                     <th>MRP</th>
                     <th>Discount in MRP</th>
                     <th>Price</th>
                     <th>Amount</th>
                     <th>GST</th>
                     <th>SGST</th>
                     <th>IGST</th>
                     <th>Cess</th>
                     <th>Total Amount</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($doc_type_id == 56) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where scan_id='$scan_id'")->result();
                        foreach ($get_invoice_detail as $key => $value) {
                           ?>
                           <tr>
                              <td><?= $value->Particular ?></td>
                              <td><?= $value->HSN ?></td>
                              <td><?= $value->Qty ?></td>
                              <td><?= $value->unit_name ?></td>
                              <td><?= $value->MRP ?></td>
                              <td><?= $value->Discount ?></td>
                              <td><?= $value->Price ?></td>
                              <td><?= $value->Amount ?></td>
                              <td><?= $value->GST ?></td>
                              <td><?= $value->SGST ?></td>
                              <td><?= $value->IGST ?></td>
                              <td><?= $value->Cess ?></td>
                              <td><?= $value->Total_Amount ?></td>
                           </tr>
                        <?php } ?>
                     <?php } else { ?>
                        <tr>
                           <td colspan="6" style="text-align: center;">No Record Found</td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <table class="table">
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Sub Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->SubTotal; ?></b></td>
                  </tr>
                  <?php if ($file_detail->TCS != '0.00') { ?>
                     <tr>
                        <td colspan="7" style="text-align: right;"><b>TCS:</b></td>
                        <td style="text-align: right;"><b><?= $file_detail->TCS; ?>%</b></td>
                     </tr>
                  <?php } ?>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Total_Amount; ?></b></td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Round Off:</b></td>
                     <td style="text-align: right;"><b>
                           ( <?php
                           if ($file_detail->Grand_Total < $file_detail->Total_Amount) {
                              echo '-';
                           } else {
                              echo '+';
                           }
                           ?>
                           )
                           <?= $file_detail->Total_Discount; ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Grand Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Grand_Total; ?></b></td>
                  </tr>
               </table>
               <table>
                  <tr>
                     <td><b>Remarks :</b></td>
                     <td colspan="6" style="text-align: left;">&nbsp;&nbsp;<?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php }
         ?>
         <?php if ($this->customlib->haveSupportFile($scan_id) == 1) { ?>
            <div class="row" style="margin-top: 20px;">
               <div class="col-md-12">
                  <label for="">Supporting File:</label>
                  <div class="form-group">
                     <?php $support_file = $this->customlib->getSupportFile($scan_id);
                     foreach ($support_file as $row) { ?>
                        <div class="col-md-3">
                           <a href="javascript:void(0);" target="popup"
                              onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                              <?php echo $row['file_name'] ?></a>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
   </div>
</div>
<div id="rejectModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
   <div class="modal-dialog">
      <div class="modal-content">
         <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
         <div class="scroll-area">
            <div class="modal-body ">
               <div class="form-group">
                  <input type="hidden" name="scan_id" id="scan_id">
                  <label for="Reject_Remark">Rejection Reason :</label> <span class="text-danger">*</span>
                  <select name="Reject_Remark" id="Reject_Remark" class="form-control form-select select2">
                     <option value="">Select</option>
                     <?php foreach ($rj_list as $row) { ?>
                        <option value="<?php echo $row['reason']; ?>"><?php echo $row['reason']; ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
            <div class="box-footer">
               <button type="button" id="reject_btn" class="btn btn-success">Submit</button>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="myModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
   <div class="modal-dialog">
      <div class="modal-content">
         <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
         <div class="scroll-area">
            <div class="modal-body ">
               <div class="form-group">
                  <label for="reason">Rejection Reason :</label> <span class="text-danger">*</span>
                  <input type="text" name="reason" id="reason" class="form-control" required>
               </div>
            </div>
            <div class="box-footer">
               <button type="button" id="save_btn" class="btn btn-success pull-right">Save</button>
            </div>
         </div>
      </div>
   </div>
</div>
<script>

   function approveRecord(scan_id) {
      if (confirm("Are you sure to approve this file")) {
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>approve_record_by_super_approver/' + scan_id,
            async: false,
            dataType: 'json',
            success: function (response) {
               if (response.status == 200) {
                  alert(response.message);
                  setTimeout(() => {

                     location.reload();
                  }, 1000);
               } else {
                  alert(response.message);
               }
            }
         });
      }
   }

   function rejectRecord(scan_id) {


      $("#scan_id").val(scan_id);
      $("#rejectModal").modal("show");
      $("#Reject_Remark").select2({
         dropdownParent: $('#rejectModal'),
         width: '100%',
         allowClear: true,
         escapeMarkup: function (markup) {
            return markup;
         },
         placeholder: "Select Rejection Reason",
         language: {
            noResults: function () {
               return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add New Reason</button>";
            }
         }
      });
   }

   $(document).on('click', "#save_btn", function () {
      var reason = $("#reason").val();
      if (reason == '' || reason == null) {
         $("#reason").focus();
         $("#reason").css('border-color', 'red');
         return false;
      }
      $.ajax({
         type: 'POST',
         url: '<?= base_url() ?>master/RejectionReasonController/save_reason',
         data: {
            reason: reason,
         },
         async: false,
         dataType: 'json',
         beforeSend: function () { },
         success: function (response) {
            if (response.status == 200) {
               $("#Reject_Remark").append('<option value="' + reason + '">' + reason + '</option>');

               //modal close
               $("#myModal").modal('hide');
            } else {
               alert(response.msg);
            }
         },

      });
   });

   $(document).on('click', "#reject_btn", function () {
      var scan_id = $("#scan_id").val();


      var Reject_Remark = $("#Reject_Remark").val();
      if (Reject_Remark == '' || Reject_Remark == null) {
         $("#Reject_Remark").focus();
         $("#Reject_Remark").css('border-color', 'red');
         return false;
      }
      $.ajax({
         type: 'POST',
         url: '<?php echo base_url(); ?>reject_record/' + scan_id,
         data: {
            Remark: Reject_Remark,
         },
         async: false,
         dataType: 'json',
         success: function (response) {
            if (response.status == 200) {
               //modal close
               $("#rejectModal").modal('hide');
               alert('Record Rejected Successfully');
               setTimeout(() => {
                  location.reload();
               }, 1000);
               $("#Reject_Remark").val("");
            }
         }

      });

   });
</script>