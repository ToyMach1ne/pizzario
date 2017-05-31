jQuery(document).ready(function ($) {

    if ($('.product-option').length > 0) {

        var addToCartButton = null;

        var addingToCart = false;
        var numberOfResponses = 0;
        var variation = null;
        var productPrice = parseFloat($('.product-options-product-price').val());
        //var thisExpanded = false;

        function showHideOptions() {
            $('.hide-product-options').each(function () {
                var productOptions = $(this).closest('.product-options');
                var productOption = $(this).closest('.product-option');
                var isChecked = productOption.find('input[type=checkbox]:checked').length;
                if (isChecked > 0) {
                    $(this).find('.hide-product-option').each(function () {
                        var productOptionId = $(this).val();
                        productOptions.find('.product-option-' + productOptionId).hide();
                    });
                } else {
                    $(this).find('.hide-product-option').each(function () {
                        var productOptionId = $(this).val();
                        productOptions.find('.product-option-' + productOptionId).show();
                    });
                }
            });
            $('.show-product-options').each(function () {
                var productOptions = $(this).closest('.product-options');
                var productOption = $(this).closest('.product-option');
                var isChecked = productOption.find('input[type=checkbox]:checked').length;
                if (isChecked > 0) {
                    $(this).find('.show-product-option').each(function () {
                        var productOptionId = $(this).val();
                        productOptions.find('.product-option-' + productOptionId).show();
                    });
                } else {
                    $(this).find('.show-product-option').each(function () {
                        var productOptionId = $(this).val();
                        productOptions.find('.product-option-' + productOptionId).hide();
                    });
                }
            });
        }

        showHideOptions();
        $('.product-option input[type=checkbox]').change(function () {
            showHideOptions();
        });
        function updateMessage(productOption) {
            productOption.find('.product-option-appended-message').hide();
            // setTimeout(function() {
            productOption.find('.product-option-appended-message').remove();
            var message = '';
            productOption.find('input:checked').each(function () {
                var messageElement = $(this).closest('label').find('.product-option-message');
                if (messageElement.length > 0) {
                    message = message + '<div class="product-option-appended-message">' + messageElement.html() + '</div>';
                }
            });
            productOption.find('option:selected').each(function () {
                var messageElement = productOption.find('.product-option-message-' + $(this).val());
                if (messageElement.length > 0) {
                    message = message + '<div class="product-option-appended-message">' + messageElement.html() + '</div>';
                }
            });
            if (message.length > 0) {
                productOption.append(message);
            }
            productOption.find('.product-option-appended-message').show();
            //}, 500);
        }


        function wc_price(price, showFree, addSign) {
            price = parseFloat(price);
            price = price * woocommerce_product_options_settings.currency_multiplier;
            var negative = false;
            if (price == 0 && showFree === 'yes') {
                return woocommerce_product_options_settings.free;
            } else {
                if (price < 0) {
                    negative = true;
                }
                price = price.toFixed(woocommerce_product_options_settings.number_of_decimals);
                if (negative) {
                    price = price.replace('-', '');
                }
            }
            if (price.indexOf('.') >= 0) {
                var formatted_price_before_decimal = price.split('.')[0];
                if (formatted_price_before_decimal.length > 3) {
                    var i = formatted_price_before_decimal.length - 1;
                    var new_string = '';
                    var j = 0;
                    while (i >= 0) {
                        if (j % 3 == 0 && j > 0) {
                            new_string = woocommerce_product_options_settings.thousands_separator + new_string;
                        }
                        new_string = formatted_price_before_decimal[i] + new_string;
                        j++;
                        i--;
                    }
                    formatted_price_before_decimal = new_string;
                }
                var formatted_price_after_decimal = '';
                if (price.split('.').length > 0) {
                    formatted_price_after_decimal = price.split('.')[1];
                    price = formatted_price_before_decimal + woocommerce_product_options_settings.decimal_separator + formatted_price_after_decimal;
                } else {
                    price = formatted_price_before_decimal;
                }
            }
            var price_format = woocommerce_product_options_settings.price_format;
            if (addSign && negative) {
                price_format = '<span class="price-sign">-</span>' + price_format;
            } else if (addSign) {
                price_format = '<span class="price-sign">+</span>' + price_format;
            }
            var formatted_price = price_format.replace('%1$s', woocommerce_product_options_settings.currency_symbol).replace('%2$s', price);
            var returnValue = formatted_price;
            return returnValue;
        }

        function wpshowcaseParseFloat(stringToParse) {
            if (typeof stringToParse == 'undefined') {
                return 0;
            }
            return parseFloat(stringToParse);
        }

        function getOptionPrice(productOption, productPrice) {
            productOption.find('.required-error').remove();
            if ($('.required-error').length == 1) {
                $('.required-error').remove();
            }
            var price = 0;
            var type = productOption.find('.product-option-type').val();
            if (type == 'radio' || type == 'select' || type == 'radio_image' || type == 'checkboxes' || type == 'multi_select') {
                productOption.find('input:checked').each(function () {
                    var label = $(this).closest('label');
                    price += parseFloat(label.find('.product-option-option-price').val());
                    price += parseFloat(label.find('.product-option-option-percentage-price').val()) * productPrice / 100;
                });
                productOption.find('option:selected').each(function () {
                    var optionId = $(this).attr('value');
                    var span = productOption.find('.product-option-option-' + optionId + '-data');
                    price += parseFloat(span.find('.product-option-option-price').val());
                    price += parseFloat(span.find('.product-option-option-percentage-price').val()) * productPrice / 100;
                });
            } else if (type == 'text' || type == 'textarea') {
                var priceIfNotEmpty = wpshowcaseParseFloat(productOption.find('.product-option-price-if-not-empty').val());
                var pricePerCharacter = wpshowcaseParseFloat(productOption.find('.product-option-price-per-character').val());
                var pricePerWord = wpshowcaseParseFloat(productOption.find('.product-option-price-per-word').val());
                var pricePerLowerCaseCharacter = wpshowcaseParseFloat(productOption.find('.product-option-price-per-lower-case-character').val());
                var pricePerUpperCaseCharacter = wpshowcaseParseFloat(productOption.find('.product-option-price-per-upper-case-character').val());
                var value = productOption.find('input[type=text],textarea').val();
                value = $.trim(value);
                if (value != '' && $.isNumeric(priceIfNotEmpty)) {
                    price += priceIfNotEmpty;
                }
                var len = value.length;
                var words = value.match(/\S+/g);
                var numberOfWords = 0;
                if (words != null) {
                    numberOfWords = words.length;
                }
                var numberOfCharacters = value.length;
                var numberOfLowerCaseCharacters = 0;
                for (var i = 0; i < len; i++) {
                    if (/[a-z]/.test(value.charAt(i)))
                        numberOfLowerCaseCharacters++;
                }
                var numberOfUpperCaseCharacters = 0;
                for (var i = 0; i < len; i++) {
                    if (/[A-Z]/.test(value.charAt(i)))
                        numberOfUpperCaseCharacters++;
                }
                price += numberOfWords * pricePerWord + numberOfCharacters * pricePerCharacter + pricePerLowerCaseCharacter * numberOfLowerCaseCharacters + pricePerUpperCaseCharacter * numberOfUpperCaseCharacters;
            } else if (type == 'number' || type == 'number_as_text' || type == 'range') {
                var value = productOption.find('.product-option-value').val();
                if (value === '') {
                    value = 0;
                }
                var startingFrom = parseFloat(productOption.find('.product-option-starting-from').val());
                var percentagePrice = parseFloat(productOption.find('.product-option-percentage-price').val());
                price = (parseFloat(value) - startingFrom) * percentagePrice / 100;
            } else if (type == 'checkbox') {
                if (productOption.find('input[type="checkbox"]:checked').length > 0) {
                    price = parseFloat(productOption.find('.product-option-checked-price').val());
                } else {
                    price = parseFloat(productOption.find('.product-option-unchecked-price').val());
                }
            } else if (type == 'image_upload') {
                var src = productOption.find('.product-options-image-upload').val();
                var defaultFileName = productOption.find('.product-option-default-file-name').val();
                if (!src.indexOf(defaultFileName) > 0) {
                    price = parseFloat(productOption.find('.product-option-price-for-image').val());
                }
            }
            return price;
        }

        function updateOptionChanged(optionChanged) {
            if ($('table.variations').length == 0) {
                productPrice = parseFloat(optionChanged.closest('.product-options').find('.product-options-product-price').val());
            }
            optionChanged = optionChanged.closest('.product-option');
            var summary = optionChanged.closest('.summary, .product-top,  .product-option-summary-wrapper, .main-data, .product-info, li.product, .product');

            var optionPrice = getOptionPrice(optionChanged, productPrice);
            var totalPrice = productPrice;
            summary.find('.product-option:visible').each(function () {
                var productOptionPrice = getOptionPrice($(this), productPrice);
                totalPrice += productOptionPrice;
            });

            var amountToUpdate = $('.woocommerce-product-options-length-zero');
            var summary = optionChanged.closest('.summary, .product-top,  .product-option-summary-wrapper, .main-data, .product-info, li.product, .product');
            var product = optionChanged.closest('.product');
            if (product.find('.variations').length > 0) {
                amountToUpdate = product.find('.woocommerce-variation-price .amount');
                if (amountToUpdate.length == 0) {
                    amountToUpdate = product.find('.woocommerce-variation-price .price');
                }
            }
            if (amountToUpdate.length == 0) {
                if (summary.find('.price').length == 1 && summary.find('.price .amount').length < 2) {
                    amountToUpdate = summary.find('.price');
                }
            }
            var productOnSale = false;
            if (summary.find('ins .amount').length === 1) {
                amountToUpdate = summary.find('ins .amount');
                productOnSale = true;
            } else if (amountToUpdate.find('.amount').length === 1) {
                amountToUpdate = amountToUpdate.find('.amount');
            }
            if (amountToUpdate.length == 0 && product.find('.variations').length == 0) {
                summary = optionChanged.closest('.product');
                var amountToUpdate = summary.find('.price');
                if (amountToUpdate.find('.amount').length === 1) {
                    amountToUpdate = amountToUpdate.find('.amount');
                }
            }
            var optionPriceToUpdate = optionChanged.find('.current-price');
            var showFree = 'yes';
            if (optionChanged.find('.hide-free').length > 0 && optionChanged.find('.hide-free').val() === 'yes') {
                showFree = 'no';
            }
            amountToUpdate.html(wc_price(totalPrice, showFree, false));
            optionPriceToUpdate.html(wc_price(optionPrice, showFree, true));
            if (optionChanged.find('.show-price').val() === 'total') {
                optionPriceToUpdate.html(wc_price(totalPrice, showFree, false));
            }
            updateSubOptionPrices(productPrice, totalPrice);

            if (!productOnSale) {
                return;
            }
            if ($('table.variations').length == 0) {
                productPrice = parseFloat(optionChanged.closest('.product-options').find('.product-options-product-nonsale-price').val());
            }
            var totalPrice = productPrice;
            summary.find('.product-option:visible').each(function () {
                var productOptionPrice = getOptionPrice($(this), productPrice);
                totalPrice += productOptionPrice;
            });
            var amountToUpdate = summary.find('.price');
            if (amountToUpdate.find('ins').length > 0) {
                amountToUpdate = amountToUpdate.find('del').find('.amount');
            }
            amountToUpdate.html(wc_price(totalPrice, 'yes', false));
            if ($('table.variations').length == 0) {
                productPrice = parseFloat(optionChanged.closest('.product-options').find('.product-options-product-price').val());
            }
        }

        function updateSubOptionPrices(productPrice, totalPrice) {
            $('.product-option').each(function () {
                var needsUpdating = $(this).find('.show-price').val();
                if (needsUpdating !== 'yes') {
                    return;
                }
                if ($(this).find('.product-option-option-price').length > 0) {
                    var currentOptionPrice = getOptionPrice($(this), productPrice);
                    var basePrice = totalPrice;
                    if ($(this).find('input[type="checkbox"]').length === 0 && !$(this).hasClass('product-option-multi_select')) {
                        basePrice = totalPrice - currentOptionPrice;
                    }
                    var showFree = 'yes';
                    if ($(this).find('.hide-free').length > 0 && $(this).find('.hide-free').val() === 'yes') {
                        showFree = 'no';
                    }
                    $(this).find('.product-option-option-data').each(function () {
                        var label = $(this).find('.product-option-option-label').val();
                        var id = $(this).find('.product-option-option-id').val();
                        var option = $(this).closest('.product-option').find('option[value="' + id + '"]');
                        var suboptionPrice = parseFloat($(this).find('.product-option-option-price').val()) + productPrice * parseFloat($(this).find('.product-option-option-percentage-price').val()) / 100;
                        if (option.is(':selected')) {
                            suboptionPrice = 0;
                        }
                        option.text(label + ' (' + wc_price(basePrice + suboptionPrice, showFree, false) + ')');
                    });
                    $(this).find('label .product-option-option-price').each(function () {
                        var label = $(this).closest('label');
                        var originalLabel = label.find('.product-option-option-label').val();
                        var suboptionPrice = parseFloat(label.find('.product-option-option-price').val()) + productPrice * parseFloat(label.find('.product-option-option-percentage-price').val()) / 100;
                        if (label.find('input[type="checkbox"]').length > 0 && label.find('input[type="checkbox"]').is(':checked')) {
                            suboptionPrice = 0;
                        }
                        label.find('.product-option-suboption-label').html(originalLabel + ' (' + wc_price(basePrice + suboptionPrice, showFree, false) + ')');
                    });
                }
            });
        }

        function ajax_update_option(optionChanged, currentVariation) {
            if (optionChanged.length == 0) {
                return;
            }
            updateSelectedBoxEffect();
            var postType = optionChanged.closest('.product-options').find('.product-options-post-type').val();
            if (postType != 'order_option_group' && optionChanged.find('.upload-image').length == 0) {
                updateOptionChanged(optionChanged);
                return;
            }
            update_option_with_ajax(optionChanged);
        }

        function getProductOptionValue(optionChanged) {
            var productOption = optionChanged.val();
            if (optionChanged.closest('.product-option').find('input[type=checkbox].radio-image-radio').length > 0) {
                productOption = [];
                optionChanged.closest('.product-option').find('input[type=checkbox]:checked.radio-image-radio').each(function () {
                    productOption.push($(this).val());
                });
            }
            if (optionChanged.closest('.product-option').find('input[type=checkbox].product-option-checkbox').length > 0) {
                productOption = '';
                if (optionChanged.closest('.product-option').find('input[type=checkbox].product-option-checkbox:checked').length > 0) {
                    productOption = 'yes';
                }
            }
            var checkboxes = optionChanged.closest('.product-option').find('.checkboxes-checkbox');
            if (checkboxes.length > 0) {
                productOption = [];
                var optionId = optionChanged.closest('.product-option').find('.product-option-id').val();
                var optionPostId = optionChanged.closest('.product-options').find('.product-option-post-id').val();
                optionChanged.closest('.product-option').find('.checkboxes-checkbox:checked').each(function () {
                    productOption.push($(this).attr('name').replace('product-options[' + optionPostId + '][' + optionId + '][', '').replace(']', ''));
                });
            }
            /*var radioImageCheckboxes = optionChanged.closest('.product-option').find('.radio-image-radio[type=checkbox]');
             if (radioImageCheckboxes.length > 0) {
             productOption = [];
             var optionId = optionChanged.closest('.product-option').find('.product-option-id').val();
             optionChanged.closest('.product-option').find('.radio-image-radio:checked').each(function () {
             productOption.push($(this).attr('name').replace('product-options[' + optionId + '][', '').replace(']', ''));
             });
             }*/
            return productOption;
        }

        function update_option_with_ajax(optionChanged) {
            return;
            //Disable cart button and options
            $('form.cart button').prop("disabled", true);
            $('form.cart button').css('opacity', '0.5');
            $('.product-option, .variations').find('input, select, textarea').prop("disabled", true);
            $('.product-option').find('input, select, textarea').css('opacity', '0.5');
            $('.product-option').find('input, select, textarea').css('opacity', '0.5');
            $('.variations').find('.value, .product_value, .product-value').css('opacity', '0.5');
            //Accordion
            var productOptions = optionChanged.closest('.product-options');
            if (productOptions.length > 0 && productOptions.find('.accordion-keep-open-on-select').val() == '' && productOptions.find('.accordion-expand').length > 0) {
                optionChanged.closest('.product-option').find('.product-option-accordion-content').hide(500);
                optionChanged.closest('.product-option').find('.accordion-expand').html('+');
            }
//Price
            var summary = optionChanged.closest('.summary, .product-top,  .product-option-summary-wrapper, .main-data, .product-info, li.product, .product');
            var amountToUpdate = $('.woocommerce-product-options-length-zero');
            if (summary.find('.variations').length > 0) {
                amountToUpdate = summary.find('.single-variation, .single_variation');
            }
            if (amountToUpdate.length == 0) {
                if ($('.price').length == 1 && $('.price .amount').length < 2) {
                    amountToUpdate = $('.price');
                }
            }
            var optionPriceToUpdate = optionChanged.closest('.product-option').find('.current-price');
            var productOption = getProductOptionValue(optionChanged);
            var fpd_product_price = '';
            if ($('input[name=fpd_product_price]').length > 0) {
                fpd_product_price = $('input[name=fpd_product_price]').val();
            }

            var attributes = {};
            summary.find('.variations').find('input, select').each(function () {
                attributes[$(this).attr('name')] = $(this).val();
            });
            var post_id = optionChanged.closest('.product-options').find('.product-post-id').val();
            var product_option_post_id = optionChanged.closest('.product-option').find('.product-option-post-id').val();
            var product_option_id = optionChanged.closest('.product-option').find('.product-option-id').val();
            var variation_id = 0;
            if (summary.find('input[name="variation_id"]').length > 0) {
                variation_id = summary.find('input[name="variation_id"]').val();
            }
            optionChanged.find('.product-option-error').remove();
            $.post(woocommerce_product_options_settings.ajaxurl,
                    {
                        'action': 'update_product_option',
                        'post_id': post_id,
                        'variation_id': variation_id,
                        'product_option_post_id': product_option_post_id,
                        'product_option': productOption,
                        'product_option_id': product_option_id,
                        'attributes': attributes,
                        fpd_product_price: fpd_product_price
                    },
            function (response) {
                response = $.parseJSON(response);
                amountToUpdate.html(response.price);
                optionPriceToUpdate.html(response.option_price);
                if (response.src != '') {
                    optionChanged.find('.upload-image').attr('src', response.src);
                }
                $('form.cart button').prop("disabled", false);
                $('form.cart button').css('opacity', '1');
                $('.product-option, .variations').find('input, select, textarea').prop("disabled", false);
                $('.product-option').find('input, select, textarea').css('opacity', '1');
                $('.variations').find('.value, .product_value, .product-value').css('opacity', '0.5');
                numberOfResponses--;
                if (numberOfResponses == 0) {
                    $('.single_add_to_cart_button').click();
                }
                $('body').trigger('update_checkout');
            });
        }

        $('.product-option:not(.product-option-tooltip)').each(function () {
            var productOption = $(this);
            productOption.find('input, option, select').change(function () {
                updateMessage(productOption);
            });
            updateMessage(productOption);
        });
        $('.product-option .max-selected').each(function () {
            var maxSelected = parseInt($(this).val());
            var parent = $(this).closest('.product-option');
            parent.find('input[type=checkbox],select').change(function (event) {
                $('.max-checkboxes-error').remove();
                var numberSelected = parent.find('input[type=checkbox]:checked, option:selected').length;
                if (numberSelected > maxSelected) {
                    $(this).removeAttr('checked');
                    $(this).find('option:selected').removeAttr('selected');
                    $(this).closest('label,select').after('<span class="max-checkboxes-error product-option-error"> ' + woocommerce_product_options_settings.checkboxes_max_error + '</span>');
                    updateMessage(parent);
                    event.preventDefault();
                }
            });
        });
        $('.product-option-datepicker').on('change', '.product-option-datepicker-div', function () {
            var productOption = $(this).closest('.product-option');
            var date = $.datepicker.formatDate($(this).datepicker('option', 'dateFormat'), $(this).datepicker('getDate'));
            productOption.find('.product-option-value').val(date);
        });
        function addDatepicker(element, options) {
            var productOption = element.closest('.product-option');
            options.defaultDate = productOption.find('.date-chosen').val();
            if (productOption.find('.disallow-past').val() != '') {
                options.minDate = -1;
            }
            if (productOption.find('.disallow-today').val() != '') {
                options.minDate = 0;
            }
            if (productOption.find('.disallow-weekends').val() != '') {
                options.beforeShowDay = function (date) {
                    var day = date.getDay();
                    if (day == 0 || day == 6) {
                        return new Array(false, '', date.toLocaleDateString());
                    }
                    return new Array(true, '', date.toLocaleDateString());
                }
            }
            var locale = productOption.find('.locale').val();
            if (locale != '') {
                element.datepicker($.datepicker.regional[ locale ]);
            } else {
                element.datepicker(options);
            }
        }
        $('.product-option-input-datepicker').each(function () {
            addDatepicker($(this), {});
        });
        $('.product-option-datepicker-div').each(function () {
            var nameOfInput = $(this).find('.name-of-input').val();
            addDatepicker($(this), {altField: 'input[name="' + nameOfInput + '"]',
                onSelect: function () {
                    var productOption = $(this).closest('.product-option');
                    var nameOfInput = productOption.find('.name-of-input').val();
                    $('input[name="' + nameOfInput + '"]').change();
                }});
        });
        $('.datepicker-icon').each(function () {
            $(this).closest('.product-option').find('.product-option-datepicker-div').hide();
        });
        $('.datepicker-icon').click(function (event) {
            $(this).closest('.product-option').find('.product-option-datepicker-div').show(1000);
            $(this).hide(1000);
            event.preventDefault();
        });
        $('.datepicker-icon-value').change(function () {
            /*var productOption = $(this).closest('.product-option');
             productOption.find('.datepicker-icon').show(1000);
             productOption.find('.product-option-datepicker-div').hide(1000);*/
        });
        $('.accordion-group').click(function () {
            var productOptions = $(this).closest('.product-option-groups');
            if (productOptions.find('.accordion-keep-open').val() == '') {
                productOptions.find('.product-option-accordion-group-content').hide(500);
            }
            var thisAccordionExpand = $(this).find('.accordion-group-expand');
            var thisExpanded = false;
            if (thisAccordionExpand.text().indexOf('+') != -1) {
                $(this).closest('.product-option-group').find('.product-option-accordion-group-content').show(500);
                $(this).find('.accordion-group-expand').html('-');
                thisExpanded = true;
            }
            if (productOptions.find('.accordion-keep-open').val() == '') {
                productOptions.find('.accordion-group-expand').html('+');
            } else {
                if (!thisExpanded) {
                    $(this).closest('.product-option').find('.product-option-accordion-group-content').hide(500);
                    $(this).find('.accordion-group-expand').html('+');
                }
            }
        });
        $('.product-option-accordion-group-content').hide(500);
        $('.accordion').click(function () {
            var productOptions = $(this).closest('.product-options');
            if (productOptions.find('.accordion-keep-open').val() == '') {
                productOptions.find('.product-option-accordion-content').hide(500);
            }
            var thisAccordionExpand = $(this).find('.accordion-expand');
            var thisExpanded = false;
            if (thisAccordionExpand.text().indexOf('+') != -1) {
                $(this).closest('.product-option').find('.product-option-accordion-content').show(500);
                thisExpanded = true;
            }
            if (productOptions.find('.accordion-keep-open').val() == '') {
                productOptions.find('.accordion-expand').html('+');
            } else {
                if (!thisExpanded) {
                    $(this).closest('.product-option').find('.product-option-accordion-content').hide(500);
                    $(this).find('.accordion-expand').html('+');
                }
            }
            if (thisExpanded) {
                $(this).find('.accordion-expand').html('-');
            }
        });
        $('.product-options').each(function () {
            if ($(this).find('.accordion').length > 0) {
                $('.product-option-accordion-content').hide();
            }
        });
        $('.number-as-text').change(function () {
            $('.number-as-text-error').remove();
            $('.number-as-text').each(function () {
                if (!$.isNumeric($(this).val())) {
                    $(this).after(woocommerce_product_options_settings.numeric_error);
                }
            });
        });
        $('.product-options').each(function () {
            $(this).find('input[type=text], textarea').keyup(function () {
                var productOption = $(this).closest('.product-option');
                var value = $(this).val();
                var numberOfLineBreaks = (value.match(/\n/g) || []).length;
                if (productOption.find('.maximum-number-of-characters').length > 0) {
                    var maximumNumberOfCharacters = parseInt(productOption.find('.maximum-number-of-characters').val());
                    var numberOfCharacters = parseInt($(this).val().length);
                    if (numberOfCharacters - numberOfLineBreaks > maximumNumberOfCharacters) {
                        $(this).val(value.substring(0, maximumNumberOfCharacters + numberOfLineBreaks));
                    }
                }
            })
        });
        $('.product-option').each(function () {
            $(this).find('input[type=color], input[type=text], input[type=number], input[type=checkbox], input[type=radio], input[type=checkbox], .product-options-image-upload, select, textarea').change(function () {
                ajax_update_option($(this), null);
            });
        });
        $('.product-option-number .product-option-value').on('spinstop', function () {
            ajax_update_option($(this), null);
        });
        $(document.body).on('adding_to_cart', function (data1, data2, data) {
            var product = $(this).closest('.summary, .product-top, .product-option-summary-wrapper, .main-data, .product-info, li.product, .product');
            var productPostId = product.find('.product-post-id').val();
            var productOptionss = {};
            $('.product-options').each(function () {
                var productOptions = {};
                var productOptionPostId = $(this).find('.product-option-post-id').val();
                $(this).find('.product-option').each(function () {
                    var productOptionId = $(this).find('.product-option-id').val();
                    var productOption = $(this).find('.product-option-value').val();
                    var optionChanged = $(this);
                    if (optionChanged.closest('.product-option').find('input[type=checkbox].radio-image-radio').length > 0) {
                        productOption = [];
                        optionChanged.closest('.product-option').find('input[type=checkbox]:checked.radio-image-radio').each(function () {
                            productOption.push($(this).val());
                        });
                    }
                    if (optionChanged.closest('.product-option').find('input[type=checkbox].product-option-checkbox').length > 0) {
                        productOption = '';
                        if (optionChanged.closest('.product-option').find('input[type=checkbox].product-option-checkbox:checked').length > 0) {
                            productOption = 'yes';
                        }
                    }
                    var checkboxes = optionChanged.closest('.product-option').find('.checkboxes-checkbox');
                    if (checkboxes.length > 0) {
                        productOption = [];
                        var optionId = optionChanged.closest('.product-option').find('.product-option-id').val();
                        var optionPostId = optionChanged.closest('.product-options').find('.product-option-post-id').val();
                        optionChanged.closest('.product-option').find('.checkboxes-checkbox:checked').each(function () {
                            productOption.push($(this).attr('name').replace('product-options[' + optionPostId + '][' + optionId + '][', '').replace(']', ''));
                        });
                    }
                    productOptions[productOptionId] = productOption;
                });
                productOptionss[productOptionPostId] = productOptions;
            });
            data['variation_id'] = '';
            data['product_post_id'] = data.product_id;
            data['product-options'] = productOptionss;
        });

        $(document).on('found_variation', 'form', function (event, currentVariation) {
            variation = currentVariation;
            if (variation != null) {
                productPrice = variation.display_price;
            }
        });
        $('form').on('woocommerce_variation_has_changed', function () {
            var summary = $(this).closest('.summary, .product-top,  .product-option-summary-wrapper, .main-data, li.product, .product-info, .product');
            var productOptions = summary.find('.product-options');
            if (productOptions.length > 0) {
                ajax_update_option(productOptions.find('.product-option').find('input[type=color], input[type=text], input[type=number], input[type=checkbox], input[type=radio], input[type=checkbox], .product-options-image-upload, select, textarea').first(), variation);
            }
        });
        var updateTimer = null;
        $('.product-option').find('input[type=color], input[type=text], input[type=number], input[type=checkbox], input[type=radio], input[type=checkbox], .product-options-image-upload, select, textarea').keyup(function () {
            clearTimeout(updateTimer);
            var productOptionToUpdate = $(this);
            updateTimer = setTimeout(function () {
                ajax_update_option(productOptionToUpdate, null);
            }, 5000);
        });

        $('.product-options').closest('.summary, .product-top,  .product-option-summary-wrapper, .main-data, li.product, .product-info, .product').find('input[name=variation_id]').change(function () {
            if ($(this).val() != '') {
                ajax_update_option($('.product-option .product-option-content').find('input, select, textarea').first(), null);
            }
        });
        $('input[name=fpd_product_price]').change(function () {
            ajax_update_option($('.product-option .product-option-content').find('input, select, textarea').first(), null);
        });
        $('.product-option input[type=radio]').change(function () {
            $(this).closest('.product-option').find('.product-option-value').removeClass('product-option-value');
            $(this).addClass('product-option-value');
        });
        $('.product-option input[type=radio]:checked').addClass('product-option-value');
        $('.radio-image-radio').change(function () {
            var img = $(this).closest('.radio-image-label').find('.radio-image-image');
            if (img.hasClass('change-thumb-on-select')) {
                var imageToChange;
                if (img.closest('.product').find('img.woocommerce-main-image, .woocommerce-main-image img, .wp-post-image, #product-img-slider .product-slider-image, .thb-product-slideshow rsImg').length > 0) {
                    imageToChange = img.closest('.product').find('img.woocommerce-main-image, .woocommerce-main-image img, .wp-post-image, #product-img-slider .product-slider-image, .thb-product-slideshow rsImg');
                } else {
                    imageToChange = img.closest('.product').find('.images').find('img').first().attr('src', img.attr('src'));
                }
                imageToChange.attr('src', img.attr('src'));
                var srcset = img.attr('srcset');
                if (typeof srcset != 'undefined' && srcset != '') {
                    imageToChange.attr('srcset', img.attr('srcset'));
                } else {
                    imageToChange.removeAttr('srcset');
                }
                $('a.zoom, a[data-rel^=\'prettyPhoto\']').attr('href', img.attr('src'));
            }
        });
        $('.product-option-spinner').each(function () {
            $(this).spinner({
                min: 0, //$(this).attr('min'),
                max: 100, /// $(this).attr('max'),
                start: 3, //$(this).closest('.product-option').find('.product-option-value').val(),
                numberFormat: 'n',
                slide: function (event, ui) {
                    $(this).closest('.product-option').find('.product-option-value').val(ui.value);
                }
            });
        });
        $('.product-option-slider').each(function () {
            $(this).slider({range: false,
                min: parseFloat($(this).attr('min')),
                max: parseFloat($(this).attr('max')),
                value: $(this).closest('.product-option').find('.product-option-value').val(),
                slide: function (event, ui) {
                    $(this).closest('.product-option').find('.product-option-value').val(ui.value);
                }
            });
        });
        function updateRange(element) {
            var productOption = element.closest('.product-option');
            var unit = productOption.find('.range-unit').val();
            productOption.find('.range-value').remove();
            var productOptionValue = productOption.find('.product-option-value');
            productOption.find('.product-option-content').append('<span class="range-value"> ' + productOptionValue.val() + ' ' + unit + '</span>');
        }
        $('.product-option').find('.product-option-slider').mouseup(function () {
            ajax_update_option($(this).closest('.product-option').find('.product-option-value'), null);
            updateRange($(this));
        });
        $('.product-option').find('.product-option-slider').keyup(function () {
            ajax_update_option($(this).closest('.product-option').find('.product-option-value'), null);
            updateRange($(this));
        });
        $('.product-option .product-option-slider').each(function () {
            updateRange($(this));
        });

        function addError(productOption) {
            productOption.closest('.product-option-accordion-group-content, .product-option-accordion-content').show();
        }

        function checkForErrorsBeforeCartSubmit(form, event) {
            $('.required-error, .min-checkboxes-error').remove();
            var requiredErrorExists = false;
            form.find('.product-option').each(function () {
                var requiredProductOption = $(this).find('.required-product-option').first();
                if (requiredProductOption.length > 0) {
                    if (requiredProductOption.attr('type') == 'checkbox' || requiredProductOption.attr('type') == 'radio') {
                        var productOption = requiredProductOption.closest('div.product-option');
                        requiredErrorExists = true;
                        productOption.find('input[type="radio"], input[type="checkbox"]').each(function () {
                            if ($(this).is(':checked')) {
                                requiredErrorExists = false;
                            }
                        });
                        if (requiredErrorExists) {
                            addError(requiredProductOption);
                            if (requiredProductOption.closest('div.product-option').find('.required-error').length == 0) {
                                requiredProductOption.closest('div.product-option').append('<span class="required-error"> ' + woocommerce_product_options_settings.required_error + '</span>');
                            }
                        }
                    } else if (requiredProductOption.hasClass('product-options-image-upload')) {
                        if (requiredProductOption.val() == "" || requiredProductOption.val().indexOf(requiredProductOption.closest('.product-option').find('.product-option-default-file-name').val()) > 0) {
                            addError(requiredProductOption);
                            requiredErrorExists = true;
                            requiredProductOption.closest('div.product-option').append('<span class="required-error"> ' + woocommerce_product_options_settings.required_error + '</span>');
                        }
                    } else {
                        if (requiredProductOption.val().length == 0) {
                            addError(requiredProductOption);
                            requiredErrorExists = true;
                            requiredProductOption.after('<span class="required-error"> ' + woocommerce_product_options_settings.required_error + '</span>');
                        }
                    }
                    if (requiredProductOption.attr('type') == 'checkbox' && !requiredProductOption.hasClass('checkboxes-checkbox')) {
                        if (requiredProductOption.closest('.product-option').find(':checked').length == 0) {
                            addError(requiredProductOption);
                            requiredErrorExists = true;
                            requiredProductOption.after('<span class="required-error"> ' + woocommerce_product_options_settings.required_error + '</span>');
                        }
                    }
                }
            });
            form.find('.product-option .min-selected').each(function () {
                var minSelected = parseInt($(this).val());
                var parent = $(this).closest('.product-option');
                parent.find('.min-checkboxes-error').remove();
                var numberSelected = parent.find('input[type=checkbox]:checked, option:selected').length;
                if (numberSelected < minSelected) {
                    parent.find('label, select').last().after('<span class="min-checkboxes-error product-option-error"> ' + woocommerce_product_options_settings.checkboxes_min_error + '</span>');
                    addError($(this));
                    requiredErrorExists = true;
                }
            });
            form.find('.product-options').find('.number-as-text, .product-option-number .product-option-value').each(function () {
                var min = $(this).attr('min');
                var max = $(this).attr('max');
                var value = $(this).val();
                if (isNaN(value)) {
                    addError($(this));
                    requiredErrorExists = true;
                    $(this).after('<span class="required-error product-option-error"> ' + woocommerce_product_options_settings.number_error + '</span>');
                } else {
                    value = parseFloat(value);
                    if (typeof min != 'undefined') {
                        min = parseFloat(min);
                        if (min > value) {
                            addError($(this));
                            requiredErrorExists = true;
                            $(this).after('<span class="required-error product-option-error"> ' + woocommerce_product_options_settings.min_error + min + '</span>');
                        }
                    }
                    if (typeof max != 'undefined') {
                        max = parseFloat(max);
                        if (max < value) {
                            addError($(this));
                            requiredErrorExists = true;
                            $(this).after('<span class="required-error product-option-error"> ' + woocommerce_product_options_settings.max_error + max + '</span>');
                        }
                    }
                }
            });
            if (requiredErrorExists) {
                form.find('.add_to_cart_button, form.cart button, #place_order, #place-order').after('<span class="required-error product-option-error">' + woocommerce_product_options_settings.error_exists + '</span>');
                event.preventDefault();
                return false;
            }
            return true;
        }

        $('.woocommerce').on('click', '#place_order, #place-order', function (event) {
            if (!checkForErrorsBeforeCartSubmit($('.woocommerce'), event)) {
                return false;
            }
            return true;
        });

        $('.woocommerce-cart .product-options').find('input, select').change(function () {
            $(document.body).trigger('shipping_method_selected');
        });

        $('.woocommerce-checkout .product-options').find('input, select').change(function () {
            $(document.body).trigger('update_checkout');
        });

        var productOptionsToClone = {};
        $(document.body).on('update_checkout', function () {
            $('.product-options').each(function () {
                if ($(this).closest('form.checkout').length == 0) {
                    var multipleSelectValues = {};
                    $(this).each(function () {
                        var name = $(this).attr('name');
                        multipleSelectValues[name] = new Array();
                        var i = 0;
                        $(this).find('option:selected').each(function () {
                            multipleSelectValues[name][i] = $(this).val();
                            i++;
                        });
                    });
                    var options = $(this).clone(true);
                    $(this).find('.product-option:hidden').each(function () {
                        var productOptionId = $(this).find('.product-option-id').val();
                        var productOptionPostId = $(this).find('.product-option-post-id').val();
                        options.append('<input type="hidden" name="product-options-hidden[' + productOptionPostId + '][' + productOptionId + ']" value="yes" />');
                    });
                    $('.product-option-groups, .product-options').find('input[type=text], input[type=number], textarea').each(function () {
                        var name = $(this).attr('name');
                        options.find('input[name="' + name + '"], textarea[name="' + name + '"]').val($(this).val());
                    });
                    options.hide(0);
                    options.addClass('product-options-delete-me');
                    options.find('.product-option-checkbox input[type="checkbox"]').each(function () {
                        if (!$(this).is(':checked')) {
                            $(this).val('no');
                            $(this).attr('checked', 'checked');
                        }
                    });
                    $('form.checkout').append(options);
                    var checkoutForm = $('form.checkout');
                    options.hide(0);
                    $.each(multipleSelectValues, function (index, value) {
                        var select = checkoutForm.find('select[name="' + index + '"]');
                        $.each(value, function (valueindex, valuevalue) {
                            select.find('option[value="' + valuevalue + '"]').attr('selected', 'selected');
                        });
                    });
                }
            });
        });
        $(document.body).on('updated_checkout', function () {
            $('.product-options-delete-me').remove();
        });
        $(document.body).on('updated_shipping_method', function () {
            $('.product-options-delete-me').remove();
        });

        var clonedOptions = null;
        $('form.cart, li.product, .product').on('click', '.add_to_cart_button, .single_add_to_cart_button, form.cart button, form.cart input[type=submit]', function (event) {
            addToCartButton = $(this);
            var product = $(this).closest('.product');
            if (product.length == 1) {
                if (!checkForErrorsBeforeCartSubmit(product, event)) {
                    return false;
                }
            }
            var multipleSelectValues = {};
            $('.product-option-groups select, .product-options select').each(function () {
                var name = $(this).attr('name');
                multipleSelectValues[name] = new Array();
                var i = 0;
                $(this).find('option:selected').each(function () {
                    multipleSelectValues[name][i] = $(this).val();
                    i++;
                });
            });
            if (clonedOptions != null) {
                clonedOptions.remove();
            }
            var serializedOptions = $('.product-option-groups, .product-options, input[name=fpd_product_price]').not('form.cart .product-option-groups, form.cart .product-options, form.cart input[name=fpd_product_price]').find(':input[name]').serialize();
            
            //clonedOptions = $('.product-option-groups, .product-options, input[name=fpd_product_price]').not('form.cart .product-option-groups, form.cart .product-options, form.cart input[name=fpd_product_price]').clone();
            /*$('.product-option-groups, .product-options, input[name=fpd_product_price]').find('.product-option:hidden').each(function () {
                var productOptionId = $(this).find('.product-option-id').val();
                var productOptionPostId = $(this).find('.product-option-post-id').val();
                clonedOptions.append('<input type="hidden" name="product-options-hidden[' + productOptionPostId + '][' + productOptionId + ']" value="yes" />');
            });
            clonedOptions.find('.product-option-checkbox input[type="checkbox"]').each(function () {
                if (!$(this).is(':checked')) {
                    $(this).val('no');
                    $(this).attr('checked', 'checked');
                }
            });
            addToCartButton.after(clonedOptions);
            var addToCartForm = addToCartButton.closest('form');
            clonedOptions.hide(0);
            $('.product-option-groups, .product-options').not('form.cart .product-option-groups, form.cart .product-options, form.cart input[name=fpd_product_price]')
                .find('input[type=text], input[type=number], textarea').each(function () {
                var name = $(this).attr('name');
                clonedOptions.find('input[name="' + name + '"], textarea[name="' + name + '"]').val($(this).val());
            });
            $('.product-option-groups, .product-options').not('form.cart .product-option-groups, form.cart .product-options, form.cart input[name=fpd_product_price]')
                    .find('input[type=radio], input[type=checkbox]').each(function () {
                if ($(this).is(':checked')) {
                    var value = $(this).val();
                    var name = $(this).attr('name');
                    clonedOptions.find('input[name="' + name + '"][value="' + value + '"]').attr('checked', 'checked');
                }
            });
            $.each(multipleSelectValues, function (index, value) {
                var select = addToCartForm.find('select[name="' + index + '"]');
                $.each(value, function (valueindex, valuevalue) {
                    select.find('option[value="' + valuevalue + '"]').attr('selected', 'selected');
                });
            });*/
            return true;
        });

        $(window).load(function () {
            $('.product-option').each(function () {
                var element = $(this).find('input[type=color], input[type=text], .product-options-image-upload, input[type=number], input[type=radio], input[type=checkbox], select, textarea').first();
                ajax_update_option(element, null);
            });
        });
        $('.product-options .product-option-tooltip').tooltip({
            track: true
        });
        $('.radio-image-label').mouseenter(function () {
        }).mouseleave(function () {
        }).click(function (event) {
            if ($(this).find('input[type=radio]').length > 0) {
                $(this).closest('.product-option').find('input[type=radio]').removeAttr('checked');
                $(this).find('input[type=radio]').attr('checked', 'checked');
                $(this).find('input[type=radio]').change();
                $(this).closest('.product-option').find('.selected-radio-image')
                        .removeClass('selected-radio-image');
                $(this).addClass('selected-radio-image');
            } else {
                var checkbox = $(this).find('input[type=checkbox]');
                if (checkbox.attr('checked') == 'checked') {
                    checkbox.removeAttr('checked');
                } else {
                    checkbox.attr('checked', 'checked');
                }
                checkbox.change();
                var productOption = $(this).closest('.product-option');
                ajax_update_option($(this), null);
                productOption.find('.selected-radio-image').removeClass('selected-radio-image');
                productOption.find('input[type=checkbox]:checked').closest('.radio-image-label').addClass('selected-radio-image');
                //checkForErrorsBeforeCartSubmit( productOption.closest('.summary, .product-top,  .product-option-summary-wrapper, .main-data, .product-info, li.product'), event );
            }
        });
        $('.product-option-combobox select').comboboxPRO();
    }

    //$('.product-option-number .product-option-value').spinner();

    function getValuesOfImageUploads() {
        var imageUploadProductOptions = {};
        $('.product-option-image_upload').each(function () {
            var productOptionPostId = $(this).find('.product-option-post-id').val();
            var productOptionId = $(this).find('.product-option-id').val();
            if (typeof (imageUploadProductOptions[productOptionPostId]) == 'undefined') {
                imageUploadProductOptions[productOptionPostId] = {};
            }
            imageUploadProductOptions[productOptionPostId][productOptionId] = true;
        });
        $.post(woocommerce_product_options_settings.ajaxurl, {
            'action': 'is_upload_image_option_empty',
            'image_upload_product_options': imageUploadProductOptions
        }, function (response) {
            var responseArray = $.parseJSON(response);
            $.each(responseArray, function (index, value) {
                $.each(value, function (i2, v2) {
                    var productOption = $('.product-options-' + index + ' .product-option-' + i2);
                    productOption.find('.required-error').remove();
                    if ($('.required-error').length == 1) {
                        $('.required-error').remove();
                    }
                    productOption.find('.product-options-image-upload').val(v2);
                    var img = $(this).closest('.radio-image-label').find('.radio-image-image');
                    if (productOption.find('.change-thumb').length > 0 && productOption.find('.change-thumb').val() == 'yes') {
                        var imageToChange;
                        if (productOption.closest('.product').find('img.woocommerce-main-image, .woocommerce-main-image img, .wp-post-image, #product-img-slider .product-slider-image, .thb-product-slideshow rsImg').length > 0) {
                            imageToChange = productOption.closest('.product').find('img.woocommerce-main-image, .woocommerce-main-image img, .wp-post-image, #product-img-slider .product-slider-image, .thb-product-slideshow rsImg');
                        } else {
                            imageToChange = productOption.closest('.product').find('.images').find('img').first().attr('src', img.attr('src'));
                        }
                        imageToChange.attr('src', v2.src);
                        imageToChange.removeAttr('srcset');
                        $('a.zoom, a[data-rel^=\'prettyPhoto\']').attr('href', imageToChange.attr('src'));
                    }
                });
            });
            setTimeout(function () {
                getValuesOfImageUploads();
            }, 1000);
        });
    }
    if ($('.product-option-image_upload').length > 0) {
        getValuesOfImageUploads();
    }

    $('.variations select').change();

    function updateSelectedBoxEffect() {
        $('.box-effect-label, .box-effect-label-no-icon').each(function () {
            if ($(this).find('input[type=checkbox], input[type=radio]').is(':checked')) {
                $(this).addClass('selected-box-effect-label');
            } else {
                $(this).removeClass('selected-box-effect-label');
            }
        });
    }
    updateSelectedBoxEffect();

    $('.box-effect').closest('.product-option').find('input[type=checkbox], input[type=radio]').closest('label').addClass('box-effect-label');
    $('.box-effect-no-icon').closest('.product-option').find('input[type=checkbox], input[type=radio]').closest('label').addClass('box-effect-label').addClass('box-effect-label-no-icon');

});