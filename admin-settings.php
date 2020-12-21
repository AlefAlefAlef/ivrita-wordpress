<?php


class IvritaAdmin {
  protected $fields;

  function __construct() {
    add_action( 'plugins_loaded', array( $this, 'init_fields' ) );
    add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
    add_action( 'admin_init', array( $this, 'setup_sections' ) );
    add_action( 'admin_init', array( $this, 'setup_fields' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

    // Meta Boxes
    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'save_meta_box_data' ), 10, 2 );
  }

  public function init_fields() {
    $modes = array(
      'type' => 'matrix',
      'label' => __( 'Modes', 'ivrita' ),
      'section' => 'global_settings',
      'columns' => array(
        'disabled' => array(
          'label' => _x( 'Disable', 'The option to disable neutral mode in the admin settings page', 'ivrita' ),
          'type' => 'checkbox',
          'default' => false,
          'rows' => array( 'neutral' ),
        ),
        'default_mode' => array(
          'label' => __( 'Default', 'ivrita' ),
          'type' => 'radio',
          'default' => 'neutral'
        ),
        'name' => array(
          'label' => __( 'Label', 'ivrita' ),
          'type' => 'text',
        ),
        'icon' => array(
          'type' => 'hidden',
        ),
        'order' => array(
          'type' => 'hidden',
        )
      ),
      'rows' => array(
        'male' => array(
          'label' => __( 'Male', 'ivrita' ),
          'default' => array(
            'name' => __( 'Male', 'ivrita' ),
            'icon' => '♂',
          ),
        ),
        'female' => array(
          'label' => __( 'Female', 'ivrita' ),
          'default' => array(
            'name' => __( 'Female', 'ivrita' ),
            'icon' => '♀',
          ),
        ),
        'neutral' => array(
          'label' => __( 'Neutral', 'ivrita' ),
          'default' => array(
            'name' => __( 'Neutral', 'ivrita' ),
            'icon' => '⚥',
          ),
        ),
      ),
    );
    $modes['default'] = array(
      'default_mode' => $modes['columns']['default_mode']['default'],
    );
    foreach ( $modes['rows'] as $row_id => $row ) {
      $modes['default']['label'] = $row['default']['label'];
      $modes['default']['icon'] = $row['default']['icon'];
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
      'use_local_js' => array(
        'label' => __( 'Local JS', 'ivrita' ),
        'section' => 'global_settings',
        'type' => 'checkbox',
        'placeholder' => false,
        'helper' => __( 'Use local JavaScript file instead of secure, cached, fast CDN', 'ivrita' ),
        'supplemental' => __( 'Tip: Use this only if you know what you\'re doing!', 'ivrita' ),
        'default' => false
      ),
    );
  }
  
