<?php
$male_label = $modes['labels']['male'];
$female_label = $modes['labels']['female'];
$neutral_label = $modes['labels']['neutral'];
?>
<div class="ivrita-switch <?php
if ( 'right' === $position ) {
  echo 'ivrita-switch--right';
}
?>">
  <a href="#" class="ivrita-logo" title="<?php echo esc_attr( 'Ivrita', 'ivrita' ); ?>">⚥</a>
  <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="MALE" title="<?php echo esc_attr( $male_label ); ?>">♂</a>
  <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="FEMALE" title="<?php echo esc_attr( $female_label ); ?>">♀</a>
  <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?> ivrita-active" data-ivrita-mode="NEUTRAL" title="<?php echo esc_attr( $neutral_label ); ?>">⚥</a>
  <a href="https://alefalefalef.co.il/ivrita/" class="ivrita-info-link" title="<?php echo esc_attr_e( 'About the Ivrita project', 'ivrita' ); ?>">ⓘ</a>
</div>