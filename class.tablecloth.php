<?php
/**
 * Plugin Name: TableCloth
 * Plugin URI: http://wwww.chriswgerber.com/tablecloth-plugin/
 * Description: A plugin for adding table styling using tablecloth.js, created by Brian Sewell
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
         * @var string $js_dir Directory for tablecloth JS files
         */
        public $js_dir;

        /**
         * @var string $css_dir Directory for tablecloth CSS files
         */
        public $css_dir;

        /**
         * Constructor
         *
         * Begins registering scripts and preparing variables.
         */
        public function __construct() {

            $this->js_dir  = dirname( __FILE__ ) . '/source/assets/js';
            $this->css_dir = dirname( __FILE__ ) . '/source/assets/css';

            // Register Styles
            add_action( 'wp_enqueue_styles', array($this, 'register_styles') );
            // Register Scripts
            add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );
        }

        public function shortcode( $atts = null, $content = '' ) {

            $this->init_tablecloth();

            return '<div class="tableloth-container">' . $content . '</div>';
        }

        public function init_tablecloth() {
            add_action('wp_enqueue_styles', array($this, 'enqueue_styles'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        public function register_styles() {

            // Bootstrap
            wp_register_style(
                self::PLUGIN_NAME . '-bootstrap',
                $this->css_dir . 'bootstrap.css',
                array(),
                false
            );

            // Bootstrap Responsive
            wp_register_style(
                self::PLUGIN_NAME . '-bootstrap-responsive',
                $this->css_dir . 'bootstrap-responsive.css',
                array(
                    self::PLUGIN_NAME . '-bootstrap'
                ),
                false
            );

            // Tablecloth
            wp_register_style(
                self::PLUGIN_NAME,
                $this->css_dir . 'tablecloth.css',
                array(
                    self::PLUGIN_NAME . '-bootstrap',
                    self::PLUGIN_NAME . '-bootstrap-responsive'
                ),
                false
            );

            // Prettify
            wp_register_style(
                self::PLUGIN_NAME . '-prettify',
                $this->css_dir . 'prettify.css',
                array(
                    self::PLUGIN_NAME . '-bootstrap',
                    self::PLUGIN_NAME . '-bootstrap-responsive',
                    self::PLUGIN_NAME
                ),
                false
            );
        }

        public function register_scripts() {
            // BootStrap
            wp_register_script(
                self::PLUGIN_NAME . '-bootstrap',
                $this->js_dir . '/bootstrap.min.js',
                array( 'jquery' ),
                false,
                true
            );
            // Metadata
            wp_register_script(
                self::PLUGIN_NAME . '-metadata',
                $this->js_dir . '/jquery.metadata.js',
                array( 'jquery', self::PLUGIN_NAME . '-bootstrap' ),
                false,
                true
            );

            // Tablesorter
            wp_register_script(
                self::PLUGIN_NAME . '-tablesorter',
                $this->js_dir . '/jquery.tablesorter.min.js',
                array( 'jquery', self::PLUGIN_NAME . '-bootstrap', self::PLUGIN_NAME . '-metadata' ),
                false,
                true
            );

            // Tablecloth
            wp_register_script(
                self::PLUGIN_NAME,
                $this->js_dir . '/jquery.tablecloth.js',
                array(
                    'jquery',
                    self::PLUGIN_NAME . '-bootstrap',
                    self::PLUGIN_NAME . '-metadata',
                    self::PLUGIN_NAME . '-tablesorter'
                ),
                false,
                true
            );

            // Tablecloth Call
            wp_register_script(
                self::PLUGIN_NAME . '-clothed',
                dirname( __FILE__ ) . '/assets/clothed.js',
                array(
                    'jquery',
                    self::PLUGIN_NAME . '-bootstrap',
                    self::PLUGIN_NAME . '-metadata',
                    self::PLUGIN_NAME . '-tablesorter',
                    self::PLUGIN_NAME
                ),
                false,
                true
            );

        }

        public function enqueue_styles() {
            wp_enqueue_style(self::PLUGIN_NAME . '-bootstrap');
            wp_enqueue_style(self::PLUGIN_NAME . '-bootstrap-responsive');
            wp_enqueue_style(self::PLUGIN_NAME);
            wp_enqueue_style(self::PLUGIN_NAME . '-prettify');
        }

        public function enqueue_scripts() {
            wp_enqueue_script(self::PLUGIN_NAME . '-bootstrap');
            wp_enqueue_script(self::PLUGIN_NAME . '-metadata');
            wp_enqueue_script(self::PLUGIN_NAME . '-tablesorter');
            wp_enqueue_script(self::PLUGIN_NAME);
            wp_enqueue_script(self::PLUGIN_NAME . '-clothed');
        }

        static function add_shortcode() {
            add_shortcode( 'tablecloth' , array( __CLASS__, 'shortcode') );
        }

    }

}

new tablecloth;