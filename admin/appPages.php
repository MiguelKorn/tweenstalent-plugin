<?php
function admin_app_page()
{
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'tab1';

    $tabs = array(
        'tab1' => array(
            'name'    => 'TAB 1',
            'content' => 'tabOneContent'
        ),
        'tab2' => array(
            'name'    => 'TAB 2',
            'content' => 'tabTwoContent'
        )
    );

    $tab = new Tabs( 'tt-app', $tabs );

    ?>
    <div class="wrap">
        <h2>An Example Welcome Screen</h2>

        <h2 class="nav-tab-wrapper">
            <?php $tab->displayNav( $active_tab ); ?>
        </h2>


        <form action="#">
            <?php $tabs[$active_tab]['content']() ?>
        </form>
    </div>
    <?php
}

function tabOneContent()
{
    echo 'test tab one content';

    return false;
}

function tabTwoContent()
{
    echo 'test tab TWo content';

    return false;
}