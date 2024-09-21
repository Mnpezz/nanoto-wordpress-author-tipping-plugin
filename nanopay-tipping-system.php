<?php
/*
Plugin Name: Nano tipping for blog authors
Description: Implements a tipping system for blogs or news sites using Nano cryptocurrency
Version: 2.0
Author: mnpezz
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the Nanopay_User_Profile class
require_once plugin_dir_path(__FILE__) . 'includes/class-nanopay-user-profile.php';

class NanoPay_Tipping_System {
    private $user_profile;

    public function __construct() {
        $this->user_profile = new Nanopay_User_Profile();

        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_nanopay_get_author_info', array($this, 'get_author_info'));
        add_action('wp_ajax_nopriv_nanopay_get_author_info', array($this, 'get_author_info'));
        add_action('wp_head', array($this, 'inject_tipping_link_script'));
    }

    public function init() {
        // No need for additional initialization
    }

    public function enqueue_scripts() {
        wp_enqueue_script('nanopay', 'https://pay.nano.to/latest.js', array(), null, true);
        wp_enqueue_script('nanopay-tipping', plugin_dir_url(__FILE__) . 'js/nanopay-tipping.js', array('jquery'), '1.9', true);
        wp_localize_script('nanopay-tipping', 'nanopay_tipping_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'tip_text' => __('Tip the author Ӿ', 'nanopay')
        ));
        wp_enqueue_style('nanopay-tipping', plugin_dir_url(__FILE__) . 'css/nanopay-tipping.css');
    }

    public function get_author_info() {
        $author_slug = sanitize_text_field($_POST['author_id']);
        $author = get_user_by('slug', $author_slug);
        
        if (!$author) {
            wp_send_json_error('Author not found.');
            return;
        }
        
        $nano_address = get_user_meta($author->ID, 'nano_address', true);
        $default_tip_amount = get_user_meta($author->ID, 'nano_default_tip', true);
        
        if (empty($nano_address)) {
            wp_send_json_error('Author has not set up a Nano address.');
        } else {
            wp_send_json_success(array(
                'address' => $nano_address,
                'default_tip' => $default_tip_amount ? $default_tip_amount : '0.1'
            ));
        }
    }

    public function inject_tipping_link_script() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.entry-header').each(function() {
                var $header = $(this);
                var $postAuthor = $header.find('.post-author');
                var $postComments = $header.find('.post-comments');
                
                if ($postAuthor.length && $postComments.length) {
                    var authorId = $postAuthor.find('a').attr('href').match(/author\/([^/]+)/);
                    if (authorId && authorId[1]) {
                        var tipLink = ' - <span class="post-tip"><a href="#" class="nanopay-tip-link-container" data-author-id="' + authorId[1] + '"><?php echo esc_js(__('Tip the author Ӿ', 'nanopay')); ?></a></span>';
                        $postAuthor.after(tipLink);
                    }
                }
            });
        });
        </script>
        <?php
    }
}

$nanopay_tipping_system = new NanoPay_Tipping_System();

// Remove this function as it's no longer needed
// function run_nanopay_tipping_system() {
//     require_once plugin_dir_path(__FILE__) . 'includes/class-nanopay-user-profile.php';
//     $plugin_user_profile = new Nanopay_User_Profile();
// }

// run_nanopay_tipping_system();
