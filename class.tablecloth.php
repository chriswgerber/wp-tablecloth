<?php
/**
 * Plugin Name: TableCloth
 * Plugin URI: http://wwww.chriswgerber.com/tablecloth-plugin/
 * Description: Creating beautiful interactive tables.
 * Version: 0.0.1-alpha
 * Author: Christopher Gerber
 * Author URI: http://www.chriswgerber.com/
 * License: GPL2
 */

if ( ! class_exists( 'tablecloth' ) ) {

    class tablecloth {

        /**
         * @const PLUGIN_NAME Name of the plugin
         */
        CONST PLUGIN_NAME = 'tablecloth';

        /**
         * @const PLUGIN_VER Version number for the plugin
         */
        CONST PLUGIN_VER  = '0.0.1-alpha';

        /**
         * @var string $asset_uri Directory for tablecloth JS files
         */
        public $asset_uri;

        /**
         * Constructor
         *
         * Begins registering scripts and preparing variables.
         */
        public function __construct() {

            $this->asset_uri = plugins_url('', __FILE__) . '/assets';
            // Register Scripts
            add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );

            add_action( 'wp', array($this, 'your_prefix_detect_shortcode') );

            // Create Shortcode
            add_shortcode('tablecloth' , array($this, 'shortcode'));
        }

        public function shortcode( $atts = null, $content = '' ) {

            return '<div class="tablecloth-container">' . $content . '</div>';
        }

        public function init_tablecloth() {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        public function register_scripts() {
            // CSS
            wp_register_style(
                self::PLUGIN_NAME . '-tablecloth',
                $this->asset_uri . '/tablecloth.css',
                array(),
                null
            );
            // Tablecloth Call
            wp_register_script(
                self::PLUGIN_NAME . '-clothed',
                $this->asset_uri . '/clothed.js',
                array(),
                null,
                true
            );
        }

        public function enqueue_scripts() {
            wp_enqueue_style(self::PLUGIN_NAME . '-tablecloth');
            wp_enqueue_script(self::PLUGIN_NAME . '-clothed');
        }

        public function your_prefix_detect_shortcode() {
            global $wp_query;
            $posts = $wp_query->posts;
            $pattern = get_shortcode_regex();


            foreach ($posts as $post){
                if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
                       && array_key_exists( 2, $matches )
                       && in_array( 'tablecloth', $matches[2] ) )
                {
                    // enque my css and js
                    $this->init_tablecloth();
                    break;
                }
            }
        }

    }

}

new tablecloth;