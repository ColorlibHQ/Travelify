<?php
/**
 * Add block to WordPress theme customizer
 * @package Travelify
 */
function travelify_options_register_theme_customizer($wp_customize)
{
    require_once(trailingslashit(get_template_directory()) . '/library/panel/travelify-custom-control.php');

    global $travelify_theme_options_settings, $travelify_theme_options_defaults;
    $options = get_option('travelify_theme_options');

    /* Main option Settings Panel */
    $wp_customize->add_panel('travelify_main_options', array(
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Travelify Main Options', 'travelify'),
        'description' => esc_html__('Panel to update travelify theme options', 'travelify'), // Include html tags such as <p>.
        'priority' => 10 // Mixed with top-level-section hierarchy.
    ));

    /* Travelify Header Area */
    $wp_customize->add_section('travelify_menu_options', array(
        'title' => esc_html__('Travelify Header Area', 'travelify'),
        'description' => esc_html__('Use the following settings change color for menu and website title', 'travelify'),
        'priority' => 31,
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_logo_color', array(
            'default' => '#57ad68',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_logo_color', array(
            'label' => esc_html__('Website Title Color', 'travelify'),
            'section' => 'travelify_menu_options',
            'settings' => 'travelify_logo_color',
            'priority' => 1
        )));

        $wp_customize->add_setting('travelify_logo_hover_color', array(
            'default' => '#439f55',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_logo_hover_color', array(
            'label' => esc_html__('Website Title Hover Color', 'travelify'),
            'section' => 'travelify_menu_options',
            'settings' => 'travelify_logo_hover_color',
            'priority' => 2
        )));

        $wp_customize->add_setting('travelify_menu_color', array(
            'default' => '#57ad68',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_menu_color', array(
            'label' => esc_html__('Menu Bar Color', 'travelify'),
            'section' => 'travelify_menu_options',
            'settings' => 'travelify_menu_color'
        )));

        $wp_customize->add_setting('travelify_menu_hover_color', array(
            'default' => '#439f55',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_menu_hover_color', array(
            'label' => esc_html__('Menu Bar Hover Color', 'travelify'),
            'section' => 'travelify_menu_options',
            'settings' => 'travelify_menu_hover_color'
        )));

        $wp_customize->add_setting('travelify_menu_item_color', array(
            'default' => '#FFF',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_menu_item_color', array(
            'label' => esc_html__('Menu Item Text Color', 'travelify'),
            'section' => 'travelify_menu_options',
            'settings' => 'travelify_menu_item_color',
            'priority' => 3
        )));

        $wp_customize->add_setting('travelify_social_color', array(
            'default' => '#D0D0D0',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_social_color', array(
            'label' => esc_html__('Social Icon Color', 'travelify'),
            'section' => 'travelify_menu_options',
            'settings' => 'travelify_social_color',
            'priority' => 13
        )));

    /* Travelify Element Color */
    $wp_customize->add_section('travelify_element_options', array(
        'title' => esc_html__('Travelify Element Color', 'travelify'),
        'description' => esc_html__('Use the following settings change color for website elements', 'travelify'),
        'priority' => 32,
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_element_color', array(
            'default' => '#57ad68',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_element_color', array(
            'label' => esc_html__('Element Color', 'travelify'),
            'section' => 'travelify_element_options',
            'settings' => 'travelify_element_color',
            'priority' => 4
        )));

        $wp_customize->add_setting('travelify_element_hover_color', array(
            'default' => '#439f55',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_element_hover_color', array(
            'label' => esc_html__('Element Hover Color', 'travelify'),
            'section' => 'travelify_element_options',
            'settings' => 'travelify_element_hover_color',
            'priority' => 5
        )));

        $wp_customize->add_setting('travelify_wrapper_color', array(
            'default' => '#F8F8F8',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_wrapper_color', array(
            'label' => esc_html__('Wrapper Color', 'travelify'),
            'section' => 'travelify_element_options',
            'settings' => 'travelify_wrapper_color',
            'priority' => 6
        )));

        $wp_customize->add_setting('travelify_content_bg_color', array(
            'default' => '#FFF',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_content_bg_color', array(
            'label' => esc_html__('Content Background Color', 'travelify'),
            'section' => 'travelify_element_options',
            'settings' => 'travelify_content_bg_color',
            'priority' => 7
        )));

    /* Travelify Typography Color */
    $wp_customize->add_section('travelify_typography_options', array(
        'title' => esc_html__('Travelify Typography Color', 'travelify'),
        'description' => esc_html__('Use the following settings change color for typography such as links, headings and content', 'travelify'),
        'priority' => 33,
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_entry_color', array(
            'default' => '#444',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_entry_color', array(
            'label' => esc_html__('Entry Content Color', 'travelify'),
            'section' => 'travelify_typography_options',
            'settings' => 'travelify_entry_color'
        )));

        $wp_customize->add_setting('travelify_header_color', array(
            'default' => '#444',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_header_color', array(
            'label' => esc_html__('Header/Title Color', 'travelify'),
            'section' => 'travelify_typography_options',
            'settings' => 'travelify_header_color',
            'priority' => 8
        )));

        $wp_customize->add_setting('travelify_link_color', array(
            'default' => '#57ad68',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_link_color', array(
            'label' => esc_html__('Link Color', 'travelify'),
            'section' => 'travelify_typography_options',
            'settings' => 'travelify_link_color',
            'priority' => 11
        )));

        $wp_customize->add_setting('travelify_link_hover_color', array(
            'default' => '#439f55',
            'sanitize_callback' => 'travelify_sanitize_hexcolor'
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'travelify_link_hover_color', array(
            'label' => esc_html__('Link Hover Color', 'travelify'),
            'section' => 'travelify_typography_options',
            'settings' => 'travelify_link_hover_color',
            'priority' => 12
        )));

    /* Travelify Footer Section */
    $wp_customize->add_section('travelify_footer_options', array(
        'title' => esc_html__( 'Travelify Footer', 'travelify' ),
        'description' => esc_html__('Use the following settings customize Footer', 'travelify'),
        'priority' => 34,
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_footer_textbox', array(
            'default' => esc_html__( 'Default footer text', 'travelify' ),
            'sanitize_callback' => 'wp_kses_post'
        ));
        $wp_customize->add_control('travelify_footer_textbox', array(
            'label' => esc_html__('Copyright text', 'travelify'),
            'section' => 'travelify_footer_options',
            'type' => 'text'
        ));

    /* Header Options */
    $wp_customize->add_section('travelify_header_options', array(
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Header Options', 'travelify'),
        'description' => esc_html__('Section to update theme options for header', 'travelify'),
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_theme_options[header_logo]', array(
            'default' => $travelify_theme_options_defaults['header_logo'],
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw'
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'travelify_theme_options[header_logo]', array(
            'label' => esc_html__('Header Logo', 'travelify'),
            'section' => 'travelify_header_options',
            'mime_type' => 'image',
            'settings' => 'travelify_theme_options[header_logo]'
        )));

        $wp_customize->add_setting('travelify_theme_options[header_show]', array(
            'default' => $travelify_theme_options_defaults['header_show'],
            'type' => 'option',
            'sanitize_callback' => 'travelify_sanitize_radio_header'
        ));
        $wp_customize->add_control('travelify_theme_options[header_show]', array(
            'type' => 'radio',
            'label' => esc_html__('Show', 'travelify'),
            'section' => 'travelify_header_options',
            'choices' => array(
                'header-logo' => esc_html__('Header Logo Only', 'travelify'),
                'header-text' => esc_html__('Header Text Only', 'travelify'),
                'disable-both' => esc_html__('Disable', 'travelify')
            )
        ));

    /* Layout Options */
    $wp_customize->add_section('travelify_layout_options', array(
        'priority' => 30,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Layout Options', 'travelify'),
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_theme_options[default_layout]', array(
            'default' => $travelify_theme_options_defaults['default_layout'],
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_radio_layout'
        ));
        $wp_customize->add_control(new Travelify_Layout_Picker_Custom_Control($wp_customize, 'travelify_theme_options[default_layout]', array(
            'description' => esc_html__('This will set the default layout style. However, you can choose different layout for each page via editor', 'travelify'),
            'section' => 'travelify_layout_options',
            'type' => 'radio-image',
            'settings' => 'travelify_theme_options[default_layout]',
            'choices' => array(
                'no-sidebar'            => esc_html__('No Sidebar', 'travelify'),
                'no-sidebar-full-width' => esc_html__('No Sidebar, Full Width', 'travelify'),
                'no-sidebar-one-column' => esc_html__('No Sidebar, One Column', 'travelify'),
                'left-sidebar'          => esc_html__('Left Sidebar', 'travelify'),
                'right-sidebar'         => esc_html__('Right Sidebar', 'travelify')
            )
        )));

        $wp_customize->add_setting('travelify_theme_options[reset_layout]', array(
            'default' => $travelify_theme_options_defaults['reset_layout'],
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'travelify_sanitize_checkbox'
        ));
        $wp_customize->add_control('travelify_theme_options[reset_layout]', array(
            'label' => esc_html__('Check to reset Layout', 'travelify'),
            'section' => 'travelify_layout_options',
            'type' => 'checkbox',
            'settings' => 'travelify_theme_options[reset_layout]'
        ));

    /* RSS URL */
    $wp_customize->add_section('travelify_rss_options', array(
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('RSS URL', 'travelify'),
        'description' => esc_html__('Enter your preferred RSS URL. (Feedburner or other)', 'travelify'),
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_theme_options[feed_url]', array(
            'default' => $travelify_theme_options_defaults['feed_url'],
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw'
        ));
        $wp_customize->add_control('travelify_theme_options[feed_url]', array(
            'label' => esc_html__('Feed Redirect URL', 'travelify'),
            'section' => 'travelify_rss_options'
        ));

    /* Homepage Post Options */
    $wp_customize->add_section('travelify_homepage_post_options', array(
        'priority' => 60,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Homepage Post Options', 'travelify'),
        'panel' => 'travelify_main_options'
    ));
        $wp_customize->add_setting('travelify_theme_options[front_page_category]', array(
            'default' => $travelify_theme_options_defaults['front_page_category'],
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            'sanitize_callback' => 'travelify_sanitize_multiselect'
        ));
        $wp_customize->add_control(new Travelify_Customize_Control_Multi_Select_Category($wp_customize, 'travelify_theme_options[front_page_category]', array(
            'description' => esc_html__('You may select multiple categories by holding down the CTRL (Windows) or cmd (Mac).', 'travelify'),
            'section' => 'travelify_homepage_post_options',
            'priority' => 10,
            'type' => 'multi-select-cat'
        )));

    /* Featured Slider Settings Panel */
    $wp_customize->add_panel('travelify_slider_options', array(
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Travelify Featured Slider', 'travelify'),
        'description' => esc_html__('Panel to update travelify theme options', 'travelify'), // Include html tags such as <p>.
        'priority' => 15 // Mixed with top-level-section hierarchy.
    ));
    $wp_customize->add_section('travelify_post_slider_options', array(
        'priority' => 60,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Featured Post/Page Slider Options', 'travelify'),
        'panel' => 'travelify_slider_options'
    ));
        $wp_customize->add_setting('travelify_theme_options[exclude_slider_post]', array(
            'default' => 0,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_checkbox'
        ));
        $wp_customize->add_control('travelify_theme_options[exclude_slider_post]', array(
            'label' => esc_html__('Check to exclude slider post from Homepage posts', 'travelify'),
            'section' => 'travelify_post_slider_options',
            'type' => 'checkbox',
            'settings' => 'travelify_theme_options[exclude_slider_post]'
        ));

        $wp_customize->add_setting( 'travelify_theme_options[featured_post_slider]', array(
            'default' => $travelify_theme_options_defaults['featured_post_slider'],
            'type'    => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_slider',
            'transport' => 'postMessage'
        ) );
        $wp_customize->add_control(
        new Travelify_Featured_Slider_Custom_Control(
        $wp_customize,
            'travelify_theme_options[featured_post_slider]', array(
            'label' => esc_html__( 'Number of slides', 'travelify' ),
            'section' => 'travelify_post_slider_options',
            'settings'    => 'travelify_theme_options[featured_post_slider]',
            'type'  => 'featured-slider'
        )
        ));

    $wp_customize->add_section('travelify_slide_effect_options', array(
        'priority' => 60,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Slider Options', 'travelify'),
        'panel' => 'travelify_slider_options'
    ));
        $wp_customize->add_setting('travelify_theme_options[disable_slider]', array(
            'default' => 0,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_checkbox'
        ));
        $wp_customize->add_control('travelify_theme_options[disable_slider]', array(
            'label' => esc_html__('Check to disable Slider', 'travelify'),
            'section' => 'travelify_slide_effect_options',
            'type' => 'checkbox',
            'settings' => 'travelify_theme_options[disable_slider]'
        ));

        $wp_customize->add_setting('travelify_theme_options[transition_effect]', array(
            'default' => '',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_nohtml'
        ));
        $wp_customize->add_control('travelify_theme_options[transition_effect]', array(
            'label' => esc_html__('Transition Effect', 'travelify'),
            'section' => 'travelify_slide_effect_options',
            'type'    => 'select',
            'choices'    => array(
                'fade'          =>  esc_html__( 'Fade', 'travelify' ),
                'wipe'          =>  esc_html__( 'Wipe', 'travelify' ),
                'scrollUp'      =>  esc_html__( 'ScrollUp', 'travelify' ),
                'scrollDown'    =>  esc_html__( 'ScrollDown', 'travelify' ),
                'scrollLeft'    =>  esc_html__( 'ScrollLeft', 'travelify' ),
                'scrollRight'   =>  esc_html__( 'ScrollRight', 'travelify' ),
                'blindX'        =>  esc_html__( 'BlindX', 'travelify' ),
                'blindY'        =>  esc_html__( 'BlindY', 'travelify' ),
                'blindZ'        =>  esc_html__( 'BlindZ', 'travelify' ),
                'cover'         =>  esc_html__( 'Cover', 'travelify' ),
                'shuffle'       =>  esc_html__( 'Shuffle', 'travelify' ),
            ),
        ));

        $wp_customize->add_setting('travelify_theme_options[transition_delay]', array(
            'default' => '',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_number'
        ));
        $wp_customize->add_control('travelify_theme_options[transition_delay]', array(
            'label' => esc_html__('Transition delay( in second(s) )', 'travelify'),
            'section' => 'travelify_slide_effect_options',
        ));

        $wp_customize->add_setting('travelify_theme_options[transition_duration]', array(
            'default' => '',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_number'
        ));
        $wp_customize->add_control('travelify_theme_options[transition_duration]', array(
            'label' => esc_html__('Transition length (in second(s))', 'travelify'),
            'section' => 'travelify_slide_effect_options',
        ));

    /* Social Links Settings Panel */
    $wp_customize->add_section('travelify_social_url_options', array(
        'priority' => 17,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Travelify Social Links', 'travelify'),
        'description' => esc_html__('Enter URLs for your social networks e.g.', 'travelify') . 'https://twitter.com/colorlib'
    ));

        $social_links = array(
            'social_facebook'   => esc_html__( 'Facebook', 'travelify'),
            'social_twitter'    => esc_html__( 'Twitter', 'travelify'),
            'social_pinterest'  => esc_html__( 'Pinterest', 'travelify'),
            'social_youtube'    => esc_html__( 'YouTube', 'travelify'),
            'social_vimeo'      => esc_html__( 'Vimeo', 'travelify'),
            'social_linkedin'   => esc_html__( 'LinkedIn', 'travelify'),
            'social_flickr'     => esc_html__( 'Flickr', 'travelify'),
            'social_tumblr'     => esc_html__( 'Tumblr', 'travelify'),
            'social_instagram'  => esc_html__( 'Instagram', 'travelify'),
            'social_rss'        => esc_html__( 'RSS', 'travelify'),
            'social_github'     => esc_html__( 'GitHub', 'travelify'),
        );
        foreach ($social_links as $key => $val) {

            $wp_customize->add_setting('travelify_theme_options[' . $key . ']', array(
                'default' => '',
                'type' => 'option',
                'capability' => 'edit_theme_options',
                'transport' => 'postMessage',
                'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control('travelify_theme_options[' . $key . ']', array(
                'label' => $val,
                'section' => 'travelify_social_url_options',
                'settings' => 'travelify_theme_options[' . $key . ']',
                'type' => 'text'
            ));
        }

    /* Other options Section */
    // We don't need this section as there is and Additional CSS default by wordpress
    // @todo: delete commented lines after we migrated the Custom CSS code into WP default Additional CSS
    // @todo: but first lets see if there are any problems
    /*$wp_customize->add_section('travelify_others_options', array(
        'priority' => 19,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Travelify Other Options', 'travelify'),
        'description' => esc_html__('Enter your custom CSS styles.', 'travelify')
    ));
        $wp_customize->add_setting('travelify_theme_options[custom_css]', array(
            'default' => '',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'travelify_sanitize_strip_slashes'
        ));
        $wp_customize->add_control('travelify_theme_options[custom_css]', array(
            'label' => esc_html__('This CSS will overwrite the CSS of style.css file.', 'travelify'),
            'section' => 'travelify_others_options',
            'settings' => 'travelify_theme_options[custom_css]',
            'type' => 'textarea'
        ));*/

    $wp_customize->add_section('travelify_important_links', array(
        'priority' => 6,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Travelify Important Links', 'travelify'),
    ));
        $wp_customize->add_setting('travelify_theme_options[imp_links]', array(
          'capability' => 'edit_theme_options',
          'sanitize_callback' => 'esc_url_raw'
       ));
        $wp_customize->add_control(
        new Travelify_Important_Links(
        $wp_customize,
            'travelify_theme_options[imp_links]', array(
            'section' => 'travelify_important_links',
            'type' => 'travelify-important-links'
        )));

    /*
     * Every colour previews without a reload; library/js/customizer.js keeps a
     * <style> block in step with them.
     *
     * The logo, the header display mode and the sidebar layout deliberately
     * stay on the default refresh transport: their preview handlers used to
     * rebuild the header and shuffle #primary/#secondary around by hand, which
     * drifted out of step with the templates.
     */
    $travelify_live_preview = array(
        'travelify_menu_color',
        'travelify_menu_hover_color',
        'travelify_menu_item_color',
        'travelify_entry_color',
        'travelify_element_color',
        'travelify_element_hover_color',
        'travelify_logo_color',
        'travelify_logo_hover_color',
        'travelify_header_color',
        'travelify_wrapper_color',
        'travelify_content_bg_color',
        'travelify_social_color',
        'travelify_link_color',
        'travelify_link_hover_color',
    );

    foreach ( $travelify_live_preview as $travelify_setting ) {
        $wp_customize->get_setting( $travelify_setting )->transport = 'postMessage';
    }

    $wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '#site-title a',
                'render_callback' => 'travelify_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '#site-description',
                'render_callback' => 'travelify_customize_partial_blogdescription',
            )
        );
    }
}

