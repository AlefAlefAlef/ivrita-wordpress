var modesMap = {
  'male': Ivrita.MALE,
  'female': Ivrita.FEMALE,
  'neutral': Ivrita.NEUTRAL,
  'multi': Ivrita.MULTI,
};

if (!window._disableIvrita) {
  
  document.addEventListener('ivrita-ui-ready', function() {
    // Default switch config
    if (typeof _ivrita_switch_config === 'object') {
      if (_ivrita_switch_config.position) {
        Ivrita.ui.config.position = _ivrita_switch_config.position;
      }

      if (_ivrita_switch_config.style) {
        Ivrita.ui.config.style = _ivrita_switch_config.style.replace('style-', '');
      }
      
      if (typeof _ivrita_switch_config.modes === 'object') {
        var switchModes = {};
        var defaultMode = null;

        for (var m in _ivrita_switch_config.modes) {
          var modeConf = _ivrita_switch_config.modes[m];
          if (modeConf.disabled) { // Skip if disabled
            continue;
          }

          if (modeConf.default_mode) { // Save as default
            defaultMode = modesMap[m];
          }

          switchModes[modesMap[m]] = { // Add to modes config
            icon: modeConf.icon,
            label: modeConf.name,
            order: modeConf.order
          };
        }

        Ivrita.ui.config.modes = switchModes;

        if (defaultMode) {
          Ivrita.ui.config.default = defaultMode;
        }
      }
    }

    // Page <title>
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
  
    Array.prototype.forEach.call(document.querySelectorAll('.ivrita-toolbar'), function(el, i){
      new Ivrita.ui.custom(el, '.ivrita-mode-changer');
    });
  
  });
}