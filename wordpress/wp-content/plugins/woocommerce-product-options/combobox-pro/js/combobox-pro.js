/**
 * Copyright (c) All Rights Reserved WPShowCase.net
 */
jQuery(document).ready(function ($) {
//The combobox function
    $.fn.comboboxPRO = function (args) {
        if (typeof (args) == 'undefined') {
            args = {};
        }
        $(this).each(function () {
//Variables
            var text = '';
            var combobox = $(this);
            var comboboxText = null;
            var comboboxWrapper = null;
            var height = $(this).height();
            var width = $(this).width();
            var comboboxDownArrow = null;
            var comboboxInnerWrapper = null;
            var caseSensitive = false;
            if (typeof (args.caseSensitive) != 'undefined' && args.caseSensitive) {
                caseSensitive = true;
            }
            //When text is typed in the combobox
            function comboboxTextChanged(showAll) {
                var originalCombobox = combobox;
                text = comboboxText.val();
                var comparisonText = text;
                if (!caseSensitive) {
                    comparisonText = comparisonText.toLowerCase();
                }
                var topOfList = Math.ceil(comboboxText.outerHeight());
                var scrolling = '';
                if (typeof (args.numberOfLines) != 'undefined') {
                    scrolling = 'overflow-y:scroll;';
                }
                var optionHtml = '<ul class="combobox-pro-list" style="' + scrolling + 'min-width:' + totalWidth + 'px;top:' + topOfList + 'px">';
                var line = 0;
                originalCombobox.find('option').each(function () {
                    var value = $(this).text();
                    var comparisonValue = value;
                    if (!caseSensitive) {
                        comparisonValue = value.toLowerCase();
                    }
                    $(this).removeAttr('selected');
                    if (showAll || comparisonValue.indexOf(comparisonText) >= 0) {
                        optionHtml += '<li><span class="text">' + value + '</span><span class="value">' + $(this).val() + '</span></li>';
                    }
                    line = line + 1;
                });
                optionHtml += '</ul>';
                $('.combobox-pro-list').remove();
                comboboxText.after(optionHtml);
                if (typeof (args.numberOfLines) != 'undefined') {
                    if (parseInt(args.numberOfLines) < line) {
                        var list = comboboxWrapper.find('.combobox-pro-list');
                        var lineHeight = list.find('li').outerHeight();
                        var heightForList = Math.ceil(lineHeight * parseInt(args.numberOfLines));
                        list.css('height', heightForList + 'px');
                    }
                }
                $('.combobox-pro-list li').click(function () {
                    var selectedValue = $(this).find('span.value').text();
                    combobox.find('option').removeAttr('selected');
                    combobox.find('option[value="' + selectedValue + '"]').attr('selected', 'selected');
                    comboboxText.val($(this).find('.text').text());
                    $('.combobox-pro-list').remove();
                });
                $('.combobox-pro-inner-wrapper').css('z-index', '9000');
                comboboxWrapper.find('.combobox-pro-inner-wrapper').css('z-index', 10000);
            }

            $(this).wrap('<div class="combobox-pro-wrapper" style="position:relative;"></div>');
            $(this).hide();
            $(this).before('<div class="combobox-pro-inner-wrapper" style="z-index:9000;position:absolute;"><input type="text" class="combobox-pro-text" value="' + text + '" />' +
                    '<span class="combobox-pro-down-arrow" >&#9660;</span></div>');

            comboboxWrapper = $(this).closest('.combobox-pro-wrapper');
            comboboxInnerWrapper = comboboxWrapper.find('.combobox-pro-inner-wrapper');
            comboboxText = comboboxWrapper.find('.combobox-pro-text');
            comboboxText.val('').val(text).focus().click();
            comboboxText.on("click", function () {
                $(this).select();
            });
            height = comboboxText.height();
            width = comboboxText.width();
            comboboxWrapper.height(height + 'px');
            comboboxDownArrow = comboboxWrapper.find('.combobox-pro-down-arrow');
            comboboxDownArrow.css('height', comboboxText.css('height'));
            comboboxDownArrow.css('line-height', comboboxText.css('height'));
            comboboxDownArrow.css('border', comboboxText.css('border'));
            comboboxDownArrow.css('left', width + 'px');
            if (typeof (args.autocomplete) !== 'undefined' && args.autocomplete == true) {
                comboboxDownArrow.hide(0);
            }
            var totalWidth = width + comboboxWrapper.find('.combobox-pro-down-arrow').width();
            $('.combobox-pro-inner-wrapper').css('width', totalWidth + 'px');

            comboboxText = comboboxWrapper.find('.combobox-pro-text').val('').val(text).focus().click();
            /*comboboxText.css('padding-top', combobox.css('padding-top'));
             comboboxText.css('padding-bottom', combobox.css('padding-bottom'));
             comboboxText.css('padding-right', combobox.css('padding-right'));
             comboboxText.css('padding-left', combobox.css('padding-left'));*/
            comboboxDownArrow.css('left', comboboxText.outerWidth() + 'px');
            comboboxText.keyup(function () {
                comboboxTextChanged(false);
            }).focusin(function () {
                comboboxTextChanged(false);
            }).focusout(function () {
                setTimeout(function () {
                }, 200);
            });

            comboboxWrapper.find('.combobox-pro-down-arrow').click(function () {
                comboboxTextChanged(true);
            });

            var selectedValue = combobox.find(':selected');
            if (selectedValue.length == 1) {
                comboboxText.val(selectedValue.text());
            }
        });
        return $(this);
    }
//Defining functions with different capitalizations (for typos)
    $.fn.comboboxPro = function (args) {
        $(this).comboboxPRO(args);
    }
    $.fn.comboboxPROAutocomplete = function (args) {
        args.autocomplete = true;
        $(this).comboboxPRO(args);
    }
    $.fn.comboboxProComplete = function (args) {
        $(this).comboboxPROAutocomplete(args);
    }

});

jQuery(document).ready(function ($) {
    $('.combobox-pro').comboboxPRO({});
    $('.combobox-pro-autocomplete').comboboxPROAutocomplete({});
    $('.combobox-pro-case-sensitive').comboboxPRO({caseSensitive: true});
    $('.combobox-pro-3-lines').comboboxPRO({numberOfLines: 3});
});