<?php

namespace AU\TabbedProfile;

$profile = $vars['profile'];

echo elgg_view('tabbed_profile/iframe', array('profile' => $profile));