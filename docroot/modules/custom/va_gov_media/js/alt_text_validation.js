/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/
(function ($, Drupal, once, drupalSettings) {
  if (typeof drupalSettings.cvJqueryValidateOptions === "undefined") {
    drupalSettings.cvJqueryValidateOptions = {};
  }
  if (drupalSettings.clientside_validation_jquery.force_validate_on_blur) {
    drupalSettings.cvJqueryValidateOptions.onfocusout = function (element) {
      this.element(element);
    };
  }
  drupalSettings.cvJqueryValidateOptions.rules = {
    "image[0][alt]": {
      remote: {
        url: drupalSettings.path.baseUrl + "media/validate",
        type: "post",
        data: {
          value: function value() {
            return $("input[data-drupal-selector='edit-image-0-alt']").val();
          }
        },
        dataType: "json"
      }
    },
    "media[0][fields][image][0][alt]": {
      remote: {
        url: drupalSettings.path.baseUrl + "media/validate",
        type: "post",
        data: {
          value: function value() {
            return $("input[data-drupal-selector='edit-media-0-fields-image-0-alt']").val();
          }
        },
        dataType: "json"
      }
    }
  };
  $.extend($.validator.messages, drupalSettings.clientside_validation_jquery.messages);
  Drupal.behaviors.altTextValidate = {
    attach: function attach(context) {
      $(document).trigger("cv-jquery-validate-options-update", drupalSettings.cvJqueryValidateOptions);
      once("altTextValidate", "body form").forEach(function (element) {
        $(element).validate(drupalSettings.cvJqueryValidateOptions);
      });
    }
  };
})(jQuery, Drupal, once, drupalSettings);