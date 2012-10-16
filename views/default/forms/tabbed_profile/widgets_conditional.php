<?php

$profile = $vars['entity'];

// display the profile owners block?
echo '<label>' . elgg_echo('tabbed_profile:widgets:display:profile') . '</label>&nbsp;&nbsp;';
echo elgg_view('input/dropdown', array(
   'name' => 'widget_profile_display',
    'value' => $profile->widget_profile_display ? $profile->widget_profile_display : 'yes',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo '<br><br>';


$options_values = array();
for ($i=1; $i<7; $i++) {
  $options_values[$i] = $i;
}

echo '<label>' . elgg_echo('tabbed_profile:widgets:columns') . '</label>&nbsp;&nbsp;';
echo elgg_view('input/dropdown', array(
    'name' => 'widget_layout',
    'value' => $profile->widget_layout ? $profile->widget_layout : 3,
    'options_values' => $options_values
));
