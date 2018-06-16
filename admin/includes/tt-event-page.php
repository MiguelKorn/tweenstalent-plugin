<?php

/**
 * Main event page content
 */
function tt_event_page()
{
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'event';
    $api        = new ExternalApi();

    if ( isset( $_POST['submit-delete'] ) ) {
        $result = $api->deleteGuest( $_POST['submit-delete'] );
        if ( ! $result ) {
            TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
        } else {
            TweensTalent::notice( 'success', '<p>Successvol verwijderd!</p>', true );
        }
    }

    $tabs = array(
        'event'      => array(
            'name'    => 'Evenement',
            'content' => 'eventContent'
        ),
        'guests'     => array(
            'name'    => 'Gastenlijst',
            'content' => 'guestsContent'
        ),
        'add-guest'  => array(
            'name'    => 'Gast Toevoegen',
            'content' => 'newGuestContent'
        ),
        'edit-email' => array(
            'name'    => 'Registratie Email',
            'content' => 'registerEmailContent'
        )
    );

    $tab = new Tabs( 'tt-events', $tabs );

    $guests = $api->getGuests();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Evenement</h1>
        <br/>
        <h2 class="nav-tab-wrapper">
            <?php $tab->displayNav( $active_tab ); ?>
        </h2>
        <br/>
        <!--        <div class="wrap">-->
        <?php $tabs[ $active_tab ]['content']( $guests ) ?>
        <!--        </div>-->
    </div>
    <?php
}

/**
 * Event content
 *
 * @return bool
 */
function eventContent()
{
    $api = new ExternalApi();

    if ( isset( $_POST['submit'] ) ) {
        $id         = sanitize_text_field( $_POST['eventid'] );
        $name       = sanitize_text_field( $_POST['eventname'] );
        $startDate  = sanitize_text_field( $_POST['eventstart'] );
        $endDate    = sanitize_text_field( $_POST['eventend'] );
        $street     = sanitize_text_field( $_POST['eventstreet'] );
        $number     = sanitize_text_field( $_POST['eventstreetnumber'] );
        $postalCode = sanitize_text_field( $_POST['eventzipcode'] );
        $city       = sanitize_text_field( $_POST['eventcity'] );

        $result = $api->patchEvent( $id, $name, $street, $number, $postalCode, $city, $startDate, $endDate );
        if ( isset( $result ) ) {
            if ( ! $result ) {
                TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
            } else {
                TweensTalent::notice( 'success', '<p>Successvol gewijzigd!</p>', true );
            }
        }
    }

    $ad      = $api->getEvent();
    $address = $ad['address'];
    $dates   = $ad['dates'];


    ?>
    <form method="post" action="<?= esc_url( $_SERVER['REQUEST_URI'] ) ?>" novalidate="novalidate" id="form">
        <?php settings_fields( 'media' ); ?>
        <input type="hidden" name="eventid" value="<?= $ad['id'] ?>">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="eventname">Naam</label>
                </th>
                <td>
                    <input name="eventname" type="text" id="eventname" value="<?= $ad['name'] ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="eventstart">Start Evenement</label>
                </th>
                <td>
                    <input type="text" name="eventstart" id="eventstart">
                    <input type="hidden" id="eventstarthidden" value="<?= $dates['startEvent']['date'] ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="endevent">Eind Evenement</label>
                </th>
                <td>
                    <input type="text" name="eventend" id="eventend">
                    <input type="hidden" id="eventendhidden" value="<?= $dates['endEvent']['date'] ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <br/>
        <h2 class="title">Address Gegevens</h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="eventstreet">Straatnaam</label>
                </th>
                <td>
                    <input name="eventstreet" type="text" id="eventstreet" value="<?= $address['streetName'] ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="eventstreetnumber">Straatnummer</label>
                </th>
                <td>
                    <input name="eventstreetnumber" type="text" id="eventstreetnumber"
                           value="<?= $address['streetNumber'] ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="eventzipcode">Postcode</label>
                </th>
                <td>

                    <input name="eventzipcode" type="text" id="eventzipcode" value="<?= $address['postalCode'] ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="eventcity">Stad</label>
                </th>
                <td>
                    <input name="eventcity" type="text" id="eventcity" value="<?= $address['city'] ?>"
                           class="regular-text">
                </td>
            </tr>
        </table>


        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Wijzigingen opslaan"></p></form>
    <?php
    return false;
}

/**
 * Guests Content
 *
 * @param $guests
 *
 * @return bool
 */
