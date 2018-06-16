<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 28/05/2018
 * Time: 09:31
 */

class Tabs
{
    private $page;
    private $tabNames;

    /**
     * Tabs constructor.
     *
     * @param $page
     * @param $tabNames
     */
    public function __construct($page, $tabNames)
    {
        $this->page = $page;
        $this->tabNames = $tabNames;
    }

    /**
     * @param $active
     */
    public function displayNav($active)
    {
        foreach ( $this->tabNames as $key => $tab ) {
            $activeTab = ( $active === $key ? 'nav-tab-active' : '' );
            echo "<a class='nav-tab {$activeTab}' href='?page={$this->page}&tab={$key}'>{$tab['name']}</a>";
        }
    }
}