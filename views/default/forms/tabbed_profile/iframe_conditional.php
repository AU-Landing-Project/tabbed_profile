<?php
$profile = $vars['entity'];

// iframe url
echo "<label>" . elgg_echo('tabbed_profile:iframe:label') . "</label><br>";
echo elgg_view('input/url', array(
    'name' => 'iframe_url',
    'value' => $profile->iframe_url ? $profile->iframe_url : 'http://'
));

echo '<div class="elgg-subtext">';
echo elgg_echo('tabbed_profile:iframe:help');
echo '</div>';

echo "<br><br>";


// iframe height
echo "<label>" . elgg_echo('tabbed_profile:iframe:height') . "</label>";
echo elgg_view('input/url', array(
    'name' => 'iframe_height',
    'value' => $profile->iframe_height ? $profile->iframe_height : 500,
    'maxlength' => '4',
    'class' => 'tabbed_profile_iframe_height'
)) . 'px';