
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
          window.location.href = 'thank-you';
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
        window.location.href = 'thank-you';
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
        window.location.href = 'thank-you';
      },
      error: function () {
        $(document).find('.toast2_career').toggleClass('hide', true).fadeOut();
        $(document).find('.toast2_error').toggleClass('hide', false).fadeIn();
      }
    });
  });
});

// AI Sales Agent popup form (ai-sales-agent-for-ecommerce.html)
$(document).ready(function () {
  const form = $('#ai-sales-agent-form');
  if (!form.length) return;

  form.on('submit', function (e) {
    e.preventDefault();

    let isValid = true;
    form.find('.ai-sales-error').remove();

    const fields = [
      //{ id: 'ai-sales-name', label: 'Name', required: true },
      { id: 'ai-sales-email', label: 'Email', required: true, type: 'email' },
      //{ id: 'ai-sales-business', label: 'Business', required: true },
      //{ id: 'ai-sales-website', label: 'Website', required: true }
    ];

    fields.forEach(function (fieldData) {
      const field = form.find('#' + fieldData.id);
      if (!field.length) return;
      const value = (field.val() || '').trim();

      if (fieldData.required && !value) {
        field.after('<div class="ai-sales-error">' + fieldData.label + ' is required</div>');
        isValid = false;
      } else if (fieldData.type === 'email' && value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
          field.after('<div class="ai-sales-error">Enter a valid ' + fieldData.label + '</div>');
          isValid = false;
        }
      }
    });

    const agreeCheckbox = form.find('#agree');
    if (agreeCheckbox.length && !agreeCheckbox.is(':checked')) {
      const checkContainer = agreeCheckbox.closest('.landing-popup-required-check');
      (checkContainer.length ? checkContainer : agreeCheckbox)
        .after('<div class="ai-sales-error">Please tick the box so we can reply.</div>');
      isValid = false;
    }

    if (!isValid) return;

    const submitBtn = form.find('#ai-sales-submit-btn');
    submitBtn.prop('disabled', true).css('opacity', '0.7');

    const formData = new FormData(form[0]);
    formData.append('page_link', currentPageUrl);

    $.ajax({
      url: form.attr('action') || '/ai_sales_agent_form_handler.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        form[0].reset();
        window.location.href = 'thank-you?source=ai-sales-agent-for-ecommerce';
      },
      error: function () {
        $('.toast2_ai_sales').addClass('hide').hide();
        $('.toast2_ai_sales_error').removeClass('hide').show();
      },
      complete: function () {
        submitBtn.prop('disabled', false).css('opacity', '');
      }
    });
  });
});

// AI Sales Agent lead form (ai-sales-agent-for-ecommerce-new.html)
$(document).ready(function () {
  const form = $('#lead-form');
  if (!form.length) return;

  form.on('submit', function (e) {
    e.preventDefault();

    // Honeypot — silently succeed so bots don't retry
    if (($('#company_website_hp').val() || '').trim()) {
      if (typeof window.__aiSalesLeadFormShowSuccess === 'function') {
        window.__aiSalesLeadFormShowSuccess({});
      }
      return;
    }

    let isValid = true;
    if (typeof window.__aiSalesLeadFormValidate === 'function') {
      isValid = window.__aiSalesLeadFormValidate();
    }

    const captchavalidation = typeof validateCaptcha === 'function' ? validateCaptcha() : false;
    if (!captchavalidation) {
      isValid = false;
    }

    if (!isValid) {
      const first = form.find('[aria-invalid="true"]').get(0);
      if (first) first.focus();
      return;
    }

    const submitBtn = form.find('#submit-btn');
    const originalBtnHtml = submitBtn.html();
    submitBtn.prop('disabled', true).html('Sending…');

    const formData = new FormData(form[0]);
    formData.append('page_link', currentPageUrl);
    formData.append('captchafield', 'captcha_code');
    // Map new-page fields to handler field names
    if (!formData.get('website') && formData.get('store')) {
      formData.append('website', formData.get('store'));
    }
    if (!formData.get('business') && formData.get('platform')) {
      formData.append('business', formData.get('platform'));
    }

    const q = new URLSearchParams(window.location.search);
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'fbclid', 'wbraid', 'gbraid'].forEach(function (k) {
      if (q.get(k)) formData.append(k, q.get(k));
    });

    const payload = {};
    formData.forEach(function (v, k) { payload[k] = v; });

    $.ajax({
      url: form.attr('action') || '/ai_sales_agent_form_handler.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        form[0].reset();
        if (typeof refreshCaptcha === 'function') {
          refreshCaptcha();
        }
        if (typeof window.__aiSalesLeadFormShowSuccess === 'function') {
          window.__aiSalesLeadFormShowSuccess(payload);
        } else {
          window.location.href = 'thank-you';
        }
      },
      error: function () {
        submitBtn.prop('disabled', false).html('Try again <span class="arw">→</span>');
        const err = document.getElementById('e-consent');
        if (err) {
          err.textContent = "That didn't send. Try again, or call +91 90331 99949 and we'll book you in.";
          err.classList.add('on');
        }
        if (typeof refreshCaptcha === 'function') {
          refreshCaptcha();
        }
      },
      complete: function (xhr) {
        if (xhr && xhr.status >= 200 && xhr.status < 300) {
          submitBtn.prop('disabled', false).html(originalBtnHtml);
        }
      }
    });
  });
});

