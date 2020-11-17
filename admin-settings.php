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
      array(
        'id' => 'label_male',
        'label' => __( 'Male Label', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'text',
        'options' => false,
        'placeholder' => __( 'Male', 'ivrita' ),
        'helper' => '',
        'supplemental' => __( 'The text that will be shown in the button title. Leave empty for default.', 'ivrita' ),
        'default' => __( 'Male', 'ivrita' ),
      ),
      array(
        'id' => 'label_female',
        'label' => __( 'Female Label', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'text',
        'options' => false,
        'placeholder' => __( 'Female', 'ivrita' ),
        'helper' => '',
        'supplemental' => '',
        'default' => __( 'Female', 'ivrita' ),
      ),
      array(
        'id' => 'label_neurtal',
        'label' => __( 'Neurtal Label', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'text',
        'options' => false,
        'placeholder' => __( 'Neurtal', 'ivrita' ),
        'helper' => '',
        'supplemental' => '',
        'default' => __( 'Neurtal', 'ivrita' ),
      ),
      array(
        'id' => 'menu_style',
        'label' => __( 'Menu style', 'ivrita' ),
        'section' => 'global_settings',
        'type'    => 'select',
        'options' => [
            'style-1' => esc_html__( 'Ivrita Default', 'ivrita' ),
            'style-2' => esc_html__( 'Venus & Mars', 'ivrita' ),
            'style-3' => esc_html__( 'Hebrew M.F.X', 'ivrita' ),
            'style-4' => esc_html__( 'M.F.X', 'ivrita' ),
        ],
        'helper' => '',
        'supplemental' => '',
        'default'     => 'style-1',
      ),
      array(
        'id' => 'enable_roles',
        'label' => __( 'Enable for roles', 'ivrita' ),
        'section' => 'global_settings',
        'type'    => 'checkbox',
        'options' => array_merge(
            wp_roles()->get_names(),
            array( 'everyone' => __( 'Everyone', 'ivrita' ) )
        ),
        'helper' => '',
        'supplemental' => '',
        'default'     => array( 'everyone' => 'on' ),
      ),
    );

    foreach( $fields as $field ){
      $field['uid'] = 'ivrita_' . $field['id'];
      add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'ivrita', $field['section'], $field );
      register_setting( 'ivrita', $field['uid'] );
    }
  }

  public function field_callback( $arguments ) {
    $value = get_option( $arguments['uid'], $arguments['default'] );

    switch ( $arguments['type'] ){
    case 'text':
      printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
      break;
    case 'textarea':
      printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
      break;
    case 'checkbox':
      if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
        $options_markup = '';
        foreach( $arguments['options'] as $key => $label ){
            $options_markup .= sprintf( '<label><input name="%1$s[%2$s]" id="%1$s_%2$s" type="checkbox" %3$s />%4$s</label><br>', $arguments['uid'], $key, checked( $value[$key], 'on', false ), $label );
        }
        echo $options_markup;
      } else {
        printf( '<input name="%1$s" id="%1$s" type="%2$s" %3$s />', $arguments['uid'], $arguments['type'], checked( $value, 'on', false ) );
      }
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