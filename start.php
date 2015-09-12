<?php

namespace AU\TabbedProfile;

const TABBED_PROFILE_WIDGET_RELATIONSHIP = 'widget_of_profile_tab';
const TABBED_PROFILE_MAX_TABS = 7;
const PLUGIN_ID = 'tabbed_profile';
const PLUGIN_VERSION = 20150905;

require_once __DIR__ . '/lib/hooks.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');


function init() {
  
  // Extend our views
  elgg_extend_view('css/elgg', 'tabbed_profile/css');
  elgg_extend_view('page/layouts/widgets', 'tabbed_profile/navigation', 0);
  elgg_extend_view('groups/profile/layout', 'tabbed_profile/navigation', 0);
  elgg_extend_view('page/layouts/tabbed_profile_widgets', 'tabbed_profile/navigation', 0);
  elgg_extend_view('tabbed_profile/iframe', 'tabbed_profile/navigation', 0);
  
  // create urls for tabs
  elgg_register_page_handler('profiletab', __NAMESPACE__ . '\\profiletab_pagehandler');
  elgg_register_plugin_hook_handler('entity:url', 'object', __NAMESPACE__ . '\\url_handler');

  // register our plugin hooks
 elgg_register_plugin_hook_handler('route', 'profile', __NAMESPACE__ . '\\profile_router');
 elgg_register_plugin_hook_handler('route', 'groups', __NAMESPACE__ . '\\group_router');
 elgg_register_plugin_hook_handler('permissions_check', 'all', __NAMESPACE__ . '\\permissions_check');
 elgg_register_plugin_hook_handler('available_widgets_context', 'widget_manager', __NAMESPACE__ . '\\widget_context_normalize');
 elgg_register_plugin_hook_handler('action', 'widgets/add', __NAMESPACE__ . '\\widgets_add_action_handler');
 
 // register actions
 elgg_register_action('tabbed_profile/edit', __DIR__ . '/actions/tabbed_profile/edit.php');
 elgg_register_action('tabbed_profile/order', __DIR__ . '/actions/tabbed_profile/order.php');
 
 // register other events
 elgg_register_event_handler('create', 'object', __NAMESPACE__ . '\\widget_create');
 
 elgg_register_ajax_view('tabbed_profile/edit');
 
 // register our widgets
 elgg_register_widget_type('group_avatar', elgg_echo("tabbed_profile:group_avatar:widget:title"), elgg_echo("tabbed_profile:group_avatar:widget:description"), array('groups'), TRUE);
 elgg_register_widget_type('group_profile_stats', elgg_echo("tabbed_profile:group_stats:widget:title"), elgg_echo("tabbed_profile:group_stats:widget:description"), array('groups'), TRUE);
 elgg_register_widget_type('group_profile_block', elgg_echo("tabbed_profile:group_profile:widget:title"), elgg_echo("tabbed_profile:group_profile:widget:description"), array('groups'), TRUE);
 elgg_register_widget_type('user_avatar', elgg_echo("tabbed_profile:user_avatar:widget:title"), elgg_echo("tabbed_profile:user_avatar:widget:description"), array('profile'), TRUE);
 elgg_register_widget_type('user_profile_block', elgg_echo("tabbed_profile:user_details:widget:title"), elgg_echo("tabbed_profile:user_details:widget:description"), array('profile'), TRUE);
 elgg_register_widget_type('user_menu_block', elgg_echo("tabbed_profile:user_menu:widget:title"), elgg_echo("tabbed_profile:user_menu:widget:description"), array('profile'), TRUE);
 
 // register for upgrades
 elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrades');
}



/**
 * modify widgets context and create relationship if necessary
 * 
 * @param type $event
 * @param type $type
 * @param type $object
 */
function widget_create($event, $type, $object) {
  if ($object->getSubtype() == 'widget') {
	  
	$profile_guid = get_input('tabbed_profile_guid', false);
	$profile = get_entity($profile_guid);
	  
	if (elgg_instanceof($profile, 'object', 'tabbed_profile')) {
	  
	  // if not a default profile, we need to add the relationship
	  if (!$profile->default) {
	    add_entity_relationship($object->guid, TABBED_PROFILE_WIDGET_RELATIONSHIP, $profile->guid);
	  }
	}
	
  }
}


function upgrades() {
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	require_once __DIR__ . '/lib/upgrades.php';
	
	run_function_once(__NAMESPACE__ . '\\upgrade_20150905');
}


/**
 * Renders the profile page
 * @param type $page
 */
function profiletab_pagehandler($page) {
	
	// dirty check to prevent duplicate urls
	// we don't really want this url, just pagehandler to handle
	// response from route hook
	if (strpos(current_page_url(), elgg_get_site_url() . 'profiletab') === 0) {
		return false;
	}
	
	$profile = get_entity($page[0]);

	if (!($profile instanceof Profile)) {
		return false;
	}

	$profile->render();
	return true;
}