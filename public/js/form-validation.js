$(function() {
    // Initialize form validation on the registration form.
    // It has the name attribute "registration"
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
        return arg !== value;
       }, "Value must not equal Select Type.");

    $("form[name='add_notification']").validate({
      // Specify validation rules
      rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        customer_type: { valueNotEquals: "Select Type"},
        notification_date: "required",
        start_time:{ valueNotEquals: "Select Time"},
        notification_type: { valueNotEquals: "Select Type" },

      },
      // Specify validation error messages
      messages: {
        customer_type: "Please Enter Customer Type",
        notification_date: "Please Enter Notification Date",
        start_time: "Please Enter Start Time",
        notification_type: "Please Enter Notification Type",
    },

    submitHandler: function(form) {
    form.submit();
    },

  });
});
