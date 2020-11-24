<?php
$male_label = $modes['labels']['male'];
$female_label = $modes['labels']['female'];
$neutral_label = $modes['labels']['neutral'];
?>
<div class="ivrita-toolbar" id="ivrita-toolbar-<?php echo intval($toolbar_id); ?>">
  <div class="ivrita-toolbar-menu">
    <div class="ivrita-toolbar-label"><?php _e( 'Reference tongue:', 'ivrita' ); ?></div>
    <?php
    $options = array(
      'MALE' => $male_label,
      'FEMALE' => $female_label,
      'NEUTRAL' => $neutral_label,
    );
    $menu_style_icon = ['♂', '♀', '⚥'];
    
    foreach ( $options as $key => $label ) {
      ?>
      <a href="#" class="ivrita-mode-changer" data-ivrita-mode="<?php echo esc_attr( $key ); ?>" data-ivrita-icon="<?php echo $menu_style_icon[$key]; ?>">
        <?php echo esc_html( $label ); ?>
      </a>
      <?php
    }
    ?>
  </div>
  <a class="ivrita-toolbar-info" href="<?php echo esc_attr( $info_link ); ?>" target="_blank" title="<?php echo esc_attr( 'About the Ivrita project', 'ivrita' ); ?>" data-ivrita-icon="ⓘ"><?php _e( 'Info', 'ivrita' ); ?></a>
</div>