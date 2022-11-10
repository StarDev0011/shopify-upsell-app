<?php 
    if (isset($_POST['stripeToken'])) {
        try {
            $this->stripe->customers->createSource(
                $this->customer_id,
                ['source' => $_POST['stripeToken']]
            );
        } catch (Exception $e) {
            echo $e;
        }
    }
    if (isset($_POST['delete-stripe-card'])) {
        $this->stripe->customers->deleteSource(
            $this->customer_id,
            $this->stripe->customers->retrieve($this->customer_id, [])->default_source,
            []
        );               
    }
?>
<div id="snackbar"></div>
<div id="snackbar_success"></div>
<div class="wrap-1000">
    <div class="card padding-20 setting-card">
        <h3 class="title">App Settings</h3>        
        <div class="response"></div>
        <form id="setting-form" onsubmit="return false;">
            <div class="steps step2">
                <div class="row" style="display:none">
                    <div class="col-md-6">
                        <div class="form-group">                        
                            <label class="custom-label" for="default_offer_title"><?php echo lang('default_offer_title') ?></label>
                            <!-- <input class="form-control" maxlength="150" type="text" name="default_offer_title" id="default_offer_title" value="<?php echo!empty($records->default_offer_title) ? $records->default_offer_title : '' ?>"> -->
                            <input class="form-control" maxlength="150" type="text" name="default_offer_title" id="default_offer_title" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">                        
                            <label class="custom-label" for="default_offer_description"><?php echo lang('default_offer_desc') ?></label>
                            <textarea class="form-control form-item" name="default_offer_description" id="default_offer_description" rows="3"></textarea>
                            <!-- <textarea class="form-control form-item" name="default_offer_description" id="default_offer_description" rows="3"><?php echo!empty($records->default_offer_description) ? $records->default_offer_description : '' ?></textarea> -->
                        </div>
                    </div>
                </div>
                <!-- <div class="devider"></div> -->
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="check-container">
                            <label class="fancy-checkbox">
                                <input type="checkbox" name="show_sku_product" value="1" <?php echo!empty($records->show_sku_product) ? ($records->show_sku_product == 1 ? 'checked' : '') : '' ?>>
                                <span><i></i><p class="chk-label"><?php echo lang('show_sku_product') ?></p></span>
                            </label>
                            <p><?php echo lang('show_sku_product_desc') ?></p>
                        </div>                            
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <?php
                        $show_no_thank_link = 'checked';
                        if (isset($records->show_no_thank_link)) {
                            $show_no_thank_link = '';
                            if ($records->show_no_thank_link == 1)
                                $show_no_thank_link = 'checked';
                        }
                        ?>
                        <div class="check-container">
                            <label class="fancy-checkbox">
                                <p style="color: red; font-size: 13px; font-weight: 700; margin-bottom: 8px; margin-top: -14px;">Recommended: Keep Checked to Give Customer Options</p>
                                <input type="checkbox" name="show_no_thank_link" value="1" <?php echo $show_no_thank_link ?>>
                                <span><i></i><p class="chk-label"><?php echo lang('show_no_thank_link') ?></p></span>
                            </label>
                            <p><?php echo lang('show_no_thank_link_desc') ?></p>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="shop_id" name="shop_id" value="<?= $this->shopId; ?>">
                <input type="hidden" name="id" id="id" value="<?= !empty($records->id) ? $records->id : '' ?>">
                <div class="text-right mt20">
                    <button type="submit" class="btn btn-custom smar7-btn next-btn-dic next-btn width-150" curr="step2" next="step3" ><?php echo lang('submit') ?></button>
                </div>
            </div>
        </form>

    </div>
</div>

<!--
<div class="wrap-1000">
    <div class="card padding-20 setting-card">
        <h3 class="title">Billing Settings</h3>
        <?php
            if (isset($this->stripe->customers->retrieve($this->customer_id, [])->default_source) && !isset($_POST['delete-stripe-card'])): ?>
                <p>Payment info is set.</p>
                <form class="mt20" method="POST">
                    <button class="btn btn-custom smar7-btn next-btn-dic next-btn width-150" type="submit" name="delete-stripe-card">Remove Card</button>
                </form>
            <?php else: ?>
                <form method="post" id="payment-form">
                    <div class="form-row">
                        <label for="card-element">
                        Credit or debit card
                        </label>
                        <div id="card-element">
                        </div>

                        <div id="card-errors" role="alert"></div>
                    </div>

                    <button class="btn btn-custom smar7-btn next-btn-dic next-btn width-150 mt20">Save</button>
                </form>
                

                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    var stripe = Stripe('<?= $this->stripePublic ?>');
                    var elements = stripe.elements();
                    var card = elements.create('card');
                    card.mount('#card-element');
                    // Create a token or display an error when the form is submitted.
                    var form = document.getElementById('payment-form');
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();

                        stripe.createToken(card).then(function(result) {
                            if (result.error) {
                            // Inform the customer that there was an error.
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                            } else {
                            // Send the token to your server.
                            stripeTokenHandler(result.token);
                            }
                        });
                    });
                    function stripeTokenHandler(token) {
                        // Insert the token ID into the form so it gets submitted to the server
                        var form = document.getElementById('payment-form');
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', token.id);
                        form.appendChild(hiddenInput);

                        // Submit the form
                        form.submit();
                    }
                </script>
        <?php endif; ?>
    </div>
</div>-->

<script>
    jQuery.noConflict();
    jQuery(document).ready(function (e) {
        jQuery('#setting-form').on('submit', function (e) {
            data = jQuery(this).serialize();
            jQuery('.next-btn').attr('disabled', true);
            jQuery(".page-loader").show();
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('settings/update_setting') ?>',
                data: data,
                dataType: 'json',
                success: function (data) {
                    jQuery(".page-loader").hide();
                    if (data.status == "success") {
                        jQuery('#snackbar_success').html('Settings updated successfully..!!');
                    } else {
                        jQuery('#snackbar_success').html('Something went wrong. Please try again later..!!');
                    }
                    var x = document.getElementById("snackbar_success");
                    x.className = "show";
                    setTimeout(function () {
                        x.className = x.className.replace("show", "");
                    }, 3000);
                    jQuery('.next-btn').attr('disabled', false);
                    //window.location.href = "<?= site_url('settings/index/') ?>";
                }
            });
        });
    });
</script>