(function(fn) {
  if (document.readyState != "loading"){
    fn();
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
})(function() {
  if (window._disableIvrita) {
    return;
  }

  window._ivrita = new Ivrita();

  var mode;
  if (mode = window.localStorage.getItem('ivrita-mode')) {
    window._ivrita.setMode(Ivrita[mode]);
  }

  var buttonsSelector = '.ivrita-mode-changer';

  function modeChangerClicked(e) {
    var btnMode = this.dataset.ivritaMode;
    if (!btnMode || !Ivrita.GENDERS.includes(Ivrita[btnMode])) {
      return;
    }

    e.preventDefault();
    document.querySelectorAll(buttonsSelector).forEach(function(btn){
      if (btn.dataset.ivritaMode === btnMode) {
        btn.classList.add('ivrita-active');
      } else {
        btn.classList.remove('ivrita-active');
      }
    });
    window._ivrita.setMode(Ivrita[btnMode]);
    window.localStorage.setItem('ivrita-mode', btnMode);
  }

  document.addEventListener('click', function(e) {
    // loop parent nodes from the target to the delegation node
    for (var target = e.target; target && target != this; target = target.parentNode) {
      if (target.matches(buttonsSelector) && target.dataset.ivritaMode) {
        modeChangerClicked.call(target, e);
        break;
      }
    }
  }, false);
});