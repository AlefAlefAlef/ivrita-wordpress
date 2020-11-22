<?php


class IvritaAdmin {
  protected $fields;

  function __construct() {
    $this->init_fields();
    add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
    add_action( 'admin_init', array( $this, 'setup_sections' ) );
    add_action( 'admin_init', array( $this, 'setup_fields' ) );

    // Meta Boxes
    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'save_meta_box_data' ), 10, 2 );
  }

  protected function init_fields() {
    $modes = array(
      'type' => 'matrix',
      'label' => __( 'Modes', 'ivrita' ),
      'section' => 'global_settings',
      'columns' => array(
        'default_mode' => array(
          'label' => __( 'Default', 'ivrita' ),
          'type' => 'radio',
          'default' => 'neutral'
        ),
        'labels' => array(
          'label' => __( 'Label', 'ivrita' ),
          'type' => 'text',
        ),
      ),
      'rows' => array(
        'male' => array(
          'label' => __( 'Male', 'ivrita' ),
          'default' => array(
            'labels' => __( 'Male', 'ivrita' ),
          ),
        ),
        'female' => array(
          'label' => __( 'Female', 'ivrita' ),
          'default' => array(
            'labels' => __( 'Female', 'ivrita' ),
          ),
        ),
        'neutral' => array(
          'label' => __( 'Neutral', 'ivrita' ),
          'default' => array(
            'labels' => __( 'Neutral', 'ivrita' ),
          ),
        ),
      ),
    );
    $modes['default'] = array(
      'default_mode' => $modes['columns']['default_mode']['default'],
    );
    foreach ( $modes['rows'] as $row_id => $row ) {
      $modes['default']['labels'] = $row['default']['label'];
    }


    $this->fields = array(
      'enable_global' => array(
        'label' => __( 'Global Enable', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'checkbox',
        'options' => false,
        'placeholder' => false,
        'helper' => __( 'Enable everywhere in the website', 'ivrita' ),
        'supplemental' => __( 'You can turn this off/on for each post individually.', 'ivrita' ),
        'default' => true
      ),
      'switch_position' => array(
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
      'modes' => $modes,
      'menu_style' => array(
        'label' => __( 'Menu style', 'ivrita' ),
        'section' => 'global_settings',
        'type'    => 'select',
        'options' => [
            'style-1' => esc_html__( 'Ivrita default', 'ivrita' ),
            'style-2' => esc_html__( 'Venus & Mars', 'ivrita' ),
            'style-3' => esc_html__( 'Hebrew M.F.X', 'ivrita' ),
            'style-4' => esc_html__( 'M.F.X', 'ivrita' ),
            'style-5' => esc_html__( 'Lips & mustache', 'ivrita' ),
            'style-6' => esc_html__( 'Stick figures', 'ivrita' ),
        ],
        'helper' => '',
        'supplemental' => '',
        'default'     => 'style-1',
      ),
      'enable_roles' => array(
        'label' => __( 'Enable for roles', 'ivrita' ),
        'section' => 'global_settings',
        'type'    => 'checkbox',
        'options' => array_merge(
          array( 'everyone' => __( 'Everyone', 'ivrita' ) ),
          wp_roles()->get_names()
        ),
        'helper' => '',
        'supplemental' => '',
        'default'     => array( 'everyone' => 'on' ),
      ),
    );
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
    foreach( $this->fields as $id => $field ){
      $field['uid'] = 'ivrita_' . $id;
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
    case 'matrix':
      $this->print_matrix($arguments, $value);
    }
    
    
    if ( $helper = $arguments['helper'] ){
      printf( '<span class="helper">%s</span>', $helper );
    }
    
    if ( $supplimental = $arguments['supplemental'] ){
      printf( '<p class="description">%s</p>', $supplimental );
    }
  }
  
  public function global_settings_section() {
    ?>
    <script>
      jQuery(function($){
        $('#ivrita_enable_roles_everyone').change(function(){
          $other_checkboxes = $('input[name^="ivrita_enable_roles"]').not('#ivrita_enable_roles_everyone');
          if ($(this).prop('checked')) {
            $other_checkboxes.prop('disabled', true);
          } else {
            $other_checkboxes.prop('disabled', false);
          }
        }).change();
      });
    </script>
    <?php
  }

  protected function print_matrix( $field, $value = array() ) {
    ?>
    <table style="max-width: 25em">
      <tr>
        <th></th>
        <?php
        foreach ( (array) $field['columns'] as $column ) {
          ?>
          <th>
            <?php echo esc_html( $column['label'] ); ?>
          </th>
          <?php
        }
        ?>
      </tr>
      <?php
      foreach ( (array) $field['rows'] as $row_id => $row ) {
        ?>
        <tr>
          <th>
            <?php echo esc_html( $row['label'] ); ?>
          </th>
          <?php foreach ( (array) $field['columns'] as $column_id => $column ) { ?>
            <td>
              <?php
              switch ( $column['type'] ){
              case 'text':
              case 'number':
                $name = sprintf( '%s[%s][%s]', $field['uid'], $column_id, $row_id );
                $input_value = $value[$column_id][$row_id];
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $name, $arguments['type'], $row['default'][$column_id], $input_value );
                break;
              case 'radio':
                $name = sprintf( '%s[%s]', $field['uid'], $column_id );
                printf( '<input name="%1$s" id="%1$s" type="radio" value="%2$s" %3$s />', $name, $row_id, checked( $value[$column_id], $row_id, false ) );
                break;
              }
              ?>
            </td>
          <?php } ?>
        </tr>
        <?php
      }
    ?> </table> <?php
  }

  public function get_field( $key ) {
    if ( $default === null && isset( $this->fields[$key] ) && $this->fields[$key]['default'] ) {
      $default = $this->fields[$key]['default'];
    }
    return get_option('ivrita_' . $key, $default);
  }


  public function add_meta_boxes() {
    add_meta_box(
      'ivrita-post-settings',
      __( 'Ivrita Per-Post Settings', 'ivrita' ),
      array( $this, 'per_post_settings' ),
      array( 'post', 'page' ),
      'side'
    );
  }

  public function per_post_settings($post) {
    wp_nonce_field( 'ivrita_metabox_nonce', 'ivrita_metabox_nonce' ); // TODO: add the post id to the nonce
    $value = get_post_meta( $post->ID, '_ivrita_post_disable', true );
    ?>
    <label for="ivrita-post-disable">
      <input type="checkbox" name="ivrita-post-disable" id="ivrita-post-disable" class="postbox" <?php checked( $value ); ?> />
      <?php _e( 'Disable Ivrita for this post', 'ivrita' ); ?>
    </label>
    <?php
  }

  public function save_meta_box_data( $post_id, $post ) {
    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['ivrita_metabox_nonce'] ) || !wp_verify_nonce( $_POST['ivrita_metabox_nonce'], 'ivrita_metabox_nonce' ) ) {
      return $post_id;
    }


    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
      return $post_id;
    }

    $new_meta_value = ( ( isset( $_POST['ivrita-post-disable'] ) && 'on' === $_POST['ivrita-post-disable'] ) ? true : '' );

    /* Get the meta key. */
    $meta_key = '_ivrita_post_disable';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );
    
    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' === $meta_value ) {
      add_post_meta( $post_id, $meta_key, $new_meta_value, true );
    }
    
    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
      update_post_meta( $post_id, $meta_key, $new_meta_value );
    }
    
    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' === $new_meta_value && $meta_value ) {
      delete_post_meta( $post_id, $meta_key, $meta_value );
    }
  }
}