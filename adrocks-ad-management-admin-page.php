<?php
add_action('admin_menu','adrocks_ad_management_add_page');
add_action('admin_enqueue_scripts','adrocks_admin_opt_javascript');

function adrocks_ad_management_translate_localization()
{
// Localization
load_plugin_textdomain('adrocks_ad_management_opt', false, dirname(plugin_basename(__FILE__)).'/languages/');
}

// Add actions
add_action('plugins_loaded', 'adrocks_ad_management_translate_localization');



function adrocks_admin_opt_javascript($hook)
{
	global $wp_roles;

	if('settings_page_adrocks_ad_management_op'!= $hook)
	{
		return;
	}
	wp_enqueue_script('admin_opt_js',plugin_dir_url(__FILE__).'/js/admin-page-opt.js');
	
	$option_data = get_option('adrocks_ad_management_option');
	$all_roles = $wp_roles->roles;
    $editable_roles = apply_filters('editable_roles', $all_roles);
	$roles_array = array();
	$roles_opt_array= array();
	

	if(array_key_exists('roles',$option_data))
	{
		foreach($option_data['roles'] as $roles_opt)
		{
			$roles_opt_array['name_'.$roles_opt['name']] = $roles_opt['name'];
			$roles_opt_array['code_free_'.$roles_opt['name']] = $roles_opt['code_free'];
			$roles_opt_array['perc_gold_'.$roles_opt['name']] = $roles_opt['perce_gold'];
			$roles_opt_array['perc_silver_'.$roles_opt['name']] = $roles_opt['perce_silver'];
			$roles_opt_array['perc_bronze_'.$roles_opt['name']] = $roles_opt['perce_bronze'];
			$roles_opt_array['posts_gold_'.$roles_opt['name']] = $roles_opt['posts_gold'];
			$roles_opt_array['posts_silver_'.$roles_opt['name']] = $roles_opt['posts_silver'];
			$roles_opt_array['posts_bronze_'.$roles_opt['name']] = $roles_opt['posts_bronze'];
		}	
		wp_localize_script('admin_opt_js','roles_opt',$roles_opt_array);
	}
}

function adrocks_ad_management_add_page()
{
	add_options_page('Ad Management (Options)', 'Adrocks Ad Management',  'manage_options', 'adrocks_ad_management_op', 'adrocks_ad_management_do_page');
}

