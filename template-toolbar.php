<div class="ivrita-toolbar" id="ivrita-toolbar-<?php echo intval($toolbar_id); ?>">
  <div class="ivrita-toolbar-menu">
    <div class="ivrita-toolbar-label"><?php _e( 'Reference tongue:', 'ivrita' ); ?></div>
    <?php

    foreach ( $modes as $mode_key => $mode ) {
      if ( $mode['disabled'] ) {
        continue;
      }
      ?>
      <a href="#" class="ivrita-mode-changer" data-ivrita-mode="<?php echo esc_attr( strtoupper( $mode_key ) ); ?>" data-ivrita-icon="<?php echo esc_attr( $mode['icon'] ); ?>">
        <?php echo esc_html( $mode['name'] ); ?>
      </a>
      <?php
    }
    ?>
  </div>
  <a class="ivrita-toolbar-info" href="<?php echo esc_attr( $info_link ); ?>" target="_blank" title="<?php echo esc_attr( 'About the Ivrita project', 'ivrita' ); ?>" data-ivrita-icon="ⓘ"> <?php _e( 'Info', 'ivrita' ); ?></a>
</div>