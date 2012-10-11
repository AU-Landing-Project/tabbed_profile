<?php

function tabbed_profile_generate_default_profile($page_owner) {
  if (!elgg_instanceof($page_owner, 'user') && !elgg_instanceof($page_owner, 'group')) {
    return false;
  }
  
  $context = elgg_get_context();
  elgg_set_context('tabbed_profile_permissions');
  
  $profile = new ElggObject();
  $profile->subtype = 'tabbed_profile';
  $profile->title = elgg_echo('tabbed_profile:default');
  $profile->owner_guid = $page_owner->getGUID();
  $profile->container_guid = $page_owner->getGUID();
  $profile->access_id = ACCESS_PUBLIC;
  $profile->save();
  $profile->order = 1;
  $profile->default = 1;
  
  elgg_set_context($context);
  
  return $profile;
}