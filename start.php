<?php

/**
 * Moderators for Roles plugin
 * 
 * @author Andras Szepeshazi
 * @copyright Arck Interactive, LLC 2012
 * @link http://www.arckinteractive.com/
 */
elgg_register_event_handler('init', 'system', 'roles_moderators_init');

/**
 * Initialize
 * @return void
 */
function roles_moderators_init() {
	elgg_register_plugin_hook_handler('roles:config', 'role', 'roles_moderators_config', 700);
}

/**
 * Adds moderator role config to global roles configuration
 *
 * @param string $hook_name "roles:config"
 * @param string $hook_type "role"
 * @param array  $roles     Roles config
 * @param array  $params    Hook params
 * @return array
 */
function roles_moderators_config($hook_name, $hook_type, $roles, $params) {

	if (!is_array($roles)) {
		$roles = array();
	}

	$roles['moderator'] = array(
		'title' => 'roles_moderators:role:title',
		'extends' => array(ADMIN_ROLE),
		'permissions' => array(
			'pages' => array(
				'regexp(/^admin\/((?!administer_utilities\/reportedcontent).)*$/)' => 'deny',
			),
			'menus' => array(
				'topbar::administration' => 'deny',
			),
			'actions' => array(
				'regexp(/^admin\/((?!user\/ban|user\/unban).)*$/)' => 'deny',
			),
			'views' => array(
				'admin/sidebar' => 'deny',
				'roles/settings/account/role' => array(
					'rule' => 'replace',
					'view_replacement' => array(
						'location' => 'mod/roles_moderators/views/override/',
					),
				),
			),
		),
	);

	if (elgg_is_active_plugin('reportedcontent')) {
		$roles['moderator']['menus']['site'] = array(
			'rule' => 'extend',
			'menu_item' => array(
				'name' => 'reported_content',
				'text' => elgg_echo('reportedcontent'),
				'href' => '/admin/administer_utilities/reportedcontent'
			),
		);
	}
	
	return $roles;
}
