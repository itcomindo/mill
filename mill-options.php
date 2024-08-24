<?php

/**
 *
 * Silence is golden
 *@package mill
 */

defined('ABSPATH') || die('No script kiddies please!');

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Load Theme Options From Carbon Fields.
 */
function mill_plugin_options()
{
    Container::make('theme_options', 'MILL Options')
        ->add_fields(
            array(
                Field::make('textarea', 'kw_link', 'Keyword|Link')
                    ->set_help_text('Ketik keyword dan link dipisahkan dengan pipe. Example: keyword|https://example.com. Gunakan baris baru untuk setiap keyword dan link baru. Note: Jangan gunakan spasi, text case sensitive (huru besar dan huruf kecil berpengaruh).'),
                // Checkbox option open in new tab.
                Field::make('checkbox', 'kw_new_tab', 'Open in New Tab')
                    ->set_option_value('yes')
                    ->set_default_value(false)
                    ->set_help_text('Check this box to open the link in a new tab.'),
            )
        );
}

add_action('carbon_fields_register_fields', 'mill_plugin_options');