// Service lead form (hire-*-developers.html)
$(document).ready(function () {
  const forms = $('form.js-service-lead-form, #service-lead-form');
  if (!forms.length) return;

  forms.each(function () {
    const form = $(this);

    form.on('submit', function (e) {
      e.preventDefault();

      let isValid = true;
      form.find('.service-lead-error').remove();

      const isContactStyle = form.find('[name="first_name"]').length > 0;

      const fields = isContactStyle
        ? [
            { id: 'service-lead-first-name', name: 'first_name', label: 'First Name' },
            { id: 'service-lead-last-name', name: 'last_name', label: 'Last Name' },
            { id: 'service-lead-email', name: 'email', label: 'Email', type: 'email' },
            { id: 'service-lead-tel', name: 'tel', label: 'Phone Number', required: false },
            { id: 'service-lead-applyforjob', name: 'applyforjob[]', label: 'Apply for role' },
            { id: 'service-lead-cover-letter', name: 'cover-letter', label: 'Tell us about your needs' }
          ]
        : [
            { id: 'service-lead-name', name: 'name', label: 'Name' },
            { id: 'service-lead-email', name: 'email', label: 'Work Email', type: 'email' },
            { id: 'service-lead-company', name: 'company', label: 'Company' },
            { id: 'service-lead-build', name: 'build', label: 'What you need to build' },
            { id: 'service-lead-stack', name: 'stack', label: 'Your current stack' },
            { id: 'service-lead-timeline', name: 'timeline', label: 'Timeline' }
          ];

      fields.forEach(function (fieldData) {
        let field = form.find('#' + fieldData.id);
        if (!field.length && fieldData.name) {
          field = form.find('[name="' + fieldData.name + '"]').first();
        }
        if (!field.length) return;

        let value = field.val();
        if (Array.isArray(value)) {
          value = value.join(',').trim();
        } else {
          value = (value || '').trim();
        }

        const required = fieldData.required !== false;
        if (required && !value) {
          field.after('<div class="service-lead-error">' + fieldData.label + ' is required</div>');
          isValid = false;
        } else if (fieldData.type === 'email' && value) {
          const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailPattern.test(value)) {
            field.after('<div class="service-lead-error">Enter a valid ' + fieldData.label + '</div>');
            isValid = false;
          }
        }
      });

      if (!isValid) return;

      const submitBtn = form.find('#service-lead-submit-btn, button[type="submit"]').first();
      const originalBtnText = submitBtn.text();
      submitBtn.prop('disabled', true).text('Submitting…');

      const formData = new FormData(form[0]);
      formData.append('page_link', currentPageUrl);

      // Prefer full intl-tel-input number when available
      const telInput = form.find('input[type="tel"], #service-lead-tel, #tel').get(0);
      if (telInput && window.intlTelInputGlobals) {
        try {
          const iti = window.intlTelInputGlobals.getInstance(telInput);
          if (iti) {
            formData.set('tel', iti.getNumber() || (telInput.value || ''));
          }
        } catch (err) {}
      }

      $.ajax({
        url: form.attr('action') || '/service_lead_form_handler.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
          form[0].reset();

          const $scope = form.closest('.service-lead-form-col, .form-box, .career-popup-overlay, .service-lead-form');
          let $success = $scope.find('.toast2_service_lead').first();
          let $error = $scope.find('.toast2_service_lead_error').first();

          // Fallback for pages that still use career toast classes
          if (!$success.length) {
            $success = $scope.find('.toast2_career').first();
          }
          if (!$error.length) {
            $error = $scope.find('.toast2_error').first();
          }

          // Ensure toast exists near the form
          if (!$success.length) {
            $success = $('<div class="toast2_service_lead">Thank you! Your inquiry has been submitted successfully. Our team will connect with you soon.</div>');
            form.before($success);
          }

          $error.addClass('hide').hide();
          $success
            .removeClass('hide')
            .stop(true, true)
            .css({ display: 'block', opacity: 1 })
            .fadeIn(200);

          if ($success.length && $success[0].scrollIntoView) {
            $success[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          }

          setTimeout(function () {
            $success.addClass('hide').fadeOut(200);
          }, 5000);
        },
        error: function () {
          const $scope = form.closest('.service-lead-form-col, .form-box, .career-popup-overlay, .service-lead-form');
          let $success = $scope.find('.toast2_service_lead').first();
          let $error = $scope.find('.toast2_service_lead_error').first();

          if (!$success.length) {
            $success = $scope.find('.toast2_career').first();
          }
          if (!$error.length) {
            $error = $scope.find('.toast2_error').first();
          }

          if (!$error.length) {
            $error = $('<div class="toast2_service_lead_error">Oops! Something went wrong. Please try again later.</div>');
            form.before($error);
          }

          $success.addClass('hide').hide();
          $error
            .removeClass('hide')
            .stop(true, true)
            .css({ display: 'block', opacity: 1 })
            .fadeIn(200);

          if ($error.length && $error[0].scrollIntoView) {
            $error[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          }

          setTimeout(function () {
            $error.addClass('hide').fadeOut(200);
          }, 5000);
        },
        complete: function () {
          submitBtn.prop('disabled', false).text(originalBtnText);
        }
      });
    });
  });
});