function guestsContent($guests)
{
    global $api;
    if ( isset( $_POST['submit-delete'] ) ) {
        $result = $api->deleteGuest($_POST['submit-delete']);
        if ( isset( $result ) ) {
            if ( ! $result ) {
                TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
            } else {
                TweensTalent::notice( 'success', '<p>Successvol verwijderd!</p>', true );
            }
        }
    }
    ?>
    <form method="post" action="<?= esc_url( $_SERVER['REQUEST_URI'] ) ?>" novalidate="novalidate" id="form">
        <?php settings_fields( 'media' ); ?>
        <table id="guestList" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
            <thead>
            <tr>
                <th></th>
                <th>Aanwezigheid</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Email</th>
                <th>Bedrijf</th>
                <!--            <th>Status</th>-->
                <th>Aangemeld Op</th>
                <th>Genodigd</th>
                <th/>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ( $guests as $guest ) {
                $lastName      = ( $guest['name']['lastNamePrefix'] !== '' ? $guest['name']['lastNamePrefix'] . ' ' : '' ) . $guest['name']['lastName'];
                $date          = gmdate( 'd-m-Y H:i:s', strtotime( $guest['dates']['createdAt']['date'] ) );
                $changedStatus = ( $guest['dates']['createdAt']['date'] !== $guest['dates']['updatedAt']['date'] );
                $status        = ( $guest['boolean']['isInvited'] && ! $changedStatus ) ? 2 : ( $guest['boolean']['isAttending'] ? 0 : 1 );

                $statuses = array(
                    '<i class="fas fa-circle text-success"/>', // aanwezig
                    '<i class="fas fa-circle text-info"/>', // afgemeld
                    '<i class="fas fa-circle text-secondary"/>', // geen reactie
                    '<i class="fas fa-circle text-white"/><i class="far fa-circle text-success"/>' // uitgenodigd
                );

                $statusIcon = $statuses[ $status ];
                switch ( $status ) {
                    case 1:
                        $statusName = 'Afgemeld';
                        break;
                    case 0:
                        $statusName = 'Aanwezig';
                        break;
                    case 2:
                        $statusName = 'Geen reactie';
                        break;
                    default:
                        $statusName = 'Geen reactie';
                        break;
                };

                // TODO: add canceled attending, pos via API

                echo "<tr>";
                echo "<td class='table-icon'><div class='fa-lg'><span class='fa-layers fa-fw'>{$statusIcon}</span></div></td>";
                echo "<td>{$statusName}</td>";
                echo "<td>{$guest['name']['firstName']}</td>";
                echo "<td>{$lastName}</td>";
                echo "<td>{$guest['email']}</td>";
                echo "<td>{$guest['company_name']}</td>";
                echo "<td>{$date}</td>";
                echo "<td>{$guest['boolean']['isInvited']}</td>";
                echo "<td>";
                echo "<button type='submit' name='submit-delete' value='{$guest['id']}' class='btn btn-danger btn-sm'><i class='fas fa-minus-circle'></i></button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </form>
    <?php
    return false;
}

/**
 * Add guest Content
 *
 * @return bool
 */
function newGuestContent()
{
    $guest = false;
    if ( isset( $_POST['submit'] ) ) {
        $api = new ExternalApi();

        $firstName      = sanitize_text_field( $_POST['guestFirstName'] );
        $lastNamePrefix = sanitize_text_field( $_POST['guestLastNamePrefix'] );
        $lastName       = sanitize_text_field( $_POST['guestLastName'] );
        $email          = sanitize_text_field( $_POST['guestEmail'] );
        $company        = sanitize_text_field( $_POST['guestCompany'] );
        $job            = sanitize_text_field( $_POST['guestJob'] );

        $result = $api->addGuest( $firstName, $lastNamePrefix, $lastName, $email, $company, $job );
        if ( ! $result ) {
            $guest = true;
            TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
        } else {
            TweensTalent::notice( 'success', '<p>Successvol toegevoegd!</p>', true );
        }
    }

    ?>
    <h2 class="title">Gast toevoegen</h2>
    <form method="post" action="<?= esc_url( $_SERVER['REQUEST_URI'] ) ?>" novalidate="novalidate" id="form">
        <?php settings_fields( 'media' ); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="guestFirstName">Voornaam</label>
                </th>
                <td>
                    <input name="guestFirstName" type="text" id="guestFirstName" class="regular-text"
                           value="<?= $guest ? $firstName : '' ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="guestLastNamePrefix">Tussenvoegsel</label>
                </th>
                <td>
                    <input name="guestLastNamePrefix" type="text" id="guestLastNamePrefix" class="regular-text"
                           value="<?= $guest ? $lastNamePrefix : '' ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="guestLastName">Achternaam</label>
                </th>
                <td>
                    <input name="guestLastName" type="text" id="guestLastName" class="regular-text"
                           value="<?= $guest ? $lastName : '' ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="guestEmail">Email</label>
                </th>
                <td>
                    <input name="guestEmail" type="text" id="guestEmail" class="regular-text"
                           value="<?= $guest ? $email : '' ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="guestCompany">Bedrijf</label>
                </th>
                <td>
                    <input name="guestCompany" type="text" id="guestCompany" class="regular-text"
                           value="<?= $guest ? $company : '' ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="guestJob">Functie</label>
                </th>
                <td>
                    <input name="guestJob" type="text" id="guestJob" class="regular-text"
                           value="<?= $guest ? $job : '' ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Wijzigingen opslaan"></p>
    </form>
    <?php
    echo file_get_contents( get_stylesheet_directory() . '/admin/templates/default.php' );

    return false;
}

/**
 * Register Email Content
 */
function registerEmailContent()
{
    ?>
    <div class="wrap">
        <form method="post" action="options.php">
            <?php settings_fields( 'tt-register-email-message' ); ?>
            <?php do_settings_sections( 'tt-register-email-message' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Bericht:</th>
                    <td>
                        <?php wp_editor( get_option( 'register_email_message' ), 'mailbody', array(
                            'textarea_name' => 'register_email_message',
                            'tinymce'       => true,
                            'quicktags'     => true,
                            'media_buttons' => false,
                            'teeny'         => true
                        ) ) ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}