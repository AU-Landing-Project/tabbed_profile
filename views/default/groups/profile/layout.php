<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */
$profile = $vars['profile'];

if (!$profile || ($profile && $profile->widget_profile_display == 'yes')) {
  echo elgg_view('groups/profile/summary', $vars);
}

if (group_gatekeeper(false)) {
	echo elgg_view('groups/profile/widgets', $vars);
} else {
	echo elgg_view('groups/profile/closed_membership', $vars);
}
