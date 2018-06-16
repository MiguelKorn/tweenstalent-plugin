<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 09/06/2018
 * Time: 15:40
 */

class EmailTemplate
{
    private $bgcolor;
    private $header;
    private $body;

    public function __construct($bgcolor = null, $header = null, $body = null)
    {
        $this->bgcolor = ! is_null( $bgcolor ) ? $bgcolor : '#f6f6f6';
        $this->header  = ! is_null( $header ) ? $header : array(
            'id'     => 5,
            'width'  => 550,
            'height' => 200
        );
        $this->body = !is_null($body) ? stripslashes($body) : 'some text';
    }

    function showTemplate()
    {
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="body"
               style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; background-color: <?= $this->bgcolor ?>; width: 100%;"
               width="100%" bgcolor="<?= $this->bgcolor ?>">
            <tbody>
            <tr>
                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
                <td class="container"
                    style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; Margin: 0 auto;"
                    width="580" valign="top">
                    <div class="content"
                         style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">
                        <!-- START CENTERED WHITE CONTAINER -->
                        <?= $this->getHeader() ?>
                        <?= $this->getBody() ?>
                        <!-- START FOOTER -->
                        <?= $this->getFooter() ?>
                        <!-- END FOOTER -->
                        <!-- END CENTERED WHITE CONTAINER -->
                    </div>
                </td>
                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    function getHeader()
    {
        $image = wp_get_attachment_image_src( 5, array( $this->header['width'], $this->header['height'] ) );
        ?>
        <span class="preheader"
              style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
        <img class="img-responsive" src="<?= $image[0] ?>" alt="header.jpg"
             style="display: block; border: none; -ms-interpolation-mode: bicubic; width: 100%; height: 100%;">
        <?php
        return false;
    }

    function getBody()
    {
        ?>
        <table class="main"
               style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; background: #ffffff; border-radius: 3px; width: 100%;"
               width="100%">
            <!-- START MAIN CONTENT AREA -->

            <tbody>
            <tr>
                <td class="wrapper"
                    style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"
                    valign="top">
                    <table border="0" cellpadding="0" cellspacing="0"
                           style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; width: 100%;"
                           width="100%">
                        <tbody>
                        <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;"
                                valign="top">
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    Geachte bezoeker,</p>-->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    {salutation}</p>-->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    Uw registratie voor de gastenlijst van TweensTalent is geslaagd.-->
<!--                                    We-->
<!--                                    verheugen-->
<!--                                    ons op uw komst! Onderstaand vindt u uw registratiegegevens en-->
<!--                                    meer handige-->
<!--                                    informatie over het event.</p>-->
<!---->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    <b>Gegevens:</b>-->
<!--                                    <br>Voornaam: {firstname}-->
<!--                                    <br>Achternaam: {lastname}-->
<!--                                    <br>E-mail: {email}-->
<!--                                    <br><a href="wijzigen.html"-->
<!--                                           style="color: #3498db; text-decoration: underline;">Wijzigen</a>-->
<!--                                </p>-->
<!---->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    <b>Agenda</b></p>-->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    Met onderstaande link kunt u het evenement direct toevoegen aan-->
<!--                                    uw agenda.-->
<!--                                    In het agenda item vindt u het adres van de eventlocatie en de-->
<!--                                    link naar de-->
<!--                                    event website. Op de event website staat altijd de meest actuele-->
<!--                                    en-->
<!--                                    gedetailleerde informatie.</p>-->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    Voeg toe aan agenda</p>-->
<!---->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    Met vriendelijke groet,</p>-->
<!---->
<!--                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">-->
<!--                                    <b>Tweenstalent</b></p>-->

                                <?= $this->body ?>

                                <!--CTO BUTTON-->
                                <!--<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">-->
                                <!--<tbody>-->
                                <!--<tr>-->
                                <!--<td align="left">-->
                                <!--<table border="0" cellpadding="0" cellspacing="0">-->
                                <!--<tbody>-->
                                <!--<tr>-->
                                <!--<td><a href="http://htmlemail.io" target="_blank">Call To-->
                                <!--Action</a></td>-->
                                <!--</tr>-->
                                <!--</tbody>-->
                                <!--</table>-->
                                <!--</td>-->
                                <!--</tr>-->
                                <!--</tbody>-->
                                <!--</table>-->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <!-- END MAIN CONTENT AREA -->
            </tbody>
        </table>
        <?php
        return false;
    }

    function getFooter()
    {
        ?>
        <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
            <table border="0" cellpadding="0" cellspacing="0"
                   style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; width: 100%;"
                   width="100%">
                <!--DEFAULT FOOTER-->
                <!--<tr>-->
                <!--<td class="content-block">-->
                <!--<span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>-->
                <!--<br> Don\'t like these emails? <a href="http://i.imgur.com/CScmqnj.gif">Unsubscribe</a>.-->
                <!--</td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--<td class="content-block powered-by">-->
                <!--Powered by <a href="http://htmlemail.io">HTMLemail</a>.-->
                <!--</td>-->
                <!--</tr>-->
                <tbody>
                <tr>
                    <td class="content-block powered-by"
                        style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;"
                        valign="top" align="center">
                        <a href="eventweb.html"
                           style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Event
                            website</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        return false;
    }
}