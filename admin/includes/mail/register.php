<?php
function getRegisterMailString()
{
    return <<<EOT
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Simple Transactional Email</title>
   
    <style>
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table[class=body] .btn table {
                width: 100% !important;
            }

            table[class=body] .btn a {
                width: 100% !important;
            }

            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            .btn-primary table td:hover {
                background-color: #34495e !important;
            }

            .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
            }
        }
    </style>
</head>
<body class=""
      style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
<table border="0" cellpadding="0" cellspacing="0" class="body"
       style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; background-color: #f6f6f6; width: 100%;"
       width="100%" bgcolor="#f6f6f6">
    <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
        <td class="container"
            style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; Margin: 0 auto;"
            width="580" valign="top">
            <div class="content"
                 style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                <!-- START CENTERED WHITE CONTAINER -->
<!--                <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>-->
                <table class="main"
                       style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; background: #ffffff; border-radius: 3px; width: 100%;"
                       width="100%">
                    <!-- START MAIN CONTENT AREA -->
                    <img class="img-responsive" src="header.jpg" alt="header.jpg"
                         style="display: block; border: none; -ms-interpolation-mode: bicubic; width: 100%; height: 100%;">
                    <tr>
                        <td class="wrapper"
                            style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"
                            valign="top">
                            <table border="0" cellpadding="0" cellspacing="0"
                                   style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; width: 100%;"
                                   width="100%">
                                <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;"
                                        valign="top">
                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            Geachte bezoeker,</p>
                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            {salutation}</p>
                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            Uw registratie voor de gastenlijst van TweensTalent is geslaagd. We
                                            verheugen
                                            ons op uw komst! Onderstaand vindt u uw registratiegegevens en meer handige
                                            informatie over het event.</p>

                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            <b>Gegevens:</b>
                                            <br>Voornaam: {firstname}
                                            <br>Achternaam: {lastname}
                                            <br>E-mail: {email}
                                            <br><a href="wijzigen.html"
                                                   style="color: #3498db; text-decoration: underline;">Wijzigen</a>
                                        </p>

                                        <b>Agenda</b>
                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            Met onderstaande link kunt u het evenement direct toevoegen aan uw agenda.
                                            In het agenda item vindt u het adres van de eventlocatie en de link naar de
                                            event website. Op de event website staat altijd de meest actuele en
                                            gedetailleerde informatie.</p>
                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            <a href="agenda.html" style="color: #3498db; text-decoration: underline;">Voeg
                                                toe aan agenda</a></p>

                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            Met vriendelijke groet,</p>

                                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            <b>Tweenstalent</b>
                                        </p>

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
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>

                <!-- START FOOTER -->
                <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0"
                           style="border-collapse: separate; mso-table-lspace: 0; mso-table-rspace: 0; width: 100%;"
                           width="100%">
                        <!--DEFAULT FOOTER-->
                        <!--<tr>-->
                        <!--<td class="content-block">-->
                        <!--<span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>-->
                        <!--<br> Don't like these emails? <a href="http://i.imgur.com/CScmqnj.gif">Unsubscribe</a>.-->
                        <!--</td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--<td class="content-block powered-by">-->
                        <!--Powered by <a href="http://htmlemail.io">HTMLemail</a>.-->
                        <!--</td>-->
                        <!--</tr>-->
                        <tr>
                            <td class="content-block powered-by"
                                style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;"
                                valign="top" align="center">
                                <a href="eventweb.html"
                                   style="color: #999999; font-size: 12px; text-align: center; text-decoration: underline">TweensTalent</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END FOOTER -->

                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
    </tr>
</table>
</body>
</html>
EOT;
}