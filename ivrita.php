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
  function __construct() {
    $this->settings = new IvritaAdmin();
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 90 );
    add_action( 'wp_footer', array( $this, 'print_switch' ), 90 );
  }

  public function enqueue_scripts() {
    wp_enqueue_script( 'ivrita-js', plugin_dir_url( __FILE__ ) . 'js/ivrita.min.js', '1.0', true );
  
    if ( $this->enabled_for_page() ) {
      wp_add_inline_script( 'ivrita-js', $this->inline_script() );
    }
  }

  public function print_switch() {
    $position = $this->settings->get_field( 'switch_position', 'left' );
    $male_label = $this->settings->get_field( 'label_male', __( 'Male', 'ivrita' ) );
    $female_label = $this->settings->get_field( 'label_female', __( 'Female', 'ivrita' ) );
    $neutral_label = $this->settings->get_field( 'label_neutral', __( 'Neutral', 'ivrita' ) );
    $menu_style = $this->settings->get_field( 'menu_style', __( 'Menu style', 'ivrita' ) );
    include 'template-switch.php';
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
    if ( !$this->settings->get_field( 'enable_global', true ) ) {
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
}

global $ivrita;
$ivrita = new IvritaWP();

