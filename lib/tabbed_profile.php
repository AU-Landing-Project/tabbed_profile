<?php

function tabbed_profile_draw_user_profile($profile) {
  $layout = 'tabbed_profile_' . $profile->profile_type;
  
  $params = array('profile' => $profile);
  
  if ($profile->profile_type == 'widgets') {
    if ($profile->widget_profile_display == 'yes') {
      $params['content'] = elgg_view('profile/wrapper');
    }

    $params['num_columns'] = (int) $profile->widget_layout;
  }
  
	$body = elgg_view_layout($layout, $params);
  
  echo elgg_view_page($profile->title, $body);
}

function tabbed_profile_generate_default_profile($page_owner) {
  if (!elgg_instanceof($page_owner, 'user') && !elgg_instanceof($page_owner, 'group')) {
    return false;
  }
  
  $context = elgg_get_context();
  elgg_set_context('tabbed_profile_permissions');
  
  $profile = new ElggObject();
  $profile->subtype = 'tabbed_profile';
  $profile->title = elgg_echo('tabbed_profile:default');
  $profile->owner_guid = elgg_get_logged_in_user_guid();
  $profile->container_guid = $page_owner->getGUID();
  $profile->access_id = ACCESS_PUBLIC;
  $profile->save();
  
  // save our metadata
  $profile->order = 1;
  $profile->default = 1;
  $profile->profile_type = 'widgets';
  $profile->widget_layout = 3;
  
  $page_owner->tabbed_profile_setup = 1;
  
  elgg_set_context($context);
  
  return $profile;
}


function tabbed_profile_get_last_order($container) {
  $profiles = elgg_get_entities_from_metadata(array(
    'types' => array('object'),
    'subtypes' => array('tabbed_profile'),
    'container_guids' => array($container->getGUID()),
    'metadata_names' => array('order'),
    'order_by_metadata' => array('name' => 'order', 'direction' => 'DESC', 'as' => 'integer'),
    'limit' => 1
  ));
  
  if (!$profiles || !is_array($profiles)) {
    return 0;
  }
  
  return $profiles[0]->order;
}