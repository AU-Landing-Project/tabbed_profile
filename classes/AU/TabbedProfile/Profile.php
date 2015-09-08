<?php

namespace AU\TabbedProfile;

class Profile extends \ElggObject {

	/**
	 * Set subtype to tabbed_profile.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'tabbed_profile';
	}

	/**
	 * No comments on profile entities
	 *
	 * @see ElggObject::canComment()
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0) {
		return false;
	}

	/**
	 * set the order of the tab relative to sibling tabs
	 * 
	 * @param type $order
	 */
	public function setOrder($order) {
		// update the orders
		$tabs = elgg_get_entities_from_metadata(array(
			'types' => array('object'),
			'subtypes' => array('tabbed_profile'),
			'container_guids' => array($this->container_guid),
			'metadata_names' => array('order'),
			'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
			'limit' => 0
		));

		$this->order = $order;

		$o = 0;
		foreach ($tabs as $tab) {
			if ($tab->guid == $this->guid) {
				continue;
			}

			$o++;

			if ($o == $order) {
				// the current tab has this spot, so iterate to the next one
				$o++;
			}

			$tab->order = $o;
		}
	}

	private function addDefaultMetadata() {
		elgg_push_context('tabbed_profile_permissions');
		$ignore = elgg_set_ignore_access(true);

		$page_owner = elgg_get_page_owner_entity();

		// save our metadata
		$this->order = $this->order ? $this->order : 1;
		$this->default = 1;
		$this->profile_type = $this->profile_type ? $this->profile_type : 'widgets';
		$this->widget_layout = $this->widget_layout ? $this->widget_layout : elgg_instanceof($page_owner, 'user') ? 3 : 2;
		$this->widget_profile_display = $this->widget_profile_display ? $this->widget_profile_display : 'yes';
		$this->group_sidebar = $this->group_sidebar ? $this->group_sidebar : 'yes';
		$this->md_version = '1.5';

		elgg_set_ignore_access($ignore);
		elgg_pop_context();
	}

	/**
	 * Render the profile
	 */
	public function render() {
		$container = $this->getContainerEntity();
		if ($container instanceof \ElggUser) {
			$this->drawUserProfile();
		} else {
			$this->drawGroupProfile();
		}
	}

	/**
	 * @todo check for 1.9 profile rendering
	 */
	private function drawUserProfile() {
		if ($this->default && ($this->md_version != '1.5')) {
			$this->addDefaultMetadata();
		}

		$layout = 'tabbed_profile_' . $this->profile_type;
		$owner = $this->getContainerEntity();

		elgg_push_breadcrumb($owner->name, $owner->getURL());
		elgg_push_breadcrumb($this->title);

		$params = array('profile' => $this);

		if ($this->profile_type == 'widgets') {
			if ($this->widget_profile_display == 'yes') {
				$params['content'] = elgg_view('profile/wrapper');
			}

			$params['num_columns'] = (int) $this->widget_layout;
		}

		$content = elgg_view_layout($layout, $params);

		$body = elgg_view_layout('one_column', array(
			'content' => $content,
			'title' => $owner->name
		));

		echo elgg_view_page($this->title, $body);
	}

	/**
	 * @todo check for 1.9 version of group page rendering
	 * 
	 * @global boolean $autofeed
	 * @global type $NOTIFICATION_HANDLERS
	 */
	private function drawGroupProfile() {
		// turn this into a core function
		global $autofeed;
		$autofeed = true;

		// this context issue is unique to the AU theme, not necessary for core compatibility
		if ($this->profile_type == 'widgets' && $this->group_sidebar == 'no') {
			elgg_push_context('group_profile_tabs');
		} else {
			// default
			elgg_push_context('group_profile');
		}

		$group = $this->getContainerEntity();
		$title = $group->name;

		elgg_push_breadcrumb($group->name);

		groups_register_profile_buttons($group);

		$layout = 'content';
		$content = '';
		if ($this->group_sidebar == 'no') {
			$layout = 'one_column';
			$content .= elgg_view_menu('title');
		}

		if (group_gatekeeper(false) && $this->group_sidebar == 'yes') {
			$sidebar = '';
			if (elgg_is_active_plugin('search')) {
				$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
			}
			$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));

