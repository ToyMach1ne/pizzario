jQuery(document).ready(function ($) {

    $(document).on('change', '.allow-multiple', function () {
        var optionId = $(this).closest('.backend-product-option').find('.backend-product-option-id').val();
        if ($(this).is(':checked')) {
            $(this).closest('.backend-product-option').find('.backend-option-default').attr('type', 'checkbox');
            var suboptionId = 1;
            $(this).closest('.backend-product-option').find('.backend-option-default').each(function () {
                $(this).attr('name', 'backend-product-options[' + optionId + '][default][' + suboptionId + ']');
                suboptionId = suboptionId+1;
            });
        } else {
            var checkMe = $(this).closest('.backend-product-option').find('.backend-option-default:checked').first();
            $(this).closest('.backend-product-option').find('.backend-option-default').removeAttr('checked').attr('type', 'radio').attr('name', 'backend-product-options[' + optionId + '][default]');
            checkMe.attr('checked', 'checked');
        }
    });

    $(document).on('change', '.show-price', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.backend-product-option').find('.hide-free-wrapper').show();
        } else {
            $(this).closest('.backend-product-option').find('.hide-free-wrapper').hide();
        }
    });
    $('.show-price').change();

    $(document).on('change', '.allow-multiple', function () {
        if($(this).is(':checked')) {
            $(this).closest('.backend-product-option').find('.maximum-and-minimum').show();   
        } else {                  
            $(this).closest('.backend-product-option').find('.maximum-and-minimum').hide();
        }
    });
    $('.allow-multiple').change();

    function updateDefaults(defaultCheckbox) {
        if (defaultCheckbox.is(':checked')) {
            defaultCheckbox.closest('.backend-product-option').find('.default-column').hide();
        } else {
            defaultCheckbox.closest('.backend-product-option').find('.default-column').show();
        }
    }

    $(document).on('click', '.backend-no-default-checkbox', function (event) {
        updateDefaults($(this));
    });
    $('.backend-no-default-checkbox').each(function () {
        updateDefaults($(this));
    });

    var file_frame;
    var element_clicked;
    $(document).on('click', '.backend-upload-image-button', function (event) {
        event.preventDefault();
        element_clicked = $(this);
        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            var optionForImage = element_clicked.closest('.backend-upload-image');
            optionForImage.find('.backend-upload-image-src').val(attachment.url);
            optionForImage.find('.backend-upload-image-id').val(attachment.id);
            optionForImage.find('.backend-image-upload').attr('src', attachment.url);
        });

        // Finally, open the modal
        file_frame.open();
    });

    $(document).on('click', '.backend-product-option h2', function () {
        $(this).closest('.backend-product-option').find('.backend-product-option-toggle').toggle(500);
        var expandHideText = $(this).closest('.backend-product-option').find('.backend-expand-hide');
        expandHideText.text(expandHideText.text() == '+' ? '-' : '+');
    });

    $('#product-options-form').submit(function (event) {
        event.preventDefault();
    });

    $(document).on('change', 'input, select, textarea', function () {
        ajax_option_chosen($(this));
    });

    $('.backend-product-option-id').each(function () {
        ajax_option_chosen($(this));
    });

    function ajax_option_chosen(optionChanged) {
        if (optionChanged.closest('.backend-product-option').parent().closest('.backend-product-option').length > 0) {
            return;
        }
        var productOption = optionChanged.closest('.backend-product-option');
        var productOptionId = productOption.closest('.backend-product-option').find('.backend-product-option-id').val();
        var optionsChosen = {};
        productOption.find('.backend-outside-preview').find('input, select, textarea').each(function () {
            if ($(this).attr('name') != undefined) {
                var name = $(this).attr('name');
                if (name.indexOf('[options]') >= 0) {
                    var splitOption = name.split('[options]');
                    if (splitOption.length == 2) {
                        var productOption = splitOption[0];
                        var option = splitOption[1];
                        var optionId = $(this).closest('.backend-option').find('.backend-option-id').val();
                        var optionName = option.replace('[' + optionId + '][', '').replace(']', '');
                        if (optionsChosen[productOptionId] == undefined) {
                            optionsChosen[productOptionId] = {};
                        }
                        if (optionsChosen[productOptionId]['options'] == undefined) {
                            optionsChosen[productOptionId]['options'] = {};
                        }
                        if (optionsChosen[productOptionId]['options'][optionId] == undefined) {
                            optionsChosen[productOptionId]['options'][optionId] = {};
                        }
                        var value = $(this).val();
                        if ($(this).attr('type') == 'radio') {
                            value = $('input[name="' + $(this).attr('name') + '"]:checked').val();
                        }
                        optionsChosen[productOptionId]['options'][optionId][optionName] = value;
                    }
                } else {
                    var optionName = name.replace('backend-product-options[' + productOptionId + '][', '').replace(']', '');
                    if (optionsChosen[productOptionId] == undefined) {
                        optionsChosen[productOptionId] = {};
                    }
                    var value = $(this).val();
                    if ($(this).attr('type') == 'radio') {
                        value = $('input[name="' + $(this).attr('name') + '"]:checked').val();
                    }
                    optionsChosen[productOptionId][optionName] = value;
                }
            }
        });
        /*var preview = productOption.find('.backend-product-option-preview');
        preview.css('opacity', '0.5');
        $.post(ajaxurl, {action: 'get_product_option_preview', options_chosen: optionsChosen}, function (response) {
            preview.html(response);
            preview.css('opacity', '1');
        });*/
    }

    $('.backend-product-option-toggle').hide();
    $('.backend-expand-hide').text('+')

    $(document).on('keyup', '.backend-product-option-title', function () {
        $(this).closest('.backend-product-option').find('.backend-option-title').text($(this).val());
    });

    $('#option-draggable div').draggable({
        appendTo: "body",
        helper: "clone"
    });

    /*$('div.backend-product-option').draggable({
     appendTo: "body",
     helper: "clone"
     });*/

    function updateOptionsAndTitles() {
        var optionsAndTitles = new Array();
        $('.backend-product-option').each(function () {
            var optionAndTitle = {};
            optionAndTitle.id = $(this).find('.backend-product-option-id').val();
            optionAndTitle.title = $(this).find('h2').text();
            optionAndTitle.title = optionAndTitle.title.substring(optionAndTitle.title, optionAndTitle.title.length - 1);
            optionsAndTitles.push(optionAndTitle);
        });
        $('.backend-conditional-option-checkboxes').each(function () {
            var wrapper = $(this);
            var name = $(this).find('.backend-option-conditional-checkboxes-base-name').val();
            var thisOptionId = $(this).closest('.backend-product-option').find('.backend-product-option-id').val();
            $.each(optionsAndTitles, function (id, value) {
                if (value.id != thisOptionId) {
                    var nameToUse = name + '[' + value.id + ']';
                    if (wrapper.find('input[name="' + nameToUse + '"]').length == 0) {
                        wrapper.append('<label><input type="checkbox" name="' + nameToUse + '" value="yes" /><span class="backend-conditional-title-text">' + value.title + '</span></label>');
                    } else {
                        wrapper.find('input[name="' + nameToUse + '"]').closest('label').find('.backend-conditional-title-text')[0].innerText = value.title;
                    }
                }
            });
            if ($(this).find('input[type="checkbox"]').length === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
        $('.allow-multiple').change();
        $('.box-effect-select').change();
    }
    updateOptionsAndTitles();

    $(document).on('change', '.backend-product-option-title', function () {
        updateOptionsAndTitles();
    });

    /*$('form').submit(function () {
        var newId = 1;
        $('.backend-product-option').each(function () {
            var oldId = $(this).find('.backend-product-option-id').val();
            if (!$.isNumeric(oldId)) {
                return;
            }
            $(this).find('[name*="backend-product-options[' + oldId + ']"]').each(function () {
                var oldName = $(this).attr('name');
                var newName = oldName.replace('backend-product-options[' + oldId + ']', 'backend-product-options[' + newId + ']');
                $(this).attr('name', newName);
            });
            $(this).find('.backend-product-option-id').val(newId);
            newId = newId + 1;
        });
    });*/

    $("#options-droppable").droppable({
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        //accept: ":not(.ui-sortable-helper)",
        drop: function (event, ui) {
            if (ui.draggable.hasClass('backend-product-option') || ui.draggable.hasClass('backend-option')) {
                return;
            }
            var type = ui.draggable.find('.backend-widget-type').val();
            var htmlForType = woocommerce_product_options_backend_settings[type];
            var maxId = 0;
            $(this).find('.backend-product-option-id').each(function () {
                if (parseInt($(this).val()) > parseInt(maxId)) {
                    maxId = $(this).val();
                }
            });
            maxId = parseInt(maxId) + 1;
            htmlForType = htmlForType.replace(new RegExp("product_option_id", "g"), maxId);
            $(this).append(htmlForType);
            $(this).find(".backend-placeholder").remove();
            hideOptions();
            $('.backend-product-option-' + maxId + ' .conditional-logic').hide();
            updateOptionsAndTitles();
        }
    }).sortable({
        items: ".backend-product-option",
        //cancel: ".backend-expand-hide, input, select, option, textarea, label, .backend-options-wrapper, table, tr, td",
        sort: function () {
            // $(this).removeClass("ui-state-default");
        }
    });

    $('.backend-options tr.backend-option').draggable({
        appendTo: "body",
        helper: "clone"
    });

    function updateOrderOfInputOptions(productOption) {
        var inputOptionId = 1;
        var allOptions = productOption.find('.backend-option');
        allOptions.each(function () {
            var oldInputOptionId = $(this).find('.backend-option-id').val();
            $(this).find('.backend-option-id').val(inputOptionId);
            $(this).removeClass('option-' + oldInputOptionId);
            $(this).addClass('option-' + inputOptionId);
            $(this).find('input, select, textarea').each(function () {
                if (!$(this).hasClass('backend-option-id')) {
                    var value = $(this).val();
                    var name = $(this).attr('name').replace('[options][' + oldInputOptionId + ']', '[options][' + inputOptionId + ']');
                    $(this).attr('name', name);
                    $(this).val(value);
                }
            });
            inputOptionId = inputOptionId + 1;
        });
    }

    $('.backend-options').droppable({
        activeClass: "ui-options-state-default",
        hoverClass: "ui-options-state-hover",
        accept: ":not(.ui-sortable-helper)",
        drop: function (event, ui) {
            if (ui.draggable.hasClass('backend-product-option')) {
                return;
            }
            var parent = $(this).closest('.backend-product-option');
            var allOptions = parent.find('.backend-option');
            if (allOptions.length == 1) {
                return;
            }
            var y = event.pageY;
            var newPosition = 0;
            var elementAfterThisOne = allOptions.first();
            var setAsFirst = true;
            allOptions.each(function () {
                if (!$(this).hasClass('ui-draggable-dragging')) {
                    if ($(this).offset().top < y) {
                        elementBeforeThisOne = $(this);
                        setAsFirst = false;
                    }
                }
            });
            if (setAsFirst) {
                allOptions.first().before('<tr class="backend-option">' + ui.draggable.html() + '</tr>');
            } else {
                elementBeforeThisOne.after('<tr class="backend-option">' + ui.draggable.html() + '</tr>');
            }
            ui.draggable.remove();
            parent.find('tr.backend-option').draggable({
                appendTo: "body",
                helper: "clone"
            });
            updateOrderOfInputOptions(parent);
        }
    });

    function sortInputOptions(productOption) {
        var inputOptions = productOption.find('.backend-option');
        var inputOptionByLabel = {};
        var inputOptionLabels = new Array();
        var j = 0;
        inputOptions.each(function () {
            var label = $(this).find('.input-option-label').val();
            inputOptionByLabel[label] = new Array();
        });
        inputOptions.each(function () {
            var label = $(this).find('.input-option-label').val();
            inputOptionByLabel[label].push(j);
            if (inputOptionByLabel[label].length == 1) {
                inputOptionLabels.push(label);
            }
            j = j + 1;
        });
        inputOptionLabels.sort();
        var inputOptionsTable = productOption.find('.backend-options');
        for (var i = 0; i < inputOptionLabels.length; i++) {
            var labelIds = inputOptionByLabel[ inputOptionLabels[i] ];
            for (var j = 0; j < labelIds.length; j++) {
                inputOptionsTable.append('<tr class="backend-option updating-backend-option">' + inputOptions.eq(labelIds[j]).html() + '</tr>');
                updatingBackendOption = $('.updating-backend-option');
                inputOptions.eq(labelIds[j]).find('input, select, textarea').each(function () {
                    if (!$(this).hasClass('backend-option-id')) {
                        var name = $(this).attr('name');
                        if ($(this).prop('checked')) {
                            updatingBackendOption.attr('checked', 'checked');
                        }
                        updatingBackendOption.find('[name="' + name + '"]').val($(this).val());
                    }
                });
            }
            updatingBackendOption.removeClass('updating-backend-option');
        }
        inputOptions.each(function () {
            $(this).remove();
        });
        updateOrderOfInputOptions(productOption);
    }

    $(document).on('click', '.sort-input-options', function (event) {
        sortInputOptions($(this).closest('.backend-product-option'));
        event.preventDefault();
    });

    $(document).on('click', '.backend-add-new-option', function (event) {
        event.preventDefault();
        var options = $(this).closest('.backend-product-option').find('.backend-options');
        options.show();
        var maxId = 0;
        options.find('.backend-option-id').each(function () {
            if (parseInt($(this).val()) > parseInt(maxId))
                maxId = $(this).val();
        });
        maxId = parseInt(maxId) + 1;
        var productOption = $(this).closest('.backend-product-option');
        var productOptionId = productOption.find('.backend-product-option-id').val();
        var newOption = woocommerce_product_options_backend_settings['input_option']
                .replace(new RegExp("product_option_id", "g"), productOptionId)
                .replace(new RegExp("option_id", "g"), maxId);
        options.append(newOption);
        options.find('.backend-option-' + maxId).hide(0);
        options.find('.backend-option-' + maxId).show(500);
        updateDefaultOption($(this));
        ajax_option_chosen($(this));
        productOption.find('tr.backend-option').draggable({
            appendTo: "body",
            helper: "clone"
        })
        updateOptionsAndTitles();
    });


    $(document).on('click', '.backend-add-new-image-option', function (event) {
        event.preventDefault();
        var options = $(this).closest('.backend-product-option').find('.backend-options');
        options.show();
        var maxId = 0;
        options.find('.backend-option-id').each(function () {
            if (parseInt($(this).val()) > parseInt(maxId))
                maxId = $(this).val();
        });
        maxId = parseInt(maxId) + 1;
        var productOption = $(this).closest('.backend-product-option');
        var productOptionId = productOption.find('.backend-product-option-id').val();
        var newOption = woocommerce_product_options_backend_settings['image_input_option']
                .replace(new RegExp("product_option_id", "g"), productOptionId)
                .replace(new RegExp("option_id", "g"), maxId);
        options.append(newOption);
        options.find('.backend-option-' + maxId).hide(0);
        options.find('.backend-option-' + maxId).show(500);
        options.find('.backend-option-' + maxId + ' .conditional-logic').hide();
        updateDefaultOption($(this));
        ajax_option_chosen($(this));
        updateOptionsAndTitles();
    });

    $(document).on('click', '.backend-remove-option', function (event) {
        event.preventDefault();
        var rowToRemove = $(this).closest('.backend-option');
        rowToRemove.addClass('backend-remove-this-row');
        rowToRemove.hide(500);
        var element = $(this).closest('.backend-product-option').find('.backend-product-option-id');
        setTimeout(function () {
            $('.backend-remove-this-row').remove();
            updateDefaultOption(element);
            ajax_option_chosen(element);
            updateOptionsAndTitles();
        }, 500);
    });

    $(document).on('click', '.backend-remove-product-option', function (event) {
        event.preventDefault();
        var rowToRemove = $(this).closest('.backend-product-option');
        rowToRemove.addClass('backend-remove-this-row');
        rowToRemove.hide(500);
        var element = $(this).closest('.backend-product-option').find('.backend-product-option-id');
        setTimeout(function () {
            $('.backend-remove-this-row').remove();
            updateDefaultOption(element);
            ajax_option_chosen(element);
            $('input[name$="[hides][' + element.val() + ']"]').each(function () {
                $(this).closest('label').remove();
            });
            $('input[name$="[shows][' + element.val() + ']"]').each(function () {
                $(this).closest('label').remove();
            });
            updateOptionsAndTitles();
        }, 500);
    });

    function updateDefaultOption(element) {
        if (element.closest('.backend-product-option').find('.backend-option-default:checked').length == 0) {
            element.closest('.backend-product-option').find('.backend-option-default').first().attr('checked', 'checked');
        }
    }


    function hideOptions() {
        $('.backend-options').each(function () {
            if ($(this).find('tr').length == 1) {
                $(this).hide();
            }
        });
    }

    $('.default-color-picker').wpColorPicker();

    $(document).on('change', '.box-effect-select', function () {
        if ($(this).val() == 'yes') {
            $(this).closest('.backend-product-option').find('.box-effect-options').show();
        } else {
            $(this).closest('.backend-product-option').find('.box-effect-options').hide();
        }
    });
    $('.box-effect-select').change();

});