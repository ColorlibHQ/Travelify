<?php

if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
/**
 * Multi Select category customize control class.
 *
 * @access public
 */
class Travelify_Customize_Control_Multi_Select_Category extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     *
     * @access public
     * @var    string
     */
    public $type = 'multi-select-cat';

    /**
     * Displays the control content.
     *
     * @access public
     * @return void
     */
    public function render_content() {
        $options = is_array( $this->value() ) ? $this->value() : explode( ',', (string) $this->value() );
        $options = array_map( 'absint', $options );
        ?>

        <label for="frontpage_posts_cats"><b><?php esc_html_e( 'Homepage posts categories:', 'travelify' ); ?></b></label>
        <small><?php esc_html_e( 'Only posts that belong to the categories selected here will be displayed on the front page.', 'travelify' ); ?></small><br><br>

        <select <?php $this->link(); ?> name="travelify_theme_options[front_page_category][]" id="frontpage_posts_cats" multiple="multiple" class="select-multiple" style="width: 100%;">
            <option value="0" <?php selected( empty( $options ) ); ?>><?php esc_html_e( '--Disabled--', 'travelify' ); ?></option>
            <?php foreach ( get_categories() as $category ) : ?>
                <option value="<?php echo absint( $category->cat_ID ); ?>" <?php selected( in_array( (int) $category->cat_ID, $options, true ) ); ?>><?php echo esc_html( $category->cat_name ); ?></option>
            <?php endforeach; ?>
        </select><br />

        <?php if ( ! empty( $this->description ) ) : ?>
            <span class="description"><?php echo esc_html( $this->description ); ?></span>
        <?php endif; ?>
    <?php }
}

/**
 * Class to create a custom layout control
 */
class Travelify_Layout_Picker_Custom_Control extends WP_Customize_Control
{

    /**
     * Declare the control type.
     *
     * @access public
     * @var string
     */
    public $type = 'radio-image';

     /**
      * Render the control to be displayed in the Customizer.
      */
     public function render_content() {
             if ( empty( $this->choices ) ) {
                     return;
             }

             $name = $this->id;
             $images = array(
                        'no-sidebar' 		=> get_template_directory_uri().'/library/panel/images/no-sidebar.png',
                        'no-sidebar-full-width' => get_template_directory_uri().'/library/panel/images/no-sidebar-fullwidth.png',
                        'no-sidebar-one-column'	=> get_template_directory_uri().'/library/panel/images/one-column.png',
                        'left-sidebar' 		=> get_template_directory_uri().'/library/panel/images/left-sidebar.png',
                        'right-sidebar'         => get_template_directory_uri().'/library/panel/images/right-sidebar.png',
            )?>

             <span class="customize-control-title">
                    <?php echo esc_html( $this->label ); ?>
                    <?php if ( ! empty( $this->description ) ) : ?>
                            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                    <?php endif; ?>
             </span>
             <div id="input_<?php echo esc_attr( $this->id ); ?>" class="image">
                     <?php foreach ( $this->choices as $value => $label ) : ?>
                                <?php if ( ! isset( $images[ $value ] ) ) { continue; } ?>
                                <label for="<?php echo esc_attr( $this->id . '[' . $value . ']' ); ?>">
                                    <img src="<?php echo esc_url( $images[ $value ] ); ?>" alt="<?php echo esc_attr( $label ); ?>">
                                    <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo esc_attr( $this->id . '[' . $value . ']' ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                                    <span class="radio-text"><?php echo esc_html( $label ); ?></span>
                                </label>
                     <?php endforeach; ?>
             </div><?php
     }
}

/**
 * Class to create a custom Featured Slider control
 */
class Travelify_Featured_Slider_Custom_Control extends WP_Customize_Control
{
    /**
     * The type of customize control being rendered.
     *
     * @access public
     * @var    string
     */
    public $type = 'featured-slider';

    /**
      * Enqueue scripts and styles for the custom control.
      *
      * @access public
      */
    public function enqueue() {
        wp_enqueue_script(
            'travelify_custom_js',
            get_template_directory_uri() . '/library/js/customizer-slider-control.js',
            array(),
            TRAVELIFY_VERSION,
            true
        );
    }

