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
 * Domain Path: /languages/
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
    add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

    $this->settings = new IvritaAdmin();
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 90 );
    add_action( 'wp_footer', array( $this, 'print_switch' ), 90 );

    // Shortcodes
    add_shortcode( 'ivrita-toolbar', array( $this, 'toolbar_html' ) );
  }

  public function load_textdomain() {
    load_plugin_textdomain( 'ivrita', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }

  public function enqueue_scripts() {
    wp_enqueue_style( 'ivrita-css', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), '1.0' );
    wp_enqueue_script( 'ivrita-lib-js', plugin_dir_url( __FILE__ ) . 'js/ivrita.min.js', array(), '1.0', true );
    
    if ( $this->enabled_for_page() ) {
      wp_enqueue_script( 'ivrita-wp-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'ivrita-lib-js' ), '1.0', true );
    }
  }

  public function print_switch() {
    if ( ! $this->enabled_for_page() ) {
      return;
    }

    $position = $this->settings->get_field( 'switch_position' );
    $modes = $this->settings->get_field( 'modes' );
    $menu_style = $this->settings->get_field( 'menu_style' );
    include 'template-switch.php';
  }

  public function enabled_for_page( $id = null ) {
    global $post;
    if ( $id === null ) {
      $id = $post->ID;
    }

    // Globally enabled
    if ( !$this->settings->get_field( 'enable_global' ) ) {
      return false;
    } else if (get_post_meta( $id, '_ivrita_post_disable', true )) {
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
    $info_link = $this->info_link;
    $modes = $this->settings->get_field( 'modes' );
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

