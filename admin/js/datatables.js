jQuery(document).ready(function () {
        var guestList = jQuery("#guestList");
        var questionList = jQuery("#questionList");
        var schoolList = jQuery("#schoolList");
        var levelList = jQuery("#levelList");

        // Add guest datatable if guestList exists
        if (guestList.length) {
            guestList.DataTable({
                dom: '<"row"<"col-sm-12"><"col-sm-12 legend">>" + "<"row"<"col-sm-12"tr>>" + "<"row"<"col-sm-5"B><"col-sm-7"p>>',
                language: {
                    'url': '//cdn.datatables.net/plug-ins/1.10.16/i18n/Dutch.json'
                },
                responsive: true,
                order: [[6, 'desc']], // date column
                columns: [
                    {orderable: false},
                    {visible: false},
                    null,
                    null,
                    null,
                    null,
                    null,
                    {visible: false},
                    {orderable: false, width: "10%"}
                ],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Exporteren (Excel)',
                        className: 'btn-sm',
                        exportOptions: {
                            columns: [1, ':visible']
                        }
                    }
                ],
                initComplete: function () {
                    // get count of presence
                    var presenceColumn = this.api().column(1).data();
                    var invitedData = this.api().column(7).data();
                    var countPresence = {
                        aanwezig: 0,
                        afgemeld: 0,
                        geenreactie: 0,
                        uitgenodigd: 0
                    };

                    presenceColumn.map(function (user) {
                        if (user === 'Aanwezig' || 'Afgemeld' || 'Geen reactie') {
                            countPresence[user.toLowerCase().replace(' ', '')]++;
                        }
                    });

                    invitedData.map(function (user) {
                        countPresence.uitgenodigd += Number(user);
                    });

                    jQuery('div.legend').html(
                        '<div class="row">' +
                        '<div class="col"><button type="button" class="btn btn-success btn-sm btn-block">Aanwezig <span class="badge badge-light">' + countPresence.aanwezig + '</span></button></div>' +
                        '<div class="col"><button type="button" class="btn btn-info btn-sm btn-block">Afgemeld <span class="badge badge-light">' + countPresence.afgemeld + '</span></button></div>' +
                        '<div class="col"><button type="button" class="btn btn-secondary btn-sm btn-block">Geen reactie <span class="badge badge-light">' + countPresence.geenreactie + '</span></button></div>' +
                        '<div class="col"><button type="button" class="btn btn-outline-success btn-sm btn-block">Genodigden <span class="badge badge-light">' + countPresence.uitgenodigd + '</span></button></div' +
                        '</div>');
                }
            });
        }

        // Add question datatable if questionList exists
        if (questionList.length) {
            var currentTalent = 'Beeldtalent';
            var table = questionList.DataTable({
                dom: '<"row">" + "<"row"<"col-sm-12"tr>>" + "<"row"<"col-sm-12 submit">>',
                language: {
                    'url': '//cdn.datatables.net/plug-ins/1.10.16/i18n/Dutch.json'
                },
                responsive: true,
                paging: false,
                ordering: false,
                pageLength: 5,
                select: false,
                columnDefs: [
                    {width: "20%", targets: 0}
                ],
                initComplete: function () {
                    // set initial seach for column talent
                    this.api().column(0).search(currentTalent).draw();

                    var legend = jQuery('div.legend');

                    // onclick handler for seach function
                    legend.find('button').on('click', function (e) {
                        var target = jQuery(e.target);
                        var text = target.text();

                        table.column(0).search(text).draw();
                        currentTalent = text;

                        legend.find('button:not(:contains(' + text + '))').removeClass('active');
                        target.addClass('active');

                        jQuery('#submit-talent').val(text + ' Opslaan');
                    });

                    // change search
                    legend.find('button:not(:contains(' + currentTalent + '))').removeClass('active');
                    legend.find('button:contains(' + currentTalent + ')').addClass('active');

                    // add save current talent
                    jQuery('div.submit').html(
                        '<div class="row">' +
                        '<div class="col-4">' +
                        '<input type="hidden" name="talent" value="' + currentTalent + '">' +
                        '<input class="btn btn-info btn-sm btn-block" id="submit-talent" type="submit" name="submit" value="' + currentTalent + ' Opslaan">' +
                        '</div>' +
                        '</div>'
                    );
                }
            });
        }

        // Add school datatable if schoolList exists
        if (schoolList.length) {
            schoolList.DataTable({
                dom: '<"row"<"col-sm-6 add"><"col-sm-6"f>>" + "<"row"<"col-sm-12"tr>>" + "<"row"<"col-sm-4"><"col-sm-8"p>>',
                language: {
                    'url': '//cdn.datatables.net/plug-ins/1.10.16/i18n/Dutch.json'
                },
                pageLength: 10,
                responsive: true,
                columns: [
                    {width: "10%"},
                    {orderable: false},
                    {orderable: false, width: "10%"},
                    {visible: false}
                ],
                initComplete: function () {
                    // add addSchool button
                    jQuery('div.add').html(
                        '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addSchoolModal" onclick="event.preventDefault()">School Toevoegen</button>'
                    )
                }
            });
        }

        // Add level datatable if levelList exists
        if (levelList.length) {
            levelList.DataTable({
                dom: '<"row"<"col-sm-6 add"><"col-sm-6">>" + "<"row"<"col-sm-12"tr>>" + "<"row"<"col-sm-4"><"col-sm-8"p>>',
                language: {
                    'url': '//cdn.datatables.net/plug-ins/1.10.16/i18n/Dutch.json'
                },
                responsive: true,
                paging: false,
                columns: [
                    {width: "10%"},
                    {orderable: false},
                    {orderable: false, width: "10%"}
                ],
                initComplete: function () {
                    // add addLevel button
                    jQuery('div.add').html(
                        '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addLevelModal" onclick="event.preventDefault()">Niveau Toevoegen</button>'
                    )
                }
            });
        }
    }
);