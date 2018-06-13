jQuery(document).ready(function () {
    jQuery('.basic').multiselect({
        templates: {
            li: '<li><a href="javascript:void(0);"><label class="pl-2"></label></a></li>'
        },
        buttonClass: 'btn btn-outline-primary btn-block',
        nonSelectedText: 'Niets geselecteerd',
        nSelectedText: 'Geselecteerd',
        allSelectedText: 'Alles Geselecteerd',
        buttonWidth: '100%'
    });
});