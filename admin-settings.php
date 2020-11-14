<?php


class IvritaAdmin {
  function __construct() {
    add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
    add_action( 'admin_init', array( $this, 'setup_sections' ) );
    add_action( 'admin_init', array( $this, 'setup_fields' ) );
  }
  
  public function create_settings_page() {
    $page_title = __( 'Ivrita Settings', 'ivrita' );
    $menu_title = _x( 'Ivrita', 'menu title', 'ivrita' );
    $capability = 'manage_options';
    $slug = 'ivrita';
    $callback = array( $this, 'settings_page_content' );
    $icon = 'dashicons-admin-plugins';
    $position = 70;
    
    add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
  }
  
  public function settings_page_content() {
    ?>
    <div class="wrap">
      <h2><?php _e( 'Ivrita Settings', 'ivrita' ); ?></h2>
      <form method="post" action="options.php">
        <?php
        settings_fields( 'ivrita' );
        do_settings_sections( 'ivrita' );
        submit_button();
        ?>
      </form>
    </div>
    <?php
  }
  
  public function setup_sections() {
    add_settings_section( 'global_settings', __( 'Global Settings', 'ivrita' ), array( $this, 'global_settings_section' ), 'ivrita' );
  }
  
  public function setup_fields() {
    $fields = array(
      array(
        'id' => 'enable_global',
        'label' => __( 'Global Enable', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'checkbox',
        'options' => false,
        'placeholder' => false,
        'helper' => __( 'Enable everywhere in the website', 'ivrita' ),
        'supplemental' => __( 'You can turn this off/on for each post individually.', 'ivrita' ),
        'default' => true
      ),
      array(
        'id' => 'switch_position',
        'label' => __( 'Switch Position', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'radio',
        'options' => [
          'right' => __( 'Right', 'ivrita' ),
          'left' => __( 'Left', 'ivrita' ),
        ],
        'placeholder' => false,
        'helper' => '',
        'supplemental' => __( 'The location of the floating switch on the entire website', 'ivrita' ),
        'default' => 'left'
      ),
    );
    foreach( $fields as $field ){
      $field['uid'] = 'ivrita_' . $field['id'];
      add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'ivrita', $field['section'], $field );
      register_setting( 'ivrita', $field['uid'] );
    }
  }

  public function field_callback( $arguments ) {
    $value = get_option( $arguments['uid'] );
    if ( $value === null ) {
      $value = $arguments['default'];
    }

    switch ( $arguments['type'] ){
    case 'text':
      printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
      break;
    case 'textarea':
      printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
      break;
    case 'checkbox':
      printf( '<input name="%1$s" id="%1$s" type="%2$s" %3$s />', $arguments['uid'], $arguments['type'], checked( $value, 'on', false ) );
      break;
    case 'select':
      if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
          $options_markup = '';
          foreach( $arguments['options'] as $key => $label ){
              $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
          }
          printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
      }
      break;
    case 'radio':
      if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
          $options_markup = '';
          foreach( $arguments['options'] as $key => $label ){
              $options_markup .= sprintf( '<label><input type="radio" name="%s" value="%s" %s />%s</label>', $arguments['uid'], $key, checked( $value, $key, false ), $label );
          }
          echo $options_markup;
      }
      break;
    }
    
    
    if ( $helper = $arguments['helper'] ){
      printf( '<span class="helper">%s</span>', $helper );
    }
    
    if ( $supplimental = $arguments['supplemental'] ){
      printf( '<p class="description">%s</p>', $supplimental );
    }
  }
  
  public function global_settings_section() {
    echo '';
  }

  public function get_field( $key, $default = false ) {
    return get_option('ivrita_' . $key, $default);
  }
}