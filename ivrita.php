<?php
/**
 * Plugin Name: Ivrita
 * Version: 0.1
 * Plugin URI: https://alefalefalef.co.il/ivrita
 * Description: Genderize your website
 * Author: Reuven Karasik and Avraham Cornfeld
 * Author URI: https://alefalefalef.co.il/ivrita
 * Requires at least: 4.0
 * Tested up to: 5.2
 *
 * Text Domain: ivrita
 * Domain Path: /i18n/
 *
 * @package WordPress
 * @author Reuven Karasik
 * @since 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'admin-settings.php';

class IvritaWP {
  
  // This is used to uniquify each toolbar to make sure they won't collide
  private $toolbar_count = 0;

  private $info_link = 'https://alefalefalef.co.il/ivrita';
  
  function __construct() {
    $this->settings = new IvritaAdmin();
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 90 );
    add_action( 'wp_footer', array( $this, 'print_switch' ), 90 );

    // Shortcodes
    add_shortcode( 'ivrita-toolbar', array( $this, 'toolbar_html' ) );
  }

  public function enqueue_scripts() {
    wp_enqueue_style( 'ivrita-css', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), '1.0' );
    wp_enqueue_script( 'ivrita-lib-js', plugin_dir_url( __FILE__ ) . 'js/ivrita.min.js', array(), '1.0', true );
    
    if ( $this->enabled_for_page() ) {
      wp_enqueue_script( 'ivrita-wp-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'ivrita-lib-js' ), '1.0', true );
    }
  }

  public function print_switch() {
    $position = $this->settings->get_field( 'switch_position' );
    $modes = $this->settings->get_field( 'modes' );
    $male_label = $modes['labels']['male'];
    $female_label = $modes['labels']['female'];
    $neutral_label = $modes['labels']['neutral'];
    $menu_style = $this->settings->get_field( 'menu_style' );
    include 'template-switch.php';
    include 'template-toolbar.php';
  }

  public function inline_script() {
    ob_start();
    include 'inline-js.php';
    $inline_js = ob_get_clean();
    return $inline_js;
  }

  public function enabled_for_page( $id = null ) {
    if ( $id === null ) {
      $id = get_the_ID();
    }

    // Globally enabled
    if ( !$this->settings->get_field( 'enable_global' ) ) {
      return false;
    }

    // Roles
    $enable_roles = $this->settings->get_field( 'enable_roles' );
    if ( $enable_roles['everyone'] === 'on' ) {
      return true;
    } else {
      $user = wp_get_current_user();
      if ( empty( $user ) ) {
        return false;
      }

      foreach ( (array) $user->roles as $role ) {
        if ( $enable_roles[$role] === 'on' ) {
          return true;
        }
      }
    }
    return ;
  }

  public function toolbar_html() {
    $toolbar_id = $this->toolbar_count++;
    $default_gender = 'NEUTRAL';
    $info_link = $this->info_link;
    ob_start();
    include 'template-toolbar.php';
    $toolbar_html = ob_get_clean();
    return $toolbar_html;
  }

  public function print_toolbar() {
    echo $this->toolbar_html();
  }
}

global $ivrita;
$ivrita = new IvritaWP();

