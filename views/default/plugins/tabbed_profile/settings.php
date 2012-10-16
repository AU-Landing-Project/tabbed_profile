<?php

// enable tabbed user profile?
echo elgg_view('input/dropdown', array(
    'name' => 'params[user_enable]',
    'value' => $vars['entity']->user_enable ? $vars['entity']->user_enable : 'yes',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo ' ' . elgg_echo('tabbed_profile:settings:user_enable');

echo "<br><br>";


// allow restricted permissions for default profile?
echo elgg_view('input/dropdown', array(
    'name' => 'params[private_user_profile]',
    'value' => $vars['entity']->private_user_profile ? $vars['entity']->private_user_profile : 'yes',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo ' ' . elgg_echo('tabbed_profile:settings:private_user_profile');

echo "<br><br>";


// enable tabbed group profile?
echo elgg_view('input/dropdown', array(
    'name' => 'params[group_enable]',
    'value' => $vars['entity']->group_enable ? $vars['entity']->group_enable : 'yes',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
));

echo ' ' . elgg_echo('tabbed_profile:settings:group_enable');

echo "<br><br>";