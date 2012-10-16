<?php

$profile = $vars['profile'];

$content = elgg_view('tabbed_profile/iframe', array('profile' => $profile));

echo elgg_view_layout('one_column', array('content' => $content));