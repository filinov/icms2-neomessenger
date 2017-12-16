/**
 * --------------------------------------------------------------------------
 * Neomessenger v2.5.0
 * Copyright 2013-2017 Victor Filinov aka NEOm@ster
 * --------------------------------------------------------------------------
 */

var icms = icms || {};

icms.neomessenger = (function ($) {

    var nm = this;

    this.editor = (function () {

        var editor = this;

        this.init = function () {
            this.field = $('#editor-nm-msg-field');
            this.submitBtn = $('.nm-submit-button');

            this.field.on('keydown', this.onKeydown);
            this.field.on('change', this.onChange);
            this.submitBtn.on('click', this.onSubmit);
        };

        this.setValue = function (value) {
            this.field.val(value);
        };

        this.getValue = function () {
            return this.field.val().trim();
        };

        this.focus = function () {
            this.field.focus();
        };

        this.disable = function () {
            this.field.attr('disabled', 'disabled');
        };

        this.enable = function () {
            this.field.removeAttr('disabled');
        };

        this.cleanMessage = function (message) {
            return message;
        };

        this.destroy = function () {
            if (this.field) {
                this.field.off('keydown', this.onKeydown);
                this.field.off('change', this.onChange);
                this.submitBtn.off('click', this.onSubmit);
            }
        };

        this.onKeydown = function (e) {
            if (e.keyCode == 13) {
                var submit = false;
                var sendOnEnter = nm.options.send_enter;

                if (sendOnEnter && !e.shiftKey) {
                    submit = true;
                } else if (!sendOnEnter && (e.ctrlKey || e.metaKey)) {
                    submit = true;
                }

                if (submit) {
                    return editor.onSubmit(e);
                }
            }
        };

        this.onChange = function () {
            nm.draft.update();
        };

        this.onSubmit = function (e) {
            e.preventDefault();
            nm.messages.send();
            return false;
        };

        return this;

    }).call({});

    this.renderMessage = function (message) {
        return nm.templates.message({
            message: message
        });
    };

    return this;

}).call(icms.neomessenger || {}, jQuery);