<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 29/05/2018
 * Time: 14:03
 */

class TweensTalent
{
    private $api;

    /**
     * TweensTalent constructor.
     */
    public function __construct()
    {
        $this->api = new ExternalApi();

        add_action( 'admin_init', array( $this, 'create_settings' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ) );
        add_filter( 'mailtpl/customizer_template', array( $this, 'custom_mailtpl_template' ), 2000, 1 );
        add_shortcode( 'tt-contact-form', array( $this, 'contact_form' ) );
        add_shortcode( 'tt-register-form', array( $this, 'register_form' ) );
        add_action( 'tt-hook', array( $this, 'hookFunction' ) );
    }

    /**
     * add admin menu
     */
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
//        add_menu_page(
//            'Statistieken',
//            'Statistieken',
//            'manage_options',
//            'tt-stats',
//            'tt_statistics_page',
//            'dashicons-chart-line',
//            3
//        );
    }

    /**
     * @param $hook
     */
    public function load_scripts($hook)
    {
        if ( $hook === 'toplevel_page_tt-events' || 'toplevel_page_tt-app' || 'toplevel_page_tt-stats' ) {
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

            // datetime picker plugin
            wp_register_script( 'dtp-script', plugins_url( '../libraries/DatetimePicker/jquery.datetimepicker.full.min.js', __FILE__ ), array( 'jquery' ), null, true );
            wp_enqueue_script( 'dtp-script' );

            // custom script for datetime picker
            wp_register_script( 'dtp-custom', plugins_url( '../js/datetime.js', __FILE__ ), array( 'jquery' ), null, true );
            wp_enqueue_script( 'dtp-custom' );

            // FontAwesome svg with js
            wp_register_script( 'fa', plugins_url( '../libraries/FontAwesome/js/fontawesome-all.min.js', __FILE__ ), array(), null, true );
            wp_enqueue_script( 'fa' );
        }
    }

    /**
     * @param $hook
     */
    function load_styles($hook)
    {
        if ( $hook === 'toplevel_page_tt-events' || 'toplevel_page_tt-app' || 'toplevel_page_tt-stats' ) {
            // DatetimePicker plugin
            wp_register_style( 'dtp-style', plugins_url( '../libraries/DatetimePicker/jquery.datetimepicker.min.css', __FILE__ ), array(), null, 'all' );
            wp_enqueue_style( 'dtp-style' );

            // DataTables
            wp_register_style( 'datatables', plugins_url( '../libraries/DataTables/datatables.css', __FILE__ ) );
            wp_enqueue_style( 'datatables' );

            // FontAwesome
            wp_register_style( 'fs', plugins_url( '../libraries/FontAwesome/css/fa-svg-with-js.css', __FILE__ ) );
            wp_enqueue_style( 'fs' );

            // Custom Styling
            wp_register_style( 'custom-style', plugins_url( '../css/index.css', __FILE__ ) );
            wp_enqueue_style( 'custom-style' );
        }
    }

    /**
     * @param $type
     * @param $text
     * @param $dismissible
     */
    static function notice($type, $text, $dismissible)
    {
        ?>
        <div class="notice notice-<?= $type ?> <?= $dismissible ? 'is-dismissible' : '' ?>">
            <?= $text ?>
        </div>
        <?php
    }

    /**
     * @param $template
     *
     * @return string
     */
    function custom_mailtpl_template($template)
    {
        return PLUGIN_URL . "templates/default.php";
    }

    /**
     * create settings
     */
    function create_settings()
    {
        register_setting( 'tt-register-email-message', 'register_email_message' );
    }

    /**
     * @param $att
     *
     * @return string
     */
    function contact_form($att)
    {
        $formMessage = '<div class="wpcf7-response-output wpcf7-display-none"></div>';

        if ( isset( $_POST['submit-contact'] ) ) {
            $email   = sanitize_text_field( $_POST['your-email'] );
            $name    = sanitize_text_field( $_POST['your-name'] );
            $subject = sanitize_text_field( $_POST['your-subject'] );
            $message = sanitize_text_field( $_POST['your-message'] );

            $result = wp_mail(
                get_option( 'admin_email' ),
                "Contact aanvraag: {$name} | {$subject}",
                "<p>Contact verzoek</p>
                <ul>
                    <li>Naam: {$name}</li>
                    <li>Email: <a href='mailto:{$email}'>{$email}</a></li>
                    <li>Onderwerp: {$subject}</li>
                    <li>Bericht: {$message}</li>
                </ul>
                "
            );

            if ( $result ) {
                $formMessage = '<div class="wpcf7-response-output wpcf7-mail-sent-ok" role="alert">Bedankt voor je bericht. Het is verzonden.</div>';
            } else {
                $formMessage = '<div class="wpcf7-response-output wpcf7-validation-errors" role="alert">Er is een fout opgetreden bij het versturen van het bericht. Probeer later nog een keer.</div>';
            }
        }

        return <<<EOT
<div role="form" class="wpcf7" id="wpcf7-f53-o1" lang="nl-NL" dir="ltr">
<div class="screen-reader-response"></div>
<form action="#" method="post" class="wpcf7-form">
<p><label> Je naam (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-name"><input required type="text" name="your-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAfBJREFUWAntVk1OwkAUZkoDKza4Utm61iP0AqyIDXahN2BjwiHYGU+gizap4QDuegWN7lyCbMSlCQjU7yO0TOlAi6GwgJc0fT/fzPfmzet0crmD7HsFBAvQbrcrw+Gw5fu+AfOYvgylJ4TwCoVCs1ardYTruqfj8fgV5OUMSVVT93VdP9dAzpVvm5wJHZFbg2LQ2pEYOlZ/oiDvwNcsFoseY4PBwMCrhaeCJyKWZU37KOJcYdi27QdhcuuBIb073BvTNL8ln4NeeR6NRi/wxZKQcGurQs5oNhqLshzVTMBewW/LMU3TTNlO0ieTiStjYhUIyi6DAp0xbEdgTt+LE0aCKQw24U4llsCs4ZRJrYopB6RwqnpA1YQ5NGFZ1YQ41Z5S8IQQdP5laEBRJcD4Vj5DEsW2gE6s6g3d/YP/g+BDnT7GNi2qCjTwGd6riBzHaaCEd3Js01vwCPIbmWBRx1nwAN/1ov+/drgFWIlfKpVukyYihtgkXNp4mABK+1GtVr+SBhJDbBIubVw+Cd/TDgKO2DPiN3YUo6y/nDCNEIsqTKH1en2tcwA9FKEItyDi3aIh8Gl1sRrVnSDzNFDJT1bAy5xpOYGn5fP5JuL95ZjMIn1ya7j5dPGfv0A5eAnpZUY3n5jXcoec5J67D9q+VuAPM47D3XaSeL4AAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;"></span> </label></p>
<p><label> Je e-mail (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-email"><input required type="email" name="your-email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false"></span> </label></p>
<p><label> Onderwerp (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-subject"><input required type="text" name="your-subject" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false"></span> </label></p>
<p><label> Je bericht (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-message"><textarea required name="your-message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></textarea></span> </label></p>
<p><input type="submit" name="submit-contact" value="Verzenden" class="wpcf7-form-control wpcf7-submit"></p>
{$formMessage}
</form></div>
EOT;

    }

    /**
     * @return string
     */
    public function register_form()
    {
        $formMessage = '<div class="wpcf7-response-output wpcf7-display-none"></div>';
        if(isset($_POST['submit-register'])) {
            $firstName      = sanitize_text_field( $_POST['first-name'] );
            $lastNamePrefix = sanitize_text_field( $_POST['middle-name'] );
            $lastName       = sanitize_text_field( $_POST['last-name'] );
            $email          = sanitize_email( $_POST['your-email'] );

            $result = $this->api->addGuest(
                $firstName,
                $lastNamePrefix,
                $lastName,
                $email,
                sanitize_text_field( $_POST['your-company'] ),
                sanitize_text_field( $_POST['your-job'] ),
                true
            );

            if ( $result ) {
                $formMessage = '<div class="wpcf7-response-output wpcf7-mail-sent-ok" role="alert">Bedankt voor je registratie.</div>';
            } else {
                $formMessage = '<div class="wpcf7-response-output wpcf7-validation-errors" role="alert">Er is een fout opgetreden bij het versturen. Probeer later nog een keer.</div>';
            }
        }

        return <<<EOT
<div role="form" class="wpcf7" id="wpcf7-f67-o1" lang="nl-NL" dir="ltr">
<div class="screen-reader-response"></div>
<form action="#" method="post" class="wpcf7-form">
<p><label> Voornaam (verplicht)<br>
    <span class="wpcf7-form-control-wrap first-name"><input type="text" name="first-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAfBJREFUWAntVk1OwkAUZkoDKza4Utm61iP0AqyIDXahN2BjwiHYGU+gizap4QDuegWN7lyCbMSlCQjU7yO0TOlAi6GwgJc0fT/fzPfmzet0crmD7HsFBAvQbrcrw+Gw5fu+AfOYvgylJ4TwCoVCs1ardYTruqfj8fgV5OUMSVVT93VdP9dAzpVvm5wJHZFbg2LQ2pEYOlZ/oiDvwNcsFoseY4PBwMCrhaeCJyKWZU37KOJcYdi27QdhcuuBIb073BvTNL8ln4NeeR6NRi/wxZKQcGurQs5oNhqLshzVTMBewW/LMU3TTNlO0ieTiStjYhUIyi6DAp0xbEdgTt+LE0aCKQw24U4llsCs4ZRJrYopB6RwqnpA1YQ5NGFZ1YQ41Z5S8IQQdP5laEBRJcD4Vj5DEsW2gE6s6g3d/YP/g+BDnT7GNi2qCjTwGd6riBzHaaCEd3Js01vwCPIbmWBRx1nwAN/1ov+/drgFWIlfKpVukyYihtgkXNp4mABK+1GtVr+SBhJDbBIubVw+Cd/TDgKO2DPiN3YUo6y/nDCNEIsqTKH1en2tcwA9FKEItyDi3aIh8Gl1sRrVnSDzNFDJT1bAy5xpOYGn5fP5JuL95ZjMIn1ya7j5dPGfv0A5eAnpZUY3n5jXcoec5J67D9q+VuAPM47D3XaSeL4AAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;"></span> </label></p>
<p><label> Tussenvoegsel<br>
    <span class="wpcf7-form-control-wrap middle-name"><input type="text" name="middle-name" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false"></span> </label></p>
<p><label> Achternaam (verplicht)<br>
    <span class="wpcf7-form-control-wrap last-name"><input type="text" name="last-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></span> </label></p>
<p><label> Je e-mail (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-email"><input type="email" name="your-email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false"></span> </label></p>
<p><label> Bedrijf (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-company"><input type="text" name="your-company" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></span> </label></p>
<p><label> Functie (verplicht)<br>
    <span class="wpcf7-form-control-wrap your-job"><input type="text" name="your-job" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></span> </label></p>
<p><input type="submit" name="submit-register" value="Verzenden" class="wpcf7-form-control wpcf7-submit"></p>
{$formMessage}</form></div>
EOT;

    }

    /**
     * Hookfunction for to add ICS data to frontend
     */
    function hookFunction() {
        if(isset($_GET['action']) && $_GET['action'] === 'add-to-agenda') {
            $event = $this->api->getEvent();
            $startDate = $event['dates']['startEvent']['date'];
            $endDate = $event['dates']['endEvent']['date'];
            $description = "{$event['name']}";
            $address = "{$event['address']['streetName']} {$event['address']['streetNumber']}, {$event['address']['postalCode']} {$event['address']['city']}";

            echo "<script>ICS_EVENT = {name: '{$event['name']}',description:'${description}', address: '${$address}', start: '${startDate}', end: '${endDate}'};</script>";
        }
    }
}