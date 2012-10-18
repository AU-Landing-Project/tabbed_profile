<?php
elgg_load_library('tabbed_profile');

$profile_guid = get_input('guid', false);
$container_guid = get_input('container_guid', false);

$title = get_input('title', false);
$profile_type = get_input('profile_type', 'widgets');
$widget_layout = get_input('widget_layout', 3);
$widget_profile_display = get_input('widget_profile_display', 'yes');
$iframe_url = get_input('iframe_url', false);
$iframe_height = get_input('iframe_height', false);
$group_sidebar = get_input('group_sidebar', 'yes');
$delete = get_input('delete', false);
$access = get_input('access');

$profile = get_entity($profile_guid);
$container = get_entity($container_guid);

if (!$container || !$container->canEdit() || ($profile && !$profile->canEdit())) {
  register_error(elgg_echo('tabbed_profile:invalid:permissions'));
}

// if we're deleting, we can take care of that and not worry about anything else
if ($profile && $delete) {
  
  //@TODO - delete any widgets on this profile
  $profile->delete();
  system_message(elgg_echo('tabbed_profile:tab:deleted'));
  forward($container->getURL());
}

// we're updating or creating a new one
// some sanity checking
if (!$title) {
  register_error(elgg_echo('tabbed_profile:error:notitle'));
  forward(REFERER);
}

// create/update our tab
$action_type = 'update';
if (!$profile) {
  $action_type = 'create';
  
  $profile = new ElggObject();
  $profile->subtype = 'tabbed_profile';
  $profile->owner_guid = $container->getGUID();
  $profile->container_guid = $container->getGUID();
  
  // set this tab as the last one
  $last_order = tabbed_profile_get_last_order($container);
  $profile->order = $last_order + 1;
}

$profile->title = $title;
$profile->access_id = $access;
$profile->save();

$profile->profile_type = $profile_type;
$profile->widget_layout = $widget_layout;
$profile->widget_profile_display = $widget_profile_display;
$profile->iframe_url = $iframe_url;
$profile->iframe_height = is_numeric($iframe_height) ? $iframe_height : 500;
$profile->group_sidebar = $group_sidebar;


system_message(elgg_echo('tabbed_profile:success:'.$action_type));
forward($profile->getURL());