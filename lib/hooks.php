<?php

namespace AU\TabbedProfile;

function user_router($hook, $type, $return, $params) {

	//@todo amd
	elgg_require_js('tabbed_profile/profile');

	$user = get_user_by_username($return['segments'][0]);
	$private = (elgg_get_plugin_setting('private_user_profile', 'tabbed_profile') != 'no');

	if ($return['segments'][1] == 'tab') {
		// sanity checking  
		// short circuit if invalid or banned username
		if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
			register_error(elgg_echo('profile:notfound'));
			forward();
		}

		if ($return['segments'][2] == 'default' && !$private) {
			return $return;
		}

		$profile = get_entity($return['segments'][2]);

		if (!elgg_instanceof($profile, 'object', 'tabbed_profile')) {
			forward($user->getURL());
		}

		// so we have a valid user and a valid profile
		elgg_set_page_owner_guid($user->getGUID());
		$profile->render();
		return false;
	} elseif (empty($return['segments'][1])) {
		// default profile page
		// show the first profile we have access to see
		if ($user && $user->tabbed_profile_setup) {
			$profile = elgg_get_entities_from_metadata(array(
				'types' => array('object'),
				'subtypes' => array('tabbed_profile'),
				'container_guids' => array($user->getGUID()),
				'metadata_names' => array('order'),
				'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
				'limit' => 1
			));

			// forward to the first tab we have access to
			// default profile gets handled by the profile plugin
			if ($profile) {
				forward($profile[0]->getURL());
			}

			if (!$profile && $private && !$user->canEdit()
			) {
				register_error(elgg_echo('tabbed_profile:private:profile'));
				forward(REFERER);
			}
		}
	}
}

function group_router($hook, $type, $return, $params) {

	if ($return['segments'][0] == 'profile') {
		elgg_load_library('elgg:groups');
		elgg_load_js('tabbed_profile.js');

		$group = get_entity($return['segments'][1]);
		if (!elgg_instanceof($group, 'group')) {
			return $return;
		}

		if ($return['segments'][3] == 'tab') {
			$profile = get_entity($return['segments'][4]);

			if (!elgg_instanceof($profile, 'object', 'tabbed_profile')) {
				return $return;
			}

			// so we have a valid group and a valid profile
			elgg_set_page_owner_guid($group->getGUID());
			$profile->render();
			return true;
		}

		// default profile page
		// show the first profile we have access to see
		if ($group && $group->tabbed_profile_setup) {
			$profile = elgg_get_entities_from_metadata(array(
				'types' => array('object'),
				'subtypes' => array('tabbed_profile'),
				'container_guids' => array($group->getGUID()),
				'metadata_names' => array('order'),
				'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
				'limit' => 1
			));

			// forward to the first tab we have access to
			if ($profile) {
				forward($profile[0]->getURL());
			}
		}
	}
}

function permissions_check($hook, $type, $return, $params) {
	if (elgg_get_context() == 'tabbed_profile_permissions') {
		return true;
	}
}

function widgets_add_action_handler($hook, $type, $return, $params) {
	$widget_context = get_input('context', false);
	if ($widget_context) {
		if (stristr($widget_context, 'tabbed_profile::') !== false) {
			$context_parts = explode('::', $widget_context);

			set_input("context", $context_parts[1]);
			set_input("tabbed_profile_guid", $context_parts[2]);
		}
	}
}

function widget_context_normalize($hook, $type, $return, $params) {
	if (strpos($return, 'tabbed_profile::') === 0) {
		$context_parts = explode('::', $return);

		return $context_parts[1];
	}
}

/**
 * Get url for our tabs
 * 
 * @param type $object
 * @return type
 */
function url_handler($hook, $type, $url, $params) {
	$object = $params['entity'];
	
	if (!($params['entity'] instanceof Profile)) {
		return $url;
	}

	$container = $object->getContainerEntity();
	return $container->getURL() . '/tab/' . $object->getGUID() . '/' . elgg_get_friendly_title($object->title);
}
