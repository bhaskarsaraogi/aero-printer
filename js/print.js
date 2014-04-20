$(document).ready(function() {
  $("#feedbackSubmit").click(function() {
    
    contactForm.clearErrors();

    
    var hasErrors = false;
    $('#feedbackForm input,textarea').not('.optional').each(function() {
      if (!$(this).val()) {
        hasErrors = true;
        contactForm.addError($(this));
      }
    });

    var $sid = $('#sid');
    if (!contactForm.isValidID($sid.val())) {
      hasErrors = true;
      contactForm.addError($sid);
    }

    var $email = $('#email');
    if (!contactForm.isValidEmail($email.val())) {
      hasErrors = true;
      contactForm.addError($email);
    }

    var $phone = $('#phone');
    if (!contactForm.isValidPhone($phone.val())) {
      hasErrors = true;
      contactForm.addError($phone);
    }

    
    if (hasErrors) {
      return false;
    }

    var fileInput = document.getElementById("#upfile");
    var file = fileInput.files[0];
    var formData = new FormData($(this)[0])
    formData.append("files", file);
    
    $.ajax({
      type: "POST",
      url: "library/sendmail.php",
      // data: $("#feedbackForm").serialize(),
      data: formData,
      xhr: function() {  // custom xhr
                            var myXhr = $.ajaxSettings.xhr();
                            // if(myXhr.upload){ // if upload property exists
                            //     myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // progressbar
                            // }
                            myXhr.open('post', url, true);
                            myXhr.send(data);
                            return myXhr;
      },
      success: function(data)
      {
        contactForm.addAjaxMessage(data.message, false);
        
        $('#captcha').attr('src', 'library/vender/securimage/securimage_show.php?' + Math.random());
      },
      error: function(response)
      {
        contactForm.addAjaxMessage(response.responseJSON.message, true);
      },
      cache: false,
      contentType: false,
      processData: false
   });
    return false;
  });
});


var contactForm = {
  isValidEmail: function (email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  },

  isValidID: function (sid) {
    var regex = /^([0-9]{4})+([a-zA-Z0-9]{4})+([0-9]{3})+([gG]{1})+$/;
    return regex.test(sid);
  },

  
  isValidPhone: function (phone) {
    phone = phone.replace(/[^0-9]/g, '');
    return (phone.length === 10);
  },
  clearErrors: function () {
    $('#emailAlert').remove();
    $('#feedbackForm .help-block').hide();
    $('#feedbackForm .form-group').removeClass('has-error');
  },
  addError: function ($input) {
    $input.siblings('.help-block').show();
    $input.parent('.form-group').addClass('has-error');
  },
  addAjaxMessage: function(msg, isError) {
    $("#feedbackSubmit").after('<div id="emailAlert" class="alert alert-' + (isError ? 'danger' : 'success') + '" style="margin-top: 5px;">' + $('<div/>').text(msg).html() + '</div>');
  }
};
