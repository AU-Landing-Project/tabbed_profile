<?php

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
  elgg_register_entity_url_handler('user', 'all', 'tabbed_profile_user_url_handler');
  
  elgg_register_library('tabbed_profile', dirname(__FILE__) . '/lib/tabbed_profile.php');
  
  // register our plugin hooks
 elgg_register_plugin_hook_handler('route', 'profile', 'tabbed_profile_user_router');
 elgg_register_plugin_hook_handler('route', 'groups', 'tabbed_profile_group_router');
 elgg_register_plugin_hook_handler('permissions_check', 'all', 'tabbed_profile_permissions_check');
 elgg_register_plugin_hook_handler('available_widgets_context', 'widget_manager', 'tabbed_profile_widget_context_normalize');
 
 // register actions
 elgg_register_action('tabbed_profile/edit', dirname(__FILE__) . '/actions/tabbed_profile/edit.php');
 elgg_register_action('tabbed_profile/order', dirname(__FILE__) . '/actions/tabbed_profile/order.php');
 
 elgg_register_ajax_view('tabbed_profile/edit');
}


//
// generate urls for profile tabs
function tabbed_profile_url_handler($object) {
  $container = $object->getContainerEntity();
  return $container->getURL() . '/tab/' . $object->getGUID() . '/' . elgg_get_friendly_title($object->title);
}
