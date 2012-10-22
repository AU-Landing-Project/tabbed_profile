<?php

function tabbed_profile_user_router($hook, $type, $return, $params) {
  
  elgg_load_library('tabbed_profile');
  elgg_load_js('tabbed_profile.js');
  
  $user = get_user_by_username($return['segments'][0]);
  $private = (elgg_get_plugin_setting('private_user_profile', 'tabbed_profile') != 'no');
  
  if ($return['segments'][1] == 'tab') {
    // sanity checking  
    // short circuit if invalid or banned username
    if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
  		register_error(elgg_echo('profile:notfound'));
    	forward();
    }
  
    if ($return['segments'][2] == 'default' && !$private) {
      return $return;
    }
    
    $profile = get_entity($return['segments'][2]);
    
    if (!elgg_instanceof($profile, 'object', 'tabbed_profile')) {
      forward($user->getURL());
    }
    
    if ($profile->default) {
      return $return;
    }
    
    // so we have a valid user and a valid profile
    elgg_set_page_owner_guid($user->getGUID());
    tabbed_profile_draw_user_profile($profile);
    return true;
  }
  
  // default profile page
  // show the first profile we have access to see
  if ($user && $user->tabbed_profile_setup) {
    $profile = elgg_get_entities_from_metadata(array(
      'types' => array('object'),
      'subtypes' => array('tabbed_profile'),
      'container_guids' => array($user->getGUID()),
      'metadata_names' => array('order'),
      'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
      'limit' => 1
    ));
        
    // forward to the first tab we have access to
    // default profile gets handled by the profile plugin
    if ($profile && !$profile[0]->default) {
      forward($profile[0]->getURL());
    }
    
    if (!$profile 
            && $private
            && !$user->canEdit()
            ) {
      register_error(elgg_echo('tabbed_profile:private:profile'));
      forward(REFERER);
    }
  }
}



function tabbed_profile_group_router($hook, $type, $return, $params) {
  
  if ($return['segments'][0] == 'profile') {
    elgg_load_library('tabbed_profile');
    elgg_load_library('elgg:groups');
    elgg_load_js('tabbed_profile.js');
    
    $group = get_entity($return['segments'][1]);
    if (!elgg_instanceof($group, 'group')) {
      return $return;
    }
    
    if ($return['segments'][3] == 'tab') {      
      $profile = get_entity($return['segments'][4]);
    
      if (!elgg_instanceof($profile, 'object', 'tabbed_profile')) {
        return $return;
      }
      
      if ($profile->default) {
        return $return;
      }
    
      // so we have a valid group and a valid profile
      elgg_set_page_owner_guid($group->getGUID());
      tabbed_profile_draw_group_profile($profile);
      return true;
    }
    
    // default profile page
    // show the first profile we have access to see
    if ($group && $group->tabbed_profile_setup) {
      $profile = elgg_get_entities_from_metadata(array(
        'types' => array('object'),
        'subtypes' => array('tabbed_profile'),
        'container_guids' => array($group->getGUID()),
        'metadata_names' => array('order'),
        'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
        'limit' => 1
      ));
    
      // forward to the first tab we have access to
      if ($profile && !$profile[0]->default) {
        forward($profile[0]->getURL());
      }
    }
  }
}



function tabbed_profile_permissions_check($hook, $type, $return, $params) {
  if (elgg_get_context() == 'tabbed_profile_permissions') {
    return true;
  }
}


function tabbed_profile_widget_context_normalize($hook, $type, $return, $params) {
  if (strpos($return, 'tabbed_profile_user_') !== false) {
    return 'profile';
  }
}