<?php

namespace AU\TabbedProfile;

$profile = $vars['profile'];
$url = elgg_normalize_url($profile->iframe_url);

echo elgg_view('output/url', array(
   'text' => '<span class="elgg-icon elgg-icon-eye"></span> ' . elgg_echo('tabbed_profile:url:open_tab'),
    'href' => $url,
    'target' => '_blank'
)) . '<br>';

echo '<iframe src="' . $url . '" style="width: 100%; height: ' . $profile->iframe_height . 'px;"></iframe>';