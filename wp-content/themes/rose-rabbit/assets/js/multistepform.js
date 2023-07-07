jQuery(document).ready(function() {

   var current_fs, next_fs, previous_fs; //fieldsets
   var opacity;
   var current = 1;
   var steps = jQuery("fieldset").length;

   setProgressBar(current);

   jQuery(".next").click(function() {

       current_fs = jQuery(this).parent();
       next_fs = jQuery(this).parent().next();

       jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");

       next_fs.show();
       current_fs.animate({
           opacity: 0
       }, {
           step: function(now) {
               opacity = 1 - now;

               current_fs.css({
                   'display': 'none',
                   'position': 'relative'
               });
               next_fs.css({
                   'opacity': opacity
               });
           },
           duration: 500
       });
       setProgressBar(++current);
   });

   jQuery(".previous").click(function() {

       current_fs = jQuery(this).parent();
       previous_fs = jQuery(this).parent().prev();

       jQuery("#progressbar li").eq(jQuery("fieldset").index(current_fs)).removeClass("active");

       previous_fs.show();

       current_fs.animate({
           opacity: 0
       }, {
           step: function(now) {
               opacity = 1 - now;

               current_fs.css({
                   'display': 'none',
                   'position': 'relative'
               });
               previous_fs.css({
                   'opacity': opacity
               });
           },
           duration: 500
       });
       setProgressBar(--current);
   });

   function setProgressBar(curStep) {
       var percent = parseFloat(100 / steps) * curStep;
       percent = percent.toFixed();
       jQuery(".progress-bar")
           .css("width", percent + "%")
   }

   jQuery(".submit").click(function() {
       return false;
   })

});