/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal) {
  Drupal.behaviors.vaGovInlineGuidance = {
    attach: function attach() {
      $("#inline-guidance-trigger").once().click(function (e) {
        e.preventDefault();
        if ($("#inline-guidance-text-box").hasClass("hide")) {
          $("#inline-guidance-text-box").removeClass("hide");
          $("#inline-guidance-text-box").addClass("show");
          $("#inline-guidance-trigger").attr("aria-expanded", "true");
          setTimeout(function () {
            $("#inline-guidance-trigger").focus();
          }, 800);
        } else {
          $("#inline-guidance-text-box").removeClass("show");
          $("#inline-guidance-text-box").addClass("hide");
          $("#inline-guidance-trigger").attr("aria-expanded", "false");
          setTimeout(function () {
            $("#inline-guidance-trigger").focus();
          }, 500);
        }
      });
    }
  };
})(jQuery, window.Drupal);