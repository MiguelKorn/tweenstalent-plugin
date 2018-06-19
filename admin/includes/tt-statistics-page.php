<?php

function tt_statistics_page()
{
    $api            = new ExternalApi();
    $students       = $api->getStudents();
    $schools        = $api->getSchools();
    $talents        = $api->getTalents();
    $parsedData     = $api->parseStudentsData( $students );
    $studentSchools = $parsedData['studentSchools'];
    $countGender    = $parsedData['countGender'];

    $schoolLabels = array();
    foreach ( $schools as $school ) {
        array_push( $schoolLabels, $school['name'] );
    }

    $talentLabels = array();
    foreach ( $talents as $talent ) {
        array_push( $talentLabels, $talent['name'] );
    }
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Statistieken</h1>
        <div class="row">
            <div class="col-12 col-md-6">
                <h5>Studenten per school</h5>
                <canvas id="schoolStudents" width="800" height="1500"/>
            </div>
            <div class="col-12 col-md-6">
                <h5>Studenten per geslacht</h5>
                <canvas id="gender" width="400" height="400"/>
            </div>
            <div class="col-12">
                <h5>Talenten per school</h5>
                <div>
                    <select name="selectSchool" id="selectSchool" class="form-control-sm">
                        <?php
                        foreach ( $schools as $key => $school ) {
                            // get selected school, set default to ifrst school
                            $selected  = ( isset( $_GET['school'] ) ) ? $_GET['school'] : 1;
                            $isCurrent = ( $key + 1 === intval( $selected ) ) ? 'selected' : ' ';
                            echo "<option value='{$school['id']}' {$isCurrent}>{$school['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <canvas id="talentsSchool" width="400" height="200"/>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var schoolStudentsCtx = document.getElementById('schoolStudents').getContext('2d');
            var schoolStudentsData = {
                labels: ['<?= implode( "', '", $schoolLabels ) ?>'],
                datasets: [{
                    label: 'Aantal studenten per school',
                    data: [<?php
                        foreach ( $schools as $key => $school ) {
                            echo( array_key_exists( $key + 1, $studentSchools ) ? $studentSchools[ $key + 1 ]['students'] : 0 );
                            if ( $key + 1 !== count( $schools ) ) {
                                echo ',';
                            }
                        }
                        ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                }]

            };
            var schoolStudentsOptions = {
                responsive: true,
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            };
            var schoolStudentsChart = new Chart(schoolStudentsCtx, {
                type: 'horizontalBar',
                data: schoolStudentsData,
                options: schoolStudentsOptions
            });

            var genderCtx = document.getElementById('gender').getContext('2d');
            var genderData = {
                labels: ['jongens', 'meisjes'],
                datasets: [{
                    label: 'Aantal studenten per geslacht',
                    data: [<?= $countGender['male']?>, <?= $countGender['female']?>],
                    backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                }]

            };
            var genderOptions = {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            };
            var genderChart = new Chart(genderCtx, {
                type: 'bar',
                data: genderData,
                options: genderOptions
            });

            var select = document.getElementById("selectSchool");

            var selectedSchool = select.value;
            var params = (new URL(document.location)).searchParams;
            var school = params.get("school");

            if (school === null) {
                window.location.href = "?page=tt-stats&school=" + selectedSchool;
            }

            select.addEventListener('change', function () {
                selectedSchool = selectSchool();
            }, false);

            var talentSchoolCtx = document.getElementById('talentsSchool').getContext('2d');
            var talentSchoolData = {
                labels: ['<?= implode( "', '", $talentLabels ) ?>'],
                datasets: [{
                    label: 'Aantal talenten per school',
                    data: [<?php
                        $selectedSchool = intval( $_GET['school'] );
                        if(array_key_exists($selectedSchool, $studentSchools)) {
                            $currentSchoolData = $studentSchools[ $selectedSchool ]['talents'];
                            foreach ( $currentSchoolData as $key => $talent ) {
                                echo( array_key_exists( $key + 1, $currentSchoolData ) ? $currentSchoolData[ $key + 1 ] : 0 );
                                if ( $key + 1 !== count( $schools ) ) {
                                    echo ',';
                                }
                            }
                        }
                        ?>],
                    backgroundColor: [
                        'rgb(105,50,165, 0.2)',
                        'rgb(0,118,255,0.2)',
                        'rgb(0,185,241,0.2)',
                        'rgb(30,222,129,0.2)',
                        'rgb(240,13,76,0.2)',
                        'rgb(255,117,0,0.2)',
                        'rgb(255,197,21,0.2)',
                        'rgb(255,102,252,0.2)'
                    ],
                    borderColor: [
                        'rgb(105,50,165, 1)',
                        'rgb(0,118,255,1)',
                        'rgb(0,185,241,1)',
                        'rgb(30,222,129,1)',
                        'rgb(240,13,76,1)',
                        'rgb(255,117,0,1)',
                        'rgb(255,197,21,1)',
                        'rgb(255,102,252,1)'
                    ]
                }]

            };
            var talentSchoolOptions = {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            };
            var talentSchoolChart = new Chart(talentSchoolCtx, {
                type: 'bar',
                data: talentSchoolData,
                options: talentSchoolOptions
            });

            function selectSchool() {
                selectedSchool = select.value;
                window.location.href = "?page=tt-stats&school=" + selectedSchool;
            }
        })();
    </script>
    <?php
}