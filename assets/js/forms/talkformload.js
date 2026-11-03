$(function() {
    if ($('#talkToUsFormSection').length) {
        $('#talkToUsFormSection').html(`<style>
          .captcha-box { display:inline-flex; align-items:center; gap:12px; margin-bottom:12px; }
          canvas { 
            border: 2px solid #d0d7ff;
            border-radius:6px;
            box-shadow: 0 2px 6px rgba(20,30,70,0.06);
            background: #eef2ff;
          }
          .captcha-refresh { cursor:pointer; padding:8px 10px; border-radius:6px; border:1px solid #c7d2fe; background:#eef2ff; }
          .captchaInput { padding:8px 10px; width:250px; border-radius:6px; border:1px solid #cfd8ff; }
          button { padding:8px 12px; border-radius:6px; border:0; background:#2563eb; color:#fff; cursor:pointer; }
          .msg { margin-top:10px; font-weight:600; }
          .msg.ok { color: #097e29; }
          .msg.warn { color: #d97706; }
          .msg.err { color: #dc2626; }
          .small { font-size:13px; color:#555; margin-top:6px; }
        </style>
        <form class="lets-talk-form">
            <div class="mb-3">
              <input type="text" class="form-control rounded-pill bg-transparent border-white" id="name"
                placeholder="Name" />
            </div>
            <div class="mb-3">
              <input type="email" class="form-control rounded-pill bg-transparent border-white" id="email"
                placeholder="Email" />
            </div>
            <div class="captcha-box">
              <canvas id="captchaCanvas" class="captchaCanvas" width="180" height="50"></canvas>
              <button id="refresh" class="captcha-refresh" type="button" title="Refresh captcha">↻</button>
            </div>
          
            <div>
              <input id="captchaInput" class="captchaInput" type="text" placeholder="Enter captcha exactly" autocomplete="off" />
            </div>
            <div id="capcha-error" class="msg" role="status" aria-live="polite"></div>
            <div class="mb-3">
              <button type="submit" class="btn btn-primary rounded-pill">Submit</button>
            </div>
          </form>`);

          // Attach event listener
          $(document).on('captchaLoaded', function() {
            if (typeof loadCaptcha === 'function') {
              loadCaptcha();
            }
          });

          // ✅ If captcha is already loaded, call immediately
          if (typeof loadCaptcha === 'function') {
            loadCaptcha();
          }
    }
});