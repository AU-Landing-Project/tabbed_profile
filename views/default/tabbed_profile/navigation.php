<?php

if ((elgg_get_context() == 'profile')
        && (elgg_get_plugin_setting('user_enable', 'tabbed_profile') != 'no')
        ) {
  echo elgg_view('tabbed_profile/tabs');
}

if ((elgg_get_context() == 'group_profile')
        && (elgg_get_plugin_setting('user_enable', 'tabbed_profile') != 'no')
        ) {
  echo elgg_view('tabbed_profile/tabs');
}