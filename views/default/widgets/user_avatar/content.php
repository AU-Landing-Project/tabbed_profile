<?php

$user = $vars['entity']->getContainerEntity();
$size = $vars['entity']->avatar_size ? $vars['entity']->avatar_size : 'large';
$align = $vars['entity']->avatar_align ? $vars['entity']->avatar_align : 'center';

echo "<div style=\"text-align: {$align};\">";
echo elgg_view_entity_icon($user, $size, array('use_link' => false, 'use_hover' => false));
echo "</div>";