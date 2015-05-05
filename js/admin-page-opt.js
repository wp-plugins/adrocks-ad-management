function adrocks_ad_management_checkCodeSelect(object,number)
{
	var id = object.id;
	

		if(object[object.selectedIndex].value == 'para' )
		{
			document.forms['adrocks-ad-management-opt-form']['adrocks_ad_management_admin_opt_code'+number+'_number'+number].readOnly = false;
		}else{
		document.forms['adrocks-ad-management-opt-form']['adrocks_ad_management_admin_opt_code'+number+'_number'+number].readOnly = true;
		document.forms['adrocks-ad-management-opt-form']['adrocks_ad_management_admin_opt_code'+number+'_number'+number].value ="";
		}
		
}
