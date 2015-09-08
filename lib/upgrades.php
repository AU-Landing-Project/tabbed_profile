<?php

namespace AU\TabbedProfile;

function upgrade_20150905() {
	$version = (int) elgg_get_plugin_setting('version', PLUGIN_ID);
	
	if ($version >= 20150905) {
		return;
	}
	
	if (get_subtype_id('object', 'tabbed_profile')) {
		update_subtype('object', 'tabbed_profile', __NAMESPACE__ . '\\Profile');
	} else {
		add_subtype('object', 'tabbed_profile', __NAMESPACE__ . '\\Profile');
	}
	
	elgg_set_plugin_setting('version', 20150905, PLUGIN_ID);
}