function adrocks_ad_management_do_page()
{
	global $wp_roles;	
	$all_roles = $wp_roles->roles;
    $editable_roles = apply_filters('editable_roles', $all_roles);
	$option_data = get_option('adrocks_ad_management_option');
	
	if(!is_array($option_data))
	{
		$option_data = array();
	}
	
	if(array_key_exists('number_codes',$option_data))
	{
		$number_codes = $option_data['number_codes'];
	}else{
		$number_codes = 3;
	}	
			
	if(wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__)))
	{
		$count = 0;
		//Later calculate the new $number_codes variable
		if(isset($_POST['adrocks_ad_management_admin_opt_submit']))
		{
			 $number_codes = adrocks_ad_management_admin_opt_submit($_POST,$option_data,$number_codes,$count);
		}
		else if(isset($_POST['adrocks_ad_management_admin_opt_show_roles_submit']))
		{
			$role = true;
			$show_role = $_POST['adrocks_ad_management_admin_opt_choose_role'];
		}
		else if(isset($_POST['adrocks_ad_management_admin_opt_create_role_submit']))
		{
			adrocks_ad_management_admin_opt_create_role_submit($_POST,$option_data);
		}
		else if(isset($_POST['adrocks_ad_management_admin_opt_change_role_submit']))
		{
			adrocks_ad_management_admin_opt_change_role_submit($_POST,$option_data);
		}
		else
		{
			return;
		}
		
		
	}
	
	$option_data = get_option('adrocks_ad_management_option');
	
	if(!is_array($option_data))
	{
		$option_data = array();
	}
	?>
	
	<div class="wrap">
	<h2><?php echo __("Properties for Adrocks Ad Management Plugin", 'adrocks_ad_management_opt');?> </h2>
	 <?php
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'code';
            if(isset($_GET['tab'])) $active_tab = $_GET['tab'];
            ?>
	 <h2 class="nav-tab-wrapper">
		<a href="?page=adrocks_ad_management_op&amp;tab=code" class="nav-tab <?php echo $active_tab == 'code' ? 'nav-tab-active' : ''; ?>"><?php _e('Code', 'adrocks_ad_management_opt'); ?></a>
		<a href="?page=adrocks_ad_management_op&amp;tab=author" class="nav-tab <?php echo $active_tab == 'author' ? 'nav-tab-active' : ''; ?>"><?php _e('Author', 'adrocks_ad_management_opt'); ?></a>
		<a href="?page=adrocks_ad_management_op&amp;tab=role" class="nav-tab <?php echo $active_tab == 'role' ? 'nav-tab-active' : ''; ?>"><?php  _e('Roles', 'adrocks_ad_management_opt'); ?></a>
    </h2>
	<form method="POST" action="" name="adrocks-ad-management-opt-form" >

	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
		<?php  if($active_tab == 'code'){ ?>
		<div class="inside">
			<h3><?php _e("Ad-Code", 'adrocks_ad_management_opt');?> </h3>
			<table style="background-color:#FFFFFF;">
				<tbody>
					<?php adrocks_ad_management_createCodeInput($number_codes,$option_data);?>
					<tr>
						<th colspan="3">
							<label for="adrocks-ad-management-admin-opt-new-codes"><?php echo _e('Number new Codes: ','adrocks_ad_management_opt'); ?> </label>
						</th>
						
						<td>
							<input type="number" name="adrocks_ad_management_admin_opt_new_codes" id="adrocks-ad-management-admin-opt-new-codes" value="0" min ="0"/>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<input type="Submit" name="adrocks_ad_management_admin_opt_submit" class="button-primary" value="<?php echo _e('Safe','adrocks_ad_management_opt'); ?>"/>
		</form>
		<?php } if($active_tab == 'author'){ ?>
			<div class="inside">
			<h3><?php _e("Authors", 'adrocks_ad_management_opt')?> </h3>
			<table style="background-color:#FFFFFF;">
				<tbody>
					<?php 
						adrocks_ad_management_chooseRole($show_role);
						
						if($role)
						{
							adrocks_ad_management_showRole($show_role);
						}
					?>
				</tbody>
			</table>
		</div>
		<input type="Submit" name="adrocks_ad_management_admin_opt_change_role_submit" class="button-primary" value="<?php echo _e('Safe','adrocks_ad_management_opt'); ?>"/>
		</form>
		<?php } if($active_tab == 'role') { ?>
		<div class="inside">
			<h3><?php _e("Roles", 'adrocks_ad_management_opt')?> </h3>
			<table style="background-color:#FFFFFF;">
				<tbody>
				<tr>
					<td>
					<?php 
						adrocks_ad_management_chooseRole($show_role);
					?>
					</td>
				</tr>
				 <?php
				 if($role)
				{
					adrocks_ad_management_expandRole($show_role);
				}
				  ?>
				  
			  </tbody>
			  </table>
		</div>
		<br />
		<input type="Submit" name="adrocks_ad_management_admin_opt_create_role_submit" class="button-primary" value="<?php echo _e('Safe','adrocks_ad_management_opt'); ?>"/>
		</form>
		<?php } ?>
	
	</div>
	<?php 
}


