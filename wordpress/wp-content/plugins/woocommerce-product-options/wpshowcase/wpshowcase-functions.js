jQuery(document).ready(function ($) {


    function updateAfterElementRemovedOrAdded(button) {
        var wrapper = button.closest('.list-elements-wrapper');
        var elementClass = wrapper.find('.element-class').val();
        if (wrapper.find('.' + elementClass + '-elements .list-element').length == 0) {
            wrapper.find('.no-' + elementClass).show();
        } else {
            wrapper.find('.no-' + elementClass).hide();
        }
        var number = 1;
        wrapper.find('.' + elementClass + '-number').each(function () {
            $(this).text(number);
            number = number + 1;
        });
    }
    $('.add-new-list-element').each(function () {
        updateAfterElementRemovedOrAdded($(this));
    });
    $('.list-elements-wrapper').on('click', '.add-new-list-element', function () {
        var wrapper = $(this).closest('.list-elements-wrapper');
        var elementClass = wrapper.find('.element-class').val();
        var maxId = 0;
        wrapper.find('.list-elements .' + elementClass + '-id').each(function () {
            var id = parseInt($(this).val());
            if (id > maxId) {
                maxId = id;
            }
        });
        maxId++;
        var newHtml = wrapper.find('.new-' + elementClass).html().replace(new RegExp('New_' + elementClass + '_Id', 'g'), maxId);
        wrapper.find('.' + elementClass + '-elements').append(newHtml);
        wrapper.find('.' + elementClass + '-' + maxId).hide(0);
        wrapper.find('.' + elementClass + '-' + maxId).find('.custom-combobox').each(function () {
            var select = $(this).prev();
            $(this).remove();
            select.combobox();
        });
        wrapper.find('.' + elementClass + '-' + maxId).show();
        updateAfterElementRemovedOrAdded($(this));
    });
    $('.list-elements-wrapper').on('click', '.remove-list-element', function () {
        var elementToRemove = $(this).closest('.list-element');
        elementToRemove.hide();
        var addButton = elementToRemove.closest('.list-elements-wrapper').find('.add-new-list-element');
        setTimeout(function () {
            elementToRemove.remove();
            updateAfterElementRemovedOrAdded(addButton);
        }, 500);
    });
    $(document).on('submit', 'form', function () {
        if ($(this).find('.list-elements-wrapper').length > 0) {
            $(this).find('.new-list-element').remove();
        }
    });

    $(document).on('change', '.select-and-display-suboptions', function () {
        var wrapper = $(this).closest('.select-and-display-suboptions-wrapper');
        wrapper.find('.select-and-display-suboptions-suboption').hide();
        var elementToShowClass = 'select-and-display-suboptions-' + $(this).val().replace(new RegExp('_', 'g'), '-');
        wrapper.find('.' + elementToShowClass).show();
    });
    $('.select-and-display-suboptions').change();

    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                    .addClass("custom-combobox")
                    .insertAfter(this.element);
            var width = this.element.width();
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
            if (width > 0) {
                this.input.width(width);
            }
        },
        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                    value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                    .appendTo(this.wrapper)
                    .val(value)
                    .attr("title", "")
                    .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy(this, "_source")
                    })
                    .tooltip({
                        classes: {
                            "ui-tooltip": "ui-state-highlight"
                        }
                    });

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                    this.element.trigger('change');
                },
                autocompletechange: "_removeIfInvalid"
            });
        },
        _createShowAllButton: function () {
            var input = this.input,
                    wasOpen = false;

            $("<a>")
                    .attr("tabIndex", -1)
                    .attr("title", "Show All Items")
                    .tooltip()
                    .appendTo(this.wrapper)
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass("ui-corner-all")
                    .addClass("custom-combobox-toggle ui-corner-right")
                    .on("mousedown", function () {
                        wasOpen = input.autocomplete("widget").is(":visible");
                    })
                    .on("click", function () {
                        input.trigger("focus");

                        // Close if already visible
                        if (wasOpen) {
                            return;
                        }

                        // Pass empty string as value to search for, displaying all results
                        input.autocomplete("search", "");
                    });
        },
        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && (!request.term || matcher.test(text)))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },
        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                    valueLowerCase = value.toLowerCase(),
                    valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
            this.input
                    .val("")
                    .attr("title", value + " didn't match any item")
                    .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },
        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });

    $('.combobox').combobox();

});