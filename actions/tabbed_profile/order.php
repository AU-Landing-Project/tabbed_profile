<?php

$guid = get_input('guid');
$order = get_input('order');

$profile = get_entity($guid);
if (!$profile) {
  register_error(elgg_echo('tabbed_profile:invalid:permissions'));
  forward();
}

$container = $profile->getContainerEntity();
if (!$container || !$container->canEdit()) {
  register_error(elgg_echo('tabbed_profile:invalid:permissions'));
  forward();
}

// update the orders
$tabs = elgg_get_entities_from_metadata(array(
    'types' => array('object'),
    'subtypes' => array('tabbed_profile'),
    'container_guids' => array($container->getGUID()),
    'metadata_names' => array('order'),
    'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
    'limit' => 0
));

$profile->order = $order;

$o = 0;
foreach ($tabs as $tab) {
  if ($tab->getGUID() == $profile->getGUID()) {
    // this is the tab we're updating, order is the value passed through
    // skip it
    continue;
  }
  $o++;
  if ($o == $order) {
    $o++;
  }
  
  $tab->order = $o;
}
