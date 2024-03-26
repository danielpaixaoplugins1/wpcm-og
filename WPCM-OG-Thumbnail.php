<?php
/*
Plugin Name: WPCM OG Thumbnail
Plugin URI: https://centralmidia.net.br
Description: Este plugin adiciona tags Open Graph personalizadas para título, imagem em miniatura e início do primeiro parágrafo ao compartilhar postagens nas redes sociais.
Version: 2.2
Author: Daniel Oliveira da Paixão
Author URI: https://centralmidia.net.br
*/

defined('ABSPATH') or die('No script kiddies please!');

class WPCMOGThumbnail {
    public function __construct() {
        add_action('wp_head', [$this, 'add_og_tags']);
    }

    public function add_og_tags() {
        if (is_single()) {
            global $post;
            $titulo_post = esc_attr(get_the_title($post->ID));
            $imagem_url = esc_url(get_the_post_thumbnail_url($post->ID, 'full'));
            $url_post = esc_url(get_permalink($post->ID));
            $resumo_post = esc_attr(wp_trim_words(get_the_excerpt($post->ID), 55, '...'));

            echo "<meta property='og:title' content='{$titulo_post}' />";
            echo "<meta property='og:image' content='{$imagem_url}' />";
            echo "<meta property='og:url' content='{$url_post}' />";
            echo "<meta property='og:type' content='article' />";
            echo "<meta property='og:description' content='{$resumo_post}' />";
        }
    }
}

new WPCMOGThumbnail();
