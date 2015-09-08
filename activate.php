<?php

namespace AU\TabbedProfile;

if (get_subtype_id('object', 'tabbed_profile')) {
	update_subtype('object', 'tabbed_profile', __NAMESPACE__ . '\\Profile');
} else {
	add_subtype('object', 'tabbed_profile', __NAMESPACE__ . '\\Profile');
}

$version = elgg_plugin_setting('version', PLUGIN_ID);
if (!$version) {
	elgg_set_plugin_setting('version', PLUGIN_VERSION, PLUGIN_ID);
}