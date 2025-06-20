<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-plus"></i> Create New Approval Rule</h3>
                    </div>
                    <div class="box-body pb-0">
                        <form id="approvalRuleForm">
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Core Details: <span class="text-muted mb-0" style="font-size: 12px;">Core parameters
                                            defining the scope and nature of this
                                            approval rule.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Function</label>
                                                <select style="width:75%" class="form-control" id="function" required>
                                                    <option value="">Select function</option>
                                                    <option value="HR">HR</option>
                                                    <option value="Finance">Finance</option>
                                                    <option value="Operations">Operations</option>
                                                </select>
                                                <span class="error" id="functionError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Department</label>
                                                <select style="width:75%" class="form-control" id="department" required>
                                                    <option value="">Select department</option>
                                                    <option value="Payroll">Payroll</option>
                                                    <option value="Accounting">Accounting</option>
                                                    <option value="Logistics">Logistics</option>
                                                </select>
                                                <span class="error" id="departmentError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Sub Dept.</label>
                                                <select style="width:75%" class="form-control" id="subDepartment"
                                                    required>
                                                    <option value="">Select sub-department</option>
                                                    <option value="Benefits">Benefits</option>
                                                    <option value="Tax">Tax</option>
                                                    <option value="Transport">Transport</option>
                                                </select>
                                                <span class="error" id="subDepartmentError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Scope Parameters: <span class="text-muted mb-0" style="font-size: 12px;">Define the
                                            operational context for which this rule applies.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Crop</label>
                                                <select style="width:75%" class="form-control" id="crop" required>
                                                    <option value="">Select crop</option>
                                                    <option value="Wheat">Wheat</option>
                                                    <option value="Corn">Corn</option>
                                                    <option value="Rice">Rice</option>
                                                </select>
                                                <span class="error" id="cropError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Activity</label>
                                                <select style="width:75%" class="form-control" id="activity" required>
                                                    <option value="">Select activity</option>
                                                    <option value="Planting">Planting</option>
                                                    <option value="Harvesting">Harvesting</option>
                                                    <option value="Processing">Processing</option>
                                                </select>
                                                <span class="error" id="activityError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Location</label>
                                                <select style="width:75%" class="form-control" id="location" required>
                                                    <option value="">Select location</option>
                                                    <option value="Farm A">Farm A</option>
                                                    <option value="Farm B">Farm B</option>
                                                    <option value="Farm C">Farm C</option>
                                                </select>
                                                <span class="error" id="locationError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Organizational Location: <span class="text-muted mb-0" style="font-size: 12px;">Specify
                                            the regional and business unit applicability.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Region</label>
                                                <select style="width:75%" class="form-control" id="region" required>
                                                    <option value="">Select region</option>
                                                    <option value="North">North</option>
                                                    <option value="South">South</option>
                                                    <option value="West">West</option>
                                                </select>
                                                <span class="error" id="regionError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Zone</label>
                                                <select style="width:75%" class="form-control" id="zone" required>
                                                    <option value="">Select zone</option>
                                                    <option value="Zone 1">Zone 1</option>
                                                    <option value="Zone 2">Zone 2</option>
                                                    <option value="Zone 3">Zone 3</option>
                                                </select>
                                                <span class="error" id="zoneError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Business Unit</label>
                                                <select style="width:75%" class="form-control" id="businessUnit"
                                                    required>
                                                    <option value="">Select business unit</option>
                                                    <option value="Unit A">Unit A</option>
                                                    <option value="Unit B">Unit B</option>
                                                    <option value="Unit C">Unit C</option>
                                                </select>
                                                <span class="error" id="businessUnitError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Financial Parameters & Bill Type: <span
                                            class="text-muted mb-0" style="font-size: 12px;">Set financial thresholds and relevant document
                                            types.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Ledger</label>
                                                <select style="width:75%" class="form-control" id="ledger" required>
                                                    <option value="">Select ledger</option>
                                                    <option value="General">General</option>
                                                    <option value="Accounts Payable">Accounts Payable</option>
                                                    <option value="Accounts Receivable">Accounts Receivable</option>
                                                </select>
                                                <span class="error" id="ledgerError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Subledger</label>
                                                <select style="width:75%" class="form-control" id="subledger" required>
                                                    <option value="">Select subledger</option>
                                                    <option value="Subledger 1">Subledger 1</option>
                                                    <option value="Subledger 2">Subledger 2</option>
                                                    <option value="Subledger 3">Subledger 3</option>
                                                </select>
                                                <span class="error" id="subledgerError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Bill Type</label>
                                                <select style="width:75%" class="form-control" id="billType" required>
                                                    <option value="">Select bill type</option>
                                                    <option value="Invoice">Invoice</option>
                                                    <option value="PO">Purchase Order</option>
                                                    <option value="Expense">Expense</option>
                                                </select>
                                                <span class="error" id="billTypeError"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <label style="width: 20%;font-size: 12px;">Min Amount</label>
                                                <input style="width: 80%;" type="number" class="form-control" id="minAmount"
                                                    placeholder="Enter Min Amount" min="0" required>
                                                <span class="error" id="minAmountError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <label style="width: 20%;font-size: 12px;">Max Amount</label>
                                                <input style="width: 80%;" type="number" class="form-control" id="maxAmount"
                                                    placeholder="Enter Max Amount" min="0" required>
                                                <span class="error" id="maxAmountError"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Approval Levels: <span class="text-muted mb-0" style="font-size: 12px;">Assign the
                                            sequence of approvers for this rule.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">L1 Approver</label>
                                                <select style="width:75%" class="form-control" id="l1Approver" required>
                                                    <option value="">Select L1 Approver</option>
                                                    <option value="User1">User 1</option>
                                                    <option value="User2">User 2</option>
                                                    <option value="User3">User 3</option>
                                                </select>
                                                <span class="error" id="l1ApproverError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">L2 Approver</label>
                                                <select style="width:75%" class="form-control" id="l2Approver">
                                                    <option value="">Select L2 Approver</option>
                                                    <option value="User4">User 4</option>
                                                    <option value="User5">User 5</option>
                                                    <option value="User6">User 6</option>
                                                </select>
                                                <span class="error" id="l2ApproverError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">L3 Approver</label>
                                                <select style="width:75%" class="form-control" id="l3Approver">
                                                    <option value="">Select L3 Approver</option>
                                                    <option value="User7">User 7</option>
                                                    <option value="User8">User 8</option>
                                                    <option value="User9">User 9</option>
                                                </select>
                                                <span class="error" id="l3ApproverError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Validity Period: <span class="text-muted mb-0" style="font-size: 12px;">Specify the
                                            dates when this approval rule will be active.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Valid From</label>
                                                <input type="date" class="form-control" id="validFrom" required>
                                                <span class="error" id="validFromError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Valid To</label>
                                                <input type="date" class="form-control" id="validTo" required>
                                                <span class="error" id="validToError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default" onclick="resetForm()">Reset</button>
                                <button type="submit" class="btn btn-primary pull-right">Save Rule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $("select").select2();
</script>