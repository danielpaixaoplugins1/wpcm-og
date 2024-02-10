<?php
/**
 * Plugin Name: WPCM Og Shared Link
 * Plugin URI: https://centralminidia.net.br/centralmidia
 * Description: Adiciona tags Open Graph personalizadas para título, imagem em miniatura, URL, tipo, descrição e indicação de 'leia mais' ao compartilhar postagens.
 * Version: 1.1
 * Author: Daniel Oliveira da Paixão
 * Author URI: https://centralminidia.net.br/centralmidia
 */

defined('ABSPATH') or die('No script kiddies please!');

class WPCMOgSharedLink {
    public function __construct() {
        add_action('wp_head', [$this, 'add_og_tags']);
        add_action('admin_menu', [$this, 'add_plugin_settings_page']);
        add_action('admin_init', [$this, 'register_plugin_settings']);
    }

    public function add_og_tags() {
        if (is_single()) {
            global $post;
            $titulo_post = esc_attr(get_the_title($post->ID));
            $imagem_url = esc_url(get_the_post_thumbnail_url($post->ID, 'full'));
            $url_post = esc_url(get_permalink($post->ID));
            $site_name = esc_attr(get_bloginfo('name'));
            $resumo_completo = get_the_excerpt($post->ID);

            $linhas_resumo = explode("\n", $resumo_completo);
            $tres_primeiras_linhas = array_slice($linhas_resumo, 0, 3);
            $resumo_curto = implode("\n", $tres_primeiras_linhas);
            $resumo_com_seta = esc_html($resumo_curto) . ' &rarr;';

            echo "<meta property='og:title' content='{$titulo_post}' />";
            if (!empty($imagem_url)) {
                echo "<meta property='og:image' content='{$imagem_url}' />";
            }
            echo "<meta property='og:url' content='{$url_post}' />";
            echo "<meta property='og:type' content='article' />";
            echo "<meta property='og:site_name' content='{$site_name}' />";
            echo "<meta property='og:description' content='{$resumo_com_seta}' />";
        }
    }

    public function add_plugin_settings_page() {
        add_options_page('WPCM OG Shared Link Settings', 'WPCM OG Shared Link', 'manage_options', 'wpcm-og-shared-link', [$this, 'plugin_settings_page_content']);
    }

    public function plugin_settings_page_content() {
        ?>
        <div class="wrap">
            <h2>WPCM OG Shared Link Settings</h2>
            <form action="options.php" method="POST">
                <?php settings_fields('wpcm-og-shared-link-settings'); ?>
                <?php do_settings_sections('wpcm-og-shared-link'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_plugin_settings() {
        register_setting('wpcm-og-shared-link-settings', 'wpcm_og_default_image', 'esc_url');
        add_settings_section('wpcm-og-shared-link-settings-section', 'Default Settings', null, 'wpcm-og-shared-link');
        add_settings_field('wpcm_og_default_image', 'Default Image URL', [$this, 'default_image_url_field_html'], 'wpcm-og-shared-link', 'wpcm-og-shared-link-settings-section');
    }

    public function default_image_url_field_html() {
        $value = get_option('wpcm_og_default_image', '');
        echo '<input type="url" id="wpcm_og_default_image" name="wpcm_og_default_image" value="' . esc_attr($value) . '" />';
    }
}

new WPCMOgSharedLink();
