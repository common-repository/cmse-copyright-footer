<?php
/**
Plugin Name: CMSE Copyright Footer
Plugin URI: http://cmsenergizer.com
Description: Add a footer copyright to the frontend with start and current year. Can also add plain text and HTML below and / or above the copyright
Version: 1.0.0
Author: Cmsenergizer.com
Author URI: http://cmsenergizer.com
License: GPL2
*/

class cmse_copyright_footer_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'cmse_copyright_footer_widget',
			__('CMSE Copyright Footer', 'cmse-copyright-footer'),
			array( 'description' => __( 'Add copyright to bottom of pages', 'cmse-copyright-footer' ), )
		);
	}

	//------------[ Frontend output construction ]----------------//
	function widget( $args, $instance ) {
		extract($args);
		$startyear = apply_filters( 'widget_title', empty( $instance['startyear'] ) ? '' : $instance['startyear'], $instance, $this->id_base );
		$pretext = apply_filters( 'widget_text', empty( $instance['pretext'] ) ? '' : $instance['pretext'], $instance );
		$posttext = apply_filters( 'widget_text', empty( $instance['posttext'] ) ? '' : $instance['posttext'], $instance );

		echo $before_widget;
		if ( !empty($pretext) ) {echo '<div class="textwidget copyright-pretext">' . $pretext . '</div>';}
		if ( !empty( $startyear ) ) {echo '<p>Copyright ' . $startyear . ' - ' . date('Y') . ' ' . get_bloginfo() . '. All rights reserved.</p>';}
		if ( !empty($posttext) ) {echo '<div class="textwidget copyright-posttext">' . $posttext . '</div>';}
		echo $after_widget;
	}

	//-----------[ Widget Administration ]----------------//

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['startyear'] = strip_tags($new_instance['startyear']);
		if ( current_user_can('unfiltered_html') ) {
			$instance['pretext'] =  $new_instance['pretext']; $instance['posttext'] =  $new_instance['posttext']; }
				else {
			$instance['pretext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['pretext']) ) );
			$instance['posttext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['posttext']) ) );
		}
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'startyear' => '', 'pretext' => '', 'posttext' => '' ) );
		$startyear = strip_tags($instance['startyear']);
		$pretext = esc_textarea($instance['pretext']);
		$posttext = esc_textarea($instance['posttext']);

		?>
		<p><label for="<?php echo $this->get_field_id( 'startyear' ); ?>"><?php _e( 'Enter the starting year', 'cmse-copyright-footer' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'startyear' ); ?>" name="<?php echo $this->get_field_name( 'startyear' ); ?>" type="text" value="<?php echo esc_attr( $startyear ); ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'pretext' ); ?>"><?php _e( 'Optional text to display befor copyright', 'cmse-copyright-footer' ); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('pretext'); ?>" name="<?php echo $this->get_field_name('pretext'); ?>"><?php echo $pretext; ?></textarea>

		<label for="<?php echo $this->get_field_id( 'posttext' ); ?>"><?php _e( 'Optional text to display below copyright', 'cmse-copyright-footer' ); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('posttext'); ?>" name="<?php echo $this->get_field_name('posttext'); ?>"><?php echo $posttext; ?></textarea>

	<?php
	}


} // Class cmse_copyright_footer_widget

//--------------[ Register and output the widget on frontend ]---------------//
function cmse_copyright_footer_load_widget() {
	register_widget( 'cmse_copyright_footer_widget' );
}
add_action( 'widgets_init', 'cmse_copyright_footer_load_widget' );