function adrocks_ad_management_admin_opt_submit($POST,$option_data,$number_codes,$count)
{
	
	//If added new Codeblock get the number of new code blocks which are needed
	if($POST['adrocks_ad_management_admin_opt_new_codes']>0)
	{
		$number_codes = $number_codes + $POST['adrocks_ad_management_admin_opt_new_codes'];
	}
	//Get the actual Position (Paragraph) for the code and if nothing is insert put a default value in.
	$number = array();
	for($i = 1; $i <= $number_codes;$i++)
	{
		if(($POST['adrocks_ad_management_admin_opt_code'.$i.'_select'.$i] == 'para') && ($POST['adrocks_ad_management_admin_opt_code'.$i.'_number'.$i] == '' || $POST['adrocks_ad_management_admin_opt_code'.$i.'_number'.$i] == 0)) 
		{
			$number[$i] =  0;
		}else{
			$number[$i] = $POST['adrocks_ad_management_admin_opt_code'.$i.'_number'.$i];
		}
	}
	
	
	
	for($i = 1; $i <=$number_codes;$i++)
	{
		$data['code'.$i] = array(
							'id' => 'code'.$i,
							'code' => $POST['adrocks_ad_management_admin_opt_code'.$i],
							'show' => $POST['adrocks_ad_management_admin_opt_code'.$i.'_select'.$i],
							'pos' => $number[$i]
							);
	}
	
	//Delete the selected code block
	for($i = 1; $i <=$number_codes;$i++)
	{
		if($POST['adrocks_ad_management_admin_opt_checkbox_code'.$i] == 'code'.$i)
		{
			$count++;
			if( $i != $number_codes)
			{	
				for($j = $i ; $j <=$number_codes;$j++)
				{
					$option_data['code'.$j]['id'] = 'code'.$j;
					$option_data['code'.$j]['show'] =$POST['adrocks_ad_management_admin_opt_code'.($j+1).'_select'.($j+1)];
					$option_data['code'.$j]['pos'] =$number[$j+1];
					if($POST['adrocks_ad_management_admin_opt_code'.($j+1)] != '')
					{
						$option_data['code'.$j]['code'] = $POST['adrocks_ad_management_admin_opt_code'.($j+1)];
					}else{
						$option_data['code'.$j]['code'] = '';
					}			
					$data['code'.$j] = array(
							'id' => $option_data['code'.$j]['id'],
							'code' => $option_data['code'.$j]['code'],
							'show' => $option_data['code'.$j]['show'],
							'pos' => intval($option_data['code'.$j]['pos'])
							);
					adrocks_ad_management_deleteFile('code'.$j);
				}
			}
		}
	}
	
	//Delete the not needed code array in the option array
	for($i = 0; $i < $count; $i++)
	{
		unset($data['code'.($number_codes-$i)]);
		unset($option_data['code'.($number_codes-$i)]);
		update_option('adrocks_ad_management_option', $option_data);
	}
	
	$number_codes -= ($count);
	$data['number_codes'] = $number_codes;
	adrocks_ad_management_saveData($data);
	
	return $number_codes;
}

function adrocks_ad_management_admin_opt_change_role_submit($data,$option_data)
{
	$users = get_users($data['adrocks_ad_management_admin_opt_choose_role']);
	foreach($users as $user)
	{	
		$old_code = adrocks_ad_management_readDataFromFile($user->user_login);	
		$new_code;
		if(array_key_exists($user->user_login,$data))
		{
			$new_code = $data[$user->user_login];
		}else{
			$new_code = '';
		}
		
		if($new_code !='' && $old_code != null)
		{
			if($new_code != $old_code)
			{
				adrocks_ad_management_saveDataInFile(__DIR__.'/adrocks-ad-management-data/'.$user->user_login.'.txt',$new_code);
			}
		}
	}
}

function adrocks_ad_management_admin_opt_create_role_submit($POST,$option_data)
{
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['name'] = $POST['adrocks_ad_management_admin_opt_roles'];
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['code_free'] = $POST['adrocks_ad_management_admin_opt_ad_code_free'];
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['perce_gold'] = intval($POST['adrocks_ad_management_admin_opt_show_percent_gold']);
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['perce_silver'] = intval($POST['adrocks_ad_management_admin_opt_show_percent_silver']);
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['perce_bronze'] = intval($POST['adrocks_ad_management_admin_opt_show_percent_bronze']);
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['posts_gold'] = intval($POST['adrocks_ad_management_admin_opt_show_number_posts_gold']);
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['posts_silver'] = intval($POST['adrocks_ad_management_admin_opt_show_number_posts_silver']);
	$option_data['roles'][$POST['adrocks_ad_management_admin_opt_roles']]['posts_bronze'] = intval($POST['adrocks_ad_management_admin_opt_show_number_posts_bronze']);
	
	$args = array(
	'role' => $POST['adrocks_ad_management_admin_opt_roles'],
	'orderby' => 'ID',
	'order' => 'DESC'
	
	);
	$users = get_users($args);
	if($POST['adrocks_ad_management_admin_opt_ad_code_free'] == 'on')
	{
		foreach($users as $user)
		{
			$user_posts = count_user_posts($user->id);
			if($user_posts >= intval($POST['adrocks_ad_management_admin_opt_show_number_posts_gold']) || intval($user_posts >= $POST['adrocks_ad_management_admin_opt_show_number_posts_silver']) ||
																								intval($user_posts >= $POST['adrocks_ad_management_admin_opt_show_number_posts_bronze']))
			{
				$option_data[$user->id]['counter'] = 0;	
				$option_data[$user->id]['role']	= $POST['adrocks_ad_management_admin_opt_roles'];		
			}
		}
	}else if( !array_key_exists($POST['adrocks_ad_management_admin_opt_ad_code_free'],$option_data))
	{
		foreach($users as $user)
		{
			$user_posts = count_user_posts($user->id);
			if($user_posts >= intval($POST['adrocks_ad_management_admin_opt_show_number_posts_gold']) || intval($user_posts >= $POST['adrocks_ad_management_admin_opt_show_number_posts_silver']) ||
																								intval($user_posts >= $POST['adrocks_ad_management_admin_opt_show_number_posts_bronze']))
			{
				unset($option_data[$user->id]);
				var_dump($option_data);
			}
		}
	}
	
	update_option('adrocks_ad_management_option', $option_data);
}

