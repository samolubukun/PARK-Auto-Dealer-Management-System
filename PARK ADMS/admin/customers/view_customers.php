<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `customers` where CustomerID = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    } else {
        echo '<script>alert("Customer ID is not valid."); location.replace("./?page=customers")</script>';
    }
} else {
    echo '<script>alert("Customer ID is Required."); location.replace("./?page=customers")</script>';
}
?>
<style>
    legend.legend-sm {
        font-size: 1.4em;
    }

    #cimg {
        max-width: 100%;
        max-height: 20em;
        object-fit: scale-down;
        object-position: center center;
    }
</style>
<div class="content py-5 px-3 bg-gradient-navy">
    <h4 class="font-wight-bolder"><?= isset($CustomerID) ? "Customer Details" : "Invalid Customer ID" ?></h4>
</div>
<div class="row mt-n4 align-items-center justify-content-center flex-column">
    <div class="col-lg-10 col-md-11 col-sm-12 col-xs-12">
        <?php if (isset($CustomerID)): ?>
            <div class="card rounded-0 shadow">
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="FirstName" class="control-label">First Name:</label>
                                <div><?= isset($FirstName) ? $FirstName : '' ?></div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="LastName" class="control-label">Last Name:</label>
                                <div><?= isset($LastName) ? $LastName : '' ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="Address" class="control-label">Address:</label>
                                <div><?= isset($Address) ? $Address : '' ?></div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="PhoneNumber" class="control-label">Phone Number:</label>
                                <div><?= isset($PhoneNumber) ? $PhoneNumber : '' ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="Email" class="control-label">Email:</label>
                                <div><?= isset($Email) ? $Email : '' ?></div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="DateOfVisit" class="control-label">Date of Visit:</label>
                                <div><?= isset($DateOfVisit) ? date("Y-m-d", strtotime($DateOfVisit)) : '' ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="ModelOfInterest" class="control-label">Model of Interest:</label>
                                <div><?= isset($ModelOfInterest) ? $ModelOfInterest : '' ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="LeadSource" class="control-label">Lead Source:</label>
                                <div><?= $LeadSource ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-1 text-center">
                    <a class="btn btn-flat btn-sm btn-light bg-gradient-light border" href="./?page=customers"><i class="fa fa-angle-left"></i> Back to List</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
