$(function() {
    var animateTitle_interval = null,
        animateTitle_title    = document.title,
        animateTitle_idx      = 0;

    // Methods used in animation.
    var methods = {
        clear : function() {
            clearInterval(animateTitle_interval);
            document.title        = animateTitle_title;
            animateTitle_idx      = 0;
            animateTitle_interval = null;
        },
        create : function(text, time) {
            document.title        = text[0];
            animateTitle_interval = setInterval(function(){
                methods.swap(text);
            }, time);
        },
        swap : function(text) {
            document.title = text[animateTitle_idx++ % text.length];
        }
    };

    $.fn.animateTitle = function(options, time) {        
        // Clear & return quickly.
        if (options == "clear") {
            methods.clear();
            return this;
        }

        // Default settings
        var settings = {
            'timeout' : 1000,
            'text'    : []
        };

        // Verify we have an object
        if (typeof options != "object") {
            $.error('This plugin does not support these parameters in the constructor');
            return this;
        }

        // If it's a full object:
        if (typeof options.text != 'undefined'){
            $.extend(settings, options);
        }
        // If it's just an array of strings:
        else{
            settings.text = options;    
        }

        // Verify that the array to be animated actually exists 
        if (settings.text.length == 0) {
            $.error('Array has no members.');
            return this;
        }

        // Verify they passed in a number
        if (typeof time == 'number'){
            settings.timeout = time;
        }

        methods.clear();
        methods.create(settings.text, settings.timeout);
        return this;
    };
    // Add animateTitle to jQuery to use it as a $. function.
    $.extend({
       animateTitle: $.fn.animateTitle
    });
});