  public function create_settings_page() {
    $page_title = __( 'Ivrita Settings', 'ivrita' );
    $menu_title = _x( 'Ivrita', 'menu title', 'ivrita' );
    $capability = 'manage_options';
    $slug = 'ivrita';
    $callback = array( $this, 'settings_page_content' );
    $icon = 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pZFlNaWQgbWVldCIKCSB2aWV3Qm94PSIwIDAgOTM3Ljk5IDg1OC4wOCI+CjxnPgoJPHBhdGggZmlsbD0id2hpdGUiIGQ9Ik01MC4wMywzNjMuOTVoMzA5LjRjMTEuNTcsMCwxNS40Mi0zLjg2LDE1LjQyLTE1LjQydi05NC40NmMwLTExLjU3LTMuODYtMTUuNDItMTUuNDItMTUuNDJINTAuMDMKCQljLTEwLjYsMC0xNC40NiwzLjg2LTE0LjQ2LDE1LjQydjk0LjQ2QzM1LjU3LDM2MC4wOSwzOS40MywzNjMuOTUsNTAuMDMsMzYzLjk1eiIvPgoJPHBhdGggZmlsbD0id2hpdGUiIGQ9Ik01MC4wMywxODQuNjdoMzA5LjRjMTEuNTcsMCwxNS40Mi0zLjg2LDE1LjQyLTE0LjQ2Vjc0Ljc5YzAtMTEuNTctMy44Ni0xNS40Mi0xNS40Mi0xNS40Mkg1MC4wMwoJCWMtMTAuNiwwLTE0LjQ2LDMuODYtMTQuNDYsMTUuNDJ2OTUuNDJDMzUuNTcsMTgwLjgxLDM5LjQzLDE4NC42Nyw1MC4wMywxODQuNjd6Ii8+Cgk8cGF0aCBmaWxsPSJ3aGl0ZSIgZD0iTTIyMC42Myw0MjkuNDljLTEwOS44OCwwLTE5OS41Miw4OS42NC0xOTkuNTIsMTk5LjUyYzAsMTA4LjkyLDg5LjY0LDE5OS41MiwxOTkuNTIsMTk5LjUyCgkJYzEwOC45MiwwLDE5OS41Mi05MC42LDE5OS41Mi0xOTkuNTJDNDIwLjE2LDUxOS4xMywzMjkuNTUsNDI5LjQ5LDIyMC42Myw0MjkuNDl6IE0xMzEuOTYsNjk1LjUyYy0xMC42LDAtMTcuMzUtOS42NC0xNy4zNS0yNi45OQoJCXM2Ljc1LTI3Ljk1LDE3LjM1LTI3Ljk1czE3LjM1LDEwLjYsMTcuMzUsMjcuOTVTMTQyLjU2LDY5NS41MiwxMzEuOTYsNjk1LjUyeiBNMjgxLjM2LDc1My4zNWMtMTAuNiw5LjY0LTMzLjc0LDIxLjIxLTYwLjcyLDIxLjIxCgkJYy0yNi45OSwwLTUwLjEyLTExLjU3LTYxLjY5LTIxLjIxYy03LjcxLTcuNzEtMTAuNi0xNy4zNS0yLjg5LTI1LjA2YzUuNzgtNi43NSwxNS40Mi00LjgyLDIyLjE3LDAuOTYKCQljOC42Nyw3LjcxLDI1LjA2LDEzLjQ5LDQyLjQxLDEzLjQ5YzE2LjM5LDAsMzIuNzctNS43OCw0MS40NS0xMy40OWM3LjcxLTUuNzgsMTYuMzktNy43MSwyMi4xNy0wLjk2CgkJQzI5Mi45Miw3MzYsMjkwLjAzLDc0NS42NCwyODEuMzYsNzUzLjM1eiBNMzA5LjMxLDY5NS41MmMtMTAuNiwwLTE3LjM1LTkuNjQtMTcuMzUtMjYuOTlzNi43NS0yNy45NSwxNy4zNS0yNy45NQoJCXMxNy4zNSwxMC42LDE3LjM1LDI3Ljk1UzMxOS45MSw2OTUuNTIsMzA5LjMxLDY5NS41MnogTTQwMC44OCw2MzIuODdjLTMxLjgxLDAtMTAyLjE3LTE3LjM1LTE1NC4yMi03Ny4xMQoJCWMtNzMuMjUsNjMuNjItMTczLjUsNjguNDMtMjA4LjIsNjguNDNjMC0xMS41NywxLjkzLTI0LjEsNC44Mi0zNC43YzMwLjg0LDAsMTMxLjA5LTEwLjYsMTkxLjgxLTcwLjM2CgkJYzEwLjYtOC42NywyMS4yMS02Ljc1LDI5Ljg4LDUuNzhjMzguNTUsNTEuMDksMTA0LjEsNzQuMjIsMTMzLjk4LDc0LjIyQzQwMC44OCw2MTAuNyw0MDEuODQsNjIxLjMsNDAwLjg4LDYzMi44N3oiLz4KCTxwYXRoIGZpbGw9IndoaXRlIiBkPSJNOTEzLjY2LDM1OC4xN2MtMjUuMDYtMTQuNDYtMzguNTUtODQuODItMzguNTUtMTMxLjA5YzAtMTA5Ljg4LTkwLjYtMTk5LjUyLTE5OS41Mi0xOTkuNTJTNDc2LjA2LDExNy4yLDQ3Ni4wNiwyMjcuMDgKCQljMCw0Ni4yNy0xMi41MywxMTQuNy00Mi40MSwxMzMuMDFjLTE0LjQ2LDguNjctMTUuNDIsMjQuMS0xLjkzLDMwLjg0YzI1Ljc4LDEzLjM3LDgzLjcxLDE0LjQzLDEzMS42NCwwLjQKCQljMzIuMTMsMjIuMTgsNzAuODUsMzUuMjcsMTEyLjIyLDM1LjI3YzQxLjQ5LDAsODAuMzItMTMuMTYsMTEyLjUtMzUuNDZjNDcuMTEsMTQuMjQsMTA1LjQ2LDEzLjIzLDEzMS4zNi0wLjIKCQlDOTMyLjk0LDM4NC4xOSw5MzAuMDQsMzY3LjgsOTEzLjY2LDM1OC4xN3ogTTQ5OS4xOSwxODIuNzRjMzAuODQsMCwxMzEuMDktMTAuNiwxOTEuODEtNzAuMzZjMTAuNi04LjY3LDIxLjIxLTYuNzUsMjkuODgsNS43OAoJCWMzOC41NSw1MS4wOSwxMDQuMSw3NC4yMiwxMzMuOTgsNzQuMjJjMS45MywxMS41NywyLjg5LDIyLjE3LDEuOTMsMzMuNzRjLTMxLjgxLDAtMTAyLjE3LTE3LjM1LTE1NC4yMi03Ny4xMQoJCWMtNzMuMjUsNjMuNjItMTczLjUsNjguNDMtMjA4LjIsNjguNDNDNDk0LjM3LDIwNS44Nyw0OTYuMywxOTMuMzQsNDk5LjE5LDE4Mi43NHogTTQ2My4xOCwzNzAuOTkKCQljMTQuMTQtMTIuNTMsMjQuMTctMzQuNzgsMzAuMzUtNjMuMTRjMTAuMTYsMjIuNDgsMjQuMzksNDIuODQsNDEuNzIsNjAuMUM1MTMuMTgsMzczLjgyLDQ4MS40NiwzNzUuNjgsNDYzLjE4LDM3MC45OXoKCQkgTTU4Ny44NywyOTQuNTVjLTExLjU3LDAtMTguMzEtMTAuNi0xOC4zMS0yNy45NWMwLTE3LjM1LDYuNzUtMjcuOTUsMTguMzEtMjcuOTVjOS42NCwwLDE2LjM5LDEwLjYsMTYuMzksMjcuOTUKCQlDNjA0LjI2LDI4My45NSw1OTcuNTEsMjk0LjU1LDU4Ny44NywyOTQuNTV6IE03MzguMjMsMzU0LjMxYy0xNi4zOSwxMy40OS0zNy41OSwyMi4xNy02Mi42NSwyMi4xN3MtNDcuMjMtOC42Ny02MS42OS0yMi4xNwoJCWMtOC42Ny02Ljc1LTkuNjQtMTcuMzUtMC45Ni0yNS4wNmM0LjgyLTQuODIsMTMuNDktMy44NiwxOS4yOCwwLjk2YzEwLjYsOC42NywyNS4wNiwxNS40Miw0NC4zNCwxNS40MnMzMi43Ny03LjcxLDQzLjM3LTE2LjM5CgkJYzUuNzgtNC44MiwxNC40Ni0zLjg2LDIxLjIxLDEuOTNDNzQ2LjkxLDMzNy45Miw3NDUuOTQsMzQ3LjU2LDczOC4yMywzNTQuMzF6IE03NjUuMjIsMjk0LjU1Yy0xMS41NywwLTE4LjMxLTEwLjYtMTguMzEtMjcuOTUKCQljMC0xNy4zNSw2Ljc1LTI3Ljk1LDE4LjMxLTI3Ljk1YzkuNjQsMCwxNi4zOSwxMC42LDE2LjM5LDI3Ljk1Qzc4MS42MSwyODMuOTUsNzc0Ljg2LDI5NC41NSw3NjUuMjIsMjk0LjU1eiBNODE1Ljc4LDM2OC4wOAoJCWMxNi43NC0xNi42NSwzMC41OS0zNi4xOSw0MC43LTU3Ljc0YzUuNjEsMjcuOSwxNS4yMyw1MC41OCwzMC4xNSw2MC44OUM4NjguNDMsMzc2LjU1LDgzNy43NiwzNzQuMzQsODE1Ljc4LDM2OC4wOHoiLz4KCTxwYXRoIGZpbGw9IndoaXRlIiBkPSJNODM4LjQ4LDUzNy40NWMtMS45My01Ljc4LTcuNzEtOC42Ny0xMy40OS02Ljc1bC05MC42LDM3LjU5bDYuNzUtOTUuNDJjMC02Ljc1LTMuODYtMTAuNi05LjY0LTEwLjZINjE3Ljc1CgkJYy01Ljc4LDAtMTAuNiwzLjg2LTkuNjQsMTAuNmw3LjcxLDk1LjQybC05Mi41My0zNy41OWMtNS43OC0xLjkzLTEwLjYsMC0xMi41Myw1Ljc4TDQ3NS4xLDYzOS42MgoJCWMtMS45Myw2Ljc1LDAuOTYsMTIuNTMsNi43NSwxMy40OWw5Ni4zOSwyMi4xN2wtNjQuNTgsNzIuMjljLTQuODIsNC44Mi0yLjg5LDExLjU3LDEuOTMsMTUuNDJsOTIuNTMsNjMuNjIKCQljNC44MiwzLjg2LDEwLjYsMi44OSwxNC40Ni0yLjg5bDUyLjA1LTgyLjg5bDUzLjAxLDgyLjg5YzMuODYsNS43OCw3LjcxLDYuNzUsMTMuNDksMi44OWw5Mi41My02My42MgoJCWM1Ljc4LTMuODYsNi43NS0xMC42LDIuODktMTUuNDJsLTY1LjU0LTcyLjI5bDk4LjMyLTIyLjE3YzUuNzgtMC45Niw5LjY0LTYuNzUsNi43NS0xMy40OUw4MzguNDgsNTM3LjQ1eiIvPgo8L2c+Cjwvc3ZnPgo=';
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
      $field['id'] = $id;
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
  
  public function global_settings_section () {
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

        $('#field_matrix_modes tbody').sortable({
          update: function( event, ui ) {
            $(this).find('input[name^="ivrita_modes[order]"]').each(function(index){
              $(this).val(index); // Set the new ordered index
            });
          }
        });
      });
    </script>
    <style>
      label {
        margin-left: 1em;
      }

