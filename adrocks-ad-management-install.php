<?php 
global $aadrocks_d_management_db_version;
$adrocks_ad_management_db_version = '1.0';

function adrocks_ad_management_install(){
	add_option( "adrocks_ad_management_version", "1.0" );
	
	
	if(!file_exists(__DIR__.'/adrocks-ad-management-data'))
	{
		mkdir(__DIR__.'/adrocks-ad-management-data',0777,true);
	}
	adrocks_ad_management_db();
}



function adrocks_ad_management_db() {
	global $wpdb;
	global $adrocks_ad_management_db_version;

	$table_name = $wpdb->prefix . 'ad_fraud_check';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		click_time timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
		ip_address varchar(48) NOT NULL,
		click_number varchar(255) NOT NULL,
		blocked tinyint(1) NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'adrocks_ad_management_db_version', $adrocks_ad_management_db_version );
}



?>