<?php

$profile = $vars['profile'];
$url = elgg_normalize_url($profile->iframe_url);

echo elgg_view('output/url', array(
   'text' => '<span class="elgg-icon elgg-icon-eye"></span> View in a separate tab/window',
    'href' => $url,
    'target' => '_blank'
)) . '<br>';

echo '<iframe src="' . $url . '" style="width: 100%; height: ' . $profile->iframe_height . 'px;"></iframe>';