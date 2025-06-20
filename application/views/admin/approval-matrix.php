<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid1 box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-dashboard"></i> Approval Rules Overview</h3>
                        <div class="box-tools">
                            <a href="<?php echo site_url('approval-matrix/add'); ?>" class="btn btn-primary btn-sm pull-right">Create New Rule</a>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Function</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Department</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Sub-Dep</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Ledger</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Zone</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Region</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Bill Type</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control">
                                                <option>Status</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button class="btn btn-secondary btn-sm form-control">Reset
                                                Filters</button>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Rule ID</th>
                                            <th>Function</th>
                                            <th>Department</th>
                                            <th>Ledger</th>
                                            <th>Amount Range</th>
                                            <th>Bill Type</th>
                                            <th>Approver Levels</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>AR-001</td>
                                            <td>Procurement</td>
                                            <td>Finance</td>
                                            <td>Operating</td>
                                            <td>₹0 - ₹1000</td>
                                            <td>Invoice</td>
                                            <td>L1, L2</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-002</td>
                                            <td>Sales</td>
                                            <td>Marketing</td>
                                            <td>Revenue</td>
                                            <td>₹1001 - ₹5000</td>
                                            <td>Expense Report</td>
                                            <td>L1, L3</td>
                                            <td><span class="label label-danger">Inactive</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-003</td>
                                            <td>HR</td>
                                            <td>HR Operations</td>
                                            <td>Payroll</td>
                                            <td>₹5001 - ₹10,000</td>
                                            <td>Travel Claim</td>
                                            <td>L2, L4</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-004</td>
                                            <td>IT</td>
                                            <td>Infrastructure</td>
                                            <td>Capital</td>
                                            <td>₹10,001 - ₹50,000</td>
                                            <td>Purchase Order</td>
                                            <td>L3, L5</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-005</td>
                                            <td>Legal</td>
                                            <td>Compliance</td>
                                            <td>Operating</td>
                                            <td>₹0 - ₹1</td>
                                            <td>Contract</td>
                                            <td>L4, L6</td>
                                            <td><span class="label label-danger">Inactive</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-006</td>
                                            <td>Procurement</td>
                                            <td>Supply Chain</td>
                                            <td>Operating</td>
                                            <td>₹0 - ₹1000</td>
                                            <td>Invoice</td>
                                            <td>L1</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-007</td>
                                            <td>Finance</td>
                                            <td>Treasury</td>
                                            <td>Revenue</td>
                                            <td>₹1001 - ₹5000</td>
                                            <td>Payment Request</td>
                                            <td>L2</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-008</td>
                                            <td>Operations</td>
                                            <td>Logistics</td>
                                            <td>Operating</td>
                                            <td>₹10,001 - ₹10,000</td>
                                            <td>Service Agreement</td>
                                            <td>L1, L2</td>
                                            <td><span class="label label-danger">Inactive</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-009</td>
                                            <td>Customer Service</td>
                                            <td>Support</td>
                                            <td>Operating</td>
                                            <td>₹0 - ₹1000</td>
                                            <td>Refund Request</td>
                                            <td>L1</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-010</td>
                                            <td>R&D</td>
                                            <td>Engineering</td>
                                            <td>Capital</td>
                                            <td>₹10,001 - ₹90,000</td>
                                            <td>Software License</td>
                                            <td>L3, L4</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>AR-011</td>
                                            <td>Marketing</td>
                                            <td>Brand</td>
                                            <td>Operating</td>
                                            <td>₹0 - ₹1000</td>
                                            <td>Advertisement</td>
                                            <td>L1</td>
                                            <td><span class="label label-success">Active</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>