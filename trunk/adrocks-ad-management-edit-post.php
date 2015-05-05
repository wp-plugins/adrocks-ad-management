<?php
add_filter('the_content','adrocks_ad_management_add_ad_posts');

function adrocks_ad_management_add_ad_posts($content)
{
	global $post;
	$author_id=$post->post_author;
	$user = get_user_by( 'id', $author_id );
	$admin_opt = get_option('adrocks_ad_management_option');
	$output = '';
	
	//print_r($admin_opt);
	if($admin_opt)
	{
		foreach($admin_opt as $opt)
		{
			if($opt['show'] == 'para')
			{
				//This part checks if the website owner Ad is shown or the choosen Authors Ad is shown if he have one.
				if( array_key_exists($author_id,$admin_opt))
				{
					
					$counter = intval($admin_opt[$author_id]['counter']);
					$role = $admin_opt[$author_id]['role'];
					
					$gold_perc =  intval($admin_opt['roles'][$role]['perce_gold']);
					$silver_perc = intval($admin_opt['roles'][$role]['perce_silver']);
					$bronze_perc = intval($admin_opt['roles'][$role]['perce_bronze']);
					
					$gold_number =  intval($admin_opt['roles'][$role]['posts_gold']);
					$silver_number = intval($admin_opt['roles'][$role]['posts_silver']);
					$bronze_number = intval($admin_opt['roles'][$role]['posts_bronze']);
			
					$user_posts = count_user_posts($author_id);

					if($user_posts >= $gold_number && adrocks_ad_management_existFileInFolder($user->user_login) && $gold_number > 0)
					{
						
						$owner_perce = 100-$gold_perc;
						
						if($counter > $owner_perce )
						{
							//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
							$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($user->user_login)."</div>";
							$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
							$counter++;
							if($counter >= 100)
							{
								$counter = 0;
							}
							$admin_opt[$author_id]['counter'] = $counter++;
						}else{
							//adrocks_ad_management_readDataFromFile is from adrocks-ad-management-admin-page.php
							$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($opt['id'])."</div>";
							$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
							$counter++;
							if($counter >= 100)
							{
								$counter = 0;
							}
							$admin_opt[$author_id]['counter'] = $counter;
						}
					}else if($user_posts >= $silver_number && adrocks_ad_management_existFileInFolder($user->user_login) && $silver_number > 0)
					{
						
						$owner_perce = 100-$silver_perc;
						
						if($counter > $owner_perce )
						{
							//mf_ad_management_readDataFromFile is from ad-management-admin-page.php
							$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($user->user_login)."</div>";
							$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
							$counter++;
							if($counter >= 100)
							{
								$counter = 0;
							}
							$admin_opt[$author_id]['counter'] = $counter;
						}else{
							//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
							$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($opt['id'])."</div>";
							$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
							$counter++;

							if($counter >= 100)
							{
								$counter = 0;
							}
							$admin_opt[$author_id]['counter'] = $counter;
						}
					}else if ($user_posts >= $bronze_number && adrocks_ad_management_existFileInFolder($user->user_login) && $bronze_number > 0)
					{
						
						$owner_perce = 100-$bronze_perc;
						
						if($counter > $owner_perce )
						{
							//mf_ad_management_readDataFromFile is from ad-management-admin-page.php
							$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($user->user_login)."</div>";
							$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
							$counter++;
							echo "<br />counter bronze: ". $counter;
							if($counter >= 100)
							{
								$counter = 0;
							}
							$admin_opt[$author_id]['counter'] = $counter;
						}else{	
							//adrocks_ad_management_readDataFromFile is from ad-management-admin-page.php
							$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($opt['id'])."</div>";
							$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
							$counter++;
							if($counter >= 100)
							{
								$counter = 0;
							}
							$admin_opt[$author_id]['counter'] = $counter;
						}
					}else{
						echo "id ".$opt['id'];
						$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($opt['id'])."</div>";
						$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
					}					
					update_option('adrocks_ad_management_option',$admin_opt);
				}else{
					//echo "id ".$opt['id'];
					$data_new = "<div id='adrocks_ad_management_post' class='adrocks-ad-management-post'>".adrocks_ad_management_readDataFromFile($opt['id'])."</div>";
					$content = adrocks_ad_management_countPara($content, $opt['pos'],$data_new);	
				}

			}
		}
	}
	return $content;
}

//Count the Paragraphs where the Ad should be shown
function adrocks_ad_management_countPara($text, $length, $insert_text)
{
	$content = explode("</p>", $text);
	$count = count($content);
	$output = '';
	for($i = 0; $i < $count; $i++)
	{
		if($count > $length)
		{
			if($i == ($length-1))
			{
				$output .=  $content[$i] . '</p>' . $insert_text;
			}else{
				$output .=  $content[$i] . '</p>';
			}	
		}else if($i == ($count-1) && $count < $length)
		{
			$output .=  $content[$i] . '</p>' . $insert_text;
		}else
		{
			$output .=  $content[$i] . '</p>';
		}
	}
	return $output;
}


function adrocks_ad_management_existFileInFolder($file_name)
{
	if(file_exists (__DIR__.'/adrocks-ad-management-data/'.$file_name.'.txt'))
	 {
		return true;
	 }else{
		 return false;
	 }
}
?>