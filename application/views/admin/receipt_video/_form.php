<link href="<?php echo site_url(); ?>assets/admin/colorpicker/css/colorpicker.css" rel="stylesheet" media="all" />
<link href="<?php echo site_url(); ?>assets/admin/colorpicker/css/layout.css" rel="stylesheet" media="all" />
<div id="snackbar"></div>
<div id="snackbar_success"></div>
<div class="wrap-1000">
    <div class="card padding-20">
        <div class="support-details">
            <div class="support-form mt0">
                <form id="receipt_video_form" method="post" onsubmit="return false;">
                    <span class="note-text"><b>Note: Please use embeded video url only to make it work. You can add one or multiple videos.</b></span>
                    <div class="support-form-group">
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>Title:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="title" name="title" class="form-control" value="<?php echo!empty($result->title) ? $result->title : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="support-form-group">
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>URL of Video:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="video_url_1" name="video_url_1" class="form-control" value="<?php echo!empty($result->video_url_1) ? $result->video_url_1 : ''; ?>">
                            </div>
                            <div class="col-md-3 click-count-no">
                                <div class="video-url-count">
                                    <label>Total No. of clicks: <span><?php echo!empty($result) ? $result->count_url_1 : 0; ?></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>Redirection URL:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="redirection_url_1" name="redirection_url_1" class="form-control" value="<?php echo!empty($result->redirection_url_1) ? $result->redirection_url_1 : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="support-form-group">
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>URL of Video:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="video_url_2" name="video_url_2" class="form-control" value="<?php echo!empty($result->video_url_2) ? $result->video_url_2 : ''; ?>">
                            </div>
                            <div class="col-md-3 click-count-no">
                                <div class="video-url-count">
                                    <label>Total No. of clicks: <span><?php echo!empty($result) ? $result->count_url_2 : 0; ?></span></label>
                                </div>
                            </div>
                        </div>                    
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>Redirection URL:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="redirection_url_2" name="redirection_url_2" class="form-control" value="<?php echo!empty($result->redirection_url_2) ? $result->redirection_url_2 : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="support-form-group">
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>URL of Video:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="video_url_3" name="video_url_3" class="form-control" value="<?php echo!empty($result->video_url_3) ? $result->video_url_3 : ''; ?>">
                            </div>
                            <div class="col-md-3 click-count-no">
                                <div class="video-url-count">
                                    <label>Total No. of clicks: <span><?php echo!empty($result) ? $result->count_url_3 : 0; ?></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>Redirection URL:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="redirection_url_3" name="redirection_url_3" class="form-control" value="<?php echo!empty($result->redirection_url_3) ? $result->redirection_url_3 : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="support-form-row">
                        <div class="support-form-row">
                            <div class="col-md-2">
                                <label>Button Text:</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="button_text" name="button_text" class="form-control" value="<?php echo!empty($result->button_text) ? $result->button_text : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>Button style:</label>
                        </div>
                        <?php
                        $buttonval = 'style="background-color:#ddd; color: #49bd23; border: 2px solid #49bd23; height: auto; text-decoration:none; text-transform: uppercase; padding: 12px 20px;min-width:200px; text-align: center; display: inline-block;"';
                        if (!empty($result))
                        {
                            $buttonval = $this->ReceiptVideo->buttonStyle[ $result->button_style ];
                            if ($result->button_style == 0)
                            {
                                $buttonval = str_replace('#49bd23',
                                                         $result->button_color,
                                                         $buttonval);
                            }
                            else
                            {
                                $buttonval = str_replace('#ddd',
                                                         $result->button_color,
                                                         $buttonval);
                            }
                        }
                        ?>
                        <div class="col-md-10">
                            <input type="radio" name="button_style" value="0" <?php echo (!empty($result) && ($result->button_style == 0) || empty($result)) ? 'checked' : ''; ?>> Style 1<br>
                            <input type="radio" name="button_style" value="1" <?php echo (!empty($result) && ($result->button_style == 1)) ? 'checked' : ''; ?>> Style 2<br>
                            <input type="radio" name="button_style" value="2" <?php echo (!empty($result) && ($result->button_style == 2)) ? 'checked' : ''; ?>> Style 3<br><br>
                            <div id="colorSelector" style="display: inline-block; margin-right: 5px; vertical-align: middle;"><div style="background-color: <?php echo!empty($result) ? $result->button_color : '#49bd23'; ?>"></div></div>
                            <a href="javascript:void(0)" id="buy-now-input" <?php echo $buttonval; ?> ><?php echo !empty($result) ? $result->button_text : ''; ?></a>                             
                        </div>
                    </div>
                    <input type="hidden" id="shop_id" name="shop_id" value="<?= $shop_id; ?>">
                    <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s') ?>"/>
                    <input type="hidden" name="button_css" id="button_css" value='<?php echo!empty($result) ? $result->button_css : ""; ?>'/>
                    <input type="hidden" name="count_url_1" value="<?php echo!empty($result) ? $result->count_url_1 : 0; ?>"/>
                    <input type="hidden" name="count_url_2" value="<?php echo!empty($result) ? $result->count_url_2 : 0; ?>"/>
                    <input type="hidden" name="count_url_3" value="<?php echo!empty($result) ? $result->count_url_3 : 0; ?>"/>
                    <input type="hidden" name="id" value="<?php echo!empty($result) ? $result->id : ''; ?>"/>
                    <input type="hidden" name="button_color" id="button_color" value="<?php echo!empty($result) ? $result->button_color : '#49bd23'; ?>"/>
                    <div class="support-form-row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-custom width-150">Save</button>
                        </div>
                    </div>
                </form>
                <?php
                if (!empty($result))
                {
                    ?>
                    <div class="wrap-1000">
                        <div class="card padding-20 setting-card">
                            <h3 class="title">Code Snippet</h3>  
                            <p class="subtitle">Please put the following code in your receipt page:</p>
                            <p class="instruction-tip">TIP - Write down the line # for where you place your code, in case you make an error,  you can easily delete and move it wherever you need to.</p>
                            <div id="content">
                                <code id="upsell_code">
                                    <span><</span>div<span>></span
                                    <br>
                                    &nbsp;&nbsp;&nbsp;<span><</span>h4 id="video_title" style=" margin-top: 20px; font-size: 20px; font-weight: bold; text-align: center;"<span>></span><?php echo !empty($result) ? $result->title : ''; ?><span><</span>/h4<span>></span>
                                    &nbsp;&nbsp;&nbsp;<span><</span>iframe allowfullscreen="allowfullscreen" frameBorder="0" width="100%" height="200" style="margin:15px 0" id="videoSelected" src=""<span>></span><span><</span>/iframe<span>></span>
                                    <span><</span>div style="text-align: center;"<span>></span>
                                    &nbsp;&nbsp;&nbsp;<span><</span>a<span> href="" <?php echo $buttonval; ?> target="_blank" style="display:none" id="rVideoSelected"></span><?php echo !empty($result) ? $result->button_text : ''; ?><span><</span>/a<span>></span>
                                    <span><</span>/div<span>></span>
                                    <span><</span>/div<span>></span>
                                </code>
                            </div>
                        </div>
                    </div>
<?php } ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo site_url(); ?>assets/admin/colorpicker/js/colorpicker.js"></script>
<script>
    
    $(document).on('ready',function(){
        $('#button_text').on('blur',function(e){
           var txt = $(this).val();
           if(txt!=''){
               $('#buy-now-input').text(txt);
           }
        });
    });

                    $('#colorSelector').ColorPicker({
                        color: '#49bd23',
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $('#colorSelector div').css('backgroundColor', '#' + hex);
                            $('#button_color').val('#' + hex);
                            var buttonVal = $("input[name='button_style']:checked").val();
                            var buttonCss = '';
                            if (buttonVal == 0) {
                                $('#buy-now-input').css({'border': '2px solid #' + hex, 'color': '#' + hex, 'backgroundColor': '#fff', 'border-radius': '0px', 'box-sizing':' border-box'});
                                buttonCss = {'background-color': '#fff', 'border': '2px solid #'+hex, 'color': '#' + hex, 'border-radius': '0px', 'box-sizing':' border-box'};
                            } else if (buttonVal == 1) {
                                $('#buy-now-input').css('backgroundColor', '#' + hex);
                                buttonCss = {'background-color': '#' + hex, 'border': 'none', 'color': "#FFF", 'border-radius': '0px', 'box-sizing':' border-box'};
                            } else {
                                $('#buy-now-input').css('backgroundColor', '#' + hex);
                                buttonCss = {'background-color': '#' + hex, 'border': 'none', 'color': "#FFF", 'border-radius': '0px', 'box-sizing':' border-box'};
                            }
                            $('#button_css').val(JSON.stringify([buttonCss]));
//                            if (buttonVal == 0) {
//                            } else {
//                            }
                        }
                    });

                    jQuery("input[name='button_style']").change(function () {
                        var buttonVal = $(this).val();
                        var selectedColor = $('#button_color').val();
                        var buttonCss = '';
                        if (buttonVal == 0) {
                            buttonCss = {'background-color': '#ddd', 'border': '2px solid '+ selectedColor, 'color': selectedColor, 'border-radius': '0px', 'box-sizing':' border-box'};
                        } else if (buttonVal == 1) {
                            buttonCss = {'background-color': selectedColor, 'border': 'none', 'color': '#FFF', 'border-radius': '0px', 'box-sizing':' border-box'};
                        } else {
                            buttonCss = {'background-color': selectedColor, 'border': 'none', 'color': '#FFF', 'border-radius': '6px', 'box-sizing':' border-box'};
                        }
                        $('#buy-now-input').css(buttonCss);
                        $('#button_css').val(JSON.stringify([buttonCss]));
                    });

                    jQuery("#receipt_video_form").validate({
                        focusInvalid: false, // do not focus the last invalid input
                        errorElement: 'span',
                        errorClass: 'help-block', // default input error message class
                        ignore: [],
                        rules: {
                            "video_url_1": {
                                required: true,
                            },
                            "redirection_url_1": {
                                required: true
                            },
                            "button_text":{
                                required: true
                            }
                        },
                        messages: {
                            "video_url_1": {
                                required: "Please enter video url of video.",
                            },
                            "redirection_url_1": {
                                required: "Please enter redirection url.",
                            },
                            "button_text":{
                                required: "Please enter button text.",
                            }
                        },
                        submitHandler: function (form) {
                            jQuery.ajax({
                                type: 'POST',
                                url: '<?= site_url('receipt_video/save') ?>',
                                data: jQuery("#receipt_video_form").serialize(),
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    jQuery(".page-loader").show();
                                    jQuery('.btn-custom').attr('disabled', true);
                                },
                                success: function (data) {
                                    //jQuery("#receipt_video_form")[0].reset();
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
                                    }, 1000);
                                }
                            });
                        }
                    });
</script>