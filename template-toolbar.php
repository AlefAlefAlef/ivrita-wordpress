<div class="ivrita-toolbar" id="ivrita-toolbar-<?php echo intval($toolbar_id); ?>">
  <div class="ivrita-toolbar-menu">
    <div class="ivrita-toolbar-label"><?php _e( 'Reference tongue:', 'ivrita' ); ?></div>
    <?php
    $options = array(
      'MALE' => 'זכר', //$male_label,
      'FEMALE' => 'אישה', //$female_label,
      'NEUTRAL' => 'ניטראלי', //$neutral_label,
    );
    foreach ( $options as $key => $label ) {
      ?>
      <a href="#" class="ivrita-mode-changer" data-ivrita-mode="<?php echo esc_attr( $key ); ?>" data-ivrita-icon="<?php echo $menu_style_icon[$key]; ?>">
        <?php echo esc_html( $label ); ?>
      </a>
      <?php
    }
    ?>
  </div>
  <a class="ivrita-toolbar-info" href="<?php echo esc_attr( $info_link ); ?>" target="_blank" title="<?php echo esc_attr( 'About the Ivrita project', 'ivrita' ); ?>"><?php _e( 'Info', 'ivrita' ); ?> <i class="icon" data-icon="."></i></a>
</div>