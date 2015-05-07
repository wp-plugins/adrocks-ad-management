<?php 
/*
Plugin Name: Adrocks Ad Management Plugin
Description: Control your Ads at your Website
Version: 1.0.1
Author: Manuel Fehrenbach
License: GPLv2
Domain Path: /languages/
*/

/* 
Copyright 2015 Manuel Fehrenbach email : mfplugindeveloper@gmail.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

register_activation_hook( __FILE__, 'adrocks_ad_management_install' );

require_once('adrocks-ad-management-install.php');
require_once('adrocks-ad-management-admin-page.php');
require_once('adrocks-ad-management-widget.php');
require_once('adrocks-ad-management-edit-post.php');
require_once('adrocks-ad-management-user-code-page.php');



?>