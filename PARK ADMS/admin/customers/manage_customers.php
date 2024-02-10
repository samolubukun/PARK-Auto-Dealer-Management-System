<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `customers` where CustomerID = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
$modelListQuery = $conn->query("SELECT * FROM `model_list`");
$modelList = $modelListQuery->fetch_all(MYSQLI_ASSOC);
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
    <h4 class="font-wight-bolder"><?= isset($CustomerID) ? "Update Customer Details" : "New Customer Entry" ?></h4>
</div>
<div class="row mt-n4 align-items-center justify-content-center flex-column">
    <div class="col-lg-10 col-md-11 col-sm-12 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <form action="" id="customer-form">
                        <input type="hidden" name="CustomerID" value="<?php echo isset($CustomerID) ? $CustomerID : '' ?>">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="FirstName" class="control-label">First Name</label>
                                <input type="text" name="FirstName" id="FirstName" class="form-control form-control-sm rounded-0" value="<?php echo isset($FirstName) ? $FirstName : ''; ?>" required />
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="LastName" class="control-label">Last Name</label>
                                <input type="text" name="LastName" id="LastName" class="form-control form-control-sm rounded-0" value="<?php echo isset($LastName) ? $LastName : ''; ?>" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="Address" class="control-label">Address</label>
                                <input type="text" name="Address" id="Address" class="form-control form-control-sm rounded-0" value="<?php echo isset($Address) ? $Address : ''; ?>" required />
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="PhoneNumber" class="control-label">Phone Number</label>
                                <input type="text" name="PhoneNumber" id="PhoneNumber" class="form-control form-control-sm rounded-0" value="<?php echo isset($PhoneNumber) ? $PhoneNumber : ''; ?>" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="Email" class="control-label">Email</label>
                                <input type="email" name="Email" id="Email" class="form-control form-control-sm rounded-0" value="<?php echo isset($Email) ? $Email : ''; ?>" required />
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="DateOfVisit" class="control-label">Date of Visit</label>
                                <input type="date" name="DateOfVisit" id="DateOfVisit" class="form-control form-control-sm rounded-0" value="<?php echo isset($DateOfVisit) ? $DateOfVisit : ''; ?>" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="ModelOfInterest" class="control-label">Model of Interest</label>
                                <!-- Replace the input field with a select dropdown -->
                                <select name="ModelOfInterest" id="ModelOfInterest" class="form-control form-control-sm rounded-0" required>
                                    <?php foreach ($modelList as $model): ?>
                                        <option value="<?= $model['model'] ?>" <?php echo isset($ModelOfInterest) && $ModelOfInterest == $model['model'] ? 'selected' : ''; ?>>
                                            <?= $model['model'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                        <!-- Move the closing div tag for the row here -->
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="LeadSource" class="control-label">Lead Source</label>
                            <select name="LeadSource" id="LeadSource" class="form-control form-control-sm rounded-0" required>
                                <option value="NewsPaper Adverts" <?php echo isset($LeadSource) && $LeadSource === 'NewsPaper Adverts' ? 'selected' : ''; ?>>NewsPaper Adverts</option>
                                <option value="Word Of Mouth" <?php echo isset($LeadSource) && $LeadSource === 'Word Of Mouth' ? 'selected' : ''; ?>>Word Of Mouth</option>
                                <option value="Website Visit" <?php echo isset($LeadSource) && $LeadSource === 'Website Visit' ? 'selected' : ''; ?>>Website Visit</option>
                            </select>
                        </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-1 text-center">
                <button class="btn btn-flat btn-sm btn-navy bg-gradient-navy" form="customer-form"><i class="fa fa-save"></i> Save</button>
                <?php if (isset($CustomerID)): ?>
                    <a class="btn btn-flat btn-sm btn-light bg-gradient-light border" href="./?page=customers/view_customer&id=<?= isset($CustomerID) ? $CustomerID : '' ?>"><i class="fa fa-angle-left"></i> Cancel</a>
                <?php else: ?>
                    <a class="btn btn-flat btn-sm btn-light bg-gradient-light border" href="./?page=customers"><i class="fa fa-angle-left"></i> Cancel</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    $('#customer-form').submit(function (e) {
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_customer",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.replace('./?page=customers/view_customers&id=' + resp.pid);
                } else if (resp.status == 'failed' && !!resp.msg) {
                    var el = $('<div>');
                    el.addClass("alert alert-danger err-msg").text(resp.msg);
                    _this.prepend(el);
                    el.show('slow');
                    $("html, body").scrollTop(0);
                } else {
                    alert_toast("An error occurred", 'error');
                }
                end_loader();
            }
        });
    });
});

</script>
