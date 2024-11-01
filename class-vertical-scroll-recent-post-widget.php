<?php
/**
 * Adds Vertical_Recent_Post_Widget widget.
 */
class Vertical_Recent_Post_Display {

    public static function vsrp( $instance ) {
        global $wpdb;
        wp_enqueue_style( 'vsrp_css' );
        wp_enqueue_script( 'vsrp_js' );

        $error = false;
        $scrollings = get_option( 'vsrp_scrollings' );
		
        $scrollings = maybe_unserialize( $scrollings );
        $id = $instance[ 'vsrp_id' ];
		
        if ( !isset( $scrollings[ $id ] ) ) {
            $error = true;
        } else {
            $vsrp_title = $scrollings[ $id ][ 'vsrp_title' ];
            $dis_num_height = $scrollings[ $id ][ 'vsrp_dis_num_height' ];
            $vsrp_title_length = $scrollings[ $id ][ 'vsrp_title_length' ];
            $dis_num_user = $scrollings[ $id ][ 'vsrp_dis_num_user' ];
            $num_user = $scrollings[ $id ][ 'vsrp_select_num_user' ];
            $vsrp_select_categories = $scrollings[ $id ][ 'vsrp_select_categories' ];
            $vsrp_select_orderby = $scrollings[ $id ][ 'vsrp_select_orderby' ];
            $vsrp_select_order = $scrollings[ $id ][ 'vsrp_select_order' ];
            $vsrp_show_date = $scrollings[ $id ][ 'vsrp_show_date' ];
            $vsrp_date_format = $scrollings[ $id ][ 'vsrp_date_format' ];
            $vsrp_show_category_link = $scrollings[ $id ][ 'vsrp_show_category_link' ];
            $vrsp_show_thumb = $scrollings[ $id ][ 'vrsp_show_thumb' ];
            $vsrp_speed = $scrollings[ $id ][ 'vsrp_speed' ];
            $vsrp_seconds = $scrollings[ $id ][ 'vsrp_seconds' ];
            $vsrp_reverse = $scrollings[ $id ][ 'vsrp_reverse' ];

            if( !is_numeric( $num_user ) ) {
                $num_user = 5;
            } 
            if( !is_numeric( $dis_num_height ) ) {
                $dis_num_height = 30;
            }
            if( !is_numeric( $dis_num_user ) ) {
                $dis_num_user = 5;
            }
            $args = array(
                'posts_per_page'   => $num_user,
                'category__in'     => explode( ',', $vsrp_select_categories ),
                'orderby'          => $vsrp_select_orderby,
                'order'            => $vsrp_select_order
                );
            $vsrp_data = get_posts( $args );
						
            $vsrp_list = "";
            if ( !empty( $vsrp_data ) ) {
                $vsrp_count = 0;
                foreach ( $vsrp_data as $vsrp_data ) {
                    
					// Starting each post's div
                    $vsrp_list .= '<div class="vsrp_div" style="height: ' . esc_html($dis_num_height) . 'px;">';
                    
					// Post's thumbnail
                    if ( $vrsp_show_thumb ) {
                        $vsrp_list .= get_the_post_thumbnail( $vsrp_data->ID, array( $dis_num_height, $dis_num_height ), array( 'class' => 'vsrp_thumb' ) );
                    }
                    
					// Post's title
                    $vsrp_post_title = $vsrp_data->post_title;
                    if ( strlen( $vsrp_post_title ) > $vsrp_title_length ) {
                        $vsrp_post_title = mb_substr( $vsrp_post_title, 0, $vsrp_title_length, "UTF-8" );
                        $vsrp_post_title .= '...';
                    }
                    
					// Post it self
                    $vsrp_link = get_permalink( $vsrp_data->ID );
                    $vsrp_list .= "<a href='$vsrp_link'>" . esc_html($vsrp_post_title) . "</a>";
                    
					// Post's date
                    if ( $vsrp_show_date ) {
                        $vsrp_post_date = date( $vsrp_date_format, strtotime( $vsrp_data->post_date ) );
                        $vsrp_list .= "<span class='vrsp_date'> -- " . esc_html($vsrp_post_date) . "</span>";
                    }
                    
					// Ending of post's div
                    $vsrp_list .= '</div>';
                    $vsrp_count++;
                }

                $vsrp_count = ( $vsrp_count >= $dis_num_user ) ? $dis_num_user : $vsrp_count;        
                $whole_height = ( ( $dis_num_height + 8 ) * $vsrp_count );
                $html = '<div style="height: ' . esc_html($whole_height) . 'px;" class="vsrp_wrapper" ';
				if( !is_numeric( $vsrp_seconds ) ) {
					$vsrp_seconds = 3;
				} 
				if( !is_numeric( $vsrp_speed ) ) {
					$vsrp_speed = 2;
				} 
                $html .= ' data-delay-seconds="' . intval($vsrp_seconds) . '" data-speed="' . intval($vsrp_speed) . '" data-direction="' . esc_html($vsrp_reverse) . '">';
				$html .= $vsrp_list;
				$html .= '</div>';
                if ( $vsrp_show_category_link ) {
                    $html .= '<span id="vsrp_category_link"><a href=\"?cat=' . esc_html($vsrp_select_categories) . '\">';
                    $html .= __( 'Show all of ', 'vertical-scroll-recent-post') . esc_html($vsrp_title);
                    $html .= '</a></span>';
                }
            } 
			else {
                $error = true;
            }
        }
		
        if ( $error ) {
            $html = "<div class=\"vsrp_error\">VSRP: " . __( 'No data available', 'vertical-scroll-recent-post' ) . "</div>";
		}
		
        return $html;
    }

}
?>