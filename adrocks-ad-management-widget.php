<?php
class Adrocks_Ad_Management_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	 function Adrocks_Ad_Management_Widget() {
		// Instantiate the parent object
		parent::__construct( false, 'Adrocks Ad Management Widget' );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		global $post;
		$option_data = get_option('adrocks_ad_management_option');
		$author_id=$post->post_author;
		$user = get_user_by( 'id', $author_id );

		if ( is_front_page() && is_home() ) {
			foreach($option_data as $code)
			{
				if($code['show'] == 'widget')
				{
					$code = adrocks_ad_management_readDataFromFile($code['id']);
					
					if(!empty($code))
					{
						echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
							echo '<div class="textwidget">';
								echo $code;
							echo '</div>';
						echo '</div>';
					}
				}
			}
		} elseif ( is_front_page() ) {
		  foreach($option_data as $code)
			{
				if($code['show'] == 'widget')
				{
					$code = adrocks_ad_management_readDataFromFile($code['id']);
					
					if(!empty($code))
					{
						echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
							echo '<div class="textwidget">';
								echo $code;
							echo '</div>';
						echo '</div>';
					}
				}
			}
		} elseif ( is_home() ) {
			foreach($option_data as $code)
			{
				if($code['show'] == 'widget')
				{
					$code = adrocks_ad_management_readDataFromFile($code['id']);
					
					if(!empty($code))
					{
						echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
							echo '<div class="textwidget">';
								echo $code;
							echo '</div>';
						echo '</div>';
					}
				}
			}
		} else {
			if(!empty($option_data))
			{
			
			foreach($option_data as $code)
			{
				if($code['show'] == 'widget')
				{
					if( array_key_exists($author_id,$option_data))
					{
						$counter = intval($option_data[$author_id]['counter']);
						$role = $option_data[$author_id]['role'];
						
						$gold_perc =  intval($option_data['roles'][$role]['perce_gold']);
						$silver_perc = intval($option_data['roles'][$role]['perce_silver']);
						$bronze_perc = intval($option_data['roles'][$role]['perce_bronze']);
						
						$gold_number =  intval($option_data['roles'][$role]['posts_gold']);
						$silver_number = intval($option_data['roles'][$role]['posts_silver']);
						$bronze_number = intval($option_data['roles'][$role]['posts_bronze']);
				
						$user_posts = count_user_posts($author_id);

						if($user_posts >= $gold_number && adrocks_ad_management_existFileInFolder($user->user_login) && $gold_number > 0)
						{
							
							$owner_perce = 100-$gold_perc;
							
							if($counter > $owner_perce )
							{
								//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
								$code = adrocks_ad_management_readDataFromFile($user->user_login);
					
								if(!empty($code))
								{
									echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
										echo '<div class="textwidget">';
											echo $code;
										echo '</div>';
									echo '</div>';
								}
								$counter++;
								if($counter >= 100)
								{
									$counter = 0;
								}
								$option_data[$author_id]['counter'] = $counter++;
							}else{
								//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
								$code = adrocks_ad_management_readDataFromFile($code['id']);
					
								if(!empty($code))
								{
									echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
										echo '<div class="textwidget">';
											echo $code;
										echo '</div>';
									echo '</div>';
								}
								
								$counter++;
								if($counter >= 100)
								{
									$counter = 0;
								}
								$option_data[$author_id]['counter'] = $counter;
							}
						}else if($user_posts >= $silver_number && adrocks_ad_management_existFileInFolder($user->user_login) && $silver_number > 0)
						{
							
							$owner_perce = 100-$silver_perc;
							
							if($counter > $owner_perce )
							{
								//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
								$code = adrocks_ad_management_readDataFromFile($user->user_login);
					
								if(!empty($code))
								{
									echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
										echo '<div class="textwidget">';
											echo $code;
										echo '</div>';
									echo '</div>';
								}
								$counter++;
								if($counter >= 100)
								{
									$counter = 0;
								}
								$option_data[$author_id]['counter'] = $counter;
							}else{
								//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
								$code = adrocks_ad_management_readDataFromFile($code['id']);
					
								if(!empty($code))
								{
									echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
										echo '<div class="textwidget">';
											echo $code;
										echo '</div>';
									echo '</div>';
								}
								$counter++;
								if($counter >= 100)
								{
									$counter = 0;
								}
								$option_data[$author_id]['counter'] = $counter;
							}
						}else if ($user_posts >= $bronze_number && adrocks_ad_management_existFileInFolder($user->user_login) && $bronze_number > 0)
						{
							
							$owner_perce = 100-$bronze_perc;
							if($counter > $owner_perce )
							{
								//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
								$code = adrocks_ad_management_readDataFromFile($user->user_login);
					
								if(!empty($code))
								{
									echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
										echo '<div class="textwidget">';
											echo $code;
										echo '</div>';
									echo '</div>';
								}

								$counter++;
								if($counter >= 100)
								{
									$counter = 0;
								}
								$option_data[$author_id]['counter'] = $counter;
							}else{	
								//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
								$code = adrocks_ad_management_readDataFromFile($code['id']);
					
								if(!empty($code))
								{
									echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
										echo '<div class="textwidget">';
											echo $code;
										echo '</div>';
									echo '</div>';
								}

								$counter++;
								if($counter >= 100)
								{
									$counter = 0;
								}
								$option_data[$author_id]['counter'] = $counter;
							}
						}else{
							$code = adrocks_ad_management_readDataFromFile($code['id']);
					
							if(!empty($code))
							{
								echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
									echo '<div class="textwidget">';
										echo $code;
									echo '</div>';
								echo '</div>';
							}
						}
						update_option('adrocks_ad_management_option',$option_data);
					}else{
							$code = adrocks_ad_management_readDataFromFile($code['id']);
					
							if(!empty($code))
							{
								echo '<div id="adrocks_ad_management_widget" class="widget widget_text">';
									echo '<div class="textwidget">';
										echo $code;
									echo '</div>';
								echo '</div>';
							}
						}
					}
				}
			}
		}
	}
	
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
	
	function form( $instance ) {
		// Output admin widget options form
	}
}

function adrocks_ad_management_register_widgets() {
	register_widget( 'Adrocks_Ad_Management_Widget' );
}

add_action( 'widgets_init', 'adrocks_ad_management_register_widgets' );
?>