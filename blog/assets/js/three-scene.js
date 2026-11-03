// Extreme lazy loading - limport { gsap } from './gsap.min.js';oads NOTHING until user interacts
(function () {
  'use strict';

  let isLoaded = false;

  function loadScript(src) {
    return new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = src;
      script.async = true;
      script.onload = resolve;
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }

  async function loadAndStart() {
    if (isLoaded) return;
    isLoaded = true;

    // Show loading indicator
    const container = document.getElementById("container");
    if (!container) return;

    try {
      await loadScript('https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js');
      await loadScript('https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/SVGLoader.js');
      startAnimation();
    } catch (e) {
      console.warn('Failed to load Three.js:', e);
    }
  }

  function startAnimation() {
    if (typeof THREE === 'undefined' || typeof gsap === 'undefined') return;

    let scene, camera, renderer, particles, mouse = { x: 0, y: 0 }, smokeCloud = [];
    let leftCornerLight, rightCornerLight, currentState = "sphere";

    window.addEventListener("mousemove", (e) => {
      mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
      mouse.y = (e.clientY / window.innerHeight) * 2 - 1;
    }, { passive: true });

    function init() {
      const container = document.getElementById("container");
      if (!container) return;

      scene = new THREE.Scene();
      camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1e3);
      renderer = new THREE.WebGLRenderer({ antialias: true, powerPreference: "high-performance" });
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
      renderer.setSize(window.innerWidth, window.innerHeight);
      renderer.setClearColor(0);
      container.appendChild(renderer.domElement);
      camera.position.z = 25;

      createParticles();
      addCornerLights();
      createSmoke();
      addGrids();
      setupCycle();
      animate();
    }

    function addGrids() {
      scene.add(createRadialFadeLineGrid({
        y: -15, size: 100, divisions: 25, innerRadius: 8, outerRadius: 40,
        color: new THREE.Color(15887973), opacity: 0.2
      }));
      scene.add(createRadialFadeLineGrid({
        y: 25, size: 150, divisions: 30, innerRadius: 8, outerRadius: 40,
        color: new THREE.Color(15887973), opacity: 0.2
      }));
    }

    function createRadialFadeLineGrid({ y = 0, size = 100, divisions = 40, innerRadius = 30, outerRadius = 50, color = new THREE.Color(16777215), opacity = 0.3 }) {
      const gridGroup = new THREE.Group();
      const material = new THREE.ShaderMaterial({
        vertexShader: `
          varying vec3 vWorldPos;
          void main() {
            vec4 worldPosition = modelMatrix * vec4(position, 1.0);
            vWorldPos = worldPosition.xyz;
            gl_Position = projectionMatrix * viewMatrix * worldPosition;
          }
        `,
        fragmentShader: `
          varying vec3 vWorldPos;
          uniform float innerRadius;
          uniform float outerRadius;
          uniform vec3 gridColor;
          uniform float opacity;
          void main() {
            float dist = length(vWorldPos.xz);
            float alpha = smoothstep(innerRadius, outerRadius, dist);
            if (alpha < 0.01) discard;
            gl_FragColor = vec4(gridColor, alpha * opacity);
          }
        `,
        uniforms: {
          innerRadius: { value: innerRadius },
          outerRadius: { value: outerRadius },
          gridColor: { value: color },
          opacity: { value: opacity }
        },
        transparent: true,
        depthTest: false
      });

      const step = size / divisions, halfSize = size / 2, middleIndex = Math.floor(divisions / 2);

      for (let i = 0; i <= divisions; i++) {
        if (i === middleIndex) continue;
        const offset = i * step - halfSize;
        gridGroup.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints([
          new THREE.Vector3(offset, y, -halfSize), new THREE.Vector3(offset, y, halfSize)
        ]), material));
        gridGroup.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints([
          new THREE.Vector3(-halfSize, y, offset), new THREE.Vector3(halfSize, y, offset)
        ]), material));
      }
      return gridGroup;
    }

    function createParticles() {
      const geometry = new THREE.BufferGeometry();
      const positions = new Float32Array(3e4), colors = new Float32Array(3e4);

      const getSpherePoint = (i) => {
        const theta = Math.acos((2 * i) / 1e4 - 1), phi = Math.sqrt(1e4 * Math.PI) * theta;
        return { x: 8 * Math.cos(phi) * Math.sin(theta), y: 8 * Math.sin(phi) * Math.sin(theta), z: 8 * Math.cos(theta) };
      };

      for (let i = 0; i < 1e4; i++) {
        const p = getSpherePoint(i);
        positions[3 * i] = p.x + 0.5 * (Math.random() - 0.5);
        positions[3 * i + 1] = p.y + 0.5 * (Math.random() - 0.5);
        positions[3 * i + 2] = p.z + 0.5 * (Math.random() - 0.5);
        const c = new THREE.Color(), d = Math.sqrt(p.x ** 2 + p.y ** 2 + p.z ** 2) / 8;
        c.setHSL(0.01, 0.84, Math.max(0, 0.67 - 0.1 * d));
        colors[3 * i] = c.r;
        colors[3 * i + 1] = c.g;
        colors[3 * i + 2] = c.b;
      }

      geometry.setAttribute("position", new THREE.BufferAttribute(positions, 3));
      geometry.setAttribute("color", new THREE.BufferAttribute(colors, 3));
      particles = new THREE.Points(geometry, new THREE.PointsMaterial({
        size: 0.08, vertexColors: true, blending: THREE.AdditiveBlending,
        transparent: true, opacity: 0.8, sizeAttenuation: true
      }));
      scene.add(particles);
    }

    function setupCycle() {
      const svgs = [
        "/assets/img/animate-wordpress-icon.svg", "/assets/img/animate-webflow-icon.svg",
        "/assets/img/animate-laravel-icon.svg", "/assets/img/animate-shopify-icon.svg",
        "/assets/img/animate-react-icon.svg", "/assets/img/animate-go-high-level-icon.svg",
        "/assets/img/animate-hubspot-icon.svg", "/assets/img/animate-ai-ml-icon.svg",
        "/assets/img/animate-gen-ai-icon.svg"
      ];
      let idx = 0;
      (function cycle() {
        loadSVGPoints(svgs[idx], (pts) => {
          morphToShape(pts, () => morphToSphere(() => setTimeout(cycle, 1e3)));
        });
        idx = (idx + 1) % svgs.length;
      })();
    }

    function loadSVGPoints(path, cb) {
      new THREE.SVGLoader().load(path, (data) => {
        const pts = [], total = data.paths.reduce((s, p) => s + p.subPaths.length, 0);
        const perSub = Math.floor(1e4 / total);
        data.paths.forEach((p) => {
          p.subPaths.forEach((sp) => {
            sp.getSpacedPoints(perSub).forEach((pt) => pts.push({ x: pt.x, y: pt.y }));
          });
        });
        cb(normalizePoints(pts));
      }, undefined, (e) => console.warn("SVG Error:", e.message));
    }

    function normalizePoints(pts) {
      let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
      pts.forEach((p) => {
        if (p.x < minX) minX = p.x;
        if (p.x > maxX) maxX = p.x;
        if (p.y < minY) minY = p.y;
        if (p.y > maxY) maxY = p.y;
      });
      const w = maxX - minX, h = maxY - minY, s = 12 / Math.max(w, h);
      return pts.map((p) => ({ x: (p.x - minX - w / 2) * s, y: -(p.y - minY - h / 2) * s }));
    }

    function morphToShape(tgt, cb) {
      currentState = "shape";
      gsap.to(particles.rotation, { x: 0, y: 0, z: 0, duration: 1 });
      const pos = particles.geometry.attributes.position.array;
      const start = new Float32Array(pos), end = new Float32Array(3e4);

      for (let i = 0; i < 1e4; i++) {
        if (i < tgt.length) {
          end[3 * i] = tgt[i].x;
          end[3 * i + 1] = tgt[i].y;
          end[3 * i + 2] = 0;
        } else {
          const a = Math.random() * Math.PI * 2, r = 20 * Math.random() + 10;
          end[3 * i] = Math.cos(a) * r;
          end[3 * i + 1] = Math.sin(a) * r;
          end[3 * i + 2] = 10 * (Math.random() - 0.5);
        }
      }

      let prg = { t: 0 };
      gsap.to(prg, {
        t: 1, duration: 2, ease: "power2.inOut",
        onUpdate: () => {
          for (let i = 0; i < pos.length; i++) pos[i] = start[i] + (end[i] - start[i]) * prg.t;
          particles.geometry.attributes.position.needsUpdate = true;
        },
        onComplete: () => { if (cb) cb(); }
      });
    }

    function createSmoke() {
      const mat = new THREE.MeshLambertMaterial({
        color: 16740156, emissive: new THREE.Color(3348992),
        map: new THREE.TextureLoader().load("/assets/img/Smoke-Element.webp"),
        transparent: true, opacity: 0.08, depthWrite: false, side: THREE.DoubleSide
      });
      const geo = new THREE.PlaneGeometry(300, 300);
      for (let i = 0; i < 20; i++) {
        const m = new THREE.Mesh(geo, mat);
        m.position.set(40 * Math.random() - 20, 40 * Math.random() - 20, -10 - 20 * Math.random());
        m.rotation.set(Math.random() * Math.PI, Math.random() * Math.PI, Math.random() * Math.PI);
        m.material.opacity = 0.05 + 0.03 * Math.random();
        scene.add(m);
        smokeCloud.push(m);
      }
    }

    function addCornerLights() {
      const c = new THREE.Color("#ff6600");
      leftCornerLight = new THREE.PointLight(c, 1.8, 100);
      rightCornerLight = new THREE.PointLight(c, 1.8, 100);
      scene.add(leftCornerLight);
      scene.add(rightCornerLight);
    }

    function morphToSphere(cb) {
      currentState = "sphere";
      const pos = particles.geometry.attributes.position.array;
      const col = particles.geometry.attributes.color.array;
      const sPos = new Float32Array(pos), sCol = new Float32Array(col);
      const ePos = new Float32Array(3e4), eCol = new Float32Array(3e4);

      const getSpherePoint = (i) => {
        const theta = Math.acos((2 * i) / 1e4 - 1), phi = Math.sqrt(1e4 * Math.PI) * theta;
        return { x: 8 * Math.cos(phi) * Math.sin(theta), y: 8 * Math.sin(phi) * Math.sin(theta), z: 8 * Math.cos(theta) };
      };

      for (let i = 0; i < 1e4; i++) {
        const p = getSpherePoint(i);
        ePos[3 * i] = p.x + 0.5 * (Math.random() - 0.5);
        ePos[3 * i + 1] = p.y + 0.5 * (Math.random() - 0.5);
        ePos[3 * i + 2] = p.z + 0.5 * (Math.random() - 0.5);
        const d = Math.sqrt(p.x ** 2 + p.y ** 2 + p.z ** 2) / 8, c = new THREE.Color();
        c.setHSL(0.01, 0.84, Math.max(0, 0.67 - 0.1 * d));
        eCol[3 * i] = c.r;
        eCol[3 * i + 1] = c.g;
        eCol[3 * i + 2] = c.b;
      }

      let prg = { t: 0 };
      gsap.to(prg, {
        t: 1, duration: 2, ease: "power2.inOut",
        onUpdate: () => {
          for (let i = 0; i < pos.length; i++) {
            pos[i] = sPos[i] + (ePos[i] - sPos[i]) * prg.t;
            col[i] = sCol[i] + (eCol[i] - sCol[i]) * prg.t;
          }
          particles.geometry.attributes.position.needsUpdate = true;
          particles.geometry.attributes.color.needsUpdate = true;
        },
        onComplete: () => { if (cb) cb(); }
      });
    }

    function screenToWorld(x, y, z) {
      const v = new THREE.Vector3(x, y, z);
      v.unproject(camera);
      return v;
    }

    function animate() {
      const tX = mouse.x * Math.PI * 0.2, tY = mouse.y * Math.PI * 0.1;
      const cX = 25 * Math.sin(tX), cZ = 25 * Math.cos(tX), cY = 25 * tY * 0.5;
      camera.position.x += 0.05 * (cX - camera.position.x);
      camera.position.y += 0.05 * (cY - camera.position.y);
      camera.position.z += 0.05 * (cZ - camera.position.z);
      camera.lookAt(0, 0, 0);
      requestAnimationFrame(animate);
      if (currentState === "sphere") particles.rotation.y += 0.002;
      leftCornerLight.position.copy(screenToWorld(-1, -1, 0));
      rightCornerLight.position.copy(screenToWorld(1, -1, 0));
      smokeCloud.forEach((m, i) => { m.rotation.z += 5e-4 + 1e-5 * i; });
      renderer.render(scene, camera);
    }

    let resizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
      }, 250);
    }, { passive: true });

    init();
  }

  // Only load on user interaction - NOTHING loads automatically
  const events = ['click', 'touchstart','scroll', 'mousemove', 'keydown'];
  let hasLoaded = false;
  
  const handler = () => {
    if (hasLoaded) return;
    hasLoaded = true;
    events.forEach(e => document.removeEventListener(e, handler));
    loadAndStart();
  };
  
  events.forEach(e => document.addEventListener(e, handler, { once: true, passive: true }));
  
})();




/* Chatbot Widget */
const interval = setInterval(() => {
    const el = document.querySelector('#cypherox-chatbot-container .chat-area    ');
  if (el) {
    el.setAttribute('data-lenis-prevent', 'true');
    clearInterval(interval);
  }
}, 300);
(function (w, d) {
  var s = d.createElement("script");
  s.src = "https://chatbot.cypherox.com/assets/cx-chat-widget-v2.js?time=" + Date.now();
  s.defer = true;
  d.body.appendChild(s);
})(window, document);
