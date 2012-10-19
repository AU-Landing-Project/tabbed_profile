<?php

$page_owner = elgg_get_page_owner_entity();


$profiles = elgg_get_entities_from_metadata(array(
    'types' => array('object'),
    'subtypes' => array('tabbed_profile'),
    'container_guids' => array($page_owner->getGUID()),
    'metadata_names' => array('order'),
    'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
    'limit' => 7
));

if ((!$profiles || !is_array($profiles)) && $page_owner->canEdit()) {
  $profiles = array();
  $profiles[] = tabbed_profile_generate_default_profile($page_owner);
}

$tabs = array();
$defaultdetected = false;

if ($profiles) {
  foreach ($profiles as $profile) {
    $text = $profile->title;
    if ($page_owner->canEdit()) {
      $text .= '<span class="elgg-icon elgg-icon-settings-alt tabbed-profile-edit"></span>';
      $class = 'tabbed-profile-sortable';
    }

    $tabs[] = array(
      'text' => $text,
      'href' => $profile->getURL(),
      'selected' => ($profile->getURL() == current_page_url()),
      'link_class' => 'tabbed_profile',
      'class' => $class,
      'rel' => $profile->getGUID()
    );
    
    if ($profile->default) {
      $defaultdetected = true;
    }
    //$profile->delete();
  }
}


if (!$defaultdetected && elgg_get_plugin_setting('private_user_profile', 'tabbed_profile') == 'no') {
  // if there's still no default tab, and we can't create one, and default privacy is turned off
  // we need to default it
  $default = array(
    'text' => elgg_echo('tabbed_profile:default'),
      'href' => $page_owner->getURL() . '/tab/default',
      'selected' => ($page_owner->getURL() . '/tab/default' == current_page_url())
  );
  
  array_unshift($tabs, $default);
}


if ($page_owner->canEdit() && count($profiles < 7)) {
  elgg_load_js('lightbox');
  elgg_load_css('lightbox');
  
  $tabs[] = array(
    'text' => '<span class="elgg-icon elgg-icon-round-plus"></span>',
      'href' => elgg_get_site_url() . 'ajax/view/tabbed_profile/edit?guid=' . $page_owner->getGUID(),
      'class' => 'tabbed-profile-add elgg-state-fixed',
      'link_class' => 'elgg-lightbox',
      'selected' => false
  );
}

// only show tabs if we're editing, or if there's more than one
if ($page_owner->canEdit() || count($tabs) > 1) {
  echo elgg_view('navigation/tabs', array('tabs' => $tabs, 'id' => 'profile-tabs-container'));
}