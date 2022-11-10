<div id="snackbar"></div>
<div id="snackbar_success"></div>
<div class="wrap-1000">
    <div class="card padding-20">
        <div class="support-details">
            <div class="support-form mt0">
                <h4>Thank you so much for using this Smart Cart Upsell App. Your satisfaction is our number one concern. If you have any issues, questions, or concerns, please fill out the form below and we will get back to you as soon as possible.</h4>
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
                            <label>Website URL:</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" id="website_link" name="website_link" class="form-control">
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
                                email:true,
                            },
                            "website_link": {
                                required: true
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
                            "website_link": {
                                required: "Please enter website URL.",
                            },
                            "message": {
                                required: "Please enter message.",
                            },
                        },
                        submitHandler: function (form) {

                            jQuery.ajax({
                                type: 'POST',
                                url: '<?= site_url('support/create') ?>',
                                data: jQuery("#contact_us").serialize(),
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    jQuery(".page-loader").show();
                                    jQuery('.btn-custom').text('Sending');
                                    jQuery('.btn-custom').attr('disabled', true);
                                },
                                success: function (data) {
                                    jQuery("#contact_us")[0].reset();
                                    jQuery('.btn-custom').attr('disabled', false);
                                    jQuery(".page-loader").hide();
                                    if (data.status == "success") {
                                        jQuery('#snackbar_success').html('Record saved successfully!!');
                                    } else {
                                        jQuery('#snackbar_success').html('Something went wrong. Please try again later..!!');
                                    }
                                    var x = document.getElementById("snackbar_success");
                                    x.className = "show";
                                    setTimeout(function () {
                                        x.className = x.className.replace("show", "");
                                        location.reload();
                                    }, 3000);
                                }
                            });
                        }
                    });
</script>