function adrocks_ad_management_saveData($data)
{	
	global $current_user;
	get_currentuserinfo();
	
	if(is_array($data))
	{
		if(!get_option('adrocks_ad_management_option'))
		{
			$option_code = array();
			foreach($data as $code)
			{
				$option_code[$code['id']] = array(
								'id'=>$code['id'],
								'show' => $code['show'],
								'pos' => $code['pos']
								);
				if(strlen($code['code']) > 0)
				{
					adrocks_ad_management_saveDataInFile(__DIR__.'/adrocks-ad-management-data/'.$code['id'].'.txt', $code['code']);
				}	
			}

			$option_code['number_codes'] = $data['number_codes'];
			update_option('adrocks_ad_management_option', $option_code);	
		}else{
			$option_code = get_option('adrocks_ad_management_option');
			foreach($data as $code)
			{
				$option_code[$code['id']] = array(
								'id'=>$code['id'],
								'show' => $code['show'],
								'pos' => $code['pos']
								);
				if(strlen($code['code']) > 0)
				{
					adrocks_ad_management_saveDataInFile(__DIR__.'/adrocks-ad-management-data/'.$code['id'].'.txt', $code['code']);
				}	
			}

			$option_code['number_codes'] = $data['number_codes'];
			update_option('adrocks_ad_management_option', $option_code);	
		}
	
	}else{
		$current_user = wp_get_current_user();
		adrocks_ad_management_saveDataInFile(__DIR__.'/adrocks-ad-management-data/'.$current_user->user_login.'.txt', $data);
	}
}

function adrocks_ad_management_saveDataInFile($path, $content)
{
	$content = str_replace('\\','',$content);
	$file = @fopen($path, 'w');
	if(strlen($content) > 0)
	{
		@fwrite($file, $content) or "<br/>Could not write to file: $path";
	}
	
	@fclose($file);
}

function adrocks_ad_management_readDataFromFile($file_name)
{
	 if(file_exists (__DIR__.'/adrocks-ad-management-data/'.$file_name.'.txt'))
	 {
		$file = fopen(__DIR__.'/adrocks-ad-management-data/'.$file_name.'.txt', "r") or die("Unable to open file!");
		$data = fread($file,filesize(__DIR__.'/adrocks-ad-management-data/'.$file_name.'.txt'));
		fclose($file); 
		return $data;
	 }else{
		 return;
	 }
}
function adrocks_ad_management_deleteFile($file_name)
{
	$path = __DIR__.'/adrocks-ad-management-data/'.$file_name.'.txt';
	if(file_exists($path))
	{
		unlink($path);
	}
}

