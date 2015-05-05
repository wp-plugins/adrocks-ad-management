<?php
add_action('admin_menu','adrocks_ad_management_add_user_code_page');

function adrocks_ad_management_add_user_code_page()
{
	$option_data = get_option('adrocks_ad_management_option');

	
	$current_user = wp_get_current_user();
	if(!($current_user instanceof WP_User) )
         return;
	
	$role_number  = count($current_user->roles);
	$post_number = count_user_posts($current_user->ID);
	$user_roles = $current_user->roles;
	$out = false;
	if(is_array($option_data))
	{
		if(array_key_exists ( 'roles', $option_data))
		{
			for($i = 0; $i <= $role_number;$i++)
			{
				if(!$out)
				{
					foreach($option_data['roles'] as $role_data)
					{
						if((strtolower($role_data['name']) == strtolower($user_roles[$i])) && ($post_number >= $role_data['posts_gold'] || $post_number >= $role_data['posts_silver'] || $post_number >= $role_data['posts_bronze']) && $role_data['code_free'] == 'on')
						{
							add_menu_page('Adrocks Ad Management User Code', 'Adrocks User Code',  'edit_posts', 'adrocks_ad_management_user_code', 'adrocks_ad_management_do_user_code_page');
							break;
						}
					}	
				}else{
					break;
				}
			}
		}
	}
}

function adrocks_ad_management_do_user_code_page()
{
	$option_data = get_option('adrocks_ad_management_option');

	
	$current_user = wp_get_current_user();
	if(!($current_user instanceof WP_User) )
         return;
	
	$role_number  = count($current_user->roles);
	$post_number = count_user_posts($current_user->ID);
	$user_roles = $current_user->roles;
	$out = false;
	if(is_array($option_data))
	{
		if(array_key_exists ( 'roles', $option_data))
		{
			for($i = 0; $i <= $role_number;$i++)
			{
				if(!$out)
				{
					foreach($option_data['roles'] as $role_data)
					{
						if((strtolower($role_data['name']) == strtolower($user_roles[$i])) && ($post_number >= $role_data['posts_gold'] || $post_number >= $role_data['posts_silver'] || $post_number >= $role_data['posts_bronze']) && $role_data['code_free'] == 'on')
						{
							adrocks_ad_management_show_user_Code_Input();
							$out = true;
							break;
						}
					}	
				}else{
					break;
				}
			}
		}
	}

}


function adrocks_ad_management_show_user_Code_Input()
{
	$current_user = wp_get_current_user();
	if(wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__)))
	{
		if(isset($_POST['adrocks_ad_management_admin_user_code_submit']))
		{
			adrocks_ad_management_saveData($_POST['adrocks_ad_management_user_code']);
		}
	}
	
	?>
	<div class="wrap">
	<h2><?php echo _e('User Advertisement Code Input','adrocks_ad_management_opt'); ?></h2>
	<form method="POST" action="" name="adrocks_ad_management_user_code_form">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
		<table style="background-color:#FFFFF0;">
			<tr>
				<th>
					Code:
				</th>
				<td>
					<?php echo'<textarea name="adrocks_ad_management_user_code">';?><?php
							echo esc_textarea(adrocks_ad_management_readDataFromFile($current_user->user_login)); 
						?></textarea>
				</td>
			</tr> 
			<tr>
				<td>
					<input type="Submit" name="adrocks_ad_management_admin_user_code_submit" class="button-primary" value="<?php echo _e('Safe','adrocks_ad_management_opt'); ?>"/>
				</td>
			</tr>
		</table>
	</form>
	</div>
	<?php 
}
?>