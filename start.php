<?php

define(TABBED_PROFILE_WIDGET_RELATIONSHIP, 'widget_of_profile_tab');
define(TABBED_PROFILE_MAX_TABS, 7);

elgg_register_event_handler('init', 'system', 'tabbed_profile_init');

// include our global functions
include dirname(__FILE__) . '/lib/hooks.php';

function tabbed_profile_init() {
  
  // Extend our views
  elgg_extend_view('css/elgg', 'tabbed_profile/css');
  elgg_extend_view('page/layouts/widgets', 'tabbed_profile/navigation', 0);
  elgg_extend_view('groups/profile/layout', 'tabbed_profile/navigation', 0);
  elgg_extend_view('page/layouts/tabbed_profile_widgets', 'tabbed_profile/navigation', 0);
  elgg_extend_view('tabbed_profile/iframe', 'tabbed_profile/navigation', 0);
  
  // register our js
  $js = elgg_get_simplecache_url('js', 'tabbed_profile/js');
	elgg_register_simplecache_view('js/tabbed_profile/js');
	elgg_register_js('tabbed_profile.js', $js);
  
  // create urls for tabs
  elgg_register_entity_url_handler('object', 'tabbed_profile', 'tabbed_profile_url_handler');
  
  elgg_register_library('tabbed_profile', dirname(__FILE__) . '/lib/tabbed_profile.php');
  
  // register our plugin hooks
 elgg_register_plugin_hook_handler('route', 'profile', 'tabbed_profile_user_router');
 elgg_register_plugin_hook_handler('route', 'groups', 'tabbed_profile_group_router');
 elgg_register_plugin_hook_handler('permissions_check', 'all', 'tabbed_profile_permissions_check');
 elgg_register_plugin_hook_handler('available_widgets_context', 'widget_manager', 'tabbed_profile_widget_context_normalize');
 elgg_register_plugin_hook_handler('action', 'widgets/add', 'tabbed_profile_widgets_add_action_handler');
 
 // register actions
 elgg_register_action('tabbed_profile/edit', dirname(__FILE__) . '/actions/tabbed_profile/edit.php');
 elgg_register_action('tabbed_profile/order', dirname(__FILE__) . '/actions/tabbed_profile/order.php');
 
 // register other events
 elgg_register_event_handler('create', 'object', 'tabbed_profile_widget_create');
 
 elgg_register_ajax_view('tabbed_profile/edit');
 
 // register our widgets
 elgg_register_widget_type('group_avatar', elgg_echo("tabbed_profile:group_avatar:widget:title"), elgg_echo("tabbed_profile:group_avatar:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('group_profile_stats', elgg_echo("tabbed_profile:group_stats:widget:title"), elgg_echo("tabbed_profile:group_stats:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('group_profile_block', elgg_echo("tabbed_profile:group_profile:widget:title"), elgg_echo("tabbed_profile:group_profile:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('user_avatar', elgg_echo("tabbed_profile:user_avatar:widget:title"), elgg_echo("tabbed_profile:user_avatar:widget:description"), 'profile', TRUE);
 elgg_register_widget_type('user_profile_block', elgg_echo("tabbed_profile:user_details:widget:title"), elgg_echo("tabbed_profile:user_details:widget:description"), 'profile', TRUE);
 elgg_register_widget_type('user_menu_block', elgg_echo("tabbed_profile:user_menu:widget:title"), elgg_echo("tabbed_profile:user_menu:widget:description"), 'profile', TRUE);
}


//
// generate urls for profile tabs
function tabbed_profile_url_handler($object) {
  $container = $object->getContainerEntity();
  return $container->getURL() . '/tab/' . $object->getGUID() . '/' . elgg_get_friendly_title($object->title);
}


// modify widgets context and create relationship if necessary
function tabbed_profile_widget_create($event, $type, $object) {
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
