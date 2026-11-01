(() => {
  'use strict';

  const root = document.documentElement;
  const themeSelect = document.getElementById('themeSelect');
  const accentSelect = document.getElementById('accentSelect');
  const menuToggle = document.getElementById('menuToggle');
  const nav = document.getElementById('mainNav');

  const validThemes = ['dark', 'light', 'midnight'];
  const validAccents = ['violet', 'blue', 'emerald', 'rose'];

  function readPreference(key, allowed, fallback) {
    try {
      const value = localStorage.getItem(key);
      return allowed.includes(value) ? value : fallback;
    } catch {
      return fallback;
    }
  }

  function applyTheme(theme) {
    if (!validThemes.includes(theme)) theme = 'dark';
    root.dataset.theme = theme;
    if (themeSelect) themeSelect.value = theme;
    try { localStorage.setItem('rodny-theme', theme); } catch {}
  }

  function applyAccent(accent) {
    if (!validAccents.includes(accent)) accent = 'violet';
    root.dataset.accent = accent;
    if (accentSelect) accentSelect.value = accent;
    try { localStorage.setItem('rodny-accent', accent); } catch {}
  }

  applyTheme(readPreference('rodny-theme', validThemes, 'dark'));
  applyAccent(readPreference('rodny-accent', validAccents, 'violet'));

  themeSelect?.addEventListener('change', () => applyTheme(themeSelect.value));
  accentSelect?.addEventListener('change', () => applyAccent(accentSelect.value));

  menuToggle?.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    menuToggle.setAttribute('aria-expanded', String(isOpen));
  });

  nav?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      nav.classList.remove('open');
      menuToggle?.setAttribute('aria-expanded', 'false');
    });
  });
})();