// CSR form (corporate-social-responsibility.html)
$(document).ready(function () {
  const form = $('#csr-form');
  if (!form.length) return;

  form.on('submit', function (e) {
    e.preventDefault();

    let isValid = true;
    form.find('.csr-error').remove();

    const fields = [
      { id: 'csr-contribution', label: 'Contribution type' },
      { id: 'csr-focus-area', label: 'Focus area' },
      { id: 'csr-full-name', label: 'Full name' },
      { id: 'csr-email', label: 'Email', type: 'email' }
    ];

    fields.forEach(function (fieldData) {
      const field = form.find('#' + fieldData.id);
      if (!field.length) return;
      const value = (field.val() || '').trim();

      if (!value) {
        field.after('<div class="csr-error">' + fieldData.label + ' is required</div>');
        isValid = false;
      } else if (fieldData.type === 'email') {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
          field.after('<div class="csr-error">Enter a valid ' + fieldData.label + '</div>');
          isValid = false;
        }
      }
    });

    if (typeof validateCaptcha === 'function' && !validateCaptcha()) {
      isValid = false;
    }

    if (!isValid) return;

    const formData = new FormData(form[0]);
    formData.append('page_link', currentPageUrl);
    formData.append('captchafield', 'captcha_code');

    $.ajax({
      url: 'csr_form_handler.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        form[0].reset();
        if (typeof refreshCaptcha === 'function') {
          refreshCaptcha();
        }
        window.location.href = 'thank-you';
      },
      error: function () {
        if (typeof refreshCaptcha === 'function') {
          refreshCaptcha();
        }
        $('.toast2_csr').addClass('hide').fadeOut();
        $('.toast2_error').removeClass('hide').fadeIn();
      }
    });
  });
});

