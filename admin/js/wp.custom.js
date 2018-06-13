jQuery(document).ready(function () {
    // Filepicker
    var file_frame;
    jQuery('#filepicker').on('click', function (e) {
        e.preventDefault();

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: false
        });

        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();

            jQuery('#headerimg').val(attachment.url);
            jQuery('#headerimg_id').val(attachment.id);
            jQuery('#headerimg_width').val(attachment.width);
            jQuery('#headerimg_height').val(attachment.height);
        });
        file_frame.open();
    });

    // Colorpicker
    jQuery('.color-field').wpColorPicker();
});