<?php
switch ($menu_style) {
  case 'style-3':
    $icon = ['ז', 'נ', 'א'];
    break;

  case 'style-4':
    $icon = ['M', 'F', 'X'];
    break;
  
  default:
    $icon = ['♂', '♀', '⚥'];
    break;
}
$icon
?>
<div class="ivrita-switch <?php
if ( 'right' === $position ) {
  echo 'ivrita-switch--right';
}
?>">
  <a href="#" class="ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="MALE" title="<?php echo esc_attr( $male_label ); ?>"><?php echo $icon[0]; ?></a>
  <a href="#" class="ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="FEMALE" title="<?php echo esc_attr( $female_label ); ?>"><?php echo $icon[1]; ?></a>
  <a href="#" class="ivrita-button ivrita-button-<?php echo $menu_style; ?> ivrita-active" data-ivrita-mode="NEUTRAL" title="<?php echo esc_attr( $neutral_label ); ?>"><?php echo $icon[2]; ?></a>
  <a href="https://alefalefalef.co.il/ivrita/" class="ivrita-info-link" title="<?php echo esc_attr( 'About the Ivrita project', 'ivrita' ); ?>">ℷ</a>
</div>
<style>

  @font-face {
    font-family: 'Ivritacons';
    font-weight: '400';
    src:  url('<?php echo plugin_dir_url( __FILE__ ); ?>fonts/ivritacons-alefalefalef.woff2') format('woff2'),
          url('<?php echo plugin_dir_url( __FILE__ ); ?>fonts/ivritacons-alefalefalef.woff') format('woff');
  }

  .ivrita-switch *{
    margin:0;
    padding:0;
    outline:0;
    font-size:1em;
    font-weight:normal;
    font-style:normal;
    border:0;
    text-decoration:none;
    list-style-type:none;
    min-width: auto;
    min-height: auto;
    max-width: none;
    max-height: none;
    -webkit-text-stroke: none;
    -moz-text-stroke: initial;
    -ms-text-stroke: initial;
    word-spacing: normal;
    text-align: left;
    width: auto;
    height: auto;
    position: static;
    display: inline-block;
    border: 0;
    float: none;
    background: none;
    border-radius: 0;
    box-shadow: none;
    direction: ltr;
    visibility: visible;
    opacity: 1;
    text-shadow: none;
    outline: 0;
    vertical-align: unset;
    white-space: normal;
    letter-spacing: 0;
  }

  .ivrita-switch *, .ivrita-switch *:before, .ivrita-switch *:after {  
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
  }

  .ivrita-switch {
    z-index: 999999;
    background-color: rgba(255, 255, 255, 0.8);
    position: fixed;
    top: calc(50% - 1em);
    left: -1px;
    border-radius: 0 7px 7px 0;
    transition: .2s all ease-out 0.2s;
    border: 1px solid #222;
    font-size: 2.7em;
    min-width: 1.2em;
}
  
  .ivrita-switch:hover {
    padding: 0.27em 0em 0em;
    top: calc(50% - 2.2em);
    transition-delay: 0s;
}

  .ivrita-switch.ivrita-switch--right {
    left: auto;
    right: -1px;
    border-radius: 7px 0 0 7px;
  }

  .ivrita-switch.ivrita-switch--right:hover {
  }

  .ivrita-switch a {
    font-family: 'Ivritacons' !important;
    font-weight: normal !important;
    font-style: normal !important;
    transition: .1s all ease-out 0.2s;
  }

  .ivrita-switch a.ivrita-button {
    display: block;
    color: #2d2828;
    line-height: 1em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    width: 1.1em;
    height: 1.1em;
    line-height: 1.1em;
    border-radius: 50%;
    margin: 0 0.12em;
    visibility: hidden;
    font-size: 0;
  }
  .ivrita-switch:hover a.ivrita-button{
    visibility: visible;
    font-size: 1em;
        transition-delay: 0s; 
  }

  .ivrita-switch a.ivrita-button.ivrita-button-style-1.ivrita-active { /* Ivrita default */
    -webkit-font-feature-settings: "ss01";
    font-feature-settings: "ss01";
  }

  .ivrita-switch a.ivrita-button.ivrita-button-style-2 { /* Venus & Mars*/
    -webkit-font-feature-settings: "ss02";
    font-feature-settings: "ss02";    
  }
  .ivrita-switch a.ivrita-button.ivrita-button-style-2.ivrita-active,
  .ivrita-switch a.ivrita-button.ivrita-button-style-3.ivrita-active,
  .ivrita-switch a.ivrita-button.ivrita-button-style-4.ivrita-active {
    background: #222;
    color: #fff;
  }

  .ivrita-switch a.ivrita-button.ivrita-button-style-3 { /* Hebrew M.F.X */
  }
  .ivrita-switch a.ivrita-button.ivrita-button-style-4 { /* M.F.X */
  }
  .ivrita-switch a.ivrita-button:hover {
    color: #6306ec;
  }
  .ivrita-switch a.ivrita-info-link {
    font-size: 1.3em;
    display: block;
    text-align: center;
    transition: .3s all ease-out;
  }
  .ivrita-switch a.ivrita-info-link:hover {
    background-color: #6306ec;
    color: #fff;
    padding: 0 0.1em;
  }
  
  .ivrita-switch:hover a.ivrita-info-link {
    padding: 0.05em 0;
    font-size: 0.7em;
    border-top: 1px solid #222;
    margin-top: 0.4em;
  } 
</style>