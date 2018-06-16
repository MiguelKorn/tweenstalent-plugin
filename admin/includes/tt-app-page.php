<?php
$api = new ExternalApi();
function tt_app_page()
{
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'questions';

    $tabs = array(
        'questions' => array(
            'name'    => 'Vragen',
            'content' => 'questionContent'
        ),
        'schools'   => array(
            'name'    => 'Scholen',
            'content' => 'schoolsContent'
        ),
        'levels'    => array(
            'name'    => 'Niveau\'s',
            'content' => 'levelsContent'
        )
    );

    $tab = new Tabs( 'tt-app', $tabs );

    ?>
    <div class="wrap">
        <h2>TweensTalent App</h2>

        <h2 class="nav-tab-wrapper">
            <?php $tab->displayNav( $active_tab ); ?>
        </h2>


        <form method="post" action="<?= esc_url( $_SERVER['REQUEST_URI'] ) ?>" novalidate="novalidate" id="form">
            <?php $tabs[$active_tab]['content']() ?>
        </form>
    </div>
    <?php
}

function questionContent()
{
    global $api;
    if ( isset( $_POST['submit'] ) ) {
        $result = $api->patchQuestions( $_POST );
        if ( ! $result ) {
            TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
        } else {
            TweensTalent::notice( 'success', '<p>Successvol gewijzigd!</p>', true );
        }
    }
    $talents   = $api->getTalents();
    $questions = $api->getQuestions();
    ?>
    <br>
    <table id="questionList" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
        <div class="col-sm-12 legend">
            <div class="row">
                <?php
                for ( $i = 0; $i < 4; $i ++ ) {
                    echo "<div class='col'>";
                    echo "<input type='hidden' name='" . $talents[ $i ]['name'] . "-id' value='" . $talents[ $i ]['id'] . "' >";
                    echo "<button type='button' class='btn btn-primary btn-sm btn-block'>{$talents[$i]['name']}</button>";
                    echo "</div>";
                }
                ?>
            </div>
            <div class="row row-top">
                <?php
                for ( $i = 4; $i < 8; $i ++ ) {
                    echo "<div class='col'>";
                    echo "<input type='hidden' name='" . $talents[ $i ]['name'] . "-id' value='" . $talents[ $i ]['id'] . "' >";
                    echo "<button type='button' class='btn btn-primary btn-sm btn-block'>{$talents[$i]['name']}</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <thead>
        <tr>
            <th>Talent</th>
            <th>Vraag</th>
            <!--            <th></th>-->
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ( $questions as $key => $question ) {
            echo "<tr>";
            echo "<td>{$question['talent']['data']['name']}</td>";
            echo "<td><input class='form-control' type='text' id='row-{$key}-name' name='question-{$key}' value='{$question['name']}'></td>";
//            echo "<td><a href='' class='btn btn-info'><i class='fas fa-save'></i></a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <?php
    return false;
}

function schoolsContent()
{
    global $api;
    if ( isset( $_POST['submit-add'] ) ) {
        $name   = sanitize_text_field( $_POST['new-school-name'] );
        $result = $api->addSchool( $name );
        $type   = 'toegevoegd';
    } elseif ( isset( $_POST['submit-edit'] ) ) {
        $id     = $_POST['submit-edit'];
        $name   = sanitize_text_field( $_POST['schools'][ $id ]['name'] );
        $result = $api->patchSchool( $id, $name );
        $type   = 'gewijzigd';
    } elseif ( isset( $_POST['submit-delete'] ) ) {
        $id     = $_POST['submit-delete'];
        $result = $api->deleteSchool( $id );
        $type   = 'verwijderd';
    }

    if ( isset( $result ) ) {
        if ( ! $result ) {
            TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
        } else {
            TweensTalent::notice( 'success', '<p>Successvol ' . $type . '!</p>', true );
        }
    }

    $schools = $api->getSchools();
    ?>
    <br>
    <table id="schoolList" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
        <thead>
        <tr>
            <td>#</td>
            <th>Naam</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ( $schools as $key => $school ) {
            ?>
            <tr>
                <td><?= $school['id'] ?></td>
                <td><input class='form-control' type='text' id='row-<?= $school['id'] ?>-name'
                           name='schools[<?= $school['id'] ?>][name]' value='<?= $school['name'] ?>'></td>
                <td>
                    <button type="submit" class='btn btn-primary btn-sm' name="submit-edit"
                            value="<?= $school['id'] ?>"><i class="fas fa-save"></i></button>
                    <button type="submit" name="submit-delete" value="<?= $school['id'] ?>"
                            class='btn btn-danger btn-sm'><i class="fas fa-minus-circle"></i></button>
                </td>
                <td><?= $school['name'] ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <div class="modal fade" id="addSchoolModal" tabindex="-1" role="dialog" aria-labelledby="addSchoolModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">School Toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="addSchoolName" class="col-form-label">Naam</label>
                            <input type="text" class="form-control" id="addSchoolName" name="new-school-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                    <button type="submit" name="submit-add" class="btn btn-primary">Toevoegen</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    return false;
}

function levelsContent()
{
    global $api;
    if ( isset( $_POST['submit-add'] ) ) {
        $name   = sanitize_text_field( $_POST['new-level-name'] );
        $result = $api->addLevel( $name );
        $type   = 'toegevoegd';
    } elseif ( isset( $_POST['submit-edit'] ) ) {
        $id     = $_POST['submit-edit'];
        $name   = sanitize_text_field( $_POST['levels'][ $id ]['name'] );
        $result = $api->patchLevel( $id, $name );
        $type   = 'gewijzigd';
    } elseif ( isset( $_POST['submit-delete'] ) ) {
        $id     = $_POST['submit-delete'];
        $result = $api->deleteLevel( $id );
        $type   = 'verwijderd';
    }

    if ( isset( $result ) ) {
        if ( ! $result ) {
            TweensTalent::notice( 'error', '<p>Er is iets misgegaan. Probeer opnieuw!</p>', true );
        } else {
            TweensTalent::notice( 'success', '<p>Successvol ' . $type . '!</p>', true );
        }
    }

    $levels = $api->getLevels();
    ?>
    <br>
    <table id="levelList" class="table table-striped table-bordered" style="width:100%" cellspacing="0">
        <thead>
        <tr>
            <td>#</td>
            <th>Naam</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ( $levels as $key => $level ) {
            ?>
            <tr>
                <td><?= $level['id'] ?></td>
                <td><input class='form-control' type='text' id='row-<?= $level['id'] ?>-name'
                           name='levels[<?= $level['id'] ?>][name]' value='<?= $level['name'] ?>'></td>
                <td>
                    <button type="submit" class='btn btn-primary btn-sm' name="submit-edit"
                            value="<?= $level['id'] ?>"><i class="fas fa-save"></i></button>
                    <button type="submit" name="submit-delete" value="<?= $level['id'] ?>"
                            class='btn btn-danger btn-sm'><i class="fas fa-minus-circle"></i></button>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <div class="modal fade" id="addLevelModal" tabindex="-1" role="dialog" aria-labelledby="addLevelModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Niveau Toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="addLevelName" class="col-form-label">Naam</label>
                            <input type="text" class="form-control" id="addLevelName" name="new-level-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                    <button type="submit" name="submit-add" class="btn btn-primary">Toevoegen</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    return false;
}