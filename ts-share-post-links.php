<?php declare(strict_types=1);

/**
 * Plugin Name: Share Post Links
 * Description: Adds a [share_post_links] shortcode to display social share buttons.
 * Version: 1.0
 * Author: Taras Shkodenko <taras.shkodenko@gmail.com>
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function spl_load_textdomain() {
    load_plugin_textdomain( 'ts-share-post-links', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'spl_load_textdomain' );

function spl_enqueue_font_awesome() {
    wp_enqueue_style(
        'font-awesome-cdn',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
        [],
        '6.0.0-beta3'
    );
}
add_action('wp_enqueue_scripts', 'spl_enqueue_font_awesome');

// Register the shortcode
function spl_register_share_shortcode() {
    add_shortcode('share_post_links', 'spl_render_share_buttons');
}
add_action('init', 'spl_register_share_shortcode');

// Render the HTML for the shortcode
function spl_render_share_buttons() {
    if (!is_single()) return ''; // Only show on single posts

    $url = esc_url(get_permalink());

    ob_start();
    ?>
    <div class="entry-share-buttons">
        <div class="share-this">
            <h3 class="share-this__title"><?php esc_html_e( 'Share This Blog Post', 'ts-share-post-links' ); ?></h3>

            <section class="social-share" data-url="<?php echo $url; ?>" id="social-share__0">
                <div class="social-share__facebook">
                    <a class="social-share__facebook-share" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" aria-label="facebook share">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
                <div class="social-share__twitter">
                    <a class="social-share__twitter-share" href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>" target="_blank" aria-label="twitter share">
                        <i class="fab fa-x fa-twitter fa-x-twitter"></i>
                    </a>
                </div>
                <div class="social-share__reddit">
                    <a class="social-share__reddit-share" href="https://www.reddit.com/submit?url=<?php echo $url; ?>" target="_blank" aria-label="reddit share">
                        <i class="fab fa-reddit-alien"></i>
                    </a>
                </div>
                <div class="social-share__linkedin">
                    <a class="social-share__linkedin-share" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" aria-label="linkedin share">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </section>
        </div>
    </div><!-- .entry-share-buttons -->
    <?php
    return ob_get_clean();
}

// Conditionally enqueue styles only if the shortcode exists in post content
function spl_maybe_enqueue_styles() {
    if (is_singular()) {
        wp_enqueue_style('spl-share-styles');
    }
}
add_action('wp_enqueue_scripts', 'spl_maybe_enqueue_styles');

// Register the inline styles
function spl_register_styles() {
    wp_register_style('spl-share-styles', false);
    wp_add_inline_style('spl-share-styles', '
        .entry-share-buttons {
            text-align: center;
        }

        .social-share {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-share div {
            display: inline-block;
        }

        .social-share a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .social-share__facebook-share {
            background-color: #3b5998;
        }

        .social-share__twitter-share {
            background-color: #1da1f2;
        }

        .social-share__reddit-share {
            background-color: #ff4500;
        }

        .social-share__linkedin-share {
            background-color: #0077b5;
        }

        .social-share a:hover {
            opacity: 0.8;
        }
    ');
}
add_action('init', 'spl_register_styles');
