
const currentPageUrl = window.location.href;

$(document).ready(function () {
  $(".lets-talk-form").each(function () {
    const form = $(this);
    // Inject toast if missing
    if (form.find(".lets-talk-toast").length === 0) {
      form.append('<div class="lets-talk-toast">Form submitted successfully!</div>');
    }

    form.on("submit", function (e) {
      e.preventDefault();

      let isValid = true;
      form.find(".lets-talk-error").remove(); // clear old errors

      const name = form.find("#name").val().trim();
      const email = form.find("#email").val().trim();

      if (name === "") {
        form.find("#name").after('<div class="lets-talk-error">Name is required</div>');
        isValid = false;
      }
      if (email === "") {
        form.find("#email").after('<div class="lets-talk-error">Email is required</div>');
        isValid = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        form.find("#email").after('<div class="lets-talk-error">Enter a valid email</div>');
        isValid = false;
      }

      //const captchaResponse = form.find('textarea[name="g-recaptcha-response"]').val();
      const captchavalidation = validateCaptcha();
      if (!captchavalidation) {
        isValid = false;
      }

      if (!isValid) return;

      var page_link = currentPageUrl;

      $.ajax({
        url: "/form-submitfooter.php",
        method: "POST",
        data: { name, email, captchavalidation, page_link },
        success: function () {
          form[0].reset();
          refreshCaptcha();
          const toast = form.find(".lets-talk-toast");
          toast.addClass("show");
          setTimeout(() => toast.removeClass("show"), 8000);
        },
        error: function () {
          form.find("#email").after('<div class="lets-talk-error">There was an error. Try again.</div>');
        }
      });
    });
  });
});


// $(document).ready(function () {
//   $('form[name="contactform"]').each(function () {
//     const form = $(this);

//     // Inject toast container per form if not present
//     if (form.find(".contact-toast").length === 0) {
//       form.append('<div class="contact-toast"></div>');
//     }

//     form.on("submit", function (e) {
//       e.preventDefault();

//       let isValid = true;
//       form.find(".contact-error").remove(); // clear old errors

//       // Loop through all inputs and textareas
//       form.find("input, textarea,select, tel").each(function () {
//         const field = $(this);
//         const value = field.val().trim();
//         const type = field.attr("type");

//         // Check if the field is required (force all fields)
//         if (!value) {
//           field.after('<div class="contact-error">This field is required</div>');
//           isValid = false;
//         } else if (type === "email") {
//           const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//           if (!emailPattern.test(value)) {
//             field.after('<div class="contact-error">Enter a valid email</div>');
//             isValid = false;
//           }
//         }
//       });

//       if (!isValid) return; // stop submission if any error

//       // AJAX submission
//       $.ajax({
//         url: form.attr("action") || "/form-submit.php",
//         method: form.attr("method") || "POST",
//         data: form.serialize(),
//         success: function () {
//           form[0].reset();
//           //const toast = form.find(".contact-toast");
//           // toast.text("Your message was submitted successfully!").addClass("show");
//           // setTimeout(() => toast.removeClass("show"), 3000);
//         },
//         error: function () {
//           // const toast = form.find(".contact-toast");
//           // toast.text("There was an error. Try again.").addClass("show");
//           // setTimeout(() => toast.removeClass("show"), 3000);
//         }
//       });
//     });
//   });
// });

$(document).ready(function () {

  const form = $('#contactform');

  form.on("submit", function (e) {
    e.preventDefault();

    let isValid = true;

    // Clear previous validation errors
    form.find(".contact-error").remove();

    // Validate all inputs, textareas, selects
    form.find("input, textarea, select, tel").each(function () {
      const field = $(this);
      //const value = field.val().trim();
      let value = field.val();
      if (Array.isArray(value)) {
          // join array values into a string
          value = value.map(v => v.trim()).join(","); 
      } else if (typeof value === "string") {
          value = value.trim();
      }
      const type = field.attr("type");

      const captchavalidation = validateCaptcha2();
      if (!captchavalidation) {
        isValid = false;
      }

      if (!value && field[0].classList[0] != 'select2-search__field' && ($(field).attr('id') != 'captchaInput2')) {
        field.after('<div class="contact-error">This field is required</div>');
        isValid = false;
      } else if (type === "email") {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
          field.after('<div class="contact-error">Enter a valid email</div>');
          isValid = false;
        }
      }
    });

    
    if (!isValid) return; // stop submission if validation fails


    // AJAX submission
    $.ajax({
      url: form.attr("action") || "/form-submit.php",
      method: form.attr("method") || "POST",
      data: form.serialize() + '&page_link=' + encodeURIComponent(currentPageUrl),

      success: function () {
        form[0].reset();
        refreshCaptcha2();
        $(document).find('.toast2_career').toggleClass('hide', false).fadeIn();
        $(document).find('.toast2_error').toggleClass('hide', true).fadeOut();

      },
      error: function () {
        $(document).find('.toast2_career').toggleClass('hide', true).fadeOut();
        $(document).find('.toast2_error').toggleClass('hide', false).fadeIn();
      }
    });
  });
});

$(document).ready(function () {
  const form = $('#contactform3');

  form.on("submit", function (e) {
    e.preventDefault();

    let isValid = true;

    // Clear previous validation errors
    form.find(".contact-error").remove();

    // Validate all inputs, textareas, selects
    form.find("input, textarea, select").each(function () {
      const field = $(this);
      const value = field.val().trim();
      const type = field.attr("type");

      if (!value && ($(field).attr('id') != 'captchaInput')) {
        field.after('<div class="contact-error">This field is required</div>');
        isValid = false;
      } else if (type === "email") {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
          field.after('<div class="contact-error">Enter a valid email</div>');
          isValid = false;
        }
      }
    });

    const captchavalidation = validateCaptcha();
      if (!captchavalidation) {
        isValid = false;
      }

    if (!isValid) return; // stop submission if validation fails


    // AJAX submission
    $.ajax({
      url: form.attr("action") || "/form-submit2.php",
      method: form.attr("method") || "POST",
      data: form.serialize() + '&page_link=' + encodeURIComponent(currentPageUrl),

      success: function () {
        form[0].reset();
        refreshCaptcha();
        $(document).find('.toast2_career').toggleClass('hide', false).fadeIn();
        $(document).find('.toast2_error').toggleClass('hide', true).fadeOut();

      },
      error: function () {
        $(document).find('.toast2_career').toggleClass('hide', true).fadeOut();
        $(document).find('.toast2_error').toggleClass('hide', false).fadeIn();
      }
    });
  });
});

