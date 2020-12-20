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
      'MALE' => array(
        'label' => $male_label,
        'icon' => '♂',
      ),
      'FEMALE' => array(
        'label' => $female_label,
        'icon' => '♀',
      ),
      'NEUTRAL' => array(
        'label' => $neutral_label,
        'icon' => '⚥',
      ),
    );
    
    foreach ( $options as $key => $gender ) {
      ?>
      <a href="#" class="ivrita-mode-changer" data-ivrita-mode="<?php echo esc_attr( $key ); ?>" data-ivrita-icon="<?php echo $gender['icon']; ?>">
        <?php echo esc_html( $gender['label'] ); ?>
      </a>
      <?php
    }
    ?>
  </div>
  <a class="ivrita-toolbar-info" href="<?php echo esc_attr( $info_link ); ?>" target="_blank" title="<?php echo esc_attr( 'About the Ivrita project', 'ivrita' ); ?>" data-ivrita-icon="ⓘ"><?php _e( 'Info', 'ivrita' ); ?></a>
</div>