/**
 * Render the site title for the selective refresh partial.
 */
function travelify_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function travelify_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Adds sanitization callback function: colors
 * @package Travelify
 */
function travelify_sanitize_hexcolor( $color ) {
    $unhashed = sanitize_hex_color_no_hash( $color );

    if ( $unhashed ) {
        return '#' . $unhashed;
    }

    // Never hand back the raw input: an invalid colour becomes no colour.
    return '';
}

/**
 * Re-validate a stored colour at output time.
 *
 * Theme mods saved before the sanitizer was tightened may hold arbitrary text,
 * which would otherwise be printed straight into the wp_head <style> block.
 *
 * @param string $mod     Theme mod name.
 * @param string $default Fallback colour, used when the stored value is unusable.
 * @return string A valid hex colour.
 */
function travelify_css_color( $mod, $default = '' ) {
    $color = travelify_sanitize_hexcolor( get_theme_mod( $mod, $default ) );

    if ( '' === $color ) {
        $color = sanitize_hex_color( $default );
    }

    return (string) $color;
}

/**
 * Adds sanitization callback function: text
 * @package Travelify
 */
function travelify_sanitize_text($input) {
    return wp_kses_post(force_balance_tags($input));
}

/**
 * Adds sanitization callback function: Number
 * @package Travelify
 */
