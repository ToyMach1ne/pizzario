/*
 * @package YITH WooCommerce Dynamic Pricing and Discounts Premium
 * @since   1.0.0
 * @author  YITHEMES
 */

jQuery(document).ready(function ($) {
    "use strict";
    var wrapper = $(document).find('.ywdpd-sections-group'),
        container = wrapper.find('.ywdpd-section'),
        head = container.find('.ywdpd-section-head'),
        remove = head.find('.ywdpd-remove'),
        clone = head.find('.ywdpd-clone'),
        active = head.find('.ywdpd-active'),
        eventType = container.find('.yith-ywdpd-eventype-select'),
        block_loader = ( typeof yith_ywdpd_admin !== 'undefined' ) ? yith_ywdpd_admin.block_loader : false,
        error_msg = ( typeof yith_ywdpd_admin !== 'undefined' ) ? yith_ywdpd_admin.error_msg : false,
        del_msg = ( typeof yith_ywdpd_admin !== 'undefined' ) ? yith_ywdpd_admin.del_msg : false,
        ajax_url = yith_ywdpd_admin.ajaxurl + '?action=ywdpd_admin_action',
        input_section = $('#yith-ywdpd-add-section'),
        add_section = $('#yith-ywdpd-add-section-button'),


        /****
         * Open function
         */
        open_func = function (head) {
            head.on('click', function () {
                var t = $(this);
                t.parents('.ywdpd-section').toggleClass('open');
                t.next('.section-body').slideToggle();
            });
        },

        /****
         * Remove function
         */
        remove_func = function (remove) {

            remove.on('click', function (e) {
                e.stopPropagation();

                var t = $(this),
                    section = t.data('section'),
                    container = t.parents('.ywdpd-section'),
                    confirm = window.confirm(del_msg);

                if (confirm == true) {

                    if (block_loader) {
                        container.block({
                            message: null,
                            overlayCSS: {
                                background: '#fff url(' + block_loader + ') no-repeat center',
                                opacity: 0.5,
                                cursor: 'none'
                            }
                        });
                    }

                    $.post(ajax_url, {
                        ywdpd_action: 'section_remove',
                        section: section
                    }, function (resp) {
                        container.remove();
                    })
                }

            })
        },


        /****
         * Clone function
         */
        clone_func = function (clone) {

            clone.off().on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var t = $(this),
                    section = t.data('section'),
                    containerw = t.parents('.ywdpd-section-handle'),
                    container = t.parents('.ywdpd-section'),
                    action = 'section_clone',
                    active = container.find('.ywdpd-active'),
                    cloned_from = active.data('section'),
                    clone_section = containerw.clone();

                $.post(ajax_url, {ywdpd_action: action, cloned_from: cloned_from}, function (resp) {
                    if (resp.key) {
                        var $section = clone_section.html(),
                            handle = '<div class="ywdpd-section-handle" data-key="' + resp.key + '">',
                            $nc = $(handle + $section.replace(RegExp(cloned_from, "g"), resp.key) + '</div>');

                        wrapper.append($nc);
                        container = $(document).find('.ywdpd-sections-group').find('.ywdpd-section').last();

                        var head = $(container).find('.ywdpd-section-head'),
                            eventType = container.find('.yith-ywdpd-eventype-select'),
                            active = container.find('.ywdpd-active'),
                            clone = container.find('.ywdpd-clone'),
                            remove = container.find('.ywdpd-remove');

                        container.removeClass('open');
                        container.find('select').chosenDestroy();
                        container.find('select').chosen({
                            width: '100%',
                            disable_search: true
                        });

                        open_func(head);
                        deps_func(eventType);
                        remove_func(remove);
                        clone_func(clone);
                        active_func(active);

                        container.find('.ajax_chosen_select_vendors').each(function () {
                            vendor_search($(this));
                        });
                        container.find('.ajax_chosen_select_brands').each(function () {
                            brand_search($(this));
                        });
                        container.find('.ajax_chosen_select_categories').each(function () {
                            category_search($(this));
                        });
                        container.find('.ajax_chosen_select_tags').each(function () {
                            tag_search($(this));
                        });
                        container.find('.ajax_chosen_select_products').each(function () {
                            product_search($(this));
                        });
                        container.find('.ajax_chosen_select_customers').each(function () {
                            customer_search($(this));
                        });
                        container.find('.datepicker').removeClass('hasDatepicker')
                            .removeData('datepicker')
                            .unbind()
                            .each(function () {
                            $(this).prop('placeholder', 'YYYY-MM-DD HH:mm')
                        }).datetimepicker({
                            timeFormat: 'HH:mm',
                            defaultDate    : '',
                            dateFormat     : 'yy-mm-dd',
                            numberOfMonths : 1,
                        });
                    }
                });

            })
        },

        /****
         * Active function
         */
        active_func = function (active) {

            active.on('click', function (e) {

                e.stopPropagation();

                var t = $(this),
                    section = t.data('section'),
                    container = t.parents('.ywdpd-section'),
                    active_field = t.parents('.ywdpd-section-head').find('.active-hidden-field');

                if (t.hasClass('activated')) {
                    active_field.val('no');
                } else {
                    active_field.val('yes');
                }


                if (block_loader) {
                    container.block({
                        message: null,
                        overlayCSS: {
                            background: '#fff url(' + block_loader + ') no-repeat center',
                            opacity: 0.5,
                            cursor: 'none'
                        }
                    });
                }

                $.post(ajax_url, {
                    ywdpd_action: 'section_active',
                    section: section,
                    active: active_field.val()
                }, function (resp) {
                    if (resp == 'yes') {
                        t.addClass('activated');
                    } else {
                        t.removeClass('activated');
                    }
                    container.unblock();
                })

            })
        },
        /****
         * Deps function option
         */
        deps_func = function (eventType) {
            eventType.each(function () {
                var t = $(this),
                    field = t.data('field'),
                    selected = t.find('option:selected');

                hide_show_func(t, selected.val(), field);

                t.on('change', function () {
                    var field = t.data('field'),
                        selected = t.find('option:selected');
                    hide_show_func(t, selected.val(), field);
                })
            });
        },

        hide_show_func = function (t, val, field) {
            var opt = t.closest('.ywdpd-select-wrapper').find('tr.deps-' + field);

            opt.each(function () {
                var types = $(this).data('type').split(';');
                if ($.inArray(val, types) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                    if (typeof $(this).data('rel') !== 'undefined') {
                        var item_class = 'deps-' + $(this).data('rel');
                        $(this).parents('.ywdpd-section').find('.' + item_class).hide();
                    }


                }
            });
        },

        product_search = function (element) {
            // Category Ajax Search
            element.ajaxChosen({
                method: 'GET',
                url: yith_ywdpd_admin.ajaxurl,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    action: 'woocommerce_json_search_products_and_variations',
                    security: yith_ywdpd_admin.search_products_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });
        },

        category_search = function (element) {

            // Category Ajax Search
            element.ajaxChosen({
                method: 'GET',
                url: ajax_url,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    ywdpd_action: 'category_search',
                    security: yith_ywdpd_admin.search_categories_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });
        },

        tag_search = function (element) {

            // Category Ajax Search
            element.ajaxChosen({
                method: 'GET',
                url: ajax_url,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    ywdpd_action: 'tag_search',
                    security: yith_ywdpd_admin.search_tags_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });
        },

        vendor_search = function (element) {

            // Category Ajax Search
            element.ajaxChosen({
                method: 'GET',
                url: yith_ywdpd_admin.ajaxurl,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    action: 'ywdpd_vendor_search',
                    security: yith_ywdpd_admin.search_vendor_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });
        },

        brand_search = function (element) {

            // Category Ajax Search
            element.ajaxChosen({
                method: 'GET',
                url: yith_ywdpd_admin.ajaxurl,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    action: 'ywdpd_brand_search',
                    security: yith_ywdpd_admin.search_brand_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });
        },

        customer_search = function (element) {

            // Category Ajax Search
            element.ajaxChosen({
                method: 'GET',
                url: ajax_url,
                dataType: 'json',
                afterTypeDelay: 100,
                data: {
                    ywdpd_action: 'customers_search',
                    security: yith_ywdpd_admin.search_customers_nonce
                }
            }, function (data) {
                var terms = {};

                $.each(data, function (i, val) {
                    terms[i] = val;
                });

                return terms;
            });
        };

    add_section.on('click', function (e) {
        e.preventDefault();

        var t       = $(this),
            id      = t.data( 'section_id'),
            name    = t.data( 'section_name' ),
            action  = t.data( 'action'),
            type    = t.data( 'type'),
            title   = input_section.val();

        if (title == '') {
            if (error_msg) {
                t.siblings('.ywdpd-error-input-section').html(error_msg);
            }
        }
        else {
            $.post( ajax_url, { ywdpd_action: action, type: type, section: title, id: id, name: name}, function( resp ) {
                // empty input
                input_section.val('');


                // remove error msg if any
                $( '.ywdpd-error-input-section' ).remove();

                wrapper.append( resp );

                var container       = wrapper.find( '.ywdpd-section').last(),
                    head            = $(container).find( '.ywdpd-section-head' ),
                    eventType       = container.find( '.yith-ywdpd-eventype-select'),
                    active          = container.find( '.ywdpd-active'),
                    clone           = container.find('.ywdpd-clone'),
                    remove          = container.find( '.ywdpd-remove');


                // re-init
                container.find('select').chosen({
                    width: '100%',
                    disable_search: true
                });

                open_func(head);
                deps_func(eventType);
                remove_func(remove);
                clone_func(clone);
                active_func(active);

                container.find('.ajax_chosen_select_vendors').each(function () {
                    vendor_search($(this));
                });
                container.find('.ajax_chosen_select_brands').each(function () {
                    brand_search($(this));
                });
                container.find('.ajax_chosen_select_categories').each(function () {
                    category_search($(this));
                });
                container.find('.ajax_chosen_select_tags').each(function () {
                    tag_search($(this));
                });
                container.find('.ajax_chosen_select_products').each(function () {
                    product_search($(this));
                });
                container.find('.ajax_chosen_select_customers').each(function () {
                    customer_search($(this));
                });
                container.find('.datepicker').each(function () {
                    $(this).prop('placeholder', 'YYYY-MM-DD HH:mm')
                }).datetimepicker({
                    timeFormat: 'HH:mm',
                    defaultDate    : '',
                    dateFormat     : 'yy-mm-dd',
                    numberOfMonths : 1,
                });

            })
        }
    });

    /****
     * Upload Button
     ****/
    $(document).on('click', '.upload_img_button', function (e) {
        e.preventDefault();

        var t = $(this),
            custom_uploader;

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

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON(),
                input_text = t.prev('.upload_img_url');

            input_text.val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();

    });

    /****
     * Add a row pricing rules
     ****/
    $(document).on('click', '.add-row', function () {
        var $t = $(this),
            table = $t.closest('table'),
            current_row = $t.closest('tr'),
            current_index = parseInt(current_row.data('index')),
            clone = current_row.clone(),
            rows = table.find('tr'),
            max_index = 1;

        rows.each(function () {
            var index = $(this).data('index');
            if (index > max_index) {
                max_index = index;
            }
        });

        var new_index = max_index + 1;
        clone.attr('data-index', new_index);
        var fields = clone.find("[name*='rules']");

        fields.each(function () {
            var $t = $(this),
                name = $t.attr('name'),
                id = $t.attr('id'),

                new_name = name.replace('[rules][' + current_index + ']', '[rules][' + new_index + ']'),
                new_id = id.replace('[rules][' + current_index + ']', '[rules][' + new_index + ']');

            $t.attr('name', new_name);
            $t.attr('id', new_id);
            $t.val('');

        });

        clone.find('.remove-row').removeClass('hide-remove');
        clone.find('.chosen-container').remove();

        clone.find('select').chosen({
            width: '100%',
            disable_search: false
        });

        table.append(clone);

        var eventType = clone.find('.yith-ywdpd-eventype-select'),
            container = clone;

        container.find('.ajax_chosen_select_vendors').each(function () {
            vendor_search($(this));
        });

        container.find('.ajax_chosen_select_brands').each(function () {
            brand_search($(this));
        });

        container.find('.ajax_chosen_select_categories').each(function () {
            category_search($(this));
        });

        container.find('.ajax_chosen_select_tags').each(function () {
            tag_search($(this));
        });

        container.find('.ajax_chosen_select_products').each(function () {
            product_search($(this));
        });

        container.find('.ajax_chosen_select_customers').each(function () {
            customer_search($(this));
        });

        deps_func(eventType);
    });

    /****
     * remove a row pricing rules
     ****/
    $(document).on('click', '.remove-row', function () {
        var $t = $(this),
            current_row = $t.closest('tr');

        current_row.remove();
    });


    jQuery('.ywdpd-sections-group').sortable({
            items: '.ywdpd-section-handle',
            cursor: 'move',
            axis: 'y',
            handle: 'form',
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            helper: 'clone',
            start: function (event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop: function (event, ui) {
                ui.item.removeAttr('style');
                var keys = $('.ywdpd-section-handle'), i = 0, array_keys = new Array();
                for (i = 0; i < keys.length; i++) {
                    array_keys[i] = $(keys[i]).data('key');
                }

                if (array_keys.length > 0) {
                    $.post(ajax_url, {
                        ywdpd_action: 'order_section',
						tab: $('.form-table').closest('.yit-admin-panel-content-wrap-full').data('type'),
						order_keys: array_keys
                    }, function (resp) {
                    });
                }

            }
        });

    // init
    open_func(head);
    remove_func(remove);
    clone_func(clone);
    active_func(active);
    deps_func(eventType);

    container.find('.ajax_chosen_select_vendors').each(function () {
        vendor_search($(this));
    });

    container.find('.ajax_chosen_select_brands').each(function () {
        brand_search($(this));
    });

    container.find('.ajax_chosen_select_categories').each(function () {
        category_search($(this));
    });

    container.find('.ajax_chosen_select_tags').each(function () {
        tag_search($(this));
    });

    container.find('.ajax_chosen_select_products').each(function () {
        product_search($(this));
    });

    container.find('.ajax_chosen_select_customers').each(function () {
        customer_search($(this));
    });

    container.find('select').chosen({
        width: '100%',
        disable_search: false
    });

    container.find('.datepicker').each(function () {
        $(this).prop('placeholder', 'YYYY-MM-DD HH:mm')
    }).datetimepicker({
        timeFormat: 'HH:mm',
        defaultDate    : '',
        dateFormat     : 'yy-mm-dd',
        numberOfMonths : 1,
    });

    $.fn.chosenDestroy = function () {
        $(this).show().removeClass('chzn-done')
        $(this).next().remove()

        return $(this);
    }
});