function adrocks_ad_management_createCodeInput($number,$option_data)
{
	for($i = 1; $i <= $number; $i++)
	{
		if(array_key_exists('code'.$i,$option_data))
		{
			echo '<tr>';
				echo '<th>';
					echo $i;
				echo '</th>';
				echo '<th>';
					echo '<label for="adrocks-ad-management-admin-opt-delete'.$i.'">'._e('Delete','adrocks_ad_management_opt').' </label>';
				echo '</th>';
				echo '<td>';
					echo '<input type="checkbox" name="adrocks_ad_management_admin_opt_checkbox_code'.$i.'" value ="code'.$i.'" />	';
				echo '</td>';
				echo '<th>';
						echo '<label for="adrocks-ad-management-admin-opt-code'.$i.'">'._e('Your Code:','adrocks_ad_management_opt').'</label>';
				echo '</th>';
				echo'<td>';
					echo'<textarea id="adrocks-ad-management-admin-opt-code'.$i.'" name="adrocks_ad_management_admin_opt_code'.$i.'" cols="50" rows="5" >'; 
							if(!empty($option_data))
							{
								echo adrocks_ad_management_readDataFromFile($option_data['code'.$i]['id']);
							} 
						echo'</textarea>';
				echo'</td>';			
				echo'<th>';
					echo'<label for="adrocks-ad-management-admin-opt-code'.$i.'-select'.$i.'">'. _e('Show','adrocks_ad_management_opt').': </label>';
				echo'</th>';
					echo'<td>';
							echo'<select id="adrocks-ad-management-admin-opt-code'.$i.'-select'.$i.'" name="adrocks_ad_management_admin_opt_code'.$i.'_select'.$i.'" size="1" onchange="adrocks_ad_management_checkCodeSelect(this,'.$i.')">';
								echo'<option value="para"';
									if(!empty($option_data))
									{
										if($option_data['code'.$i]['show'] == 'para')
										{
											echo "selected";
										}
									} 
								echo">"; echo _e('After Paragraphs', 'adrocks_ad_management_opt')."</option>";
								
								echo'<option value="widget"'; 					
									if(!empty($option_data))
									{
										if($option_data['code'.$i]['show'] == 'widget')
										{
											echo "selected";	
										}
									} 
									echo'>Widget</option>';
								
								
							echo'</select>';
					echo'</td>';
					echo'<td>';
						echo'<input type="number"  name="adrocks_ad_management_admin_opt_code'.$i.'_number'.$i.'" value="';  
								if($option_data)
								{
									if($option_data['code'.$i]['pos'] != '')
									{
										echo esc_attr($option_data['code'.$i]['pos']);
									}									
								} 
								echo '" min="0"';  

								if(!empty($option_data))
								{
									if($option_data['code'.$i]['show'] == 'widget')
									{
										echo 'readonly';
									}
								}
								echo '/>';
				echo'</td>';
			echo'</tr>';
		}
	}
}

function adrocks_ad_management_expandRole($show_role)
{
	global $wp_roles;
	$option_data = get_option('adrocks_ad_management_option');
?>		<tr>
			<td>
				<input type="hidden" id="adrocks-ad-management-admin-opt-roles" name="adrocks_ad_management_admin_opt_roles" value="<?php echo $show_role;?>"/>
				<label><?php _e('Free for Ad-Code','adrocks_ad_management_opt'); ?><input type="checkbox" id="adrocks-ad-management-admin-opt-ad-code-free" name="adrocks_ad_management_admin_opt_ad_code_free" <?php if(esc_attr( $option_data['roles'][$show_role]['code_free']) == 'on') {echo "checked=on";} ?>/></label>
			</td>
		</tr>

		<tr>
			<td>
				<label><?php _e('Article Count ','adrocks_ad_management_opt'); ?> Gold: <input type="number" id="adrocks-ad-management-admin-opt-show-number-posts-gold" name="adrocks_ad_management_admin_opt_show_number_posts_gold" value="<?php echo esc_attr($option_data['roles'][$show_role]['posts_gold']);  ?>"/></label>
			</td>
			<td>
				<label><?php _e('Article Count ','adrocks_ad_management_opt'); ?> Silver: <input type="number" id="adrocks-ad-management-admin-opt-show-number-posts-silver" name="adrocks_ad_management_admin_opt_show_number_posts_silver" value="<?php echo esc_attr($option_data['roles'][$show_role]['posts_silver']); ?>"/></label>
			</td>
			<td>
				<label><?php _e('Article Count ','adrocks_ad_management_opt'); ?> Bronze: <input type="number" id="adrocks-ad-management-admin-opt-show-number-posts-bronze" name="adrocks_ad_management_admin_opt_show_number_posts_bronze" value="<?php  echo esc_attr($option_data['roles'][$show_role]['posts_bronze']); ?>"/></label>
			</td>
		</tr>
		<tr>
			<td>
				<label><?php _e('Display % of Authors Code ','adrocks_ad_management_opt'); ?> Gold: <input type="number" id="adrocks-ad-management-admin-opt-show-percent-gold" name="adrocks_ad_management_admin_opt_show_percent_gold" value="<?php echo esc_attr($option_data['roles'][$show_role]['perce_gold']); ?>"/></label>
			</td>
			<td>
				<label><?php _e('Display % of Authors Code','adrocks_ad_management_opt'); ?> Silver: <input type="number" id="adrocks-ad-management-admin-opt-show-percent-silver" name="adrocks_ad_management_admin_opt_show_percent_silver" value="<?php echo esc_attr($option_data['roles'][$show_role]['perce_silver']);  ?>"/></label>
			</td>
			<td>
				<label><?php _e('Display % of Authors Code','adrocks_ad_management_opt'); ?> Bronze: <input type="number" id="adrocks-ad-management-admin-opt-show-percent-bronze" name="adrocks_ad_management_admin_opt_show_percent_bronze" value="<?php echo esc_attr($option_data['roles'][$show_role]['perce_bronze']);  ?>"/></label>
			</td>
		</tr>
<?php
}

