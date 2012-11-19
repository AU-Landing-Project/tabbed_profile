<?php

if ((elgg_get_context() == 'profile')
        && (elgg_get_plugin_setting('user_enable', 'tabbed_profile') != 'no')
        ) {
  echo elgg_view('tabbed_profile/tabs');
}

if (in_array(elgg_get_context(), array('group_profile', 'group_profile_tabs'))
        && (elgg_get_plugin_setting('group_enable', 'tabbed_profile') != 'no')
        ) {
  echo elgg_view('tabbed_profile/tabs');
}