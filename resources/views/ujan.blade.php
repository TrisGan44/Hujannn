<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Text Rain Simulator - Minimal</title>
  <style>
    /* —— Base —— */
    :root{
      --bg-0:#000000;
      --bg-1:#050505;
      --bg-2:#0a0a0a;
      --ink-0:#ffffff;
      --ink-1:#c8c8c8;
      --ink-2:#7a7a7a;
      --acc-0:#0ea5e9;
      --acc-1:#22d3ee;
      --ring:#0ea5e950;
      --card:#0a0a0acc;
      --border:#1a1a1a;
      --radius:16px;
      --shadow:0 20px 50px rgba(0,0,0,.7), 0 0 1px rgba(255,255,255,.03);
      --blur:20px;
      --glow:0 0 0 1px rgba(255,255,255,.04), 0 0 40px #000;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}
    body{
      font-family: ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Inter,Arial,Helvetica,sans-serif;
      background:
        radial-gradient(1200px 800px at 70% -10%, #0b0b0b 0%, transparent 60%),
        radial-gradient(900px 600px at -20% 30%, #0c0c0c 0%, transparent 55%),
        linear-gradient(180deg, var(--bg-0), var(--bg-1) 35%, var(--bg-2) 100%);
      color:var(--ink-0);
      overflow:hidden;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    body::before{
      content:"";
      position:fixed;inset:0;
      background: radial-gradient(60% 60% at 50% 0%, rgba(255,255,255,.06), transparent 60%);
      pointer-events:none;
      mix-blend-mode:overlay;
    }

    /* —— Layout —— */
    .wrap{
      position:relative;
      z-index:2;
      width:100%;
      max-width:420px; /* minimalis */
      padding:20px;
    }
    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:18px;
      backdrop-filter: blur(var(--blur)) saturate(140%);
      box-shadow:var(--shadow), var(--glow);
      transform: translateZ(0);
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      will-change: transform;
    }
    .card:hover{
      transform: translateY(-3px);
      border-color:#111827;
      box-shadow: 0 30px 70px rgba(0,0,0,.85), 0 0 1px rgba(255,255,255,.06);
    }
    .stack{display:flex;flex-direction:column;gap:14px}
    .row{display:flex;gap:10px;align-items:center;flex-wrap:wrap}

    /* —— Title —— */
    .title{
      font-weight:800;letter-spacing:.06em;
      text-transform:uppercase;
      font-size:18px;
      color:#e5e7eb;
      padding-bottom:6px;
    }
    .muted{color:var(--ink-2);font-size:12px}

    /* —— Controls —— */
    .choice{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
    }
    .radio{
      position:relative;
      border:1px solid var(--border);
      background:linear-gradient(180deg,#0a0a0a 0%, #060606 100%);
      border-radius:12px;
      padding:14px 12px;
      text-align:center;
      cursor:pointer;
      user-select:none;
      font-weight:700;
      letter-spacing:.06em;
      transition:border-color .25s ease, transform .2s ease, box-shadow .25s ease;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.02);
    }
    .radio:hover{transform:translateY(-2px)}
    .radio input{position:absolute;opacity:0;pointer-events:none}
    .radio.active{
      border-color:var(--ring);
      box-shadow: 0 0 0 2px #000, 0 0 0 1.5px var(--ring), inset 0 0 0 1px rgba(255,255,255,.03);
      background:linear-gradient(180deg,#0b0b0b 0%, #080808 100%);
      text-shadow:0 0 30px rgba(14,165,233,.25);
      color:#e6faff;
    }

    .group{display:flex;flex-direction:column;gap:8px}
    .label{
      text-transform:uppercase; letter-spacing:.08em;
      font-weight:800; font-size:12px; color:#9ca3af;
    }
    .input, .file{
      width:100%;
      background:#040404;
      color:#e5e7eb;
      border:1px solid var(--border);
      border-radius:12px;
      padding:12px 14px;
      outline:none;
      transition:border-color .25s ease, transform .2s ease, box-shadow .25s ease;
    }
    .input:hover,.file:hover{transform:translateY(-1px)}
    .input:focus, .file:focus{
      border-color:var(--ring);
      box-shadow: 0 0 0 2px #000, 0 0 0 1.5px var(--ring);
    }
    .file::-webkit-file-upload-button{
      background:#0b0b0b;
      color:#d1d5db;
      border:1px solid #111;
      padding:9px 12px;
      border-radius:9px;
      margin-right:10px;
      cursor:pointer;
      transition:transform .2s ease, border-color .25s ease;
    }
    .file::-webkit-file-upload-button:hover{transform:translateY(-1px);border-color:#1f2937}

    .slider{
      -webkit-appearance:none;appearance:none;width:100%;height:8px;border-radius:999px;
      background:linear-gradient(90deg,#0c0c0c,#121212);
      border:1px solid #121212;
      box-shadow: inset 0 2px 8px rgba(0,0,0,.75);
      cursor:pointer; transition: transform .2s ease;
    }
    .slider:hover{transform:translateY(-1px)}
    .slider::-webkit-slider-thumb{
      -webkit-appearance:none;appearance:none;
      width:22px;height:22px;border-radius:50%;
      background: radial-gradient(circle at 30% 30%, #1f2937 0%, #0b0b0b 60%);
      border:1px solid #1f2937;
      box-shadow: 0 6px 18px rgba(0,0,0,.85), 0 0 0 1.5px rgba(255,255,255,.04);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .slider::-webkit-slider-thumb:hover{transform:scale(1.1); box-shadow:0 10px 26px rgba(0,0,0,.9), 0 0 0 2px rgba(255,255,255,.06)}
    .slider::-moz-range-thumb{width:22px;height:22px;border-radius:50%;background:#0b0b0b;border:1px solid #1f2937}

    .buttons{display:flex;gap:8px;flex-wrap:wrap}
    .btn{
      flex:1 1 auto;min-width:110px;
      background:linear-gradient(180deg,#0b0b0b,#060606);
      color:#e5e7eb;border:1px solid var(--border);
      border-radius:12px;padding:12px 14px;
      font-weight:800;letter-spacing:.08em;text-transform:uppercase;
      cursor:pointer; transition: transform .2s ease, border-color .25s ease, box-shadow .25s ease;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.02);
    }
    .btn:hover{transform:translateY(-2px)}
    .btn:active{transform:translateY(-1px)}
    .btn.alt{border-color:#1f2937;background:linear-gradient(180deg,#0c0c0c,#080808)}
    .btn.stop{border-color:#3f0c0c;background:linear-gradient(180deg,#120202,#090101); color:#fca5a5}

    .status{
      margin-top:8px;
      font-size:12px; text-transform:uppercase; letter-spacing:.08em;
      color:#9ca3af;border:1px solid var(--border); padding:10px 12px;border-radius:12px;
      background:#060606;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .status.active{
      color:#c2f3ff;border-color:var(--ring);
      box-shadow: 0 0 0 2px #000, 0 0 0 1.5px var(--ring);
      transform:translateY(-2px)
    }

    /* —— Stage —— */
    .stage{position:fixed; inset:0; pointer-events:none; z-index:0}
    .storm{position:fixed; inset:0; pointer-events:none; z-index:1; opacity:0; transition:opacity .3s ease}
    .storm.active{opacity:1; animation:flash 4s infinite}
    @keyframes flash{0%,92%,94%,96%,100%{background:transparent}93%,95%{background:rgba(255,255,255,.04)}}

    .item{
      position:absolute; font-weight:800; white-space:nowrap; pointer-events:none; will-change: transform, opacity;
      text-shadow: 0 0 10px rgba(255,255,255,.05);
      filter: drop-shadow(0 0 10px rgba(0,0,0,.8));
      color:#a5f3fc;
    }
    .item img{
      width:80px;height:80px;object-fit:contain;border-radius:10px;
      box-shadow:0 0 18px rgba(255,255,255,.06);
      background:#000;
    }

    /* —— Falls —— */
    @keyframes fallA{from{transform:translateY(-110vh) rotate(0deg)}to{transform:translateY(110vh) rotate(720deg)}}
    @keyframes fallB{from{transform:translateY(-110vh) rotate(0deg) scale(1)}to{transform:translateY(110vh) rotate(-540deg) scale(.9)}}
    @keyframes fallC{from{transform:translateY(-110vh) rotate(0deg) translateX(0)}to{transform:translateY(110vh) rotate(540deg) translateX(100px)}}
    @keyframes fallD{from{transform:translateY(-110vh) rotate(0deg) skew(0)}to{transform:translateY(110vh) rotate(-720deg) skew(8deg)}}

    /* —— Helper —— */
    .hidden{display:none!important}
    .spacer{height:2px}
  </style>
</head>
<body>
  <!-- Stage -->
  <div id="stage" class="stage"></div>
  <div id="storm" class="storm"></div>

  <!-- UI -->
  <div class="wrap">
    <div class="card stack" id="panel">
      <div class="title">Simulator</div>

      <!-- Pilihan mode -->
      <div class="choice" id="modeChoice">
        <label class="radio" id="optText">
          <input type="radio" name="mode" value="text" checked />
          Teks
        </label>
        <label class="radio" id="optImage">
          <input type="radio" name="mode" value="image" />
          Gambar
        </label>
      </div>

      <!-- Input TEKS -->
      <div class="group" id="textGroup">
        <div class="label">Masukkan Teks</div>
        <input id="textInput" class="input" type="text" placeholder="Ketik teks" />
      </div>

      <!-- Input GAMBAR -->
      <div class="group hidden" id="imageGroup">
        <div class="label">Unggah Gambar</div>
        <input id="imageInput" class="file" type="file" accept="image/*" />
      </div>

      <!-- Kontrol besar (untuk TEKS dan GAMBAR) -->
      <div id="controls" class="stack">
        <div class="group">
          <div class="label">Intensitas</div>
          <input id="intensity" class="slider" type="range" min="5" max="30" value="15" />
          <div class="muted">Nilai: <span id="intensityVal">15</span></div>
        </div>

        <div class="group">
          <div class="label">BESAR HUJAN</div>
          <input id="big" class="slider" type="range" min="5" max="50" value="20" />
          <div class="muted">Skala: <span id="bigVal">20</span></div>
        </div>

        <div class="buttons">
          <button class="btn alt" id="btnOnce">Sekali</button>
          <button class="btn alt" id="btnLoop">Terus</button>
          <button class="btn alt" id="btnHeavy">Lebat</button>
          <button class="btn stop" id="btnStop">Stop</button>
        </div>
      </div>

      <div class="status" id="status">Siap</div>
    </div>
    <div class="spacer"></div>
  </div>

  <script>
    // State
    let raining = false;
    let mode = 'text';
    let textValue = '';
    let imgData = null;
    let items = [];
    let interval = null;
    let rainMode = 'stopped';
    let intensity = 15;
    let big = 20;

    // DOM
    const stage = document.getElementById('stage');
    const storm = document.getElementById('storm');
    const statusEl = document.getElementById('status');

    const optText = document.getElementById('optText');
    const optImage = document.getElementById('optImage');

    const textGroup = document.getElementById('textGroup');
    const imageGroup = document.getElementById('imageGroup');
    const controls = document.getElementById('controls');

    const textInput = document.getElementById('textInput');
    const imageInput = document.getElementById('imageInput');

    const intensityRange = document.getElementById('intensity');
    const intensityVal = document.getElementById('intensityVal');
    const bigRange = document.getElementById('big');
    const bigVal = document.getElementById('bigVal');

    const btnOnce = document.getElementById('btnOnce');
    const btnLoop = document.getElementById('btnLoop');
    const btnHeavy = document.getElementById('btnHeavy');
    const btnStop = document.getElementById('btnStop');

    // Helpers
    const setStatus = (t, active=false) => {
      statusEl.textContent = t;
      statusEl.classList.toggle('active', active);
    };

    const setModeActive = () => {
      optText.classList.toggle('active', mode === 'text');
      optImage.classList.toggle('active', mode === 'image');

      if (mode === 'text') {
        textGroup.classList.remove('hidden');
        imageGroup.classList.add('hidden');
        controls.classList.remove('hidden'); // kontrol besar tampil saat TEKS
      } else {
        textGroup.classList.add('hidden');
        imageGroup.classList.remove('hidden');
        controls.classList.remove('hidden'); // kontrol besar tampil juga saat GAMBAR
      }
    };

    const clearIntervalIfAny = () => {
      if (interval) {
        clearInterval(interval);
        interval = null;
      }
    };

    const removeItem = (el) => {
      if (el && el.parentNode) el.parentNode.removeChild(el);
      const i = items.indexOf(el);
      if (i > -1) items.splice(i, 1);
    };

    const createItem = () => {
      // Guard input
      if (mode === 'text' && !textValue) return;
      if (mode === 'image' && !imgData) return;

      const el = document.createElement('div');
      el.className = 'item';

      if (mode === 'image') {
        const img = document.createElement('img');
        img.src = imgData;
        el.appendChild(img);
      } else {
        el.textContent = textValue.toUpperCase();
        const size = Math.random() * 36 + 36;
        el.style.fontSize = size + 'px';
      }

      // Randomize
      const startX = Math.random() * (window.innerWidth + 160) - 80;
      const dur = Math.random() * 1.2 + 0.9;
      const delay = Math.random() * .4;

      const anims = ['fallA','fallB','fallC','fallD'];
      const pick = anims[Math.floor(Math.random()*anims.length)];
      el.style.left = startX + 'px';
      el.style.top = '0px';
      el.style.animation = `${pick} ${dur}s linear ${delay}s 1 both`;

      // Slight tint variety (still on cool, dim palette)
      if (mode === 'text' && Math.random() > .7) {
        const h = 190 + Math.random()*20;
        const l = 75 + Math.random()*5;
        el.style.color = `hsl(${h} 80% ${l}%)`;
      }

      stage.appendChild(el);
      items.push(el);

      setTimeout(()=> removeItem(el), (dur + delay) * 1000);
    };

    const startOnce = () => {
      if (mode === 'text' && !textValue) { setStatus('Masukkan teks terlebih dahulu', true); resetStatus(); return; }
      if (mode === 'image' && !imgData)  { setStatus('Unggah gambar terlebih dahulu', true); resetStatus(); return; }

      rainMode = 'single';
      setStatus('Menjalankan sekali', true);
      storm.classList.remove('active');

      const count = Math.max(intensity, 5);
      for (let i=0;i<count;i++){
        setTimeout(createItem, i*70);
      }
      setTimeout(()=>{ setStatus('Siap'); rainMode='stopped'; }, 2200);
    };

    const startLoop = () => {
      if (mode === 'text' && !textValue) { setStatus('Masukkan teks terlebih dahulu', true); resetStatus(); return; }
      if (mode === 'image' && !imgData)  { setStatus('Unggah gambar terlebih dahulu', true); resetStatus(); return; }
      if (raining) return;

      raining = true; rainMode='continuous';
      setStatus('Hujan berkelanjutan', true);
      storm.classList.remove('active');

      const perSec = Math.max(intensity*2, 6);
      const tick = 1000/perSec;

      clearIntervalIfAny();
      interval = setInterval(()=>{
        if (!raining) return;
        for (let i=0;i<Math.ceil(intensity/2);i++) createItem();
      }, tick);
    };

    const startHeavy = () => {
      if (!textValue && !imgData){ setStatus('Masukkan teks atau unggah gambar', true); resetStatus(); return; }

      stopRain(false);
      setTimeout(()=>{
        raining = true; rainMode='heavy';
        setStatus('Hujan sangat lebat', true);
        storm.classList.add('active');

        const perSec = Math.max(big*4, 18);
        const tick = 1000/perSec;

        clearIntervalIfAny();
        interval = setInterval(()=>{
          if (!raining) return;
          for (let i=0;i<Math.ceil(big/1.2);i++) createItem();
          if (Math.random() < .35){
            for (let j=0;j<6;j++) setTimeout(createItem, j*35);
          }
        }, tick);
      }, 80);
    };

    const stopRain = (notify=true) => {
      raining = false; rainMode='stopped';
      clearIntervalIfAny();
      storm.classList.remove('active');
      if (notify) setStatus('Berhenti');

      // sweep
      setTimeout(()=>{
        items.forEach(el => removeItem(el));
        items = [];
        if (notify) setStatus('Siap');
      }, 500);
    };

    const resetStatus = () => setTimeout(()=> setStatus('Siap'), 1400);

    // Events
    // Mode choice with hover movement
    const pickMode = (next) => {
      mode = next;
      setModeActive();
      setStatus('Siap');
      stopRain(false);
    };

    optText.addEventListener('click', ()=> pickMode('text'));
    optImage.addEventListener('click', ()=> pickMode('image'));
    optText.addEventListener('mousemove', e => optText.style.transform = `translateY(${-2 - (e.offsetY/60)}px)`);
    optImage.addEventListener('mousemove', e => optImage.style.transform = `translateY(${-2 - (e.offsetY/60)}px)`);
    optText.addEventListener('mouseleave', ()=> optText.style.transform='');
    optImage.addEventListener('mouseleave', ()=> optImage.style.transform='');

    textInput.addEventListener('input', e => textValue = e.target.value);
    imageInput.addEventListener('change', e=>{
      const f = e.target.files && e.target.files[0];
      if (!f) return;
      const r = new FileReader();
      r.onload = ev => imgData = ev.target.result;
      r.readAsDataURL(f);
    });

    intensityRange.addEventListener('input', e=>{
      intensity = parseInt(e.target.value,10);
      intensityVal.textContent = intensity;
      if (raining && rainMode==='continuous'){ stopRain(false); setTimeout(startLoop, 80); }
    });
    bigRange.addEventListener('input', e=>{
      big = parseInt(e.target.value,10);
      bigVal.textContent = big;
      if (raining && rainMode==='heavy'){ stopRain(false); setTimeout(startHeavy, 80); }
    });

    btnOnce.addEventListener('click', startOnce);
    btnLoop.addEventListener('click', startLoop);
    btnHeavy.addEventListener('click', startHeavy);
    btnStop.addEventListener('click', ()=> stopRain(true));

    window.addEventListener('resize', ()=>{
      if (raining && rainMode==='continuous'){ stopRain(false); setTimeout(startLoop, 200); }
      if (raining && rainMode==='heavy'){ stopRain(false); setTimeout(startHeavy, 200); }
    });

    // Init
    setModeActive();
    intensityVal.textContent = intensity;
    bigVal.textContent = big;
  </script>
</body>
</html>
