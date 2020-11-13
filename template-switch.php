<div class="ivrita-switch">
  <button class="ivrita-button" data-ivrita-mode="MALE" href="#" data-icon="♂" title="">ז</button>
  <button class="ivrita-button" data-ivrita-mode="FEMALE" href="#" data-icon="♀" title="לשון אישה">נ</button>
  <button class="ivrita-button ivrita-active" data-ivrita-mode="NEUTRAL" href="#" data-icon="⚥" title="א־בינארי">א</button>
</div>
<style>
  .ivrita-switch {
    background-color: rgba(255, 255, 255, 0.9);
    /* height: 14em; */
    width: 3em;
    position: fixed;
    top: calc(50% - 10em);
    left: -2em;
    border-radius: 0 1em 1em 0;
    transition: .3s left ease-out;
  }

  .ivrita-switch:hover {
    left: 0;
  }

  .ivrita-switch .ivrita-button {
    display: block;
    width: 100%;
    font-size: 2.5em;
    padding: .2em 0.3em;
    background: none;
    border: 0;
  }
  .ivrita-switch .ivrita-button:hover, .ivrita-switch .ivrita-button.ivrita-active {
    background-color: rgba(0, 0, 0, 0.1);
  }
</style>