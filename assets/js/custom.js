/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(window).load(function () {
    jQuery(".page-loader").hide();
});

jQuery(document).ready(function () {
    jQuery(document).on('keypress', '.number-with-dot', function (evt) {
        var value = jQuery(this).val();
        console.log(value);
        if ((evt.charCode >= 48 && evt.charCode <= 57) || evt.charCode == 46 || evt.charCode == 0)
        {
            if (value.indexOf('.') > -1)
            {
                if (evt.charCode == 46)
                {
                    evt.preventDefault();
                }
            }
            if ((value.indexOf('.') != -1) && (value.substring(value.indexOf('.'), value.indexOf('.').length).length > 2))
            {
                if (evt.keyCode !== 8 && evt.keyCode !== 46)
                { //exception
                    evt.preventDefault();
                }
            }
        } else
        {
            evt.preventDefault();
        }
    });
});