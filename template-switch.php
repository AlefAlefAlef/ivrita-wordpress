<?php
$male_label = $modes['male']['name'];
$female_label = $modes['female']['name'];
$neutral_label = $modes['neutral']['name'];
?>
<div class="ivrita-switch <?php
if ( 'right' === $position ) {
  echo 'ivrita-switch--right';
}
?>">
  <a href="#" class="ivrita-logo" title="<?php echo esc_attr( 'Ivrita', 'ivrita' ); ?>">&#x26A5;&#xFE0E;</a>

  <?php
  foreach ( $modes as $mode_key => $mode ) {
    if ( $mode['disabled'] ) {
      continue;
    }
    ?>
    <a href="#" class="ivrita-mode-changer ivrita-button ivrita-button-<?php echo $menu_style; ?>" data-ivrita-mode="<?php echo esc_attr( strtoupper( $mode_key ) ); ?>" title="<?php echo esc_attr( $mode['name'] ); ?>"><?php echo esc_html( $mode['icon'] ); ?></a>
  <?php } ?>

  <a href="https://alefalefalef.co.il/ivrita/" class="ivrita-info-link" title="<?php echo esc_attr_e( 'About the Ivrita project', 'ivrita' ); ?>" target="_blank">ⓘ</a>
</div>