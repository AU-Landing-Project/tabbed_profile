<?php

$container = $vars['entity'];
$profile = get_entity($vars['profile_guid']);

if (!$container && $profile) {
  $container = $profile->getContainerEntity();
}

// sanity check
if (!$container || !$container->canEdit()) {
  echo elgg_echo('tabbed_profile:invalid:permissions');
}

$old_page_owner_guid = elgg_get_page_owner_guid();
elgg_set_page_owner_guid($container->getGUID());

echo "<h1>" . elgg_echo('tabbed_profile:profile_tab') . "</h1>";


// Title
echo "<label for='title'>" . elgg_echo('tabbed_profile:title') . "</label><br>";
echo elgg_view('input/text', array(
    'name' => 'title',
    'value' => $profile->title ? $profile->title : '',
));

if (!$profile->default) {
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

  echo "<br><br>";

  // widgets profile type conditional settings

  $style = ($profile && ($profile->profile_type != 'widgets')) ? ' style="display:none"' : '';
  echo '<div class="tabbed-profile-widgets-form"' . $style . '>';
  echo elgg_view('forms/tabbed_profile/widgets_conditional', array('entity' => $profile));
  echo '</div>';


  // iframe profile type conditional settings
  $style = ($profile && ($profile->profile_type == 'iframe')) ? '' : ' style="display:none"';
  echo '<div class="tabbed-profile-iframe-form"' . $style . '>';
  echo elgg_view('forms/tabbed_profile/iframe_conditional', array('entity' => $profile));
  echo '</div>';

  if (elgg_instanceof($container, 'group')) {
    echo "<label>" . elgg_echo('tabbed_profile:group:sidebar') . "</label>";
    echo elgg_view('input/dropdown', array(
      'name' => 'group_sidebar',
      'value' => $profile->group_sidebar ? $profile->group_sidebar : 'yes',
      'options_values' => array(
          'yes' => elgg_echo('option:yes'),
          'no' => elgg_echo('option:no')
        )
    ));
  
    echo "<br><br>";
  }
}



// access
if (!$profile->default
        || (elgg_get_plugin_setting('private_user_profile', 'tabbed_profile') == 'yes' && elgg_instanceof($container, 'user'))
        ) {
  $options = array('name' => 'access');
  if ($profile) {
    $options['value'] = $profile->access_id;
  }


  echo "<label>" . elgg_echo('tabbed_profile:tab:access') . "</label><br>";
  echo elgg_view('input/access', $options);
  echo "<br><br>";
}


if (!$profile->default) {
  // option to delete
  if ($profile) {
    echo elgg_view('input/checkbox', array('name' => 'delete', 'value' => 1, 'id' => 'tabbed-profile-delete-profile'));
    echo elgg_echo('tabbed_profile:profile:delete');
  }
}

echo "<br><br>";

// If we're editing, we need to pass the profile to the action
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $profile->guid));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container->getGUID()));

// submit
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));

elgg_set_page_owner_guid($old_page_owner_guid);