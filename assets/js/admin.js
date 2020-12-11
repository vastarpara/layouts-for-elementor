jQuery(document).ready(function () {

    var lef_cur_url = window.location.href;
    var lef_res = lef_cur_url.substring(0, lef_cur_url.lastIndexOf("/") + 1);

    //Install layouts popup
    jQuery('.installbtn').each(function (idx, item) {
        var winnerId = "install-" + idx;
        this.id = winnerId;
        jQuery(this).click(function () {
            jQuery(".lfe-msg").show();
            jQuery(".lfe-msg").text('Import this template via one click');
            jQuery(".lfe-page-create, .lfe-create-page-btn").show();
            jQuery('input[type=text]').show();
            jQuery('.lfe-import-btn').bind('click');
            jQuery('.lfe-create-page-btn').bind('click');
            var btn = jQuery("#install-" + idx);
            var span = jQuery(".lfe-close-icon");
            var popId = jQuery('#content-in-' + idx);
            jQuery(popId).addClass('on');
            jQuery('body').addClass('install-popup');
            span.click(function () {
                jQuery(popId).removeClass('on');
                jQuery('body').removeClass('install-popup');
            });
        });
    });

    //Preview layouts popup
    jQuery('.previewbtn').each(function (idx, item) {

        var winnerId = "preview-" + idx;
        this.id = winnerId;
        jQuery(this).click(function () {
            jQuery(".lfe-msg").show();
            jQuery(".lfe-msg").text('Import this template via one click');
            jQuery(".lfe-page-create").show();
            jQuery(".lfe-page-create, .lfe-create-page-btn").show();
            jQuery('input[type=text]').show();
            jQuery('.lfe-import-btn').bind('click');
            jQuery('.lfe-create-page-btn').bind('click');
            jQuery('#preview-in-' + idx + " iframe").attr("src", jQuery(this).attr('data-url'));
            var btn = jQuery("#preview-" + idx);
            var span = jQuery(".lfe-close-icon");
            var popId = jQuery('#preview-in-' + idx);
            jQuery(popId).addClass('on');
            jQuery('body').addClass('preview-popup');
            span.click(function () {
                jQuery(popId).removeClass('on');
                jQuery('body').removeClass('preview-popup');
            });
        });
    });

    //Filter layouts category js
    jQuery.fn.categoryFilter = function (selector) {
        this.click(function () {
            var categoryValue = jQuery(this).attr('data-filter');
            jQuery(this).addClass('active');
            jQuery(this).parent().siblings().children().removeClass('active');

            if (categoryValue == "all") {
                jQuery('.lfe_filter').show(800);
            } else {
                jQuery(".lfe_filter").not('.' + categoryValue).hide('800');
                jQuery('.lfe_filter').filter('.' + categoryValue).show('800');
            }
        });
    }

    jQuery('.lfe-category-filter').categoryFilter();

    jQuery(".lfe-close-icon").click(function () {
        jQuery(".lfe-import-btn").show();
        jQuery(".lfe-edit-template").hide();
        jQuery(".lfe-msg").hide();
        jQuery(".lfe-page-edit").hide();
        jQuery('.lfe-create-page-btn').removeClass('lfe-disabled');
        jQuery('.lfe-import-btn').removeClass('lfe-disabled');
        jQuery('input[type=text]').val('');
    });


    //sync latest template
    jQuery(".lfe-sync-btn").on('click', function () {

        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'handle_sync',
            },
            beforeSend: function () {
                jQuery('.lfe-sync-btn').text(js_object.lfe_sync);
            },
            success: function (res) {
                var res = res.slice(0, -1);
                if (res == 'success') {
                    setTimeout(function() {
                      Toastify({
                        text: js_object.lfe_sync_suc,
                        gravity: "right",
                        duration: 4500,
                        close: true,
                        backgroundColor: "linear-gradient(135deg, rgb( 99, 89, 241 ) 0%, rgb( 49, 181, 251 ) 100%)",
                      }).showToast();
                     }, 2000);
                     setTimeout(function() {
                          window.location.href = lef_cur_url;
                        }, 5000);
                } else {
                   setTimeout(function() {
                      Toastify({
                        text: js_object.lfe_sync_fai,
                        gravity: "right",
                        duration: 4500,
                        close: true,
                        backgroundColor: "linear-gradient(135deg, rgb( 99, 89, 241 ) 0%, rgb( 49, 181, 251 ) 100%)",
                      }).showToast();
                     }, 2000);
                     setTimeout(function() {
                          window.location.href = lef_cur_url;
                        }, 5000);     
                }
            },

        });
    });

    //Import Template js
    jQuery(".lfe-import-btn").on('click', function () {
        jQuery(".lfe-loader").show();
        var template_id = jQuery(this).attr("data-template-id");
        var with_page = jQuery(".lfe-page-name-" + template_id).val();
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'handle_import',
                template_id: template_id,
                with_page: with_page,
            },
            beforeSend: function () {
                jQuery('.lfe-create-page-btn').addClass('lfe-disabled');
                jQuery(".lfe-import-btn").hide();
                jQuery(".lfe-loader").html("<div class='lfe-gradient-loader'></div>");

            },
            success: function (page_id) {
                jQuery(".lfe-msg").text(js_object.lfe_msg);
                jQuery(".lfe-loader").hide();
                jQuery(".lfe-edit-template").show().attr("href", lef_res + 'post.php?post=' + page_id + "&action=elementor");
            },
            setTimeout:1000,
        });
    });

    //Import Template with page name js
    jQuery(".lfe-create-page-btn").on('click', function () {
        var template_id = jQuery(this).attr("data-template-id");
        var crtbtn = jQuery(this).attr("data-name");
        jQuery('.lfe-loader-page').show();

        if (crtbtn == 'crtbtn') {
            var with_page = jQuery(".lfe-page-" + template_id).val();
        } else {
            var with_page = jQuery(this).siblings(".lfe-page-name-" + template_id).val();
        }

        //check page name not empty
        if (with_page == "") {
            alert(js_object.lfe_crt_page);
            jQuery(".lfe-page-name-" + template_id).addClass("lef-required");
            jQuery(".lfe-page-" + template_id).addClass("lef-required");
            return false;
        }

        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'handle_import',
                template_id: template_id,
                with_page: with_page,
            },
            beforeSend: function () {
                jQuery('.lfe-import-btn').addClass('lfe-disabled');
                jQuery(".lfe-create-page-btn, .lfe-page-name-" + template_id).hide();
                jQuery(".lfe-page-" + template_id).hide();
                jQuery(".lfe-loader-page").html("<div class='lfe-gradient-loader'></div>");
            },
            success: function (page_id) {
                jQuery(".lfe-page-create, .lfe-loader-page").hide();
                jQuery(".lfe-page-edit").show();
                jQuery(".lfe-edit-page").attr("href", lef_res + 'post.php?post=' + page_id + "&action=elementor");
            },
            setTimeout:1000,
        });
    });

});

function closeProgressIndicator() {
    jQuery(".lfeProgressIndicator").hide();
}
