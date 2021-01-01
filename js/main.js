(function(fn) {
  var fakeFn = function() {
    if (window.jQuery) {
      window.jQuery(fn);
    } else {
      fn();
    }
  }
  if (document.readyState != "loading"){
    fakeFn();
  } else {
    document.addEventListener("DOMContentLoaded", fakeFn);
  }
})(function() {
  if (window._disableIvrita) {
    return;
  }
  
  var buttonsSelector = '.ivrita-mode-changer';

  window._ivrita = new Ivrita();

  // Page title
  if (typeof _ivrita_titles === 'object' && Ivrita.textObjects) {
    var titleTextNode = document.documentElement.getElementsByTagName('title')[0].childNodes[0];
    var titleIvritaNode = Ivrita.textObjects.get(titleTextNode);
    var currentTitle = titleTextNode.data;
    
    if (titleIvritaNode) {
      if (_ivrita_titles.male) {
        titleIvritaNode.storedValues[Ivrita.MALE] = currentTitle.replace(_ivrita_titles.current, _ivrita_titles.male);
      }
      if (_ivrita_titles.female) {
        titleIvritaNode.storedValues[Ivrita.FEMALE] = currentTitle.replace(_ivrita_titles.current, _ivrita_titles.female);
      }
      if (_ivrita_titles.neutral) {
        titleIvritaNode.storedValues[Ivrita.NEUTRAL] = currentTitle.replace(_ivrita_titles.current, _ivrita_titles.neutral);
      }
    }
  }

  function setMode(newMode) {
    document.querySelectorAll(buttonsSelector).forEach(function(btn) {
      if (btn.dataset.ivritaMode === newMode) {
        btn.classList.add('ivrita-active');
      } else {
        btn.classList.remove('ivrita-active');
      }
    });
    window._ivrita.setMode(Ivrita[newMode]);
    window.localStorage.setItem('ivrita-mode', newMode);
  }


  var mode;
  if (mode = window.localStorage.getItem('ivrita-mode')) {
    setMode(mode);
  }

  function modeChangerClicked(e) {
    var btnMode = this.dataset.ivritaMode;
    if (!btnMode || !Ivrita.GENDERS.includes(Ivrita[btnMode])) {
      return;
    }

    e.preventDefault();
    setMode(btnMode);
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