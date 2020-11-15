(function(fn) {
  if (document.readyState != "loading"){
    fn();
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
})(function() {
  if (!window._disableIvrita) {
    window._ivrita = new Ivrita();

    var mode;
    if (mode = window.localStorage.getItem('ivrita-mode')) {
      window._ivrita.setMode(Ivrita[mode]);
    }

    Array.prototype.forEach.call(document.querySelectorAll('.ivrita-button'), function(el) {
      var btnMode = el.dataset.ivritaMode;
      if (btnMode && Ivrita.GENDERS.includes(Ivrita[btnMode])) {
        el.addEventListener('click', function(e) {
          e.preventDefault();
          window._ivrita.setMode(Ivrita[btnMode]);
          window.localStorage.setItem('ivrita-mode', btnMode);
        })
      }
    });
  }
});