function travelify_sanitize_number( $input ) {
    if ( is_numeric( $input ) ) {
        return abs( (float) $input );
    }

    // Never return null: it would be stored and then used in arithmetic.
    return 0;
}

/**
 * Adds sanitization callback function: Strip Slashes
 * @package Travelify
 */
function travelify_sanitize_strip_slashes($input) {
    return wp_kses_stripslashes($input);
}

/**
 * Adds sanitization callback function: Nohtml
 * @package Travelify
 */
function travelify_sanitize_nohtml($input) {
    return wp_filter_nohtml_kses($input);
}

/**
 * Adds sanitization callback function: Checkbox
 * @package Travelify
 */
function travelify_sanitize_checkbox( $input ) {
    $output = ( $input ) ? '1' : false;
    return $output;
}

/**
 * Adds sanitization callback function: Radio Header
 * @package Travelify
 */
function travelify_sanitize_radio_header( $input ) {
    $valid = array(
        'header-logo'  => esc_html__( 'Header Logo Only', 'travelify' ),
        'header-text'  => esc_html__( 'Header Text Only', 'travelify' ),
        'disable-both' => esc_html__( 'Disable', 'travelify' ) );
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Radio Layout
 * @package Travelify
 */
function travelify_sanitize_radio_layout( $input ) {
    $valid = array(
        'no-sidebar'            => esc_html__( 'No Sidebar', 'travelify' ),
        'no-sidebar-full-width' => esc_html__( 'No Sidebar, Full Width', 'travelify' ),
        'no-sidebar-one-column' => esc_html__( 'No Sidebar, One Column', 'travelify' ),
        'left-sidebar'          => esc_html__( 'Left Sidebar', 'travelify' ),
        'right-sidebar'         => esc_html__( 'Right Sidebar', 'travelify' )
    );
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Multiselect
 * @package Travelify
 */
function travelify_sanitize_multiselect( $values ) {
    // The only thing this setting ever holds is a list of category IDs.
    $multi_values = is_array( $values ) ? $values : explode( ',', (string) $values );

    return array_values( array_map( 'absint', $multi_values ) );
}

/**
 * Adds sanitization callback function: Custom Slider
 * @package Travelify
 */
function travelify_sanitize_slider( $values ) {
    $output = array();

    $slider_values = is_array( $values ) ? $values : json_decode( (string) $values );

    if ( ! is_array( $slider_values ) ) {
        return $output;
    }

    $i = 1;
    foreach ( $slider_values as $val ) {
        // Slides are post IDs and nothing else.
        $id = absint( $val );
        if ( $id > 0 ) {
            $output[ $i ] = $id;
            $i++;
        }
    }

    return $output;
}

/**
 * Output custom CSS in theme header
 * @package Travelify
 */
function travelify_customizer_css() {

    // Every colour is re-validated here; see travelify_css_color().
    $link_color         = travelify_css_color( 'travelify_link_color', '#57AD68' );
    $link_hover_color   = travelify_css_color( 'travelify_link_hover_color', '#439f55' );
    $logo_color         = travelify_css_color( 'travelify_logo_color', '#57ad68' );
    $logo_hover_color   = travelify_css_color( 'travelify_logo_hover_color', '#439f55' );
    $wrapper_color      = travelify_css_color( 'travelify_wrapper_color', '#F8F8F8' );
    $social_color       = travelify_css_color( 'travelify_social_color', '#d0d0d0' );
    $menu_color         = travelify_css_color( 'travelify_menu_color', '#57ad68' );
    $menu_hover_color   = travelify_css_color( 'travelify_menu_hover_color', '#439f55' );
    $menu_item_color    = travelify_css_color( 'travelify_menu_item_color', '#fff' );
    $content_bg_color   = travelify_css_color( 'travelify_content_bg_color', '#fff' );
    $header_color       = travelify_css_color( 'travelify_header_color', '#1b1e1f' );
    $entry_color        = travelify_css_color( 'travelify_entry_color', '#1D1D1D' );
    $element_color      = travelify_css_color( 'travelify_element_color', '#57ad68' );
    $element_hover      = travelify_css_color( 'travelify_element_hover_color', '#439f55' );
    ?>
    <style id="travelify-customizer-css">
        a { color: <?php echo esc_attr( $link_color ); ?>; }
        #site-title a { color: <?php echo esc_attr( $logo_color ); ?>; }
        #site-title a:hover, #site-title a:focus  { color: <?php echo esc_attr( $logo_hover_color ); ?>; }
        .wrapper { background: <?php echo esc_attr( $wrapper_color ); ?>; }
        .social-icons ul li a { color: <?php echo esc_attr( $social_color ); ?>; }
		#main-nav a,
		#main-nav a:hover,
		#main-nav a:focus,
		#main-nav ul li.current-menu-item a,
		#main-nav ul li.current_page_ancestor a,
		#main-nav ul li.current-menu-ancestor a,
		#main-nav ul li.current_page_item a,
		#main-nav ul li:hover > a,
		#main-nav ul li:focus-within > a { color: <?php echo esc_attr( $menu_item_color ); ?>; }
        .widget, article { background: <?php echo esc_attr( $content_bg_color ); ?>; }
        .entry-title, .entry-title a, .entry-title a:focus, h1, h2, h3, h4, h5, h6, .widget-title  { color: <?php echo esc_attr( $header_color ); ?>; }
		a:focus,
		a:active,
		a:hover,
		.tags a:hover,
		.tags a:focus,
		.custom-gallery-title a,
		.widget-title a,
		#content ul a:hover,
		#content ul a:focus,
		#content ol a:hover,
		#content ol a:focus,
		.widget ul li a:hover,
		.widget ul li a:focus,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-meta a:hover,
		.entry-meta a:focus,
		#site-generator .copyright a:hover,
		#site-generator .copyright a:focus { color: <?php echo esc_attr( $link_hover_color ); ?>; }
        #main-nav { background: <?php echo esc_attr( $menu_color ); ?>; border-color: <?php echo esc_attr( $menu_color ); ?>; }
        #main-nav ul li ul, body { border-color: <?php echo esc_attr( $menu_color ); ?>; }
		#main-nav a:hover,
		#main-nav a:focus,
		#main-nav ul li.current-menu-item a,
		#main-nav ul li.current_page_ancestor a,
		#main-nav ul li.current-menu-ancestor a,
		#main-nav ul li.current_page_item a,
		#main-nav ul li:hover > a,
		#main-nav ul li:focus-within > a,
		#main-nav li:hover > a,
		#main-nav li:focus-within > a,
		#main-nav ul ul :hover > a,
		#main-nav ul ul :focus-within > a,
		#main-nav a:focus { background: <?php echo esc_attr( $menu_hover_color ); ?>; }
		#main-nav ul li ul li a:hover,
		#main-nav ul li ul li a:focus,
		#main-nav ul li ul li:hover > a,
		#main-nav ul li ul li:focus-within > a,
		#main-nav ul li.current-menu-item ul li a:hover,
		#main-nav ul li.current-menu-item ul li a:focus { color: <?php echo esc_attr( $menu_hover_color ); ?>; }
        .entry-content { color: <?php echo esc_attr( $entry_color ); ?>; }
		input[type="reset"],
		input[type="button"],
		input[type="submit"],
		.entry-meta-bar .readmore,
		#controllers a:hover,
		#controllers a.active,
		.pagination span,
		.pagination a:hover span,
		.pagination a:focus span,
		.wp-pagenavi .current,
		.wp-pagenavi a:hover,
		.wp-pagenavi a:focus {
            background: <?php echo esc_attr( $element_color ); ?>;
            border-color: <?php echo esc_attr( $element_color ); ?> !important;
        }
		::selection,
		.back-to-top:focus-within a { background: <?php echo esc_attr( $element_color ); ?>; }
        blockquote { border-color: <?php echo esc_attr( $element_color ); ?>; }
		#controllers a:hover,
		#controllers a.active { color: <?php echo esc_attr( $element_color ); ?>; }
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="button"]:hover,
		input[type="button"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus,
		input[type="reset"]:active,
		input[type="button"]:active,
		input[type="submit"]:active,
		.entry-meta-bar .readmore:hover,
		.entry-meta-bar .readmore:focus,
		.entry-meta-bar .readmore:active,
		ul.default-wp-page li a:hover,
		ul.default-wp-page li a:focus,
		ul.default-wp-page li a:active {
            background: <?php echo esc_attr( $element_hover ); ?>;
            border-color: <?php echo esc_attr( $element_hover ); ?>;
        }
    </style>
    <?php
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 * @package Travelify
 */
function travelify_customize_preview_js() {
    wp_enqueue_script( 'travelify_customizer', get_template_directory_uri() . '/library/js/customizer.js', array( 'customize-preview' ), TRAVELIFY_VERSION, true );
}


/**
 * Validate all theme options values
 *
 * @uses esc_url_raw, absint, esc_textarea, sanitize_text_field, travelify_invalidate_caches
 */
function travelify_theme_options_validate( $options ) {
	global $travelify_theme_options_settings, $travelify_theme_options_defaults;
	$input           = is_array( $options ) ? $options : array();
	$input_validated = $input;

	if ( isset( $input['featured_post_slider'] ) && is_array( $input['featured_post_slider'] ) ) {
		$slide_count = count( $input['featured_post_slider'] );

		// Slider settings updation
		$input_validated['slider_quantity'] = $slide_count > 0 ? $slide_count : 3;
	}

	// Layout settings verification
	$reset_layout = isset( $input['reset_layout'] ) ? $input['reset_layout'] : 0;

	if ( isset( $input['reset_layout'] ) ) {
		$input_validated['reset_layout'] = 0;
	}

	if ( ! $reset_layout ) {
		if ( isset( $input['default_layout'] ) ) {
			$input_validated['default_layout'] = $input['default_layout'];
		}
	} else {
		$input_validated['default_layout'] = $travelify_theme_options_defaults['default_layout'];
	}

        //Clearing the theme option cache
	    if( function_exists( 'travelify_themeoption_invalidate_caches' ) ) travelify_themeoption_invalidate_caches();

   return $input_validated;
}

/**
 * Clearing the cache if any changes in Customizer
 */
function travelify_themeoption_invalidate_caches(){
	delete_transient( 'travelify_featured_post_slider' );
	delete_transient( 'travelify_socialnetworks' );
	delete_transient( 'travelify_footercode' );
	delete_transient( 'travelify_internal_css' );
	delete_transient( 'travelify_headercode' );
}

/**
 * Clearing the cache if any changes in post or page
 */
function travelify_post_invalidate_caches(){
   delete_transient( 'travelify_featured_post_slider' );
}

/**
 * Register options and validation callbacks
 *
 * @uses register_setting
 */
function travelify_register_settings() {
   register_setting( 'travelify_theme_options', 'travelify_theme_options', 'travelify_theme_options_validate' );
}

add_action('customize_register', 'travelify_options_register_theme_customizer');
add_action('wp_head', 'travelify_customizer_css');
add_action('customize_preview_init', 'travelify_customize_preview_js');
add_action( 'admin_init', 'travelify_register_settings' );
add_action( 'save_post', 'travelify_post_invalidate_caches' );
?>