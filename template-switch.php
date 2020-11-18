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
  <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="MALE" title="<?php echo esc_attr( $male_label ); ?>"><?php echo $icon[0]; ?></a>
  <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="FEMALE" title="<?php echo esc_attr( $female_label ); ?>"><?php echo $icon[1]; ?></a>
  <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?> ivrita-active" data-ivrita-mode="NEUTRAL" title="<?php echo esc_attr( $neutral_label ); ?>"><?php echo $icon[2]; ?></a>
  <a href="https://alefalefalef.co.il/ivrita/" class="ivrita-info-link" title="<?php echo esc_attr( 'About the Ivrita project', 'ivrita' ); ?>">ℷ</a>
</div>