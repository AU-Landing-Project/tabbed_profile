<?php

namespace AU\TabbedProfile;

$user = $vars['entity']->getContainerEntity();

echo elgg_view('profile/details', array('entity' => $user));