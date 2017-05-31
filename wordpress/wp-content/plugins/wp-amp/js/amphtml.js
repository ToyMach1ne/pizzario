var amphtml = amphtml || {};
(function ($) {

    // ColorPicker attach
    $('.amphtml-colorpicker').wpColorPicker();

    $('#reset').click(function (e) {
        var reset = confirm('Your changes will be overridden. Are you sure?');
        if (reset) {
            $('#amp-settings').attr('action', '#');
        } else {
            e.preventDefault();
        }
    });

    var manage_image = function (element, custom_uploader) {
        element.find('.reset_image_button').click(function (e) {
            element.find('.upload_image').val('');
            element.find('.logo_preview').hide();
            element.find(this).hide();
        });

        element.find('.upload_image_button').click(function (e) {
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            custom_uploader.on('select', function () {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                var img_obj = prepare_attachment(attachment);
                element.find('.upload_image').val(img_obj);
                element.find('.logo_preview img').attr('src', attachment.url);
                element.find('.logo_preview').show();
                element.find('.reset_image_button').show();

            });

            custom_uploader.open();

        });
    };
    
    function prepare_attachment( attachment ) {
        var data = {
            id:     attachment.id,
            url:    attachment.url,
            height: attachment.height,
            width:  attachment.width,
            alt:    attachment.alt
        };
        return JSON.stringify(data);
    }

    var logo_uploader;
    var image_uploader;
    var main_logo;

    manage_image($('tr[data-name=default_logo]'), logo_uploader);
    manage_image($('tr[data-name=default_image]'), image_uploader);
    manage_image($('tr[data-name=favicon]'), image_uploader);
    manage_image($('tr[data-name=logo]'), main_logo);

    $('#google_analytic').mask('SS-000099999-0999');

    $("#custom_content_width").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    var checkLogo = function () {
        switch ($('#logo_opt').val()) {
            case 'icon_logo':
                $('tr').has('.logo_preview').show();
                $('tr').has('#logo_text').hide();
                $('.img_text_size_logo').show();
                $('.img_text_size_full').hide();
                break;
            case 'text_logo':
                $('tr').has('#logo_text').show();
                $('tr').has('.hide_preview').hide();
                break;
            case 'icon_an_text':
                $('tr').has('#logo_text').show();
                $('tr').has('.logo_preview').show();
                $('.img_text_size_logo').show();
                $('.img_text_size_full').hide();
                break;
            case 'image_logo':
                $('tr').has('#logo_text').hide();
                $('tr').has('.logo_preview').show();
                $('.img_text_size_full').show();
                $('.img_text_size_logo').hide();
                break;
        }
    };
    checkLogo();

    $('#logo_opt').change(function () {
        checkLogo();
    });

    if ($('input[name="amphtml-exclude"]:checked').length === 1) {
        $('#amphtml-metabox-settings').hide();
        $('#amphtml-featured-image').hide();
    }

    $('input[name="amphtml-exclude"]').change(function () {
        $('#amphtml-metabox-settings').toggle();
        $('#amphtml-featured-image').toggle();
    });

    amphtml.postSettings = {
        postContent: 'content',
        excludedContent: 'amphtml-custom-content',
        overwriteContent: $('input[name=amphtml-override-content]'),
        overwriteTitle: $('input[name=amphtml-override-title]'),
        excludedTitle: $('input[name=amphtml-custom-title]'),
        postTitle: $('#title'),
        excludedContentWrap: $('#wp-amphtml-custom-content-wrap'),
        init: function () {
            if (!this.overwriteContent.prop('checked')) {
                amphtml.utils.disableExcludedContent();
            }
            if (!this.overwriteTitle.prop('checked')) {
                this.excludedTitle.prop('disabled', true);
            }
        },
        addEvents: function () {
            var self = this;
            this.overwriteContent.on('change', function () {
                if (this.checked) {
                    amphtml.utils.enableExcludedContnet();
                    var postContent = amphtml.utils.getContent(self.postContent);
                    var overrideContent = true;
                    if (amphtml.utils.getContent(self.excludedContent).length > 0) {
                        overrideContent = amphtml.utils.confirmBox('Do you want replace current AMPHTML content with post content?', postContent);
                    } else {
                        amphtml.utils.setExcludedContent(postContent)
                    }
                } else {
                    amphtml.utils.disableExcludedContent();
                }
            });

            this.overwriteTitle.on('change', function () {
                if (this.checked && self.excludedTitle.html().length == 0) {
                    self.excludedTitle.prop('disabled', false);
                    var title = self.postTitle.val();
                    self.excludedTitle.val(title)
                } else {
                    self.excludedTitle.prop('disabled', true);
                }
            });
        }
    };

    amphtml.utils = {
        isTinyMCE: function (name) {
            return typeof window['tinyMCE'] !== 'undefined' && tinyMCE.get(name)
        },
        getContent: function (name) {
            if (this.isTinyMCE(name)) {
                return tinyMCE.get(name).getContent();
            } else {
                return $('#' + name).html();
            }
        },
        setExcludedContent: function (content) {
            if (this.isTinyMCE(amphtml.postSettings.excludedContent)) {
                tinyMCE.get(amphtml.postSettings.excludedContent).setContent(content);
            } else {
                $('#' + amphtml.postSettings.excludedContent).html(content);
            }
        },
        disableExcludedContent: function () {
            if (this.isTinyMCE(amphtml.postSettings.excludedContent) && amphtml.postSettings.excludedContentWrap.hasClass('tmce-active')) {
                tinymce.get(amphtml.postSettings.excludedContent).getBody().setAttribute('contenteditable', 'false');
            }
            $('#' + amphtml.postSettings.excludedContent).prop('disabled', true);
        },
        enableExcludedContnet: function () {
            if (this.isTinyMCE(amphtml.postSettings.excludedContent) && amphtml.postSettings.excludedContentWrap.hasClass('tmce-active')) {
                tinymce.get(amphtml.postSettings.excludedContent).getBody().setAttribute('contenteditable', 'true');
            }
            $('#' + amphtml.postSettings.excludedContent).prop('disabled', false);
        },
        confirmBox: function (message, postContent) {
            $('<div></div>').appendTo('body')
                .html('<div>' + message + '</div>')
                .dialog({
                    modal: true,
                    autoOpen: true,
                    width: 'auto',
                    resizable: false,
                    buttons: {
                        Yes: function () {
                            amphtml.utils.setExcludedContent(postContent);
                            $(this).dialog("close");
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });
        }
    };


    amphtml.ad = {
        addNewBlockButton: $('#add-new-ad'),
        deleteAdButton: $('#delete-ad'),
        fields: [
            'ad_data_id_client',
            'ad_adsense_data_slot',
            'ad_doubleclick_data_slot',
            'ad_content_code',
            'ad_width',
            'ad_height',
            'ad_layout'
        ],
        getField: function(id) {
            return '#' + id + '_' + amphtml.current_section;
        },

        updateAd: function(type) {
            var visible_fields = [];
            switch ($('#ad_type_' + type).val()) {
                case 'adsense':
                    visible_fields = [
                        'ad_data_id_client',
                        'ad_adsense_data_slot',
                        'ad_width',
                        'ad_height',
                        'ad_layout'
                    ];
                    break;
                case 'doubleclick':
                    visible_fields = [
                        'ad_doubleclick_data_slot',
                        'ad_width',
                        'ad_height',
                        'ad_layout'
                    ];
                    break;
                case 'other':
                    visible_fields = [
                        'ad_content_code'
                    ];
                    break;
            }
            this.showFields(visible_fields);
        },
        init: function() {
            var self = this;
            self.updateAd(amphtml.current_section);

            $('#ad_type_' + amphtml.current_section).change(function () {
                self.updateAd(amphtml.current_section);
            });

            this.addNewBlockButton.click(function () {
                $('#amp-settings').attr('action', '#');
                $('input[name=action]').val('add_new_ad_block');

                $(self.data_id_client).removeAttr('required');
                $(self.ad_sense_data_slot).removeAttr('required');
                $(self.double_click_data_slot).removeAttr('required');
            });

            this.deleteAdButton.click(function() {
                $('#amp-settings').attr('action', '#');
                $('input[name=action]').val('delete_ad_block');

                $(self.data_id_client).removeAttr('required');
                $(self.ad_sense_data_slot).removeAttr('required');
                $(self.double_click_data_slot).removeAttr('required');

            });
        },
        hideField: function (field) {
            $('tr').has(field).hide();
            $(field).removeAttr('required');
        },
        showFields: function (visible_fields) {
            var self = this;
            $.each( this.fields, function(index, el) {
                var field = self.getField(el);
                if ( visible_fields.indexOf(el) !== -1 ) {
                    $('tr').has(field).show();
                    $(field).attr("required", true);
                } else {
                    self.hideField( field );
                }
            });
        }
    };

    amphtml.postSettings.addEvents();

    if (amphtml.postSettings.excludedContentWrap.has('html-active')) {
        amphtml.postSettings.init()
    }

    amphtml.ad.init();

    // add sortable for templates and wc tabs
    var tab = amphtml.current_tab;
    var tab_section = amphtml.current_section;
    if (tab == 'templates' || ( tab == 'wc' && tab_section != 'add_to_cart' )) {
        var templateElements = $('.form-table tbody');
        templateElements.sortable({
            items: 'tr:not(.unsortable)'
        });
        $('#submit').click(function (event) {
            var positions = templateElements.sortable("toArray", {attribute: 'data-name'});
            if (tab_section == 'wc_archives') {
                positions.unshift('wc_archives_desc');
            }
            var data = {
                positions: positions,
                action: amphtml.action,
                current_section: amphtml.current_section
            };

            $.ajax({
                type: 'POST',
                url: amphtml.ajaxUrl,
                data: data,
                async: false
            });
        });
    }

})(jQuery);