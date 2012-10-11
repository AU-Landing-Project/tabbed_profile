<?php

$user = $vars['entity'];
$profile = get_entity($vars['profile_guid']);

if (!$user && $profile) {
  $user = $profile->getOwnerEntity();
}

// sanity check
if ($user && !$user->canEdit()) {
  echo elgg_echo('tabbed_profile:invalid:permissions');
}


echo "<h1>" . elgg_echo('tabbed_profile:profile_tab') . "</h1>";


// Title
echo "<label for='title'>" . elgg_echo('tabbed_profile:title') . "</label><br>";
echo elgg_view('input/text', array(
    'name' => 'title',
    'value' => $profile->title ? $profile->title : '',
));

echo "<br><br>";

// Profile Type
echo "<label for='profile_type'>" . elgg_echo('tabbed_profile:profile_type') . "</label>&nbsp;&nbsp;";
echo elgg_view('input/dropdown', array(
    'id' => 'tabbed-profile-profile-type',
    'name' => 'profile_type',
    'value' => $profile->profile_type ? $profile->profile_type : 'widgets',
    'options_values' => array(
        'widgets' => elgg_echo('tabbed_profile:widgets'),
        'iframe' => elgg_echo('tabbed_profile:iframe')
    )
));