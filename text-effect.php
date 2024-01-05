<?php
/*
Plugin Name: WP Morphing Text Effect  
Description: Add a custom Morphing Text Effect to your WordPress site.  [morphing_text text=",Mercy,Law,Professional Singer" font_size="70pt" text_align="left"]

Version: 1.0
Author: Hassan Naqvi
*/

// Enqueue scripts and styles
function enqueue_text_effect_assets() {
    // Enqueue JavaScript
    wp_enqueue_script('text-effect-script', plugins_url('script.js', __FILE__), array('jquery'), '1.0', true);

    // Enqueue CSS
    wp_enqueue_style('text-effect-style', plugins_url('style.css', __FILE__), array(), '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_text_effect_assets');


function morphing_text_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'text' => 'WP,Design,Lab',
            'font_size' => '65px;',
            'text_align' => 'left',
        ),
        $atts,
        'morphing_text'
    );

    // Convert the 'text' attribute to an array
    $texts = explode(',', $atts['text']);
    $font_size = esc_attr($atts['font_size']);
    $text_align = esc_attr($atts['text_align']);

    ob_start(); // Start output buffering
    ?>
    <!-- The container for morphing text -->
    <div id="container" style="text-align: <?php echo $text_align; ?>;">
        <span id="text1" style="font-size: <?php echo $font_size; ?>;"><?php echo $texts[0]; ?></span>
        <span id="text2" style="font-size: <?php echo $font_size; ?>;"></span>
    </div>

    <!-- The SVG filter used to create the merging effect -->
    <svg id="filters">
        <defs>
            <filter id="threshold">
                <!-- Basically just a threshold effect - pixels with a high enough opacity are set to full opacity, and all other pixels are set to completely transparent. -->
                <feColorMatrix in="SourceGraphic"
                    type="matrix"
                    values="1 0 0 0 0
                            0 1 0 0 0
                            0 0 1 0 0
                            0 0 0 255 -140" />
            </filter>
        </defs>
    </svg>

    <script>
        // Initialize morphing text with shortcode attributes
        const texts = <?php echo json_encode($texts); ?>;
        const font_size = '<?php echo $font_size; ?>';
        const text_align = '<?php echo $text_align; ?>';
        document.getElementById('container').style.textAlign = text_align;
        document.getElementById('text1').style.fontSize = font_size;
        document.getElementById('text2').style.fontSize = font_size;
    </script>
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('morphing_text', 'morphing_text_shortcode');
