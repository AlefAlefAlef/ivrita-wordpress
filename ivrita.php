<?php
/**
 * Plugin Name: Ivrita
 * Version: 0.1.3
 * Plugin URI: https://alefalefalef.co.il/ivrita
 * Description: Genderize your website
 * Author: Reuven Karasik and Avraham Cornfeld
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
  private $js_version = '0.1.3';
  
  // This is used to uniquify each toolbar to make sure they won't collide
  private $toolbar_count = 0;

  private $info_link = 'https://alefalefalef.co.il/ivrita';
  
  private $javascript_uri = 'https://ivrita.alefalefalef.co.il/ivrita.min.js';

  public $settings;
  
  function __construct() {
    add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

    $this->settings = new IvritaAdmin();
    add_filter( 'the_content', array( $this, 'maybe_disable_post_content' ), 90 );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
    add_action( 'wp_footer', array( $this, 'print_switch' ), 90 );

    // Shortcodes
    add_shortcode( 'ivrita-toolbar', array( $this, 'toolbar_html' ) );
  }

  public function load_textdomain() {
    load_plugin_textdomain( 'ivrita', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }

  public function maybe_disable_post_content( $content ) {
    if ( 'on' === $this->settings->get_post_field( 'disable_content' ) ) {
      return $this->wrap_disable( $content );
    }
    return $content;
  }

  public function wrap_disable( $text, $tag = 'div' ) {
    return sprintf( '<%1$s data-ivrita-disable="true">%2$s</%1$s>', $tag, $text );
  }

  public function enqueue_scripts() {
    wp_enqueue_style( 'ivrita-css', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), $this->js_version );
    
    if ( $this->settings->get_field( 'use_local_js' ) ) {
      wp_register_script( 'ivrita-lib-js', plugin_dir_url( __FILE__ ) . 'js/ivrita.min.js', array(), $this->js_version, true );
    } else {
      wp_register_script( 'ivrita-lib-js', $this->javascript_uri, array(), $this->js_version, true );
    }
    
    if ( $this->enabled_for_page() ) {
      wp_enqueue_script( 'ivrita-wp-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'ivrita-lib-js' ), $this->js_version, true );
      
      if ( is_singular() ) {
        $title_male = $this->settings->get_post_field( 'title_male' );
        $title_female = $this->settings->get_post_field( 'title_female' );
        $title_neutral = $this->settings->get_post_field( 'title_neutral' );

        if ( $title_male || $title_female || $title_neutral ) {
          wp_localize_script( 'ivrita-wp-js', '_ivrita_titles', array(
            'male' => $title_male,
            'female' => $title_female,
            'neutral' => $title_neutral,
            'current' => get_the_title(),
          ) );
        }
      }
    }

  }

  public function print_switch() {
    if ( ! $this->enabled_for_page() ) {
      return;
    }

    $position = $this->settings->get_field( 'switch_position' );
    $modes = $this->settings->get_matrix( 'modes' );
    $menu_style = $this->settings->get_field( 'menu_style' );
    include 'template-switch.php';
  }

  public function enabled_for_page( $id = null ) {
    global $post;
    if ( $id === null && is_singular() ) {
      $id = get_the_ID();
    }

    // Globally enabled
    if ( !$this->settings->get_field( 'enable_global' ) ) {
      return false;
    } else if ($id && 'on' === $this->settings->get_post_field( 'disable', $id )) {
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
    $modes = $this->settings->get_matrix( 'modes' );
    ob_start();
    include 'template-toolbar.php';
    $toolbar_html = ob_get_clean();
    return $toolbar_html;
  }
}

global $ivrita;
$ivrita = new IvritaWP();

function ivrita() {
  global $ivrita;
  return $ivrita;
}