			$subscribed = false;
			if (elgg_is_active_plugin('notifications')) {
				global $NOTIFICATION_HANDLERS;

				foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
					$relationship = check_entity_relationship(elgg_get_logged_in_user_guid(), 'notify' . $method, $guid);

					if ($relationship) {
						$subscribed = true;
						break;
					}
				}
			}

			$sidebar .= elgg_view('groups/sidebar/my_status', array(
				'entity' => $group,
				'subscribed' => $subscribed
			));
		} else {
			$sidebar = '';
		}

		if ($this->profile_type == 'iframe') {
			$content .= elgg_view_layout('tabbed_profile_iframe', array('profile' => $this));
		} else {
			$content .= elgg_view('groups/profile/layout', array('entity' => $group, 'profile' => $this));
		}

		$params = array(
			'content' => $content,
			'sidebar' => $sidebar,
			'title' => $title,
			'filter' => '',
		);
		$body = elgg_view_layout($layout, $params);

		echo elgg_view_page($group->name, $body);
	}

	/**
	 * get the last order for the profile of a container
	 * 
	 * @param type $container
	 * @return int
	 */
	static function getLastOrder($container) {
		$profiles = elgg_get_entities_from_metadata(array(
			'types' => array('object'),
			'subtypes' => array('tabbed_profile'),
			'container_guids' => array($container->getGuid()),
			'metadata_names' => array('order'),
			'order_by_metadata' => array('name' => 'order', 'direction' => 'DESC', 'as' => 'integer'),
			'limit' => 1
		));

		if (!$profiles || !is_array($profiles)) {
			return 0;
		}

		return (int) $profiles[0]->order;
	}

	/**
	 * get the widgets for this profile for a specific context
	 * 
	 * @param type $context
	 * @return type
	 */
	public function getWidgets($context) {
		$dbprefix = elgg_get_config('dbprefix');

		$options = array(
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $this->container_guid,
			'private_setting_name' => 'context',
			'private_setting_value' => $context,
			'limit' => 0
		);

		// for default profiles widgets won't have a relationship
		// for other tabs there will be a relationship to the profile
		if ($this->default) {
			$options['wheres'] = array(
				"NOT EXISTS (
				SELECT 1 FROM {$dbprefix}entity_relationships r
				WHERE r.guid_one = e.guid
				AND r.relationship = '" . TABBED_PROFILE_WIDGET_RELATIONSHIP . "')"
			);
		} else {
			$options['wheres'] = array(
				"EXISTS (
		  SELECT 1 FROM {$dbprefix}entity_relationships r
		  WHERE r.guid_one = e.guid
		  AND r.relationship = '" . TABBED_PROFILE_WIDGET_RELATIONSHIP . "'
		  AND r.guid_two = {$this->guid})"
			);
		}

		$widgets = elgg_get_entities_from_private_settings($options);
		if (!$widgets) {
			return array();
		}

		$sorted_widgets = array();
		foreach ($widgets as $widget) {
			if (!isset($sorted_widgets[(int) $widget->column])) {
				$sorted_widgets[(int) $widget->column] = array();
			}
			$sorted_widgets[(int) $widget->column][$widget->order] = $widget;
		}

		foreach ($sorted_widgets as $col => $widgets) {
			ksort($sorted_widgets[$col]);
		}

		return $sorted_widgets;
	}

	/**
	 * Generate a default profile entity for a page owner
	 * 
	 * @param type $page_owner
	 * @return \AU\TabbedProfile\Profile|boolean
	 */
	static function generateDefaultProfile($page_owner) {
		if (!elgg_instanceof($page_owner, 'user') && !elgg_instanceof($page_owner, 'group')) {
			return false;
		}

		elgg_push_context('tabbed_profile_permissions');
		$ignore = elgg_set_ignore_access(true);

		$profile = new Profile();
		$profile->title = elgg_echo('tabbed_profile:default');
		$profile->owner_guid = $page_owner->guid;
		$profile->container_guid = $page_owner->guid;
		$profile->access_id = ACCESS_PUBLIC;
		$profile->save();

		// save our metadata
		$profile->order = 1;
		$profile->default = 1;
		$profile->profile_type = 'widgets';
		$profile->widget_layout = elgg_instanceof($page_owner, 'user') ? 3 : 2;
		$profile->widget_profile_display = 'yes';
		$profile->group_sidebar = 'yes';
		$profile->md_version = '1.5';

		$page_owner->tabbed_profile_setup = 1;

		elgg_set_ignore_access($ignore);
		elgg_pop_context();

		return $profile;
	}

}
