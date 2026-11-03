$(function() {
  if ($('#hireContactForm').length) {

    // ✅ Inject only your form (no <script> or <link> inside)
    $('#hireContactForm').html(`
      <style>
        .select2-container--default .select2-dropdown {
          z-index: 9999 !important;
        }
        .select2-container--default .select2-selection--multiple {
          background-color: transparent !important;
          border: 1px solid hsla(0, 0%, 100%, .2) !important;
          height: 60px !important;
          border-radius: 30px !important;
          padding: 0px 16px;
        }
        .select2-container--default .select2-search--inline .select2-search__field {
          border: none !important;
        }
      </style>

      <form id="contactform" enctype="multipart/form-data" name="contactform" method="POST">
        <div class="form-grid">
          <input type="hidden" name="captchafield" value="captcha_code2">
          
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="hire-first-name" name="first_name" placeholder="First Name" />
          </div>

          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="hire-last-name" name="last_name" placeholder="Last Name" />
          </div>

          <div class="form-group w-50">
            <label for="email">Email Address</label>
            <input type="email" id="hire-email" name="email" placeholder="Email" />
          </div>

          <div class="form-group w-50">
            <label for="phone">Phone Number</label>
            <input type="tel" id="tel" name="tel">
          </div>

          <div class="form-group w-full">
            <label for="apply_for_role">Apply for role</label>
            <select id="applyforjob" name="applyforjob[]" class="form-control" multiple>
              <option value="mobileApp">Mobile App Developers</option>
              <option value="ios">iOS Developer</option>
              <option value="android">Android Developer</option>
              <option value="swift">Swift Developers</option>
              <option value="kotlin">Kotlin Developers</option>
              <option value="flutter">Flutter Developers</option>
              <option value="reactNative">React Native Developers</option>
              <option value="golang">Golang Developers</option>
              <option value="java">Java Developers</option>
              <option value="dart">Dart Developers</option>
              <option value="frontendWeb">Front-End Web Developers</option>
              <option value="angularjs">AngularJS Developers</option>
              <option value="reactjs">ReactJS Developers</option>
              <option value="vuejs">VueJS Developers</option>
              <option value="backendWeb">Back-End Web Developers</option>
              <option value="nodejs">NodeJS Developers</option>
              <option value="laravel">Laravel Developers</option>
              <option value="python">Python Developers</option>
              <option value="php">PHP Developers</option>
              <option value="ecommerce">E-Commerce Developers</option>
              <option value="wordpress">WordPress Developers</option>
              <option value="shopify">Shopify Developers</option>
              <option value="magento">Magento Developers</option>
              <option value="bigcommerce">BigCommerce Developers</option>
              <option value="woocommerce">WooCommerce Developers</option>
              <option value="trending">Trending Developers</option>
              <option value="devops">DevOps Developers</option>
              <option value="aws">AWS Developers</option>
              <option value="ai">AI Developers</option>
              <option value="ml">ML Developers</option>
              <option value="blockchain">Blockchain Developers</option>
              <option value="ar">AR Developers</option>
              <option value="vr">VR Developers</option>
              <option value="dataAnalytics">Data Analytics</option>
              <option value="metaverse">Metaverse Developers</option>
              <option value="fullStack">Full Stack Developer</option>
            </select>
          </div>

          <div class="form-group w-full">
            <label for="cover-letter">Tell us about your needs.</label>
            <textarea type="text" id="cover-letter" name="cover-letter"
              placeholder="Tell us about your needs."></textarea>
          </div>

          <div class="form-group w-full">
            <div class="captcha-box">
                <canvas id="captchaCanvas2" width="180" height="50"></canvas>
                <button id="refresh2" class="captcha-refresh" type="button" title="Refresh captcha">↻</button>
              </div>
            </div>
          
            <div class="form-group w-full">
              <input id="captchaInput2" name="captchaInput" class="captchaInput" type="text" placeholder="Enter captcha exactly" autocomplete="off" />
            <div id="capcha-error2" class="msg" role="status" aria-live="polite"></div>
            </div>
            

        <div class="form-button text-center">
          <button type="submit" class="btn btn-primary rounded-pill">Submit</button>
        </div>
      </form>
    `);

    // ✅ Dynamically load Select2 CSS + JS + Popper
    var css = document.createElement("link");
    css.rel = "stylesheet";
    css.href = "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css";
    document.head.appendChild(css);

    var select2Script = document.createElement("script");
    select2Script.src = "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js";

    var popperScript = document.createElement("script");
    popperScript.src = "https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js";

    select2Script.onload = function () {
      popperScript.onload = function () {
        // ✅ Initialize Select2 only after both scripts are loaded
        if ($('#applyforjob').length) {
          $('#applyforjob').select2({
            placeholder: "Select a developer",
            allowClear: true,
            width: '100%',
            dropdownParent: $('.career-popup-overlay')
          });
        }
      };
      document.body.appendChild(popperScript);
    };

    var input = document.querySelector(".hire-dev-popup form input[type='tel']");
    if (input) {
      var iti = window.intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function (success, failure) {
          fetch("https://ipapi.co/json/")
            .then(res => res.json())
            .then(data => {
              if (data && data.country_code) {
                success(data.country_code);
              } else {
                success("us"); // fallback
              }
            })
            .catch(() => success("us")); // fallback on error
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
      });
    }


    document.body.appendChild(select2Script);
    loadCaptcha('captchaCanvas2');
  }
});
