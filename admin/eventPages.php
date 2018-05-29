<?php

function admin_event_page()
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
        var_dump( $result );
    }
    $ad      = $api->getEvent();
    $address = $ad['address'];
    $dates   = $ad['dates'];


    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Evenement</h1>

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

    </div>
    <?php
}

function admin_event_guests_page()
{
    //TODO: Get guests
    $api    = new ExternalApi();
    $guests = $api->getGuests();

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Gastenlijst</h1>
        <br/>
        <br/>
        <table id="guestList" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
            <thead>
            <tr>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Email</th>
                <th>Bedrijf</th>
                <th>Status</th>
                <th>Aangemeld Op</th>
            </tr>
            </thead>
            <tbody>
            <?php

            foreach ( $guests as $guest ) {
                $lastName = ( $guest['name']['lastNamePrefix'] !== '' ? $guest['name']['lastNamePrefix'] . ' ' : '' ) . $guest['name']['lastName'];
                $date     = gmdate( 'd-m-Y H:i:s', strtotime( $guest['dates']['createdAt']['date'] ) );

                echo "<tr>";
                echo "<td>{$guest['name']['firstName']}</td>";
                echo "<td>{$lastName}</td>";
                echo "<td>{$guest['email']}</td>";
                echo "<td>{$guest['company_name']}</td>";
                echo "<td>AT: {$guest['boolean']['isAttending']} | AP: {$guest['boolean']['isApproved']}</td>";
                echo "<td>{$date}</td>";
                echo "</td>";
            }
            ?>
            </tbody>
        </table>
        <br/>
        <h2 class="title">Gast toevoegen</h2>
        <!--        TODO: fix form-->
        <!--        <form method="post" action="--><? //= esc_url( $_SERVER['REQUEST_URI'] )
        ?><!--" novalidate="novalidate" id="form">-->
        <!--            --><?php //settings_fields( 'media' );
        ?>
        <!--            <table class="form-table">-->
        <!--                <tbody>-->
        <!--                <tr>-->
        <!--                    <th scope="row">-->
        <!--                        <label for="eventname">Naam</label>-->
        <!--                    </th>-->
        <!--                    <td>-->
        <!--                        <input name="eventname" type="text" id="eventname" value="--><? //= $ad['name']
        ?><!--"-->
        <!--                               class="regular-text">-->
        <!--                    </td>-->
        <!--                </tr>-->
        <!--                </tbody>-->
        <!--            </table>-->
        <!--        </form>-->
    </div>
    <?php
}