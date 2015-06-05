<?php

	/**
	 * Plugin Name: Lisa Westlund Instagram Portfolio
	 * Plugin URI: http://www.lisawestlund.se
	 * Description: A portfolio plugin which gets images and data from a specific Instagram account with pictures tagged with a specific hashtag.
	 * Version: 1.05
	 * Author: Lisa Westlund
	 * Author URI: http://www.lisawestlund.se
	 * License: CC BY
	 */
	 
	//Enqueue scripts and styles
    function wplw_add_styles_and_scripts() {
		wp_enqueue_style( 'wplw_style', plugins_url('/css/wplw_style.css', __FILE__) );
		wp_enqueue_script( 'wplw_gallery_js', plugins_url('lisawestlund-instagram-portfolio/js/wplw_gallery.js')); 
	}
	add_action( 'wp_enqueue_scripts', 'wplw_add_styles_and_scripts', 1 );
	
	function wplw_add_admin_style(){
		wp_register_style( 'wplw_admin_style', plugins_url('lisawestlund-instagram-portfolio/css/wplw_admin_style.css') );
		wp_enqueue_style( 'wplw_admin_style' );
	}
	add_action( 'admin_enqueue_scripts', 'wplw_add_admin_style' );
 
	/* Assign global variables */
	$options = array();
 
 	/* Add a link to plugin in the menu under 'Settings > Instagram Portfolio' */
	function wplw_portfolio_menu(){
		/* Use the add_options_page add_options_page( 
		$page_title, $menu_title, $capability, $menu-slug, $function ) */
		
	 	add_options_page(
			'Lisa Westlund Instagram Portfolio',
			'Instagram Portfolio',
			'manage_options',
			'instagram-portfolio',
			'wplw_instagram_portfolio_options_page'
		);	 
	}
	add_action( 'admin_menu', 'wplw_portfolio_menu' );
 
 	/* Settings Page  */
	function wplw_instagram_portfolio_options_page(){
		if( !current_user_can( 'manage_options' ) ){
			wp_die( 'You do not have sufficient permission to access this page.' );
		}
		
		global $options;
		
		/* Save form input data to $options array */	
				
		if( isset( $_POST['wplw_form_submitted'] ) ){
			
			if( ! isset( $_POST['wplw-update-check'] ) || ! wp_verify_nonce( $_POST['wplw-update-check'], 'my_wplw_update_check' ) ){

				print 'Sorry, your nonce did not verify.';
				exit;

			} 
	
			else{
		
				$hidden_field = esc_html($_POST['wplw_form_submitted']);
				
				if( $hidden_field == 'Y' ){
					$wplw_instagram_access_token = esc_html($_POST['wplw_instagram_access_token']);
					$wplw_instagram_userID = esc_html($_POST['wplw_instagram_userID']);
					$wplw_hashtag = esc_html($_POST['wplw_hashtag']);
									
					if( isset($_POST['wplw_portfolio_date']) ){
						$wplw_portfolio_date = 'checked';	
					}
					
					if( isset($_POST['wplw_portfolio_description']) ){
						$wplw_portfolio_description = 'checked';	
					}
									
					$options = array(
						'wplw_instagram_access_token' => $wplw_instagram_access_token,
						'wplw_instagram_userID' => $wplw_instagram_userID,
						'wplw_hashtag' => $wplw_hashtag,
						'wplw_portfolio_date' => $wplw_portfolio_date,
						'wplw_portfolio_description' => $wplw_portfolio_description
					);
					
					update_option( 'wplw_instagram_portfolio', $options );
				}
			}
		}
			
		$options = get_option( 'wplw_instagram_portfolio' );
		if( $options != '' ){
	
			$wplw_instagram_access_token = $options['wplw_instagram_access_token'];
			$wplw_instagram_userID = $options['wplw_instagram_userID'];
			$wplw_hashtag = $options['wplw_hashtag'];
			$wplw_portfolio_date = $options['wplw_portfolio_date'];
			$wplw_portfolio_description = $options['wplw_portfolio_description'];
		}
		
		require( 'inc/options-page-wrapper.php' ); 
	}
	
	
	//Load content to portfolio page using shortcode
	function wplw_portfolio_init($atts){
		$shortcodeAtts = shortcode_atts(array('count' => 24), $atts);
		$maxNumber = $shortcodeAtts['count'];
		$count = 0;
		
		$options = get_option('wplw_instagram_portfolio');
		$url_access_token = $options['wplw_instagram_access_token'];
		$url_userID = $options['wplw_instagram_userID'];
		$get_feed_url = 'https://api.instagram.com/v1/users/' . $url_userID . '/media/recent?access_token=' . $url_access_token;
		echo '<div id="portfolio-wrapper">';
		wplw_portfolio_get_instagram_data($get_feed_url, $maxNumber, $count);
	}
	add_shortcode('wplw_instagram_portfolio', 'wplw_portfolio_init');
	
	
	//Get data from Instagram
	function wplw_portfolio_get_instagram_data($get_feed_url, $maxNumber, $count){
			
		$result = wp_remote_get($get_feed_url);
		
		if ( is_wp_error( $result ) ) {
			$error_message = $result->get_error_message();
			echo 'Something went wrong: ' . $error_message;
		} 
		
		else {
			//Convert to accossiative array (by adding true) and call render function	
			$resultarray = json_decode($result['body'], true);
			wplw_render_content( $resultarray, $maxNumber, $count );
		}
	}
	
	//Loops through all media in array, and calls the view content function.
	//If optional filter hashtag is set by user, it first calls the serach for hashtag function.
	function wplw_render_content( $content, $maxNumber, $count ){
		$options = get_option('wplw_instagram_portfolio');
		$hashtag = $options['wplw_hashtag'];
			
		if( $hashtag !== '' ){

			foreach( $content['data'] as $one_post_content ){
				$caption = $one_post_content['caption']['text'];
				$output = wplw_search_for_hashtags($caption);
				
				if( $output === true ){
					$count++;
					wplw_view_content( $one_post_content, $maxNumber, $count );
				}
			}	
		}
				
		else{
			foreach( $content['data'] as $one_post_content ){
				$count++;
				wplw_view_content( $one_post_content, $maxNumber, $count );
			}
		}
		
		if( $count < $maxNumber ){		
			$pagination = $content['pagination'];
			
			if( array_key_exists('next_url', $pagination) ){
				$next_url = $content['pagination']['next_url'];
				wplw_portfolio_get_instagram_data( $next_url, $maxNumber, $count );
			}
			
			else{
				echo '</div>';
			}
		}
		
		else{
			echo '</div>';
		}
		
	}
	
	//Outputs the content to the client
	function wplw_view_content( $one_post_content, $maxNumber, $count ){
		
		if( $count > $maxNumber ){
		
		}
		
		else {
		
			$image = $one_post_content['images']['standard_resolution']['url'];
			$caption = $one_post_content['caption']['text'];
			
			$options = get_option('wplw_instagram_portfolio');
			
			echo '<div class="portfolio-piece"><a href="" class="portfolio-piece-anchor"><img class="portfolio-image" src="' . $image . '" /><div class="meta">';

				if( $options['wplw_portfolio_date'] === 'checked' ){
					echo '<p class="portfolio-date">' . date('j M, Y', $one_post_content['caption']['created_time']) . '</p>';
				}
				
				if( $options['wplw_portfolio_description'] === 'checked' ){
					echo '<p class="portfolio-caption">' . $caption . '</p>';
				}

			echo '</div></a></div>';
		}
	}
	
	//Searchs for hashtag equal to user set hashtag, returns true for every image that has that hashtag
	function wplw_search_for_hashtags( $caption ){
		$options = get_option('wplw_instagram_portfolio');
		$hashtag = $options['wplw_hashtag'];
		$regexp = '/#' . $hashtag . '/';
				
		if( preg_match($regexp, $caption) ){
			return true;	
		}
		
		else{
			return false;
		}
	}
		 
 ?>