<?php

echo elgg_echo('tabbed_profile:avatar:select:size') . ': ';

echo elgg_view('input/dropdown', array(
	'name' => 'params[avatar_size]',
	'value' => $vars['entity']->avatar_size ? $vars['entity']->avatar_size : 'large',
	'options_values' => array(
		'tiny' => elgg_echo('tiny'),
		'small' => elgg_echo('small'),
		'medium' => elgg_echo('medium'),
		'large' => elgg_echo('large')
	)
));

echo '<br><br>';

echo elgg_echo('tabbed_profile:avatar:select:align') . ': ';

echo elgg_view('input/dropdown', array(
	'name' => 'params[avatar_align]',
	'value' => $vars['entity']->avatar_align ? $vars['entity']->avatar_align : 'center',
	'options_values' => array(
		'left' => elgg_echo('left'),
		'center' => elgg_echo('center'),
		'right' => elgg_echo('right')
	)
));

echo '<br><br>';
