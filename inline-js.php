(function(fn) {
  if (document.readyState != "loading"){
    fn();
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
})(function() {
  if (!window._disableIvrita) {
    window._ivrita = new Ivrita();

    Array.prototype.forEach.call(document.querySelectorAll('.ivrita-button'), function(el) {
      var btnMode = el.dataset.ivritaMode;
      if (btnMode && Ivrita.GENDERS.includes(Ivrita[btnMode])) {
        el.addEventListener('click', function(e) {
          e.preventDefault();
          window._ivrita.setMode(Ivrita[btnMode]);
        })
      }
    });
  }
});