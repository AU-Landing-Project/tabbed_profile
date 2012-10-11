<?php

function tabbed_profile_user_router($hook, $type, $return, $params) {
  
  elgg_load_library('tabbed_profile');
  elgg_load_js('tabbed_profile.js');
  
  if ($return['segments'][1] == 'tab') {
    // sanity checking
    $user = get_user_by_username($return['segments'][0]);
    
    if (!$user) {
      return $return;
    }
    
    $profile = get_entity($return[2]);
    if (!elgg_instanceof($profile, 'object', 'tabbed_profile')) {
      return $return;
    }
    
    // so we have a valid user and a valid profile)
  }
  
  // default profile page
  $user = get_user($return['segments'][0]);
  if ($user) {
    $profile = elgg_get_entities_from_metadata(array(
      'types' => array('object'),
      'subtypes' => array('tabbed_profile'),
      'owner_guids' => array($user->getGUID()),
      'metadata_names' => array('order'),
      'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
      'limit' => 1
    ));
    
    if ($profile && !$profile[0]->default) {
      forward($profile[0]->getURL());
    }
  }
}


function tabbed_profile_group_router($hook, $type, $return, $params) {
  
  elgg_load_library('tabbed_profile');
  elgg_load_js('tabbed_profile.js');
  
  if ($return['segments'][0] == 'profile') {
    
  }
}



function tabbed_profile_permissions_check($hook, $type, $return, $params) {
  if (elgg_get_context() == 'tabbed_profile_permissions') {
    return true;
  }
}