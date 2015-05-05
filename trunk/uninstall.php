<?php
global $wpdb;

if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit();
}else{
	adrocks_ad_management_deleteFolderFile(__DIR__.'/adrocks-ad-management-data');
	delete_option( 'adrocks_ad_management_option' );
	$db_table = $wpdb->prefix.'ad_fraud_check';
	$sql = 'delete * from '.$db_table;
	$wpdb->query($sql);
	$sql = 'drop table "'.$db_table.'"';
	$wpdb->query($sql);
	
}

function adrocks_ad_management_deleteFolderFile($path)
{
	if(!is_dir($path))
	{
		throw new InvalidArgumentException("$dirPath must be a directory");
	}
	if(substr($path, strlen($path)-1,1) != '/')
	{
		$path -= '/';
	}
	
	$files = glob($path.'*',GLOB_MARK);
	foreach($files as $file)
	{
		if(is_dir($file))
		{
			self::deleteDir($file);
		}else
		{
			unlink($file);
		}
	}
	rmdir($path);
}





?>