      .matrix-table {
        max-width: 25em;
      }

      .matrix-table tbody th {
        padding: 0;
        vertical-align: middle;
      }

      .matrix-table tbody td {
        padding: 0.5em 0;
      }

      .matrix-table .ui-sortable-handle {
        cursor: move;
      }

      .matrix-table .ui-sortable-helper td {
        visibility: hidden;
      }
    </style>
    <?php
  }

  protected function print_matrix( $field, $value = array() ) {
    ?>
    <table class="form-table matrix-table" id="field_matrix_<?php echo esc_attr( $field['id'] ); ?>">
      <thead>
        <tr>
          <th></th>
          <?php
          foreach ( (array) $field['columns'] as $column ) {
            if ( empty( $column['label'] ) ) {
              continue;
            }
            ?>
            <th>
              <?php echo esc_html( $column['label'] ); ?>
            </th>
            <?php
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        if ( in_array( 'order', array_keys( $field['columns'] ) ) ) {
          uksort($field['rows'], function ($row1, $row2) use ($value) {
            if ($value['order'][$row1] == $value['order'][$row2]) return 0;
            return $value['order'][$row1] < $value['order'][$row2] ? -1 : 1;
          });
        }

        foreach ( (array) $field['rows'] as $row_id => $row ) {
          ?>
          <tr>
            <th>
              <?php echo esc_html( $row['label'] ); ?>
            </th>
            <?php foreach ( (array) $field['columns'] as $column_id => $column ) {
              if ( ! empty( $column['rows'] ) && ! in_array( $row_id, $column['rows'] ) ) {
                ?>
                <td></td>
                <?php
                continue;
              }

              if ( $column['type'] !== 'hidden' ) { ?>
              <td>
                <?php }
                $name = sprintf( '%s[%s][%s]', $field['uid'], $column_id, $row_id );
                switch ( $column['type'] ){
                case 'text':
                case 'number':
                  $input_value = $value[$column_id][$row_id];
                  printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $name, $arguments['type'], $row['default'][$column_id], $input_value );
                  break;
                case 'radio':
                  $name = sprintf( '%s[%s]', $field['uid'], $column_id );
                  printf( '<input name="%1$s" id="%1$s" type="radio" value="%2$s" %3$s />', $name, $row_id, checked( $value[$column_id], $row_id, false ) );
                  break;
                case 'checkbox':
                  printf( '<input name="%1$s" id="%1$s" type="checkbox" %2$s />', $name, checked( $value[$column_id][$row_id], 'on', false ) );
                  break;
                case 'hidden':
                  printf( '<input name="%1$s" id="%1$s" type="hidden" value="%2$s" />', $name, $value[$column_id][$row_id] );
                }
              
                if ( $column['type'] !== 'hidden' ) { ?>
              </td>
              <?php }
            } ?>
          </tr>
          <?php
        } ?> 
      </tbody>
    </table>
    <?php
  }

  public function get_field( $key ) {
    if ( isset( $this->fields[$key] ) && $this->fields[$key]['default'] ) {
      $default = $this->fields[$key]['default'];
    }

    $field = get_option('ivrita_' . $key, $default);

    return $field;
  }

  public function get_matrix( $field_name ) {
    $value = $this->get_field( $field_name );
    $field = $this->fields[ $field_name ];
    
    $matrix = array();
    foreach ( $field['rows'] as $row_id => $row ) {
      $matrix[ $row_id ] = array();
      foreach ( $field['columns'] as $column_id => $column ) {
        $col_value = null;

        if ( in_array( $column['type'], array( 'text', 'hidden' ) ) ) {
          if ( isset( $value[ $column_id ][ $row_id ] ) && $value[ $column_id ][ $row_id ] ) {
            $col_value = $value[ $column_id ][ $row_id ];
          } else if ( isset( $row['default'] ) && $row['default'][ $column_id ] ) {
            $col_value = $row['default'][ $column_id ];
          }
        } else if ( in_array( $column['type'], array( 'radio' ) )) {
          $col_value = $value[ $column_id ] === $row_id;
        } else if ( in_array( $column['type'], array( 'checkbox' ) )) {
          $col_value = $value[ $column_id ][ $row_id ] === 'on';
        }

        $matrix[ $row_id ][ $column_id ] = $col_value;
      }
    }

    if ( in_array( 'order', array_keys( $field['columns'] ) ) ) {
      uasort($matrix, function ($row1, $row2) {
        if ($row1['order'] == $row2['order']) return 0;
        return $row1['order'] < $row2['order'] ? -1 : 1;
      });
    }

    return $matrix;
  }

  public function enqueue_admin_scripts( $hook_suffix ) {
    if ( $hook_suffix != 'toplevel_page_ivrita' ) {
      return;
    }
    
    wp_enqueue_script( 'jquery-ui-sortable' );
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