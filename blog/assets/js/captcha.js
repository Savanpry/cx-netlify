(function(){
  
  let currentCaptcha = currentCaptcha2 = currentCaptcha3 =  '';

  // function generateCaptcha(len = CAPTCHA_LENGTH) {
  //   const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  //   const LOWER = 'abcdefghijklmnopqrstuvwxyz';
  //   const DIGITS = '0123456789';
  //   const ALL = UPPER + LOWER + DIGITS;
  //   if (len < 3) len = 3;
  //   const chars = [
  //     randomFrom(UPPER),
  //     randomFrom(LOWER),
  //     randomFrom(DIGITS)
  //   ];
  //   for (let i = 3; i < len; i++) chars.push(randomFrom(ALL));
  //   shuffleArray(chars);
  //   return chars.join('');
  // }

  function randomFrom(str) {
    return str.charAt(Math.floor(Math.random() * str.length));
  }

  function shuffleArray(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
  }

  function renderCaptcha(text, canvaseId = 'captchaCanvas') {
    const canvas = document.getElementById(canvaseId);
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#eef2ff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.font = "28px 'Courier New'";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    // Draw some background noise
    for (let i=0; i<10; i++) {
      ctx.strokeStyle = `rgba(0,0,0,${Math.random()*0.3})`;
      ctx.beginPath();
      ctx.moveTo(Math.random()*canvas.width, Math.random()*canvas.height);
      ctx.lineTo(Math.random()*canvas.width, Math.random()*canvas.height);
      ctx.stroke();
    }

    // Draw each char with random rotation
    const spacing = canvas.width / (text.length + 1);
    for (let i = 0; i < text.length; i++) {
      const x = (i+1)*spacing;
      const y = canvas.height/2 + (Math.random()*6 - 3);

      ctx.save();
      const angle = (Math.random()*0.4 - 0.2); // rotate -0.2 to +0.2 rad
      ctx.translate(x, y);
      ctx.rotate(angle);
      ctx.fillStyle = randomColor();
      ctx.fillText(text[i], 0, 0);
      ctx.restore();
    }

    // More noise dots
    for (let i=0; i<40; i++) {
      ctx.fillStyle = randomColor(0.3);
      ctx.beginPath();
      ctx.arc(Math.random()*canvas.width, Math.random()*canvas.height, 1.2, 0, Math.PI*2);
      ctx.fill();
    }
  }

  function randomColor(alpha=1) {
    const r = Math.floor(Math.random()*200);
    const g = Math.floor(Math.random()*200);
    const b = Math.floor(Math.random()*200);
    return `rgba(${r},${g},${b},${alpha})`;
  }

  async function refreshCaptcha() {
    const messageEl = document.getElementById('capcha-error');
    const CAPTCHA_LENGTH = 7;
    const inputEl = document.getElementById('captchaInput');

    currentCaptcha = await generateCaptcha(CAPTCHA_LENGTH, 'refreshCaptcha1');
    renderCaptcha(currentCaptcha, 'captchaCanvas');
    inputEl.value = '';
    messageEl.textContent = '';
    messageEl.className = 'msg';
  }

  async function refreshCaptcha2() {
    const messageEl = document.getElementById('capcha-error2');
    const CAPTCHA_LENGTH = 7;
    const inputEl = document.getElementById('captchaInput2');

    currentCaptcha2 = await generateCaptcha(CAPTCHA_LENGTH, 'refreshCaptcha2');
    debugger;
    renderCaptcha(currentCaptcha2, 'captchaCanvas2');
    inputEl.value = '';
    messageEl.textContent = '';
    messageEl.className = 'msg';
  }

async function refreshCaptcha3() {
  const messageEl = document.getElementById('capcha-error3');
  const CAPTCHA_LENGTH = 7;
  const inputEl = document.getElementById('captchaInput3');

  currentCaptcha3 = await generateCaptcha(CAPTCHA_LENGTH, 'refreshCaptcha3');
  renderCaptcha(currentCaptcha3, 'captchaCanvasCareer');

  inputEl.value = '';
  messageEl.textContent = '';
  messageEl.className = 'msg';
}


  function validateCaptcha() {
    const inputEl = document.getElementById('captchaInput');
    const userVal = inputEl.value.trim();
    const messageEl = document.getElementById('capcha-error');
    if (!currentCaptcha) {
      showMessage('No captcha generated. Please refresh.', 'err');
      return false;
    }
    if (!userVal) {
      showMessage('Please enter the captcha.', 'warn');
      return false;
    }
    if (userVal === currentCaptcha) {
      messageEl.textContent = '';
      return userVal;
    } else {
      showMessage('Captcha did not match. Please try again. 1', 'err');
      return false;
    }
  }

  function validateCaptcha2() {
    const inputEl = document.getElementById('captchaInput2');
    const userVal = inputEl.value.trim();
    const messageEl = document.getElementById('capcha-error2');
    if (!currentCaptcha2) {
      showMessage('No captcha generated. Please refresh.', 'err', 'capcha-error2');
      return false;
    }
    if (!userVal) {
      showMessage('Please enter the captcha.', 'warn', 'capcha-error2');
      return false;
    }
    if (userVal === currentCaptcha2) {
      messageEl.textContent = '';
      return userVal;
    } else {
      showMessage('Captcha did not match. Please try again.', 'err', 'capcha-error2');
      return false;
    }
  }


  function validateCaptcha3() {
    const inputEl = document.getElementById('captchaInput3');
    const userVal = inputEl.value.trim();
    const messageEl = document.getElementById('capcha-error3');
    if (!currentCaptcha3) {
      showMessage('No captcha generated. Please refresh.', 'err', 'capcha-error3');
      return false;
    }
    if (!userVal) {
      showMessage('Please enter the captcha.', 'warn', 'capcha-error3');
      return false;
    }
    if (userVal === currentCaptcha3) {
      messageEl.textContent = '';
      return userVal;
    } else {
      showMessage('Captcha did not match. Please try again.', 'err', 'capcha-error3');
      return false;
    }
  }

  function showMessage(text, kind, errorDiv = 'capcha-error') {
    const messageEl = document.getElementById(errorDiv);
    messageEl.textContent = text;
    messageEl.className = 'msg ' + (kind === 'ok' ? 'ok' : (kind === 'warn' ? 'warn' : 'err'));
  }


  function loadCaptcha() {
    const refreshBtn = document.getElementById('refresh');
    refreshBtn.addEventListener('click', refreshCaptcha);

    const refreshBtn2 = document.getElementById('refresh2');
    if($(refreshBtn2).length) {
      debugger;
      refreshBtn2.addEventListener('click', refreshCaptcha2);
      refreshCaptcha2();
    }

    const refreshBtn3 = document.getElementById('refresh3');
    if($(refreshBtn3).length) {
      refreshBtn3.addEventListener('click', refreshCaptcha3);
      refreshCaptcha3();
    }

    // generate first captcha
    refreshCaptcha();
    //refreshCaptcha3();
    
  }

  async function generateCaptcha(len,functionName = '') {
    const res = await fetch('https://www.cypherox.com/maildetails.php?captcha_generate=1&functionName='+functionName);
    const data = await res.json();
    return data.captcha;
  }

  window.loadCaptcha = loadCaptcha;
  window.validateCaptcha = validateCaptcha;
  window.validateCaptcha2 = validateCaptcha2;
  window.validateCaptcha3 = validateCaptcha3;
  window.refreshCaptcha = refreshCaptcha;
  window.refreshCaptcha2 = refreshCaptcha2;
  window.refreshCaptcha3 = refreshCaptcha3;
  
  
})();