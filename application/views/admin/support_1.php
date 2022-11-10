<div id="snackbar"></div>
<div id="snackbar_success"></div>
<div class="wrap-1000">
    <div class="card padding-20">
        <div class="support-details">
            <h3>How it works?</h3>
            <iframe width="100%" height="400" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
            <div class="support-form">
                <h3>Contact us</h3>
                <form id="contact_us" method="post" onsubmit="return false;">
                    <div class="support-form-row">
                        <div class="col-md-2">
                            <label>Name:</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="support-form-row">
                        <div class="col-md-2">
                            <label>Email:</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" id="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="support-form-row">
                        <div class="col-md-2">
                            <label>Message:</label>
                        </div>
                        <div class="col-md-10">
                            <textarea id="message" name="message" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="support-form-row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-custom width-150">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script>
                    jQuery.noConflict();
                    jQuery("#contact_us").validate({
                        focusInvalid: false, // do not focus the last invalid input
                        errorElement: 'span',
                        errorClass: 'help-block', // default input error message class
                        ignore: [],
                        rules: {
                            "name": {
                                required: true,
                                maxlength: 100,
                            },
                            "email": {
                                required: true,
                            },
                            "message": {
                                required: true,
                            },
                        },
                        messages: {
                            "name": {
                                required: "Please enter name.",
                            },
                            "email": {
                                required: "Please enter email.",
                            },
                            "message": {
                                required: "Please enter message.",
                            },
                        },
                        submitHandler: function (form) {
                            jQuery(".page-loader").show();
                            jQuery('.btn-custom').attr('disabled', true);
                            jQuery.ajax({
                                type: 'POST',
                                url: '<?= site_url('support/create') ?>',
                                data: $("#contact_us").serialize(),
                                dataType: 'json',
                                async: false,
                                success: function (data) {
                                    jQuery('.btn-custom').attr('disabled', false);
                                    jQuery(".page-loader").hide();
                                    if (data.status == "success") {
                                        jQuery('#snackbar_success').html('Mail sent successfully!!');
                                    } else {
                                        jQuery('#snackbar_success').html('Something went wrong. Please try again later..!!');
                                    }
                                    var x = document.getElementById("snackbar_success");
                                    x.className = "show";
                                    location.reload();
                                    setTimeout(function () {
                                        x.className = x.className.replace("show", "");
                                        
                                    }, 3000);
                                }
                            });
                        }
                    });
</script>