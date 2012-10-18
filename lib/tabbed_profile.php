<?php

function tabbed_profile_draw_user_profile($profile) {
  $layout = 'tabbed_profile_' . $profile->profile_type;
  $owner = $profile->getContainerEntity();
  
  elgg_push_breadcrumb($owner->name, $owner->getURL());
  elgg_push_breadcrumb($profile->title);
  
  $params = array('profile' => $profile);
  
  if ($profile->profile_type == 'widgets') {
    if ($profile->widget_profile_display == 'yes') {
      $params['content'] = elgg_view('profile/wrapper');
    }

    $params['num_columns'] = (int) $profile->widget_layout;
  }
  
	$content = elgg_view_layout($layout, $params);
  
  $body = elgg_view_layout('one_column', array(
      'content' => $content,
      'title' => $owner->name
  ));
  
  echo elgg_view_page($profile->title, $body);
}


function tabbed_profile_draw_group_profile($profile) {
  // turn this into a core function
	global $autofeed;
	$autofeed = true;

	elgg_push_context('group_profile');

	$group = elgg_get_page_owner_entity();

	elgg_push_breadcrumb($group->name);

	groups_register_profile_buttons($group);
  elgg_set_context('tabbed-profile-group');
  
  $layout = 'one_column';
	if (group_gatekeeper(false) && $profile->group_sidebar == 'yes') {
    $layout = 'content';
		$sidebar = '';
		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
		}
		$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));
	} else {
		$sidebar = '';
	}
  
  if ($profile->profile_type == 'iframe') {
    $content = elgg_view_layout('tabbed_profile_iframe', array('profile' => $profile));
  }
  else {
    $content = elgg_view('groups/profile/layout', array('entity' => $group, 'profile' => $profile));
  }

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $group->name,
		'filter' => '',
	);
	$body = elgg_view_layout($layout, $params);

	echo elgg_view_page($group->name, $body);
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
  $profile->widget_layout = elgg_instanceof($page_owner, 'user') ? 3 : 2;
  $profile->group_sidebar = 'yes';
  
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