    /**
     * Render the content on the theme customizer page
     */
    public function render_content() {
        $options = get_option( 'travelify_theme_options' );
        $slides  = isset( $options['featured_post_slider'] ) && is_array( $options['featured_post_slider'] ) ? $options['featured_post_slider'] : array();

        $slider_count = max( 3, count( $slides ) );
        ?>
           <div id="featuredslider">
               <h3><?php esc_html_e( 'Featured Slider', 'travelify' ); ?></h3>
               <ul class="featured-slider-sortable clone-wrapper">
                    <?php for ( $i = 1; $i <= $slider_count; $i++ ) : ?>
                        <?php $slide_id = isset( $slides[ $i ] ) ? absint( $slides[ $i ] ) : 0; ?>
                        <li class="toclone">
                            <label class="handle customize-control-title"><?php esc_html_e( 'Slide #', 'travelify' ); ?><span class="count"><?php echo absint( $i ); ?></span></label>
                            <input class="featured_post_slider" size="7" type="text" inputmode="numeric" name="travelify_theme_options[featured_post_slider][<?php echo absint( $i ); ?>]" value="<?php echo $slide_id ? absint( $slide_id ) : ''; ?>" />
                            <a href="<?php echo esc_url( add_query_arg( array( 'post' => $slide_id, 'action' => 'edit' ), admin_url( 'post.php' ) ) ); ?>" class="button slider_edit" title="<?php esc_attr_e( 'Edit', 'travelify' ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons-before dashicons-edit"></span></a>

                            <a href="#" class="clone button-primary" aria-label="<?php esc_attr_e( 'Add slide', 'travelify' ); ?>">+</a>
                            <a href="#" class="delete button" aria-label="<?php esc_attr_e( 'Remove slide', 'travelify' ); ?>">-</a>
                        </li>
                    <?php endfor; ?>
                </ul>
                <?php $value = $slides ? wp_json_encode( array_map( 'absint', $slides ) ) : ''; ?>
                <input id="featured_slider" type="hidden" name="travelify_theme_options[featured_post_slider]" <?php $this->link(); ?> value="<?php echo esc_attr( $value ); ?>"><br>

                <p><strong><?php esc_html_e( 'How to use the featured slider?', 'travelify' ); ?></strong></p>
                <ul class="slider-note">
                        <li><?php esc_html_e( 'Create Post or Page and add featured image to it.', 'travelify' ); ?></li>
                        <li><?php esc_html_e( 'Add all the Post ID that you want to use in the featured slider. Post ID can be found at All Posts table in last column', 'travelify' ); ?></li>
                        <li><?php esc_html_e( 'Featured Slider will show featured images, Title and excerpt of the respected added post IDs.', 'travelify' ); ?></li>
                        <li><?php esc_html_e( 'The recommended image size is', 'travelify' ); ?><strong> 1018px x 460px.</strong></li>
                </ul>
            </div><!-- .featured-slider -->
<?php
    }
}

/**
 * Class to create a Travelify important links
 */
class Travelify_Important_Links extends WP_Customize_Control {

   public $type = "travelify-important-links";

   public function render_content() {
      /*
       * Every destination here is the URL that actually serves the page, not
       * one that redirects to it:
       *   - colorlib.com/wp/forums/ moved to colorlibsupport.com
       *   - wordpress.org/support/view/theme-reviews/{slug} is the pre-2015
       *     layout and redirects to /support/theme/{slug}/reviews/
       *   - twitter.com no longer answers; x.com does
       * "Rate this Theme" was also listed twice, once over plain http and
       * once pre-filtered to five-star reviews.
       */
      $important_links = array(
         'other_themes'      => array(
            'link' => 'https://colorlib.com/wp/themes/',
            'text' => __( 'Other Themes', 'travelify' ),
         ),
         'theme_instruction' => array(
            'link' => 'https://colorlib.com/wp/support/travelify/',
            'text' => __( 'Theme Instructions', 'travelify' ),
         ),
         'support'           => array(
            'link' => 'https://colorlibsupport.com/',
            'text' => __( 'Support', 'travelify' ),
         ),
         'rate'              => array(
            'link' => 'https://wordpress.org/support/theme/travelify/reviews/',
            'text' => __( 'Rate this Theme', 'travelify' ),
         ),
         'facebook'          => array(
            'link' => 'https://www.facebook.com/colorlib',
            'text' => __( 'Facebook', 'travelify' ),
         ),
         'twitter'           => array(
            'link' => 'https://x.com/colorlib',
            'text' => __( 'Twitter', 'travelify' ),
         ),
      );

      foreach ( $important_links as $important_link ) {
         echo '<p><a target="_blank" rel="noopener noreferrer" href="' . esc_url( $important_link['link'] ) . '">' . esc_html( $important_link['text'] ) . '</a></p>';
      }
   }

}

add_action( 'customize_controls_enqueue_scripts', 'travelify_customizer_custom_control_css' );
/**
 * Styles for the theme's custom Customizer controls.
 *
 * A stylesheet rather than an inline <style> block so it can be cached and
 * overridden, and so the rules are readable.
 */
function travelify_customizer_custom_control_css() {
	wp_enqueue_style(
		'travelify-customizer-controls',
		get_template_directory_uri() . '/library/css/customizer.css',
		array(),
		TRAVELIFY_VERSION
	);
}