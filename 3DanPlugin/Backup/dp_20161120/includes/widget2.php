<?php
/*
Plugin Name: DP Bike 2
Plugin URI: http://mydomain.com
Description: Cycling overall results Goal vs Run.
Author: Dan Pitu
Version: 1.0
Author URI: http://mydomain.com
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');



add_action( 'widgets_init', 'dpWidget2_registration');	

function dpWidget2_registration(){
     register_widget( 'dp_Widget2' );
}

/**
 * Adds My_Widget widget.
 */
class dp_Widget2 extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'dp_Widget2', // Base ID
			__('Cycle Widget', 'text_domain'), // Name
			array( 'description' => __( 'Cycle widget!', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'dp3';
		$admin_goal = '';

     		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		if ( ! empty( $instance['goal'] ) ) {
			//if not empty admin goal set at widget level will overwrite the calculated goal
			$dp3_goal = apply_filters( 'widget_goal', $instance['goal'] );
		}
		else {
			$arrInfo = $wpdb->get_results( "SELECT sum(distance) as total FROM $table_name where type='goal'" );
			$nrows = $wpdb->num_rows;
			if($nrows>0) {
				$row_info = $arrInfo[0];
				$dp3_goal = $row_info->total;
			}
			else
				$dp3_goal = 'Unset Goal';
		}

		if ( ! empty( $instance['run'] ) ) {
			//if not empty admin run
			$dp3_entries = apply_filters( 'widget_run', $instance['run'] );
		}
		else {
			$arrInfo = $wpdb->get_results( "SELECT sum(distance) as total FROM $table_name where type='entry'" );
			$nrows = $wpdb->num_rows;
			if($nrows>0) {
				$row_info = $arrInfo[0];
				$dp3_entries = $row_info->total;
			}
			else
				$dp3_entries = 'No Entries';
		}

		$pPluginContent = plugins_url( 'includes/iphone_white.txt', dirname( __FILE__ ) );
		$contentPage = file_get_contents($pPluginContent);

		$pPluginUrl = plugins_url( '', dirname( __FILE__ ) );
		$contentPage = str_replace('##goal##', $dp3_goal, $contentPage );
		$contentPage = str_replace('##run##', $dp3_entries, $contentPage );
		$contentPage = str_replace('##pluginurl##', $pPluginUrl, $contentPage );

		echo __( $contentPage, 'text_domain' );

		//echo __( '<div class="dp3_widget_1">Goal: <b>'.$dp3_goal.' km</b><br/>Run: <b>'.$dp3_entries.' km</b></div>'.$pPluginCss, 'text_domain' );

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		//TITLE
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 

		//GOAL
		if ( isset( $instance[ 'goal' ] ) ) {
			$goal = $instance[ 'goal' ];
		}
		else {
			$goal = __( 'New goal', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'goal' ); ?>"><?php _e( 'Goal' ); ?> (overwrite or leave empty)</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'goal' ); ?>" name="<?php echo $this->get_field_name( 'goal' ); ?>" type="text" value="<?php echo esc_attr( $goal ); ?>">
		</p>
		<?php
 
		//RUN
		if ( isset( $instance[ 'run' ] ) ) {
			$run = $instance[ 'run' ];
		}
		else {
			$run = __( 'New run', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'run' ); ?>"><?php _e( 'Run' ); ?> (overwrite or leave empty)</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'run' ); ?>" name="<?php echo $this->get_field_name( 'run' ); ?>" type="text" value="<?php echo esc_attr( $run); ?>">
		</p>
		<?php 

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['goal'] = ( ! empty( $new_instance['goal'] ) ) ? strip_tags( $new_instance['goal'] ) : '';
		$instance['run'] = ( ! empty( $new_instance['run'] ) ) ? strip_tags( $new_instance['run'] ) : '';

		return $instance;
	}

} // class dp_Widget2 