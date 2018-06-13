<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 29/05/2018
 * Time: 14:03
 */

class TweensTalent
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ) );
    }

    public function add_admin_menu()
    {
        /**
         * Event Menu's
         */

        add_menu_page(
            'Event',
            'Evenement',
            'manage_options',
            'tt-events',
            'tt_event_page',
            'dashicons-calendar',
            3
        );

        /**
         * App Menu's
         */
        add_menu_page(
            'My Top Level Menu Example',
            'TweensTalent App',
            'manage_options',
            'tt-app',
            'tt_app_page',
            'dashicons-smartphone',
            3
        );

        /**
         * Statictics Menu
         */
        add_menu_page(
            'My Top Level Menu Example2',
            'Statistieken',
            'manage_options',
            'tt-title',
            'tt_statistics_page',
            'dashicons-chart-line',
            3
        );
    }

    public function load_scripts($hook)
    {
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');

        // moment plugin
        wp_register_script( 'moment-script', plugins_url( '../libraries/MomentJs/moment-with-locales.min.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'moment-script' );

        // popper
        wp_register_script( 'popper', plugins_url( '../libraries/Popper/popper.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'popper' );

        // datatables
        wp_register_script( 'dt-script', plugins_url( '../libraries/Datatables/datatables.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dt-script' );

        // custom datatables
        wp_register_script( 'dt-custom', plugins_url( '../js/datatables.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dt-custom' );

        // BootstrapMultiSelect plugin
        wp_register_script( 'bms', plugins_url( '../libraries/BootstrapMultiSelect/js/bootstrap-multiselect.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'bms' );

        // Multiselect custom
        wp_register_script( 'bms-custom', plugins_url( '../js/multiselect.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'bms-custom' );


        // datetime picker plugin
        wp_register_script( 'dtp-script', plugins_url( '../libraries/DatetimePicker/jquery.datetimepicker.full.min.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dtp-script' );

        // custom script for datetime picker
        wp_register_script( 'dtp-custom', plugins_url( '../js/datetime.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dtp-custom' );

        // custom script for address validation
        wp_register_script( 'av', plugins_url( '../js/addressValidation.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'av' );

        // FontAwesome svg with js
        wp_register_script( 'fa', plugins_url( '../libraries/FontAwesome/js/fontawesome-all.min.js', __FILE__ ), array(), null, true );
        wp_enqueue_script( 'fa' );

        // custom wp scripts
        wp_register_script( 'wpc', plugins_url( '../js/wp.custom.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'wpc' );
    }

    function load_styles($hook)
    {
        // DatetimePicker plugin
        wp_register_style( 'dtp-style', plugins_url( '../libraries/DatetimePicker/jquery.datetimepicker.min.css', __FILE__ ), array(), null, 'all' );
        wp_enqueue_style( 'dtp-style' );

        // DataTables
        wp_register_style( 'datatables', plugins_url( '../libraries/DataTables/datatables.css', __FILE__ ) );
        wp_enqueue_style( 'datatables' );

        // BootstrapMultiSelect
        wp_register_style( 'bms', plugins_url( '../libraries/BootstrapMultiSelect/css/bootstrap-multiselect.css', __FILE__ ) );
        wp_enqueue_style( 'bms' );

        // FontAwesome
        wp_register_style( 'fs', plugins_url( '../libraries/FontAwesome/css/fa-svg-with-js.css', __FILE__ ) );
        wp_enqueue_style( 'fs' );

        // ColorPicker styles
        wp_enqueue_style( 'wp-color-picker' );

        // Custom Styling
        wp_register_style( 'custom-style', plugins_url( '../css/index.css', __FILE__ ) );
        wp_enqueue_style( 'custom-style' );
    }

    static function notice($type, $text, $dismissible)
    {
        ?>
        <div class="notice notice-<?= $type ?> <?= $dismissible ? 'is-dismissible' : '' ?>">
            <?= $text ?>
        </div>
        <?php
    }
}