<?php

function tt_admin_menu() {
    /**
     * Event Menu's
     */

	add_menu_page(
		'Event',
		'Evenement',
		'manage_options',
		'tt-events',
		'admin_event_page',
		'dashicons-calendar',
		3
	);

    add_submenu_page(
        'tt-events',
        'Guestlist',
        'Gastenlijst',
        'manage_options',
        'tt-events-guests',
        'admin_event_guests_page'
    );

    /**
     * App Menu's
     */
	add_menu_page(
		'My Top Level Menu Example',
		'TweensTalent App',
		'manage_options',
		'tt-app',
		'admin_app_page',
		'dashicons-tickets',
		3
	);

    add_submenu_page(
        'tt-app',
        'app sub title',
        'Vragen',
        'manage_options',
        'tt-app-questions',
        'myplguin_admin_sub_page'
    );

    add_submenu_page(
        'tt-talent-app',
        'app sub title',
        'Scholen',
        'manage_options',
        'tt-events/questions',
        'myplguin_admin_sub_page'
    );

    /**
     * Statictics Menu
     */
    add_menu_page(
        'My Top Level Menu Example2',
        'Z',
        'manage_options',
        'tt-title',
        'myplguin_admin_page2',
        'dashicons-tickets',
        3
    );
}