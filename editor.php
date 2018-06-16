<?php
$customStyle = '<style type="text/css">
    @media only screen and (max-width: 620px) {
        table[class=body] h1 {font-size: 28px !important;margin-bottom: 10px !important;}
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {font-size: 16px !important;}
        table[class=body] .wrapper,
        table[class=body] .article {padding: 10px !important;}
        table[class=body] .content {padding: 0 !important;}
        table[class=body] .container {padding: 0 !important;width: 100% !important;}
        table[class=body] .main {border-left-width: 0 !important;border-radius: 0 !important;border-right-width: 0 !important;}
        table[class=body] .btn table {width: 100% !important;}
        table[class=body] .btn a {width: 100% !important;}
        table[class=body] .img-responsive {height: auto !important;max-width: 100% !important;width: auto !important;}
    }

    @media all {
        .ExternalClass {width: 100%;}
        .ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {line-height: 100%;}
        .apple-link a {color: inherit !important;font-family: inherit !important;font-size: inherit !important;font-weight: inherit !important;line-height: inherit !important;text-decoration: none !important;}
        .btn-primary table td:hover {background-color: #34495e !important;}
        .btn-primary a:hover {background-color: #34495e !important;border-color: #34495e !important;}
    }
</style>';
$content     = esc_html( '<h1>test</h1><table><tbody><tr><td>123</td><td>123</td><td>123</td></tr></tbody></table>' );
echo '<br>';
wp_editor( esc_html( __( get_option( 'whatever_you_need', 'whatever' ) ) ), 'editor', array(
'tinymce'       => true,
'textarea_rows' => 15,
'quicktags'     => true,
'teeny'         => true,
'editor_css'    => $customStyle
) );