function adrocks_ad_management_chooseRole($show_role)
{
global $wp_roles;
 
$all_roles = $wp_roles->roles;
$editable_roles = apply_filters('editable_roles', $all_roles);
?>
<tr>
	<td>
		<select id="adrocks-ad-management-admin-opt-choose-role" name="adrocks_ad_management_admin_opt_choose_role">
			<?php 
				 foreach($editable_roles as $roles) 
				{ 
				if($show_role == $roles['name'] )
				{
					echo "<option value=".esc_attr($roles['name'])." selected> ".esc_html($roles['name']) ."</option>";
				}else{
					echo "<option value=".esc_attr($roles['name'])."> ".esc_html($roles['name'] )."</option>";
				}
					
				}
			?>
		</select>
	</td>
	<td>
		<input type="Submit" name="adrocks_ad_management_admin_opt_show_roles_submit" class="button-primary" value="<?php echo _e('Show','adrocks_ad_management_opt'); ?>"/>
	</td>
</tr>
<?php
}

function adrocks_ad_management_showRole($role)
{

	$opt = get_option('adrocks_ad_management_option');
	$args = array(
			'role' => $role,
			'orderby' => 'ID',
			'order' => 'DESC'
			
		);
		$users = get_users($args);
		echo '<tr>';
			echo '<th>';
				_e('Name','adrocks_ad_management_opt');
			echo '</th>';
			echo '<th>';
				_e('Code','adrocks_ad_management_opt');
			echo '</th>';
			echo '<th>';
				_e('Number Articles','adrocks_ad_management_opt');
			echo '</th>';
			echo '<th>';
				_e('User Status','adrocks_ad_management_opt');
			echo '</th>';
		echo '</tr>';
		foreach($users as $user)
		{
			$numer_posts = count_user_posts($user->id);
			echo '<tr>';
				echo '<td align="center" >';
					echo $user->display_name;	
				echo '</td>';
				echo '<td>';
					echo '<textarea name="'.$user->user_login.'">';
						echo esc_textarea(adrocks_ad_management_readDataFromFile($user->user_login));
					echo '</textarea>';
				echo '</td>';
				echo '<td align="center">';
					echo $numer_posts;	
				echo '</td>';
				echo '<td align="center">';
				
				//echo ($opt['roles'][$role]['posts_silver']);
					if($numer_posts >= intval($opt['roles'][$role]['posts_gold']) && intval($opt['roles'][$role]['posts_gold']) != 0 )
					{
						echo "Gold";
					}
					else if($numer_posts >= intval($opt['roles'][$role]['posts_silver']) && intval($opt['roles'][$role]['posts_silver']) != 0)
					{
						echo "Silver";
					}
					else if($numer_posts >= intval($opt['roles'][$role]['posts_bronze']) && intval($opt['roles'][$role]['posts_bronze']) != 0)
					{
						echo "Bronze";
					}
					else
					{
						_e('No Status','adrocks_ad_management_opt');
					}
				echo '</td>';
			echo '</tr>';		
		}
}
?>