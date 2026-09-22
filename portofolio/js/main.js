/**
 * Interactive Morning Clouds & Portfolio Controller
 * Combines WebGL morning cloud shader engine, typewriter effect, and dynamic project cards.
 */
document.addEventListener('DOMContentLoaded', () => {
  'use strict';

  // --- 0. Inject Dark Mode Toggle ---
  const navContainer = document.querySelector('nav .container');
  const savedTheme = localStorage.getItem('portfolio-theme');
  let isInitialDark = savedTheme === 'dark';
  let targetDarkMode = isInitialDark ? 1.0 : 0.0;
  let currentDarkMode = targetDarkMode;

  if (isInitialDark) {
    document.body.classList.add('dark-mode');
  }

  if (navContainer) {
    const toggleHTML = `
      <div class="theme-toggle-container">
        <label class="theme-toggle" for="dark-mode-switch" title="Beralih Mode Terang / Gelap">
          <input type="checkbox" id="dark-mode-switch" ${isInitialDark ? 'checked' : ''}>
          <div class="toggle-track">
            <!-- Day Sky (Clouds) -->
            <div class="toggle-clouds">
              <svg class="cloud-svg cloud-back" viewBox="0 0 28 16" width="26" height="15">
                <path d="M4 14h20a4 4 0 0 0 0-8c-.5 0-1 .1-1.4.3A6 6 0 0 0 11 4a6 6 0 0 0-5.8 4.5A4 4 0 0 0 4 14z" fill="#9bc6de"/>
              </svg>
              <svg class="cloud-svg cloud-front" viewBox="0 0 34 20" width="32" height="19">
                <path d="M5 18h24a5 5 0 0 0 0-10c-.6 0-1.2.1-1.8.3A7.5 7.5 0 0 0 13 5a7.5 7.5 0 0 0-7.2 5.5A5 5 0 0 0 5 18z" fill="#ffffff"/>
                <path d="M13 7a5.5 5.5 0 0 1 5.3 4H20a3.5 3.5 0 0 1 3.5 3.5c0 .3 0 .6-.1.8A4.5 4.5 0 0 0 29 13a4.5 4.5 0 0 0-4.5-4.5c-.5 0-1 .1-1.5.3A7 7 0 0 0 13 7z" fill="#f0f7fb"/>
              </svg>
            </div>
            <!-- Night Sky (Stars & Silhouette) -->
            <div class="toggle-night">
              <span class="star s1"></span>
              <span class="star s2"></span>
              <span class="star s3"></span>
              <span class="star s4"></span>
              <span class="star s5"></span>
              <span class="star s6"></span>
              <div class="night-curve"></div>
            </div>
            <!-- Knob (Sun / Moon) -->
            <div class="toggle-thumb">
              <div class="moon-craters">
                <span class="crater c1"></span>
                <span class="crater c2"></span>
                <span class="crater c3"></span>
              </div>
            </div>
          </div>
        </label>
      </div>
    `;
    navContainer.insertAdjacentHTML('beforeend', toggleHTML);
  }

  const darkModeSwitch = document.getElementById('dark-mode-switch');
  if (darkModeSwitch) {
    darkModeSwitch.addEventListener('change', (e) => {
      if (e.target.checked) {
        targetDarkMode = 1.0;
        document.body.classList.add('dark-mode');
        localStorage.setItem('portfolio-theme', 'dark');
      } else {
        targetDarkMode = 0.0;
        document.body.classList.remove('dark-mode');
        localStorage.setItem('portfolio-theme', 'light');
      }
    });
  }

  // --- 1. Interactive WebGL Morning Cloud Background Engine ---
  const canvas = document.getElementById('cloud-canvas');
  const fallback = document.getElementById('cloud-fallback');

  if (canvas) {
    const gl = canvas.getContext('webgl', {
      antialias: false,
      alpha: false,
      powerPreference: 'high-performance'
    });

    if (!gl) {
      canvas.style.display = 'none';
      if (fallback) fallback.style.display = 'block';
    } else {
      const vertexShaderSource = `
        attribute vec2 a_position;
        void main() {
          gl_Position = vec4(a_position, 0.0, 1.0);
        }
      `;

      const fragmentShaderSource = `
        precision highp float;

        uniform vec2 u_resolution;
        uniform float u_time;
        uniform float u_dark_mode;

        float hash(vec2 p) {
          p = fract(p * vec2(234.34, 435.345));
          p += dot(p, p + 34.23);
          return fract(p.x * p.y);
        }

        float noise(vec2 p) {
          vec2 i = floor(p);
          vec2 f = fract(p);
          vec2 u = f * f * (3.0 - 2.0 * f);

          return mix(
            mix(hash(i + vec2(0.0, 0.0)), hash(i + vec2(1.0, 0.0)), u.x),
            mix(hash(i + vec2(0.0, 1.0)), hash(i + vec2(1.0, 1.0)), u.x),
            u.y
          );
        }

        /* Fractional Brownian Motion with rotation for isotropic detail */
        float fbm(vec2 p) {
          float value = 0.0;
          float amplitude = 0.5;
          mat2 rot = mat2(cos(0.52), sin(0.52), -sin(0.52), cos(0.52));

          for (int i = 0; i < 5; i++) {
            value += amplitude * noise(p);
            p = rot * p * 2.02 + vec2(100.0);
            amplitude *= 0.5;
          }
          return value;
        }

        /* Procedural Star field generator with twinkling */
        float starField(vec2 uv, float t) {
          vec2 p1 = uv * 55.0;
          vec2 id1 = floor(p1);
          vec2 f1 = fract(p1) - 0.5;
          float h1 = hash(id1);
          float s1 = 0.0;
          if (h1 > 0.81) {
            vec2 offset1 = vec2(hash(id1 + 1.23) - 0.5, hash(id1 + 4.56) - 0.5) * 0.7;
            float d1 = length(f1 - offset1);
            float twinkle1 = sin(t * (1.8 + h1 * 3.5) + h1 * 6.28) * 0.5 + 0.5;
            float r1 = mix(0.045, 0.10, hash(id1 + 9.87));
            s1 = smoothstep(r1, 0.005, d1) * (0.4 + 0.6 * twinkle1);
          }

          vec2 p2 = (uv + vec2(12.3, 45.6)) * 95.0;
          vec2 id2 = floor(p2);
          vec2 f2 = fract(p2) - 0.5;
          float h2 = hash(id2);
          float s2 = 0.0;
          if (h2 > 0.85) {
            vec2 offset2 = vec2(hash(id2 + 2.34) - 0.5, hash(id2 + 6.78) - 0.5) * 0.7;
            float d2 = length(f2 - offset2);
            float twinkle2 = sin(t * (2.2 + h2 * 4.0) + h2 * 6.28) * 0.5 + 0.5;
            s2 = smoothstep(0.065, 0.005, d2) * (0.35 + 0.65 * twinkle2) * 0.75;
          }

          return s1 + s2;
        }

        /* Volumetric cloud layer with domain warping and sun-directed lighting */
        vec4 renderDayCloud(
          vec2 uv,
          float scale,
          float speed,
          float densityMin,
          float densityMax,
          vec2 sunDirection
        ) {
          vec2 position = uv * scale;

          /* Natural continuous drift */
          position.x += u_time * speed * 1.8;
          position.y += u_time * speed * 0.08;

          /* Domain warping for organic puff formation */
          vec2 q = vec2(
            fbm(position + u_time * 0.008),
            fbm(position + vec2(5.2, 1.3) - u_time * 0.008)
          );

          vec2 r = vec2(
            fbm(position + 0.35 * q + u_time * 0.012),
            fbm(position + 0.35 * q + vec2(4.1, 1.9) + u_time * 0.012)
          );

          float cloudValue = fbm(position + 0.55 * r);

          float alpha = smoothstep(densityMin, densityMax, cloudValue);

          /* Directional light sampling for 3D self-shadowing and depth */
          float lightSample = fbm(position + 0.55 * r + sunDirection * 0.09);
          float directLight = clamp(cloudValue - lightSample, 0.0, 1.0);
          float rimLight = smoothstep(0.0, 0.35, directLight);

          /* Volumetric density shadow: thicker cloud bases capture ambient skylight */
          float bodyShade = smoothstep(densityMin, densityMax * 1.25, cloudValue);

          /* Day and Night cloud color palettes */
          vec3 undersideShadowDay = vec3(0.68, 0.77, 0.88);
          vec3 cloudBodyDay = vec3(0.93, 0.96, 0.99);
          vec3 sunlitHighlightDay = vec3(1.0, 0.99, 0.97);

          // Clear moonlight cloud volume at night
          vec3 undersideShadowNight = vec3(0.04, 0.07, 0.14);
          vec3 cloudBodyNight = vec3(0.12, 0.18, 0.30);
          vec3 sunlitHighlightNight = vec3(0.36, 0.46, 0.62);

          vec3 undersideShadow = mix(undersideShadowDay, undersideShadowNight, u_dark_mode);
          vec3 cloudBody = mix(cloudBodyDay, cloudBodyNight, u_dark_mode);
          vec3 sunlitHighlight = mix(sunlitHighlightDay, sunlitHighlightNight, u_dark_mode);

          vec3 cloudColor = mix(undersideShadow, cloudBody, bodyShade);
          cloudColor = mix(cloudColor, sunlitHighlight, rimLight * 0.45);

          return vec4(cloudColor, alpha);
        }

        void main() {
          vec2 uv = gl_FragCoord.xy / u_resolution.xy;
          float aspect = u_resolution.x / u_resolution.y;

          vec2 position = uv * 2.0 - 1.0;
          position.x *= aspect;

          /* Sky gradient transition (Deep midnight dark sky) */
          vec3 skyZenithDay = vec3(0.20, 0.54, 0.89);
          vec3 skyMidDay = vec3(0.42, 0.71, 0.95);
          vec3 skyHorizonDay = vec3(0.74, 0.88, 0.98);
          vec3 morningWarmthDay = vec3(0.98, 0.86, 0.74);

          vec3 skyZenithNight = vec3(0.010, 0.016, 0.038);
          vec3 skyMidNight = vec3(0.020, 0.032, 0.068);
          vec3 skyHorizonNight = vec3(0.030, 0.048, 0.095);
          vec3 morningWarmthNight = vec3(0.015, 0.025, 0.045);

          vec3 skyZenith = mix(skyZenithDay, skyZenithNight, u_dark_mode);
          vec3 skyMid = mix(skyMidDay, skyMidNight, u_dark_mode);
          vec3 skyHorizon = mix(skyHorizonDay, skyHorizonNight, u_dark_mode);
          vec3 morningWarmth = mix(morningWarmthDay, morningWarmthNight, u_dark_mode);

          vec3 sky = mix(skyHorizon, skyMid, smoothstep(0.0, 0.55, uv.y));
          sky = mix(sky, skyZenith, smoothstep(0.5, 1.0, uv.y));

          /* Procedural twinkling starry sky in dark mode */
          float stars = starField(uv, u_time);
          vec3 starColor = mix(vec3(0.92, 0.96, 1.0), vec3(1.0, 0.95, 0.88), hash(floor(uv * 55.0)));
          sky += starColor * stars * u_dark_mode * smoothstep(0.02, 0.30, uv.y);

          /* Morning sun / Night moon celestial body */
          vec2 sunPosition = vec2(aspect > 1.0 ? aspect * 0.48 : 0.45, 0.65);
          float sunDistance = length(position - sunPosition);

          float sunCore = smoothstep(0.07, 0.035, sunDistance);
          float sunGlow = exp(-sunDistance * 2.4) * 0.50;
          float wideHaze = exp(-sunDistance * 0.8) * 0.18;

          float moonCore = smoothstep(0.052, 0.028, sunDistance);
          float moonGlow = exp(-sunDistance * 4.2) * 0.38;
          float moonHaze = exp(-sunDistance * 1.5) * 0.05;

          float core = mix(sunCore, moonCore, u_dark_mode);
          float glow = mix(sunGlow, moonGlow, u_dark_mode);
          float haze = mix(wideHaze, moonHaze, u_dark_mode);

          vec3 sunCoreColor = mix(vec3(1.0, 0.99, 0.94), vec3(0.97, 0.96, 0.92), u_dark_mode);
          vec3 sunCoronaColor = mix(vec3(1.0, 0.94, 0.82), vec3(0.50, 0.62, 0.85), u_dark_mode);

          sky += (core * sunCoreColor) + (glow * sunCoronaColor) + (haze * morningWarmth);

          vec3 finalColor = sky;

          vec2 activePos = position;
          vec2 sunDir = normalize(sunPosition - position);

          /* 4 Multi-scale cloud layers */
          vec4 layerFar = renderDayCloud(
            activePos, 1.6, 0.008, 0.28, 0.78, sunDir
          );
          finalColor = mix(finalColor, layerFar.rgb, layerFar.a * 0.82);

          vec4 layerMidFar = renderDayCloud(
            activePos + vec2(1.7, 0.5), 1.2, 0.013, 0.30, 0.82, sunDir
          );
          finalColor = mix(finalColor, layerMidFar.rgb, layerMidFar.a * 0.88);

          vec4 layerMid = renderDayCloud(
            activePos + vec2(3.2, 1.1), 0.85, 0.020, 0.33, 0.86, sunDir
          );
          finalColor = mix(finalColor, layerMid.rgb, layerMid.a * 0.92);

          vec4 layerNear = renderDayCloud(
            activePos + vec2(4.8, 2.4), 0.55, 0.028, 0.38, 0.90, sunDir
          );
          finalColor = mix(finalColor, layerNear.rgb, layerNear.a * 0.94);

          /* Subtle atmospheric contrast adjustment */
          float vignette = length((uv - vec2(0.5, 0.55)) * vec2(0.8, 1.0));
          finalColor *= smoothstep(1.3, 0.3, vignette);

          gl_FragColor = vec4(finalColor, 1.0);
        }
      `;

      function compileShader(type, source) {
        const shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);

        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
          console.warn('Shader compilation failed:', gl.getShaderInfoLog(shader));
          gl.deleteShader(shader);
          return null;
        }
        return shader;
      }

      const vertexShader = compileShader(gl.VERTEX_SHADER, vertexShaderSource);
      const fragmentShader = compileShader(gl.FRAGMENT_SHADER, fragmentShaderSource);

      if (vertexShader && fragmentShader) {
        const program = gl.createProgram();
        gl.attachShader(program, vertexShader);
        gl.attachShader(program, fragmentShader);
        gl.linkProgram(program);

        if (gl.getProgramParameter(program, gl.LINK_STATUS)) {
          gl.useProgram(program);

          const quadPositions = new Float32Array([
            -1, -1,
             1, -1,
            -1,  1,
            -1,  1,
             1, -1,
             1,  1
          ]);

          const buffer = gl.createBuffer();
          gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
          gl.bufferData(gl.ARRAY_BUFFER, quadPositions, gl.STATIC_DRAW);

          const positionLocation = gl.getAttribLocation(program, 'a_position');
          gl.enableVertexAttribArray(positionLocation);
          gl.vertexAttribPointer(positionLocation, 2, gl.FLOAT, false, 0, 0);

          const resolutionLocation = gl.getUniformLocation(program, 'u_resolution');
          const timeLocation = gl.getUniformLocation(program, 'u_time');
          const darkModeLocation = gl.getUniformLocation(program, 'u_dark_mode');

          const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
          let motionScale = prefersReducedMotion.matches ? 0.05 : 1.0;
          prefersReducedMotion.addEventListener('change', (e) => {
            motionScale = e.matches ? 0.05 : 1.0;
          });

          function resize() {
            const pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
            const width = Math.floor(window.innerWidth * pixelRatio);
            const height = Math.floor(window.innerHeight * pixelRatio);

            if (canvas.width !== width || canvas.height !== height) {
              canvas.width = width;
              canvas.height = height;
              gl.viewport(0, 0, canvas.width, canvas.height);
              gl.uniform2f(resolutionLocation, canvas.width, canvas.height);
            }
          }

          window.addEventListener('resize', resize);
          resize();

          let isPageVisible = true;
          document.addEventListener('visibilitychange', () => {
            isPageVisible = !document.hidden;
          });

          let simulatedTime = 0;
          let lastTimestamp = performance.now();

          function render(timestamp) {
            const delta = Math.min((timestamp - lastTimestamp) * 0.001, 0.1);
            lastTimestamp = timestamp;

            if (isPageVisible) {
              simulatedTime += delta * motionScale;

              // Smooth transition for dark mode
              currentDarkMode += (targetDarkMode - currentDarkMode) * 0.05;

              gl.uniform1f(timeLocation, simulatedTime);
              gl.uniform1f(darkModeLocation, currentDarkMode);

              gl.drawArrays(gl.TRIANGLES, 0, 6);
            }

            requestAnimationFrame(render);
          }

          requestAnimationFrame(render);
        } else {
          console.warn('Program linking failed:', gl.getProgramInfoLog(program));
          canvas.style.display = 'none';
          if (fallback) fallback.style.display = 'block';
        }
      } else {
        canvas.style.display = 'none';
        if (fallback) fallback.style.display = 'block';
      }
    }
  }

  // --- 2. Hero Typewriter Animation ---
  const typingText = document.getElementById('typing-text');
  if (typingText) {
    const names = ['Mahasiswa SI', 'Muhamad Sholichin', 'Web Developer'];
    let nameIndex = 0;
    let charIndex = 0;
    let isDeleting = false;

    function typeEffect() {
      const currentName = names[nameIndex];
      if (isDeleting) {
        typingText.textContent = currentName.substring(0, charIndex - 1);
        charIndex--;
      } else {
        typingText.textContent = currentName.substring(0, charIndex + 1);
        charIndex++;
      }

      let delay = isDeleting ? 50 : 100;
      if (!isDeleting && charIndex === currentName.length) {
        delay = 2000;
        isDeleting = true;
      } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        nameIndex = (nameIndex + 1) % names.length;
        delay = 500;
      }
      setTimeout(typeEffect, delay);
    }

    typeEffect();
  }

  // --- 3. Dynamic Projects Rendering ---
  const projectGrid = document.getElementById('project-grid');
  if (projectGrid) {
    const projects = [
      { 
        title: 'Website Profil', 
        desc: 'Website profil pribadi dengan HTML & CSS modern', 
        image: 'images/profil.avif', 
        url: '../profil/' 
      },
      { 
        title: 'Website Donasi', 
        desc: 'Sistem manajemen CRUD donatur dengan PHP dan MySQL', 
        image: 'images/donasi.avif', 
        url: '../donasi sederhana/' 
      },
      { 
        title: 'Kalkulator Sederhana', 
        desc: 'Kalkulator multi-fitur interaktif dengan JavaScript', 
        image: 'images/kalkulator.avif', 
        url: '../kalkulator/' 
      },
      { 
        title: 'Form Interaktif', 
        desc: 'Form kontak dan validasi data interaktif', 
        image: 'images/form.avif', 
        url: 'contact.html' 
      }
    ];

    projects.forEach(project => {
      const card = document.createElement('article');
      card.className = 'project-card';
      card.innerHTML = `
        <a href="${project.url}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
          <img src="${project.image}" alt="${project.title}" loading="lazy">
          <h3>${project.title}</h3>
          <p>${project.desc}</p>
        </a>
      `;
      projectGrid.appendChild(card);
    });
  }

  // --- 4. Interactive Bento Grid Controller (Drag, Move & Corner Resize) ---
  const bentoPlayground = document.getElementById('bento-playground');
  if (bentoPlayground) {
    const cards = bentoPlayground.querySelectorAll('.bento-card');
    let highestZ = 20;

    cards.forEach((card) => {
      card._transX = 0;
      card._transY = 0;

      const resizeHandle = card.querySelector('.bento-resize-handle');

      let isDragging = false;
      let isResizing = false;
      let startX = 0;
      let startY = 0;
      let startW = 0;
      let startH = 0;
      let origTransX = 0;
      let origTransY = 0;

      function bringToFront() {
        highestZ += 1;
        card.style.zIndex = highestZ;
      }

      // 1. RESIZE CONTROLLER (Corner handle)
      if (resizeHandle) {
        function onResizeStart(e) {
          e.preventDefault();
          e.stopPropagation();
          isResizing = true;
          bringToFront();

          const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
          const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

          startX = clientX;
          startY = clientY;
          startW = card.offsetWidth;
          startH = card.offsetHeight;

          document.body.style.cursor = 'se-resize';
          document.body.style.userSelect = 'none';

          window.addEventListener('mousemove', onResizeMove, { passive: false });
          window.addEventListener('touchmove', onResizeMove, { passive: false });
          window.addEventListener('mouseup', onResizeEnd);
          window.addEventListener('touchend', onResizeEnd);
        }

        function onResizeMove(e) {
          if (!isResizing) return;
          e.preventDefault();

          const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
          const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

          const dx = clientX - startX;
          const dy = clientY - startY;

          const newW = Math.max(160, startW + dx);
          const newH = Math.max(140, startH + dy);

          card.style.width = `${newW}px`;
          card.style.height = `${newH}px`;
        }

        function onResizeEnd() {
          isResizing = false;
          document.body.style.cursor = '';
          document.body.style.userSelect = '';
          window.removeEventListener('mousemove', onResizeMove);
          window.removeEventListener('touchmove', onResizeMove);
          window.removeEventListener('mouseup', onResizeEnd);
          window.removeEventListener('touchend', onResizeEnd);
        }

        resizeHandle.addEventListener('mousedown', onResizeStart);
        resizeHandle.addEventListener('touchstart', onResizeStart, { passive: false });
      }

      // 2. DRAG & MOVE CONTROLLER (Whole card)
      function onDragStart(e) {
        if (e.target.closest('.bento-resize-handle')) return;

        isDragging = true;
        bringToFront();
        card.classList.add('is-dragging');

        const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
        const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

        startX = clientX;
        startY = clientY;
        origTransX = card._transX;
        origTransY = card._transY;

        document.body.style.cursor = 'grabbing';
        document.body.style.userSelect = 'none';

        window.addEventListener('mousemove', onDragMove, { passive: false });
        window.addEventListener('touchmove', onDragMove, { passive: false });
        window.addEventListener('mouseup', onDragEnd);
        window.addEventListener('touchend', onDragEnd);
      }

      function onDragMove(e) {
        if (!isDragging) return;
        e.preventDefault();

        const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
        const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

        const dx = clientX - startX;
        const dy = clientY - startY;

        card._transX = origTransX + dx;
        card._transY = origTransY + dy;

        card.style.transform = `translate3d(${card._transX}px, ${card._transY}px, 0)`;
      }

      function onDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        card.classList.remove('is-dragging');
        document.body.style.cursor = '';
        document.body.style.userSelect = '';

        window.removeEventListener('mousemove', onDragMove);
        window.removeEventListener('touchmove', onDragMove);
        window.removeEventListener('mouseup', onDragEnd);
        window.removeEventListener('touchend', onDragEnd);
      }

      card.addEventListener('mousedown', onDragStart);
      card.addEventListener('touchstart', onDragStart, { passive: true });
    });

    // 3. Reset Button (Tata Ulang Grid ke posisi awal)
    const resetBtn = document.getElementById('bento-reset-btn');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        cards.forEach((card) => {
          card.style.transition = 'transform 0.45s cubic-bezier(0.2, 0.8, 0.2, 1), width 0.35s ease, height 0.35s ease';
          card._transX = 0;
          card._transY = 0;
          card.style.transform = 'translate3d(0, 0, 0)';
          card.style.width = '';
          card.style.height = '';
          setTimeout(() => {
            card.style.transition = '';
          }, 460);
        });
      });
    }
  }
});
