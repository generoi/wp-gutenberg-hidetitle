<?php
/*
Plugin Name:        Gutenberg Hide Title
Plugin URI:         http://genero.fi
Description:        Add an option to the Gutenberg editor for hiding the post title
Version:            1.0.0
Author:             Genero
Author URI:         http://genero.fi/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/
namespace GeneroWP\Gutenberg\HideTitle;

use Puc_v4_Factory;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use GeneroWP\Common\Singleton;
use GeneroWP\Common\Assets;

if (!defined('ABSPATH')) {
    exit;
}

if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require_once $composer;
}


class Plugin
{
    use Singleton;
    use Assets;

    public $version = '1.0.0';
    public $plugin_name = 'wp-gutenberg-hidetitle';
    public $plugin_path;
    public $plugin_url;
    public $github_url = 'https://github.com/generoi/wp-gutenberg-hidetitle';

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->plugin_url = plugin_dir_url(__FILE__);

        Puc_v4_Factory::buildUpdateChecker($this->github_url, __FILE__, $this->plugin_name);

        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init()
    {
        add_action('enqueue_block_assets', [$this, 'block_assets'], 11);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
        add_action('init', [$this, 'load_textdomain']);

        register_meta('post', 'hide_title', [
            'object_subtype' => 'post',
            'show_in_rest' => true,
            'single' => true,
            'type' => 'boolean',
        ]);
    }

    public function block_assets()
    {
        $this->enqueueStyle("{$this->plugin_name}/block/css", 'dist/style.css', ['wp-blocks']);
    }

    public function block_editor_assets()
    {
        $this->enqueueScript("{$this->plugin_name}/js", 'dist/index.js', ['wp-i18n', 'wp-element', 'wp-plugins', 'wp-edit-post', 'wp-data', 'wp-components']);
        $this->localizeScript("{$this->plugin_name}/js", gutenberg_get_jed_locale_data($this->plugin_name));
    }

    public function load_textdomain()
    {
        // WP Performance Pack
        include __DIR__ . '/languages/javascript.php';

        load_plugin_textdomain($this->plugin_name, false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}

Plugin::getInstance();
