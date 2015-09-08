<?php

namespace AU\TabbedProfile;

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

$profile->setOrder($order);
