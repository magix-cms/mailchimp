<?php
function smarty_function_mailchimp_conf($params, $template){
	$t = new frontend_model_template();
	$t->addConfigFile(
		array(component_core_system::basePath().'/plugins/mailchimp/i18n/'),
		array('public_local_'),
		false
	);
	$t->configLoad();
}