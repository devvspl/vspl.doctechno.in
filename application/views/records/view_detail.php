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

   .tabs-container {
      margin-bottom: 10px;
   }

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
      /* Light color */
   }

   #rows_container .form-row:nth-child(even) {
      background-color: #d0d0d0;
      /* Dark color */
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
</style>

<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
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
$get_journal_entry_detail = $this->db->query("SELECT * from punchfile WHERE Scan_Id = $Scan_Id")->row();
$get_cash_payment_new_detail = $this->db->query("SELECT * from punchfile WHERE Scan_Id = $Scan_Id")->row();

?>
<div class="box-body">
   <div class="row">
      <div class="col-md-5">
          
         <?php if ($rec->File_Ext == 'pdf') { ?>
            <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
         <?php } else { ?>
            <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
            <div id="imageViewerContainer" style=" width: 450px; height:490px; border:2px solid #3a495e; border:2px solid #3a495e;"></div>
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
      <div class="col-md-7">
         <?php
         if ($_SESSION['role'] == 'super_approver' || $_SESSION['role'] == 'approver') {
            if ($file_detail->File_Punched == 'Y' && $file_detail->File_Approved == 'N' && $file_detail->Is_Rejected == 'N') {

         ?>
               <div class="row" style="float: right;">
                  <button class="btn btn-sm btn-success" onclick="approveRecord(<?= $file_detail->Scan_Id; ?>)">Approve</button>
                  <button class="btn btn-sm btn-danger" onclick="rejectRecord(<?= $file_detail->Scan_Id; ?>)">Reject</button>
               </div>
         <?php }
         } ?>
         <?php if ($DocType_Id == 4) { ?>
            <!-- Bank Statement -->
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
         <?php } elseif ($DocType_Id == 5) { ?>
            <!-- Boarding Pass -->
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
         <?php } elseif ($DocType_Id == 8) { ?>
            <!-- Certificate -->
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
         <?php } elseif ($DocType_Id == 10) { ?>
            <!-- Company Record -->
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
         <?php } elseif ($DocType_Id == 11) { ?>
            <!-- Confirmation of Account -->
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
         <?php } elseif ($DocType_Id == 18) { ?>
            <!-- ID Address Proof -->
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
         <?php } elseif ($DocType_Id == 19) { ?>
            <!-- Import Export Paper -->
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
         <?php } elseif ($DocType_Id == 30) { ?>
            <!-- Mediclaim History -->
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
         <?php } elseif ($DocType_Id == 31) { ?>
            <!-- Miscellaneous -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company No.</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Voucher Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->RegPurDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Location ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Particular</b></td>
                  <td>:&emsp;<?= $file_detail->Additional_Exposure ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor</b></td>
                  <td>:&emsp;<?= $file_detail->Vendor ?></td>
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
         <?php } elseif ($DocType_Id == 32) { ?>
            <!-- PF ESIC -->
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
         <?php } elseif ($DocType_Id == 35) { ?>
            <!-- Property Record -->
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
         <?php } elseif ($DocType_Id == 36) { ?>
            <!-- Rating Credentials -->
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
         <?php } elseif ($DocType_Id == 37) { ?>
            <!--Registration Certificate -->
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
         <?php } elseif ($DocType_Id == 41) { ?>
            <!-- Tax Credit Document -->
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
         <?php } elseif ($DocType_Id == 45) { ?>
            <!-- Vehicle Registration Paper -->
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
         <?php } elseif ($DocType_Id == 1) { ?>
            <!-- Two Four Wheeler Fare-->
            <div class="table-responsive">
               <table class=" table-bordered" border="1">
                  <tr>
                     <td><b>Employee/Payee Name:</b></td>
                     <td><?= $file_detail->Employee_Name; ?></td>
                     <td><b>Emp Code:</b></td>
                     <td><?= $file_detail->EmployeeCode; ?></td>
                     <td><b>Bill Date:</b></td>
                     <td><?= $file_detail->BillDate; ?></td>
                  </tr>
                  <tr>
                     <td><b>Vehicle No:</b></td>
                     <td><?= $file_detail->VehicleRegNo; ?></td>
                     <td><b>Vehicle Type :</b></td>
                     <td><?= $file_detail->Vehicle_Type ?></td>
                  </tr>
                  <tr>
                     <td><b>Location:</b></td>
                     <td><?= $file_detail->Loc_Name; ?></td>
                     <td><b>Rs/Km :</b></td>
                     <td><?= $file_detail->VehicleRs_PerKM ?></td>
                  </tr>
                  <tr>
                     <td colspan="6">
                        <table class="table text-center" border="1">
                           <thead style="background-color: red;">
                              <th>Opening Reading</th>
                              <th>Closing Reading</th>
                              <th>Total Km</th>
                              <th>Amount</th>
                           </thead>
                           <tbody>
                              <?php
                              if ($DocType_Id == 1) {
                                 $get_travel_detail = $this->db->query("select * from vehicle_traveling where Scan_Id='$Scan_Id'")->result();
                                 foreach ($get_travel_detail as $key => $value) {
                              ?>
                                    <tr>
                                       <td><?= $value->DistTraOpen ?></td>
                                       <td><?= $value->DistTraClose ?></td>
                                       <td><?= $value->Totalkm ?></td>
                                       <td><?= $value->FilledTAmt ?></td>
                                    </tr>
                                 <?php } ?>
                              <?php } else { ?>
                                 <tr>
                                    <td colspan="3" style="text-align: center;">No Record Found</td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3" style="text-align: right;"><b>Total KM:</b></td>
                     <td style="text-align: left;">
                        &emsp;&emsp;&emsp;&emsp;<b><?= $file_detail->TotalRunKM; ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3" style="text-align: right;"><b>Total Amount:</b></td>
                     <td style="text-align: left;">
                        &emsp;&emsp;&emsp;&emsp;<b><?= $file_detail->Total_Amount; ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3" style="text-align: right;"><b>Round Off:</b></td>
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
                     <td colspan="3" style="text-align: right;"><b>Grand Total:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Grand_Total; ?></b></td>
                  </tr>
                  <tr>
                     <td>Remarks :</td>
                     <td><?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($DocType_Id == 2) { ?>
            <!-- Air Bus Train Fare -->
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
         <?php } elseif ($DocType_Id == 3) { ?>
            <!-- Bank Loan Paper -->
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
                  <td>:&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?></td>
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
         <?php } elseif ($DocType_Id == 6) { ?>
            <!-- Cash Deposit Withdrawal -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
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
                  <td class="text-dark" style="width: 20%;"><b> Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Account No.</b></td>
                  <td>:&emsp;<?= $file_detail->BankAccountNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Beneficiary Name</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Type of Document</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
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
         <?php } elseif ($DocType_Id == 23) { ?>
            <!-- Invoice -->
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
                     if ($DocType_Id == 23) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where Scan_Id='$Scan_Id'")->result();
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
         <?php } elseif ($DocType_Id == 54) { ?>
            <!-- Sale Bill -->
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
                     if ($DocType_Id == 54) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where Scan_Id='$Scan_Id'")->result();
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
         <?php } elseif ($DocType_Id == 44) { ?>
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td colspan="2"><b>Vendor Name:</b></td>
                     <td colspan="4"><?= $file_detail->FromName; ?></td>
                  </tr>
                  <tr>
                     <td><b>Billing To:</b></td>
                     <td><?= $file_detail->ToName; ?></td>
                     <td>Vehicle No</td>
                     <td><?= $file_detail->VehicleRegNo; ?></td>
                  </tr>
                  <tr>
                     <td><b>Bill No.:</b></td>
                     <td><?= $file_detail->File_No ?></td>
                     <td><b>Bill Date:</b></td>
                     <td><?= date('d-m-Y', strtotime($file_detail->BillDate)); ?></td>
                     <td><b>Work Location:</b></td>
                     <td><?= $file_detail->Loc_Name; ?></td>
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
                     <th>Total Amount</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($DocType_Id == 44) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where Scan_Id='$Scan_Id'")->result();
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
                  <tr>
                     <td>Remarks :</td>
                     <td><?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($DocType_Id == 43) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vendor Name</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing To</b></td>
                  <td>:&emsp;<?= $file_detail->ToName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Dealer Code</b></td>
                  <td>:&emsp;<?= $file_detail->BSRCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Due Date</b></td>
                  <td>:&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Work Location </b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleRegNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Description</b></td>
                  <td>:&emsp;<?= $file_detail->FileName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Liter</b></td>
                  <td>:&emsp;<?= $file_detail->MeterNumber ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Rate per Liter</b></td>
                  <td>:&emsp;<?= $file_detail->TariffPlan ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td><b>Round Off:</b></td>
                  <td>
                     ( <?php
                        if ($file_detail->Grand_Total < $file_detail->Total_Amount) {
                           echo '-';
                        } else {
                           echo '+';
                        }
                        ?>
                     )
                     <?= $file_detail->Total_Discount; ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Grand Total</b></td>
                  <td>:&emsp;<?= $file_detail->Grand_Total ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($DocType_Id == 42) { ?>
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Biller Name</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Telephone No.</b></td>
                  <td>:&emsp;<?= $file_detail->MobileNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Period</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Taxable Value</b></td>
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
                  <td class="text-dark" style="width: 20%;"><b>Total Amount Due</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Total Amount Outstanding</b></td>
                  <td>:&emsp;<?= $file_detail->Grand_Total ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Last Payment Date</b></td>
                  <td>
                     :&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?>
                  </td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($DocType_Id == 40) { ?>
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
         <?php } elseif ($DocType_Id == 39) { ?>
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
         <?php } elseif ($DocType_Id == 38) { ?>
            <!-- RST OFD -->
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
         <?php } elseif ($DocType_Id == 34) { ?>
            <!-- Postage Courier -->
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
         <?php } elseif ($DocType_Id == 33) { ?>
            <!-- Postage Courier -->
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
                  <td>:&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?></td>
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
         <?php } elseif ($DocType_Id == 29) { ?>
            <!-- Meals -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hotel Name:</b></td>
                  <td>:&emsp;<?= $file_detail->Hotel_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Hotel Address:</b></td>
                  <td>:&emsp;<?= $file_detail->Hotel_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill No</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee</b></td>
                  <td>:&emsp;<?= $file_detail->Employee_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee Code</b></td>
                  <td>:&emsp;<?= $file_detail->EmployeeCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Detail</b></td>
                  <td>:&emsp;<?= $file_detail->FileName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($DocType_Id == 28) { ?>
            <!-- Lodging -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark"><b>Bill No.</b></td>
                  <td style="width: 33%">:&emsp;<?= $file_detail->File_No ?></td>
                  <td class="text-dark"><b> Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Billing Name</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
                  <td class="text-dark"><b>Billing Address</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Hotel Name</b></td>
                  <td>:&emsp;<?= $file_detail->Hotel_Name ?></td>
                  <td class="text-dark"><b>Hotel Address</b></td>
                  <td>:&emsp;<?= $file_detail->Hotel_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Arrival Date /Time</b></td>
                  <td>:&emsp;<?= $file_detail->FromDateTime ?></td>
                  <td class="text-dark"><b>Departure Date /Time</b></td>
                  <td>:&emsp;<?= $file_detail->ToDateTime ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Duration of Stay</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
                  <td class="text-dark"><b>Room Type</b></td>
                  <td>:&emsp;<?= $file_detail->TravelClass ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Meal Plan</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
                  <td class="text-dark"><b>Billing Instruction</b></td>
                  <td>:&emsp;<?= $file_detail->Particular ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Room Rate</b></td>
                  <td>:&emsp;<?= $file_detail->TariffPlan ?></td>
                  <td class="text-dark"><b>Amount</b></td>
                  <td>:&emsp;<?= $file_detail->SubTotal ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>Other Charge</b></td>
                  <td>:&emsp;<?= $file_detail->OthCharge_Amount ?></td>
                  <td class="text-dark"><b>Discount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Discount ?></td>
               </tr>
               <tr>
                  <td class="text-dark"><b>GST</b></td>
                  <td>:&emsp;<?= $file_detail->GSTIN ?></td>
                  <td class="text-dark"><b>Total Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Grand_Total ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
            </table>
            <table class="table text-center" border="1" style="margin-top:1px;">
               <thead class="bg-primary">
                  <th>Employee Name</th>
                  <th>Emp Code</th>
               </thead>
               <tbody>
                  <?php
                  if ($DocType_Id == 28) {
                     $emp_detail = $this->db->query("select * from lodging_employee  where Scan_Id='$Scan_Id'")->result();
                     foreach ($emp_detail as $key => $value) {
                  ?>
                        <tr>
                           <td><?= $value->emp_name ?></td>
                           <td><?= $value->emp_code ?></td>
                        </tr>
                     <?php } ?>
                  <?php }  ?>
               </tbody>
            </table>
            <tabel>
               <tr>
                  <td class="text-dark"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </tabel>
         <?php } elseif ($DocType_Id == 27) { ?>
            <!-- Local Conveyance -->
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td><b>Mode:</b></td>
                     <td><?= $file_detail->TravelMode ?></td>
                     <td><b>Employee Name:</b></td>
                     <td><?= $file_detail->Employee_Name ?></td>
                     <td><b>Employee Code:</b></td>
                     <td><?= $file_detail->EmployeeCode ?></td>
                  </tr>
                  <tr>
                     <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                     <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
                  </tr>
                  <tr>
                     <td><b>Vehicle No:</b></td>
                     <td><?= $file_detail->VehicleRegNo ?></td>
                     <td><b>Month:</b></td>
                     <td><?= $file_detail->MonthName ?></td>
                     <td><b>Calculation Base:</b></td>
                     <td><?= $file_detail->Cal_By ?></td>
                  </tr>
                  <tr>
                     <?php
                     if ($file_detail->Cal_By == 'KM_Base') {
                     ?>
                        <td><b>Per KM Rate:</b></td>
                        <td><?= $file_detail->VehicleRs_PerKM; ?></td>
                     <?php } else { ?>
                        <td><b>Fixed Amount:</b></td>
                        <td><?= $file_detail->HiredVehicle_Amount ?></td>
                     <?php } ?>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1" style="margin-top:1px;">
                  <thead class="bg-primary">
                     <th>Date</th>
                     <th>Opening Reading</th>
                     <th>Closing Reading</th>
                     <th>KM Run</th>
                     <th>Amount</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($DocType_Id == 27) {
                        $get_detail = $this->db->query("select * from vehicle_traveling  where Scan_Id='$Scan_Id'")->result();
                        foreach ($get_detail as $key => $value) {
                     ?>
                           <tr>
                              <td><?= $value->JourneyStartDt ?></td>
                              <td><?= $value->DistTraOpen ?></td>
                              <td><?= $value->DistTraClose ?></td>
                              <td><?= $value->Totalkm ?></td>
                              <td><?= $value->FilledTAmt ?></td>
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
                     <td colspan="7" style="text-align: right;"><b>Total KM:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->TotalRunKM; ?></b></td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Total Amount:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Total_Amount; ?></b></td>
                  </tr>
                  <tr>
                     <td>Remarks :</td>
                     <td colspan="6"><?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($DocType_Id == 22) { ?>
            <!-- Insurance Policy -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Insurance Type</b></td>
                  <td>:&emsp;<?= $file_detail->File_Type ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b> Insurance Company</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Policy Number</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Policy Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>From Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>To Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Vehicle No.</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleRegNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b> Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Premium Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($DocType_Id == 26) { ?>
            <!-- Lease Rent -->
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
         <?php } elseif ($DocType_Id == 25) { ?>
            <!-- jeep_campaign -->
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
         <?php } elseif ($DocType_Id == 24) { ?>
            <!-- IT Return -->
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
         <?php } elseif ($DocType_Id == 7) { ?>
            <!-- Cash Voucher -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Voucher Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payee</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payer</b></td>
                  <td>:&emsp;<?= $file_detail->AgentName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name; ?></td>
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
         <?php } elseif ($DocType_Id == 16) { ?>
            <!-- Challan -->
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
         <?php } elseif ($DocType_Id == 9) { ?>
            <!-- Cheque -->
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
         <?php } elseif ($DocType_Id == 12) { ?>
            <!-- Dealer Meeting -->
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
         <?php } elseif ($DocType_Id == 21) { ?>
            <!-- Insurance Document -->
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
                  <td>:&emsp;<?php echo !empty($file_detail->DueDate) && $file_detail->DueDate !== '0000-00-00' ? date('d-m-Y', strtotime($file_detail->DueDate)) : ''; ?></td>
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
         <?php } elseif ($DocType_Id == 13) { ?>
            <!-- Electricity -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Date</b></td>
                  <td>:&emsp;<?php
                              $formattedDate = isset($file_detail->PremiumDate) ? date('d-m-Y', strtotime($file_detail->PremiumDate)) : '';
                              echo $formattedDate;
                              ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Biller Name</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Person ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Business Partner No (BP No.)</b></td>
                  <td>:&emsp;<?= $file_detail->ReferenceNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Period</b></td>
                  <td>:&emsp;<?= $file_detail->Period ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Meter Number</b></td>
                  <td>:&emsp;<?= $file_detail->MeterNumber ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Number</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Previous Meter Reading</b></td>
                  <td>:&emsp;<?= $file_detail->PreviousReading ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Current Meter Reading</b></td>
                  <td>:&emsp;<?= $file_detail->CurrentReading ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Unit Consumed</b></td>
                  <td>:&emsp;<?= $file_detail->UnitsConsumed ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Last Date of Payment</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->LastDateOfPayment)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Mode</b></td>
                  <td>:&emsp;<?= $file_detail->NatureOfPayment ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bill Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Total_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Payment Amount</b></td>
                  <td>:&emsp;<?= $file_detail->Payment_Amount ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                  <td>:&emsp;<?= $file_detail->Remark ?></td>
               </tr>
            </table>
         <?php } elseif ($DocType_Id == 14) { ?>
            <!-- FD FV -->
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
         <?php } elseif ($DocType_Id == 15) { ?>
            <!-- Fixed Deposit -->
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
         <?php } elseif ($DocType_Id == 20) { ?>
            <!-- Income Tax TDS -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Section</b></td>
                  <td>:&emsp;<?= $file_detail->Section ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Company</b></td>
                  <td>:&emsp;<?= $file_detail->Company ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Nature of Payment</b></td>
                  <td>:&emsp;<?= $file_detail->NatureOfPayment ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Assessment Year</b></td>
                  <td>:&emsp;<?= $file_detail->Financial_Year ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Name</b></td>
                  <td>:&emsp;<?= $file_detail->BankName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>BSR Code</b></td>
                  <td>:&emsp;<?= $file_detail->BSRCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Challan Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Bank Reference No.</b></td>
                  <td>:&emsp;<?= $file_detail->ReferenceNo ?></td>
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
         <?php } elseif ($DocType_Id == 17) { ?>
            <!-- Hired Vehicle -->
            <table class="table borderless">
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agency Name</b></td>
                  <td>:&emsp;<?= $file_detail->FromName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Agency Address</b></td>
                  <td>:&emsp;<?= $file_detail->AgencyAddress ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Name</b></td>
                  <td>:&emsp;<?= $file_detail->ToName ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Billing Address</b></td>
                  <td>:&emsp;<?= $file_detail->Related_Address ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Location</b></td>
                  <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Employee Name</b></td>
                  <td>:&emsp;<?= $file_detail->Employee_Name ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Emp Code</b></td>
                  <td>:&emsp;<?= $file_detail->EmployeeCode ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Vehicle No.</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleRegNo ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 40%;"><b>Invoice No.</b></td>
                  <td>:&emsp;<?= $file_detail->File_No ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Invoice Date</b></td>
                  <td>:&emsp;<?= date('Y-m-d', strtotime($file_detail->File_Date)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Booking Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>End Date</b></td>
                  <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Per Km Rate</b></td>
                  <td>:&emsp;<?= $file_detail->VehicleRs_PerKM ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Opening Reading</b></td>
                  <td>:&emsp;<?= $file_detail->OpeningKm ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Closing Reading</b></td>
                  <td>:&emsp;<?= $file_detail->ClosingKm ?></td>
               </tr>
               <tr>
                  <td class="text-dark" style="width: 20%;"><b>Total KM </b></td>
                  <td>:&emsp;<?= $file_detail->TotalRunKM ?></td>
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
         <?php } elseif ($DocType_Id == 46) { ?>
            <!-- GST Challan -->
            <div class="table-responsive">
               <table class="table-bordered" border="1" style="width: 100%;line-height: 2;">
                  <tr>
                     <td><b>CPIN:</b></td>
                     <td><?= $file_detail->CPIN; ?></td>
                     <td><b>Deposit Date:</b></td>
                     <td><?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
                     <td><b>CIN:</b></td>
                     <td><?= $file_detail->CIN ?></td>
                  </tr>
                  <tr>
                     <td><b>Bank Name:</b></td>
                     <td><?= $file_detail->BankName ?></td>
                     <td><b>BRN:</b></td>
                     <td><?= $file_detail->BankBSRCode; ?></td>
                     <td><b>GSTIN:</b></td>
                     <td><?= $file_detail->GSTIN ?></td>
                  </tr>
                  <tr>
                     <td><b>Email ID:</b></td>
                     <td><?= $file_detail->Email ?></td>
                     <td><b>Mobile No.:</b></td>
                     <td><?= $file_detail->MobileNo ?></td>
                     <td colspan="2"><b>Company Name:</b></td>
                     <td colspan="2"><?= $file_detail->Company; ?></td>
                  </tr>
                  <tr>
                     <td><b>Company Address:</b></td>
                     <td><?= $file_detail->Related_Address ?></td>
                  </tr>
               </table>
               <br>
               <table class="table text-center" border="1">
                  <thead class="bg-primary">
                     <th>Particular</th>
                     <th>Tax(₹)</th>
                     <th>Interest(₹)</th>
                     <th>Penalty(₹)</th>
                     <th>Fees(₹)</th>
                     <th>Other(₹)</th>
                     <th>Total(₹)</th>
                  </thead>
                  <tbody>
                     <?php
                     if ($DocType_Id == 46) {
                        $get_gst_challan_detail = $this->db->query("select * from gst_challan_detail where Scan_Id='$Scan_Id'")->result();

                        foreach ($get_gst_challan_detail as $key => $value) {
                     ?>
                           <tr>
                              <td><?= $value->Particular ?></td>
                              <td><?= $value->Tax ?></td>
                              <td><?= $value->Interest ?></td>
                              <td><?= $value->Penalty ?></td>
                              <td><?= $value->Fees ?></td>
                              <td><?= $value->Other ?></td>
                              <td><?= $value->Total ?></td>
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
                     <td colspan="7" style="text-align: right;"><b>Total Challan Amount:</b></td>
                     <td style="text-align: right;"><b><?= $file_detail->Total_Amount; ?></b></td>
                  </tr>
                  <tr>
                     <td>Remarks :</td>
                     <td colspan="6"><?= $file_detail->Remark ?></td>
                  </tr>
               </table>
            </div>
         <?php } elseif ($DocType_Id == 47) { ?>
            <!-- Labour Payment -->
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
                     if ($DocType_Id == 47) {
                        $labour_payment_detail = $this->db->query("select * from labour_payment_detail where Scan_Id='$Scan_Id'")->result();

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
         <?php } elseif ($DocType_Id == 48) { ?>
            <!-- Cash Receipt -->
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
         <?php } elseif ($DocType_Id == 49) { ?>
            <!-- Fixed Asset -->
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
                     if ($DocType_Id == 49) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where Scan_Id='$Scan_Id'")->result();
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
         <?php } elseif ($DocType_Id == 50) { ?>
            <!-- Machine Operation -->
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
         <?php } elseif ($DocType_Id == 51) { ?>
            <!-- Air -->
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
                  if ($DocType_Id == 51) {
                     $emp_detail = $this->db->query("select * from lodging_employee  where Scan_Id='$Scan_Id'")->result();
                     foreach ($emp_detail as $key => $value) {
                  ?>
                        <tr>
                           <td><?= $value->emp_name ?></td>
                           <td><?= $value->emp_code ?></td>
                        </tr>
                     <?php } ?>
                  <?php }  ?>
               </tbody>
            </table>
         <?php } elseif ($DocType_Id == 52) { ?>
            <!-- Rail -->
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
                  if ($DocType_Id == 52) {
                     $emp_detail = $this->db->query("select * from lodging_employee  where Scan_Id='$Scan_Id'")->result();
                     foreach ($emp_detail as $key => $value) {
                  ?>
                        <tr>
                           <td><?= $value->emp_name ?></td>
                           <td><?= $value->emp_code ?></td>
                        </tr>
                     <?php } ?>
                  <?php }  ?>
               </tbody>
            </table>
         <?php } elseif ($DocType_Id == 53) { ?>
            <!-- Bus -->
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
         <?php } elseif ($DocType_Id == 55) { ?>
            <!-- Ticket Cancellation -->
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
                  if ($DocType_Id == 55) {
                     $ticket_detail = $this->db->query("select * from ticket_cancellation where Scan_Id='$Scan_Id'")->result();
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
         <?php } elseif ($DocType_Id == 56) { ?>
            <!-- Credit Note -->
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
                     if ($DocType_Id == 56) {
                        $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where Scan_Id='$Scan_Id'")->result();
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
         <?php } elseif ($DocType_Id == 57) {
            
         ?>
            <div class="tabs-container">

               <div class="tabs active-tab" id="invoice-tab">Invoice Details</div>
               <div class="tabs" id="additional-info-tab">Additional Information</div>
            </div>
            <div id="invoice-details" class="tab-content active">
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
                        if ($DocType_Id == 57) {
                           $get_invoice_detail = $this->db->query("select invoice_detail.*,master_unit.unit_name from invoice_detail left join master_unit on master_unit.unit_id = invoice_detail.Unit where Scan_Id='$Scan_Id'")->result();
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
            </div>
            <div id="additional-info" class="tab-content">
               <?php
               $journal_entries = $this->db->query("SELECT 
                jei.Scan_Id, 
                jei.DepartmentID, 
                md.department_name, 
                jei.business_unit_id, 
                bu.business_unit_name, 
                jei.state_id, 
                ms.state_name, 
                jei.region_id, 
                mr.region_name, 
                jei.location_id, 
                mwl.location_name, 
                jei.category_id, 
                mcc.crop_category_name, 
                jei.crop_id, 
                mc.crop_name, 
                jei.activity_id, 
                ma.activity_name, 
                jei.subledger, 
                jei.debit_ac, 
                jei.credit_ac, 
                jei.Total_Amount, 
                jei.ReferenceNo, 
                jei.Remark, 
                jei.Created_By, 
                jei.Created_Date
               FROM 
                journal_entry_items jei
               LEFT JOIN 
                master_department md ON jei.DepartmentID = md.department_id
               LEFT JOIN 
                master_business_unit bu ON jei.business_unit_id = bu.business_unit_id
               LEFT JOIN 
                master_state ms ON jei.state_id = ms.state_id
               LEFT JOIN 
                master_region mr ON jei.region_id = mr.region_id
               LEFT JOIN 
                master_work_location mwl ON jei.location_id = mwl.location_id
               LEFT JOIN 
                master_crop_category mcc ON jei.category_id = mcc.crop_category_id
               LEFT JOIN 
                master_crop mc ON jei.crop_id = mc.crop_id
               LEFT JOIN 
                master_activity ma ON jei.activity_id = ma.activity_id WHERE jei.Scan_Id = $Scan_Id");

               ?>
              <div class="row">
              <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>Document No:</b> <?= $file_detail->document_number; ?></label>
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>Date:</b> <?= $file_detail->finance_punch_date; ?></label>
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>Business Entity:</b> 
                  <?php $businessEntity = $this->db->where(['business_entity_id' => $file_detail->business_entity_id])
                           ->get('master_business_entity')
                           ->row();
                           $businessEntityName = $businessEntity ? $businessEntity->business_entity_name : 'N/A';
                        echo $businessEntityName;?>
               </label>
               </div>
               <div class="form-group col-md-12" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>Narration:</b> <?= $file_detail->narration; ?></label>
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>TDS Applicable:</b> <?= $file_detail->tdsApplicable; ?></label>
               </div>
               <?php 
                  if($file_detail->tdsApplicable === 'yes'){
                     ?>
                   <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>TDS JV No:</b> <?= $file_detail->TDS_JV_no; ?></label>
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>TDS Section:</b> <?= $file_detail->TDS_section; ?></label>
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for=""><b>TDS Amount:</b> <?= $file_detail->TDS_amount; ?></label>
               </div>
                  <?php 
                  }
               
               ?>
              </div>
               <div class="table-responsive">
                  <table class="table" border="1" cellpadding="10" cellspacing="0">
                     <thead>
                        <tr>
                           <th>Scan ID</th>
                           <th>Department</th>
                           <th>Business Unit</th>
                           <th>State</th>
                           <th>Region</th>
                           <th>Location</th>
                           <th>Category</th>
                           <th>Crop</th>
                           <th>Activity</th>
                           <th>Subledger</th>
                           <th>Debit Account</th>
                           <th>Credit Account</th>
                           <th>Total Amount</th>
                           <th>Reference No</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($journal_entries)) : ?>
                           <?php foreach ($journal_entries->result_array() as $entry) : ?>
                              <tr>
                                 <td><?= $entry['Scan_Id']; ?></td>
                                 <td><?= $entry['department_name']; ?></td>
                                 <td><?= $entry['business_unit_name']; ?></td>
                                 <td><?= $entry['state_name']; ?></td>
                                 <td><?= $entry['region_name']; ?></td>
                                 <td><?= $entry['location_name']; ?></td>
                                 <td><?= $entry['crop_category_name']; ?></td>
                                 <td><?= $entry['crop_name']; ?></td>
                                 <td><?= $entry['activity_name']; ?></td>
                                 <td><?= $entry['subledger']; ?></td>
                                 <td><?= $entry['debit_ac']; ?></td>
                                 <td><?= $entry['credit_ac']; ?></td>
                                 <td><?= $entry['Total_Amount']; ?></td>
                                 <td><?= $entry['ReferenceNo']; ?></td>
                                 <td><?= $entry['Remark']; ?></td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else : ?>
                           <tr>
                              <td colspan="17">No journal entries found.</td>
                           </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         <?php
         } elseif ($DocType_Id == 58) {
         ?>
            <div class="table-responsive">
               <table class="table-bordered mt-3" border="1" style="width: 100%; line-height: 2;">
                  <thead>
                     <tr>
                        <th>Field</th>
                        <th>Value</th>
                        <th>Field</th>
                        <th>Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $fieldLabels = [
                        'DocType' => 'Document Type',
                        'BillDate' => 'Bill Date',
                        'Total_Amount' => 'Total Amount',
                        'file_punch_date' => 'File Punch Date',
                        'business_entity_name' => 'Business Entity Name',
                        'narration' => 'Narration',
                        'document_number' => 'Document Number',
                        'tdsApplicable' => 'TDS Applicable',
                        'TDS_JV_no' => 'TDS JV Number',
                        'TDS_section' => 'TDS Section',
                        'TDS_percentage' => 'TDS Percentage',
                        'TDS_amount' => 'TDS Amount',
                     ];

                     if (isset($get_cash_payment_new_detail)) :
                        $count = 0;
                        foreach ($get_cash_payment_new_detail as $field => $value) :
                           if (isset($fieldLabels[$field])) :
                              if ($count % 2 == 0) :
                                 if ($count > 0) echo '</tr>';
                                 echo '<tr>';
                              endif;
                     ?>
                              <td><b><?= $fieldLabels[$field]; ?></b></td>
                              <td><?= $value; ?></td>
                        <?php
                              $count++;
                           endif;
                        endforeach;

                        if ($count % 2 != 0) :
                           echo '<td colspan="2"></td></tr>';
                        else :
                           echo '</tr>';
                        endif;

                     else :
                        ?>
                        <tr>
                           <td colspan="4">No data available.</td>
                        </tr>
                     <?php endif;
                     ?>
                  </tbody>
               </table>
               <?php
               $cash_payment_entries = $this->db->query("SELECT 
cpn.Scan_Id, 
cpn.DepartmentID, 
md.department_name, 
cpn.business_unit_id, 
bu.business_unit_name, 
cpn.state_id, 
ms.state_name, 
cpn.region_id, 
mr.region_name, 
cpn.location_id, 
mwl.location_name, 
cpn.ptm_category, 
cpn.category_id, 
mcc.crop_category_name, 
cpn.crop_id, 
mc.crop_name, 
cpn.activity_id, 
ma.activity_name, 
cpn.Total_Amount_item, 
cpn.ReferenceNo, 
cpn.Remark, 
mcoc.cost_center_name,
cpn.Created_By, 
cpn.Created_Date,
mac.account_name -- Ensure this is the correct column
FROM 
cash_payment_new_items cpn
LEFT JOIN 
master_department md ON cpn.DepartmentID = md.department_id
LEFT JOIN 
master_business_unit bu ON cpn.business_unit_id = bu.business_unit_id
LEFT JOIN 
master_state ms ON cpn.state_id = ms.state_id
LEFT JOIN 
master_region mr ON cpn.region_id = mr.region_id
LEFT JOIN 
master_work_location mwl ON cpn.location_id = mwl.location_id
LEFT JOIN 
master_crop_category mcc ON cpn.category_id = mcc.crop_category_id
LEFT JOIN 
master_crop mc ON cpn.crop_id = mc.crop_id
LEFT JOIN 
master_cost_center mcoc ON cpn.cost_center_id = mcoc.cost_center_id
LEFT JOIN 
master_account mac ON cpn.Account_id_item = mac.account_id -- Changed this line
LEFT JOIN 
master_activity ma ON cpn.activity_id = ma.activity_id
WHERE 
cpn.Scan_Id = $Scan_Id");

               ?>
               <table border="1" cellpadding="10" cellspacing="0">
                  <thead>
                     <tr>
                        <th>Scan ID</th>
                        <th>Cost Center</th>
                        <th>Location</th>
                        <th>Crop Category</th>
                        <th>Crop</th>
                        <th>Activity</th>
                        <th>State</th>
                        <th>Region</th>
                        <th>Department</th>
                        <th>TPM Category</th>
                        <th>Business Unit</th>
                        <th>Account</th>
                        <th>Total Amount</th>
                        <th>Reference No</th>
                        <th>Remark</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if (!empty($cash_payment_entries)) : ?>
                        <?php foreach ($cash_payment_entries->result_array() as $entry) : ?>
                           <tr>
                              <td><?= $entry['Scan_Id']; ?></td>
                              <td><?= $entry['cost_center_name']; ?></td>
                              <td><?= $entry['location_name']; ?></td>
                              <td><?= $entry['crop_category_name']; ?></td>
                              <td><?= $entry['crop_name']; ?></td>
                              <td><?= $entry['activity_name']; ?></td>
                              <td><?= $entry['state_name']; ?></td>
                              <td><?= $entry['region_name']; ?></td>
                              ` <td><?= $entry['department_name']; ?></td>
                              <td><?= $entry['business_unit_name']; ?></td>
                              <td>
                                 <?php
                                 $PTMCategory = [
                                    ['value' => '1', 'text' => 'Cash'],
                                    ['value' => '2', 'text' => 'Cheque'],
                                    ['value' => '3', 'text' => 'DD'],
                                    ['value' => '4', 'text' => 'Others'],
                                 ];


                                 $ptm_category_value = $entry['ptm_category'];
                                 $category_text = '';
                                 foreach ($PTMCategory as $category) {
                                    if ($category['value'] == $ptm_category_value) {
                                       $category_text = $category['text'];
                                       break;
                                    }
                                 }

                                 echo $category_text;
                                 ?>
                              </td>

                              <td><?= $entry['account_name']; ?></td>
                              <td><?= $entry['Total_Amount_item']; ?></td>
                              <td><?= $entry['ReferenceNo']; ?></td>
                              <td><?= $entry['Remark']; ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php else : ?>
                        <tr>
                           <td colspan="17">No cash payment entries found.</td>
                        </tr>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
         <?php
         }

         ?>
         <?php if ($this->customlib->haveSupportFile($Scan_Id) == 1) { ?>
            <div class="row" style="margin-top: 20px;">
               <div class="col-md-12">
                  <label for="">Supporting File:</label>
                  <div class="form-group">
                     <?php $support_file = $this->customlib->getSupportFile($Scan_Id);
                     foreach ($support_file as $row) { ?>
                        <div class="col-md-3">
                           <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');">
                              <?php echo $row['File'] ?></a>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         <?php }  ?>
      </div>
   </div>
</div>
<!-- Reject Modal -->
<div id="rejectModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
   <div class="modal-dialog">
      <div class="modal-content">
         <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
         <div class="scroll-area">
            <div class="modal-body ">
               <div class="form-group">
                  <input type="hidden" name="Scan_Id" id="Scan_Id">
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
<!-- Add Reason Modal -->
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
   $(document).ready(function() {
      $('#invoice-tab').click(function() {

         $('#additional-info').removeClass('active');

         $('#invoice-details').addClass('active');


         $('.tabs').removeClass('active-tab');
         $(this).addClass('active-tab');
      });

      $('#additional-info-tab').click(function() {

         $('#invoice-details').removeClass('active');

         $('#additional-info').addClass('active');

         $('.tabs').removeClass('active-tab');
         $(this).addClass('active-tab');
      });
   })

   function approveRecord(Scan_Id) {
      if (confirm("Are you sure to approve this file")) {
         $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>approve_record_by_super_approver/' + Scan_Id,
            async: false,
            dataType: 'json',
            success: function(response) {
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

   function rejectRecord(Scan_Id) {


      $("#Scan_Id").val(Scan_Id);
      $("#rejectModal").modal("show");
      $("#Reject_Remark").select2({
         dropdownParent: $('#rejectModal'),
         width: '100%',
         allowClear: true,
         escapeMarkup: function(markup) {
            return markup;
         },
         placeholder: "Select Rejection Reason",
         language: {
            noResults: function() {
               return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add New Reason</button>";
            }
         }
      });
   }

   $(document).on('click', "#save_btn", function() {
      var reason = $("#reason").val();
      if (reason == '' || reason == null) {
         $("#reason").focus();
         $("#reason").css('border-color', 'red');
         return false;
      }
      $.ajax({
         type: 'POST',
         url: '<?= base_url() ?>Rejection_reason/save_reason',
         data: {
            reason: reason,
         },
         async: false,
         dataType: 'json',
         beforeSend: function() {},
         success: function(response) {
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

   $(document).on('click', "#reject_btn", function() {
      var Scan_Id = $("#Scan_Id").val();


      var Reject_Remark = $("#Reject_Remark").val();
      if (Reject_Remark == '' || Reject_Remark == null) {
         $("#Reject_Remark").focus();
         $("#Reject_Remark").css('border-color', 'red');
         return false;
      }
      $.ajax({
         type: 'POST',
         url: '<?php echo base_url(); ?>reject_record/' + Scan_Id,
         data: {
            Remark: Reject_Remark,
         },
         async: false,
         dataType: 'json',
         success: function(response) {
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