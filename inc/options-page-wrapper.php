<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h2><?php esc_attr_e( 'Portfolio Plugin for Instagram', 'wp_admin_style' ); ?></h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">
        
        	<!-- main content -->
            <div id="post-body-content">
            	<div class="meta-box-sortables ui-sortable">
                	
                    <!-- Let´s get started box -->
                    <div class="postbox">
                        <h3 class="wplw-title">1. Let´s get started!</h3>
                        <div class="inside">
                            <p>Set up the plugin in the "Settings" box below, and hit "Save". When you´re done, do as suggested in the "Display your portfolio" box.</p>
                        </div>
                    </div>
                    
                    <!-- Settings box -->
                    <div class="postbox">
						
                        <h3 class="wplw-title">2. Settings</h3>

                        <div class="inside">
                            <h4>Basic</h4>
                            <form name="wplw_settings_form" method="post" action="">
                                <input type="hidden" name="wplw_form_submitted" value="Y" />
                                
                                <p>In order to display your photos you need an Access Token from Instagram. To get yours, and your User ID, click <a href="https://instagram.com/oauth/authorize/?client_id=1fb17789f1bf4b128589a1d6e4b6f4ee&redirect_uri=http://lisawestlund.se/wp-portfolio-plugin-auth.php&response_type=code" target="_blank">this link</a>, and then copy and paste the information below.</p>
                                <label for="wplw_instagram_access_token">Access Token (required)</label><br>
                                <input id="wplw_instagram_access_token" name="wplw_instagram_access_token" type="text" class="regular-text" value="<?php echo $wplw_instagram_access_token ?>" required /><br>
                                
                                <label for="wplw_instagram_userID">User ID (required)</label><br>
                                <input id="wplw_instagram_userID" name="wplw_instagram_userID" type="text" class="regular-text" value="<?php echo $wplw_instagram_userID ?>" required /><br>
                                
                                <label for="wplw_hashtag">The hashtag you want to use to filter your images (optional)<br>#</label>
                                <input id="wplw_hashtag" name="wplw_hashtag" type="text" value="<?php echo $wplw_hashtag ?>" class="regular-text" /><br>
                                
                                <h4>Show content from Instagram</h4>
                                <fieldset>
                                    <legend class="screen-reader-text"><span>Show publish date (optional)</span></legend>
                                        <label for="wplw_portfolio_date">
                                        <input name="wplw_portfolio_date" type="checkbox" id="wplw_portfolio_date" value="" <?php echo $options['wplw_portfolio_date']?> />
                                        <span>Show publish date (optional)</span>
                                        </label><br>
                                    <legend class="screen-reader-text"><span>Show image description (optional)</span></legend>
                                        <label for="wplw_portfolio_description">
                                        <input name="wplw_portfolio_description" type="checkbox" id="wplw_portfolio_description" value="" <?php echo $options['wplw_portfolio_description']?> />
                                        <span>Show image description (optional)</span>
                                        </label><br>
                                </fieldset>
                                <br>
                                <input class="button-primary" type="submit" name="wplw_settings_submit" value="Save" />
                            </form>

						</div>
				
                	</div>
                    
                    <div class="postbox">
						<h3 class="wplw-title">3. Display your portfolio</h3>
						<div class="inside">
							<p>In order to display your portfolio, you need to create a page where you want it displayed. Create the page and add the shortcode [wplw_instagram_portfolio] (within brackets) in the content area, and then publish the page.</p>
                            <p>If you add [wplw_instagram_portfolio] it will automatically show 24 images. If you want to show another number of images you need to add the parameter "count". Example: [wplw_instagram_portfolio count="12"].</p>
						</div>
					</div>
				
                </div>
			
            </div>

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div id ="sidebox" class="postbox">
						<div class="inside">
                            <span id="wplw-logotype"></span>
                            <p>This Portfolio Plugin uses Instagram to display your portfolio. You upload the pictures you want to show in your portfolio to your Instagram account. In the plugin settings you can choose a hashtag that you want to filter your images with, or you can leave the hashtag blank and the plugin will get all the latest posts from your account. It is also possible to choose how many pictures you want to show.</p>
							<p>The Portfolio Plugin is created by <a href="http://www.lisawestlund.se" target="_blank">Lisa Westlund</a>. Feel free to contact me if you have any questions about the plugin.</p>
                        </div>
					</div>

				</div>

			</div>

		</div>

		<br class="clear">
	</div>

</div>