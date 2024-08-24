<?php

/**
 *
 * Plugin Name: MM Internal Links Linker
 * Plugin URI: https://github.com/itcomindo/mill
 * Description: A simple plugin to add internal links to your content.
 * Version: 1.0.1
 * Author: Budi Haryono
 * Author URI: https://budiharyono.id/
 * @package mill
 */

defined('ABSPATH') || die('No script kiddies please!');

/**
 * Check CF Loaded
 */
function mill_call_carbon_fields()
{
    if (! class_exists('\Carbon_Fields\Carbon_Fields')) {
        require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
        \Carbon_Fields\Carbon_Fields::boot();
    }
}

/**
 * MCS CF Loaded
 */
function mill_cf_loaded()
{
    if (! function_exists('carbon_fields_boot_plugin')) {
        mill_call_carbon_fields();
    }
}
add_action('plugins_loaded', 'mill_cf_loaded');


//Load necessary files for the plugin.
require_once plugin_dir_path(__FILE__) . 'mill-options.php';


// Internal Link Start.

function mill_autolink_content($content)
{
    // Ambil nilai dari theme options
    $links = carbon_get_theme_option('kw_link');

    if ($links) {
        // Pisahkan setiap baris menjadi array keyword|link
        $link_lines = explode("\n", $links);

        $kw_new_tab = carbon_get_theme_option('kw_new_tab');
        if ($kw_new_tab) {
            $target = '_blank';
        } else {
            $target = '_self';
        }

        foreach ($link_lines as $line) {
            // Pisahkan keyword dan link menggunakan pipe '|'
            list($keyword, $link) = explode('|', trim($line));

            // Escape karakter spesial dalam keyword untuk pencarian regex
            $keyword = preg_quote(trim($keyword), '/');

            // Buat tag <a> untuk link
            $replace = '<a href="' . esc_url($link) . '" title="' . esc_html($keyword) . '" target="' . $target . '">' . esc_html($keyword) . '</a>';

            // Ganti hanya 1 kemunculan pertama dari keyword dalam konten
            $content = preg_replace('/\b' . esc_html($keyword) . '\b(?!([^<]*>))/', $replace, $content, 1);
        }
    }

    return $content;
}

add_filter('the_content', 'mill_autolink_content');
