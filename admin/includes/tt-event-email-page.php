<?php

function tt_event_email_page()
{
    ?>
    <div class="wrap">
    <h1>WordPress Extra Post Info</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'tt-register-email-message' ); ?>
        <?php do_settings_sections( 'tt-register-email-message' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Registratie Mail:</th>
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
function tt_event_email_page2()
{
    var_dump( $_POST );
    $api = new ExternalApi();

    if ( isset( $_POST['mail'] ) ) {
        $template = new EmailTemplate(
            $_POST['mail']['background_color'],
            array(
                'id'     => $_POST['mail']['header_id'],
                'width'  => $_POST['mail']['header_width'],
                'height' => $_POST['mail']['header_height'],
            ),
            $_POST['mail']['body']
        );
    } else {
        $template = new EmailTemplate();
    }

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Email</h1>
        <br/>
        <form method="post" action="<?= esc_url( $_SERVER['REQUEST_URI'] ) ?>" novalidate="novalidate" id="form">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="bgcolorpicker">Achtergrondkleur</label>
                    </th>
                    <td>
                        <input type="text" name="mail[background_color]" id="bgcolorpicker" class="color-field"
                               value="#f6f6f6">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="headerimg">Afbeelding</label>
                    </th>
                    <td>
                        <input id="headerimg" name="mail[header_url]" type="text" value=""/>
                        <input id="filepicker" class="button upload_image_button" name="hfilepicker" type="button"
                               value="Select image"/>
                        <input id="headerimg_id" name="mail[header_id]" type="hidden" value=""/>
                        <input id="headerimg_width" name="mail[header_width]" type="hidden" value=""/>
                        <input id="headerimg_height" name="mail[header_height]" type="hidden" value=""/>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mailbody">Tekst</label>
                    </th>
                    <td>
                        <?php wp_editor( isset($_POST['mail']) ? stripslashes( $_POST['mail']['body'] ) : 'content', 'mailbody', array(
                            'textarea_name' => 'mail[body]',
                            'tinymce'       => true,
                            'quicktags'     => true,
                            'media_buttons' => false,
                            'teeny'         => true
                        ) ) ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="Opslaan & Bekijken"></p>
        </form>
        <div id="custom-id" class="welcome-panel">
            <div>
                <!--        <div class="welcome-panel-content">-->
                <?php $template->showTemplate(); ?>
            </div>
        </div>
    </div>
    <?php
}