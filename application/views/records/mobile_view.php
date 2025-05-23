<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$fin_year = $this->customlib->getFinancial_year();
$company_list = $this->customlib->getCompanyList();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SnapDoc</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta name="theme-color" content="#424242" />
    <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>assets/images/favicon.png">

    <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
    <style>
        .table-pro {
            margin-bottom: 0px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        .padding-1 {
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <?php if ($doc_type_id == 4) { ?>
                    <!-- Bank Statement -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td class="text-dark" style="width: 20%;"><b>Account No</b></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 5) { ?>
                    <!-- Boarding Pass -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 8) { ?>
                    <!-- Certificate -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 10) { ?>
                    <!-- Company Record -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 11) { ?>
                    <!-- Confirmation of Account -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 18) { ?>
                    <!-- ID Address Proof -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 19) { ?>
                    <!-- Import Export Paper -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 30) { ?>
                    <!-- Mediclaim History -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 31) { ?>
                    <!-- Miscellaneous -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
                            <tr>
                                <td class="text-dark" style="width: 40%;"><b>File Date</b></td>
                                <td>:&emsp;<?= $file_detail->File_Date ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Company No.</b></td>
                                <td>:&emsp;<?= $file_detail->Company ?></td>
                            </tr>

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                                <td>:&emsp;<?= $file_detail->Remark ?></td>
                            </tr>

                        </table>
                    </div>
                <?php } elseif ($doc_type_id == 32) { ?>
                    <!-- PF ESIC -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 35) { ?>
                    <!-- Property Record -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td class="text-dark" style="width: 20%;"><b>New Rin Pushtika No</b></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 36) { ?>
                    <!-- Rating Credentials -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 37) { ?>
                    <!--Registration Certificate -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 41) { ?>
                    <!-- Tax Credit Document -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 45) { ?>
                    <!-- Vehicle Registration Paper -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 1) { ?>
                    <!-- Two Four Wheeler Fare-->

                    <div class="padding-1">
                        <table class="table borderless table-pro">
                            <tbody>
                                <tr>
                                    <td><b>Bill Date:</b></td>
                                    <td><?= $file_detail->BillDate; ?></td>
                                    <td><b>Vehicle Type :</b></td>
                                    <td><?= $file_detail->Vehicle_Type ?></td>

                                </tr>
                                <tr>
                                    <td><b>Rs/Km :</b></td>
                                    <td><?= $file_detail->VehicleRs_PerKM ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive" style="padding: 0 10px;">
                        <table class="table" style="padding:0 10px;border:0px;font-size:12px;margin-bottom:0px;">

                            <thead Class="bg-primary">
                                <th>Trip Started</th>
                                <th>Trip Ended</th>
                                <th>Vehicle Reg no</th>
                                <th>Dist Trvld Opening</th>
                                <th>Dist Trvld closing</th>
                                <th>Total Km</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                                <?php
                                if ($doc_type_id == 1) {
                                    $get_travel_detail = $this->db->query("select * from vehicle_traveling where scan_id='$scan_id'")->result();
                                    foreach ($get_travel_detail as $key => $value) {
                                ?>
                                        <tr>
                                            <td><?= date('d-m-Y', strtotime($value->JourneyStartDt)) ?></td>
                                            <td><?= date('d-m-Y', strtotime($value->JourneyEndDt)) ?></td>
                                            <td><?= $value->VehicleReg ?></td>
                                            <td><?= $value->DistTraOpen ?></td>
                                            <td><?= $value->DistTraClose ?></td>
                                            <td><?= $value->Totalkm ?></td>
                                            <td><?= $value->FilledTAmt ?></td>
                                        </tr>
                                    <?php  } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center;">No Record Found</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <table class="table table-bordered" style="margin-bottom: 0px; border: 1px solid #ddd;font-size: 12px;">
                            <tr>
                                <td colspan="4" style="text-align: right;"><b>Total:</b></td>
                                <td style="text-align: center;">&emsp;&emsp;&emsp;&emsp;<b><?= $file_detail->TotalRunKM; ?></b></td>
                                <td style="text-align: center;"><b><?= $file_detail->Total_Amount; ?></b></td>
                            </tr>
                            <tr>
                                <td>Remarks :</td>
                                <td><?= $file_detail->Remark ?></td>
                            </tr>

                        </table>
                    </div>
                <?php } elseif ($doc_type_id == 2) { ?>
                    <!-- Air Bus Train Fare -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 3) { ?>
                    <!-- Bank Loan Paper -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->DueDate)) ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 6) { ?>
                    <!-- Cash Deposit Withdrawal -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 23) { ?>
                    <!-- Invoice -->
                    <div style="padding:10px;">
                        <table class="table table-bordered- " style="margin-bottom:0px;border: 1px solid #ddd;font-size:12px;">
                            <tbody>
                                <tr>
                                    <td><b>Bill Date:</b></td>
                                    <td><?= $file_detail->BillDate; ?></td>
                                    <td><b>Bill No :</b></td>
                                    <td><?= $file_detail->File_No ?></td>

                                </tr>
                                <tr>
                                    <td><b>Company :</b></td>
                                    <td><?= $file_detail->Company ?></td>
                                    <td><b>Department :</b></td>
                                    <td><?= $file_detail->Department ?></td>
                                </tr>
                                <tr>
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
                                </tr>
                                <tr>
                                    <td><b>Category:</b></td>
                                    <td><?= $file_detail->Category ?></td>
                                    <td><b>File:</b></td>
                                    <td><?= $file_detail->FileName ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive" style="padding:0 10px;border:0px;font-size:12px;margin-bottom:0px;">
                        <table class="table text-center" border="1" style="border:1px solid #ddd;">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Particular</th>
                                    <th>HSN</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>MRP</th>
                                    <th>Discount</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($doc_type_id == 23) {
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
                                        </tr>
                                    <?php  } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center;">No Record Found</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:0 10px;font-size:12px;">
                        <table class="table table-striped">
                            <tbody>

                                <tr>
                                    <td style="text-align: right;"><b>Sub Total:</b></td>
                                    <td style="text-align: right;"><b><?= $file_detail->SubTotal; ?></b></td>
                                </tr>
                                <?php if ($file_detail->CGST_Amount != '0.00') { ?>
                                    <tr>
                                        <td style="text-align: right;"><b>CGST:</b></td>
                                        <td style="text-align: right;"><b><?= $file_detail->CGST_Amount; ?>%</b></td>
                                    </tr>
                                <?php }
                                if ($file_detail->SGST_Amount != '0.00') { ?>
                                    <tr>
                                        <td style="text-align: right;"><b>SGST:</b></td>
                                        <td style="text-align: right;"><b><?= $file_detail->SGST_Amount; ?>%</b></td>
                                    </tr>
                                <?php }
                                if ($file_detail->GST_IGST_Amount != '0.00') { ?>
                                    <tr>
                                        <td style="text-align: right;"><b>IGST:</b></td>
                                        <td style="text-align: right;"><b><?= $file_detail->GST_IGST_Amount; ?>%</b></td>
                                    </tr>
                                <?php }
                                if ($file_detail->Cess != '0.00') { ?>
                                    <tr>
                                        <td style="text-align: right;"><b>Cess:</b></td>
                                        <td style="text-align: right;"><b><?= $file_detail->Cess; ?>%</b></td>
                                    </tr>
                                <?php }
                                if ($file_detail->TCS != '0.00') { ?>
                                    <tr>
                                        <td style="text-align: right;"><b>TCS:</b></td>
                                        <td style="text-align: right;"><b><?= $file_detail->TCS; ?>%</b></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td style="text-align: right;"><b>Total:</b></td>
                                    <td style="text-align: right;"><b><?= $file_detail->Total_Amount; ?></b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;"><b>Discount:</b></td>
                                    <td style="text-align: right;"><b><?= $file_detail->Total_Discount; ?></b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;"><b>Grand Total:</b></td>
                                    <td style="text-align: right;"><b><?= $file_detail->Grand_Total; ?></b></td>
                                </tr>
                                <tr>
                                    <td>Remarks :</td>
                                    <td colspan="3" style="text-align:left;"><?= $file_detail->Remark ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } elseif ($doc_type_id == 44) { ?>
                    <div class="padding-1">
                        <table class="table borderless table-pro">
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Description</b></td>
                                <td>:&emsp;<?= $file_detail->FileName ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 43) { ?>
                    <div class="padding-1">
                        <table class="table borderless table-pro">
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Bill Date</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Description</b></td>
                                <td>:&emsp;<?= $file_detail->FileName ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 42) { ?>
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->DueDate)) ?></td>
                            </tr>

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                                <td>:&emsp;<?= $file_detail->Remark ?></td>
                            </tr>
                        </table>
                    </div>
                <?php } elseif ($doc_type_id == 40) { ?>
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td class="text-dark" style="width: 20%;"><b>Account No</b></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 39) { ?>
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 38) { ?>
                    <!-- RST OFD -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 34) { ?>
                    <!-- Postage Courier -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

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
                    </div>
                <?php } elseif ($doc_type_id == 33) { ?>
                    <!-- Postage Courier -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->DueDate)) ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 29) { ?>
                    <!-- Meals -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b> Bill Date</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
                            </tr>

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Detail</b></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 28) { ?>
                    <!-- Lodging -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b> Bill Date</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->BillDate)) ?></td>
                            </tr>

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Bill No.</b></td>
                                <td>:&emsp;<?= $file_detail->File_No ?></td>
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
                                <td class="text-dark" style="width: 20%;"><b>Hotel Name</b></td>
                                <td>:&emsp;<?= $file_detail->ToName ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Hotel Address</b></td>
                                <td>:&emsp;<?= $file_detail->Loc_Add ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>City Category</b></td>
                                <td>:&emsp;<?= $file_detail->File_Type ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Billing Instruction</b></td>
                                <td>:&emsp;<?= $file_detail->NatureOfPayment ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Arrival Date / Time</b></td>
                                <td>:&emsp;<?= $file_detail->FromDateTime ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Departure Date / Time</b></td>
                                <td>:&emsp;<?= $file_detail->ToDateTime ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Duration of Stay</b></td>
                                <td>:&emsp;<?= $file_detail->Period ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Room Rate/Type</b></td>
                                <td>:&emsp;<?= $file_detail->TariffPlan ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Meal Plan</b></td>
                                <td>:&emsp;<?= $file_detail->Loc_Name ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>No. of Person</b></td>
                                <td>:&emsp;<?= $file_detail->NoOfFarmers ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>GST/Tax Rate</b></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 27) { ?>
                    <!-- Local Conveyance -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b> Mode</b></td>
                                <td>:&emsp;<?= $file_detail->TravelMode ?></td>
                            </tr>

                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Trip Started On</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Trip Ended On</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 22) { ?>
                    <!-- Insurance Policy -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

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
                    </div>
                <?php } elseif ($doc_type_id == 26) { ?>
                    <!-- Lease Rent -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

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
                    </div>
                <?php } elseif ($doc_type_id == 25) { ?>
                    <!-- jeep_campaign -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 24) { ?>
                    <!-- IT Return -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 7) { ?>
                    <!-- Cash Voucher -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 16) { ?>
                    <!-- Challan -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td class="text-dark" style="width: 20%;"><b>Period of Payment</b></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 9) { ?>
                    <!-- Cheque -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 12) { ?>
                    <!-- Dealer Meeting -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 21) { ?>
                    <!-- Insurance Document -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->DueDate)) ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 13) { ?>
                    <!-- Electricity -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                                <td class="text-dark" style="width: 20%;"><b>Remark</b></td>
                                <td>:&emsp;<?= $file_detail->Remark ?></td>
                            </tr>
                        </table>
                    </div>
                <?php } elseif ($doc_type_id == 14) { ?>
                    <!-- FD FV -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
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
                    </div>
                <?php } elseif ($doc_type_id == 15) { ?>
                    <!-- Fixed Deposit -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

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
                    </div>
                <?php } elseif ($doc_type_id == 20) { ?>
                    <!-- Income Tax TDS -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

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
                    </div>
                <?php } elseif ($doc_type_id == 17) { ?>
                    <!-- Hired Vehicle -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">

                            <tr>
                                <td class="text-dark" style="width: 40%;"><b>Invoice No.</b></td>
                                <td>:&emsp;<?= $file_detail->File_No ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Date of Travel</b></td>
                                <td>:&emsp;<?= date('Y-m-d', strtotime($file_detail->File_Date)) ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Vehicle Class/Registration No.</b></td>
                                <td>:&emsp;<?= $file_detail->VehicleRegNo ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Agency Name</b></td>
                                <td>:&emsp;<?= $file_detail->AgentName ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Agency Address</b></td>
                                <td>:&emsp;<?= $file_detail->AgencyAddress ?></td>
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
                                <td class="text-dark" style="width: 20%;"><b>Journey Start Date</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->FromDateTime)) ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Journey End Date</b></td>
                                <td>:&emsp;<?= date('d-m-Y', strtotime($file_detail->ToDateTime)) ?></td>
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
                                <td class="text-dark" style="width: 20%;"><b>Charges (Daily basis) *</b></td>
                                <td>:&emsp;<?= $file_detail->HiredVehicle_Amount ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Charges (Km basis) *</b></td>
                                <td>:&emsp;<?= $file_detail->AVTent_Amount ?></td>
                            </tr>
                            <tr>
                                <td class="text-dark" style="width: 20%;"><b>Driver Charges</b></td>
                                <td>:&emsp;<?= $file_detail->DriverCharges ?></td>
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
                    </div>
                <?php } elseif ($doc_type_id == 46) { ?>
                    <!-- GST Challan -->
                    <div class="padding-1">
                        <table class="table borderless table-pro">
                            <tr>
                                <td><b>CPIN:</b></td>
                                <td><?= $file_detail->CPIN; ?></td>
                                <td><b>Deposit Date :</b></td>
                                <td><?= date('d-m-Y', strtotime($file_detail->File_Date)) ?></td>
                            </tr>
                            <tr>
                                <td><b>CIN :</b></td>
                                <td><?= $file_detail->CIN ?></td>
                                <td><b>Bank Name :</b></td>
                                <td><?= $file_detail->BankName ?></td>
                            </tr>
                            <tr>
                                <td><b>BRN:</b></td>
                                <td><?= $file_detail->BankBSRCode; ?></td>
                                <td><b>GSTIN :</b></td>
                                <td><?= $file_detail->GSTIN ?></td>
                            </tr>
                            <tr>
                                <td><b>Email ID :</b></td>
                                <td><?= $file_detail->Email ?></td>
                                <td><b>Mobile No :</b></td>
                                <td><?= $file_detail->MobileNo ?></td>
                            </tr>
                            <tr>
                                <td><b>Company Name:</b></td>
                                <td><?= $file_detail->Company; ?></td>
                                <td><b>Company Address:</b></td>
                                <td><?= $file_detail->Related_Address ?></td>

                            </tr>
                        </table>
                        <div class="table-responsive">
                            <table class="table text-center" border="1" style="border:1px solid #ddd;margin-top:10px;font-size:12px;">
                                <thead class="bg-primary">
                                    <th>Tax(₹)</th>
                                    <th>Particular</th>
                                    <th>Interest(₹)</th>
                                    <th>Penalty(₹)</th>
                                    <th>Fees(₹)</th>
                                    <th>Other(₹)</th>
                                    <th>Total(₹)</th>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($doc_type_id == 46) {
                                        $get_gst_challan_detail = $this->db->query("select * from gst_challan_detail where scan_id='$scan_id'")->result();

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
                                        <?php  } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center;">No Record Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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
                <?php } ?>
                <?php if ($this->customlib->haveSupportFile($scan_id) == 1) { ?>
                    <div class="row" style="margin-top: 10px;padding:0 10px;">
                        <div class="col-md-12">
                            <table class="table" style="border: 1px solid #d4d0d0;font-size:12px;">
                                <tr>
                                    <td class="bg-primary"><b>Supporting File:</b></td>
                                </tr>
                                <?php $support_file = $this->customlib->getSupportFile($scan_id);
                                foreach ($support_file as $row) {  ?>
                                    <tr>
                                        <td><a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a></td>
                                    </tr>
                                <?php   }  ?>
                            </table>

                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>

    </div>
    <script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>