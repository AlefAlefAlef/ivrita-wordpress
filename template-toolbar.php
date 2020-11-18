<div class="ivrita-toolbar" id="ivrita-toolbar-<?php echo intval($toolbar_id); ?>">
  <strong>לשון הפניה:</strong>
  <ul>
    <?php
    $options = array(
      'MALE' => 'זכר',
      'FEMALE' => 'נקבה',
      'NEUTRAL' => 'ניטרלי',
    );
    foreach ( $options as $key => $label ) {
      ?>
      <li>
        <a href="#" class="ivrita-mode-changer" data-ivrita-mode="<?php echo esc_attr( $key ); ?>">
          <?php echo esc_html( $label ); ?>
        </a>
      </li>
      <?php
    }
    ?>
  </ul>
  <a class="credit" href="<?php echo esc_attr( $info_link ); ?>" target="_blank">מידע <i class="icon" data-icon="."></i></a>
</div>