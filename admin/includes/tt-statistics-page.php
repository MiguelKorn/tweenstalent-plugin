<?php

function tt_statistics_page()
{
    echo 'm/v, scholen, keuze talenten';
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Statistieken</h1>
        <div class="row">
            <div class="col-12 col-md-6">
                <canvas id="myChart" width="400" height="400"/>
            </div>
            <div class="col-12 col-md-6">
                <canvas id="myChart" width="400" height="400"/>
            </div>
        </div>
    </div>
    <?php
}