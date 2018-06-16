<?php

class GuestTable extends WP_List_Table
{
    private $api;
    private $guests;

    public function __construct()
    {
        parent::__construct();

        $this->api    = new ExternalApi();
        $this->guests = $this->api->getGuests();
    }

    public function prepare_items()
    {
        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data     = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage     = 2;
        $currentPage = $this->get_pagenum();
        $totalItems  = count( $data );
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data                  = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage );
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items           = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'firstname' => 'Voornaam',
            'lastname'  => 'Achternaam',
            'email'     => 'Email',
            'company'   => 'Bedrijf',
            'status'    => 'Status',
            'createdAt' => 'Aangemeld Op'
        );

        return $columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array(
            'firstname' => array('firstname', false),
            'lastname' => array('lastname', false),
            'email' => array('email', false),
            'createdAt' => array( 'createdAt', false )
        );
    }

    private function table_data()
    {
        $data = array();
        foreach ( $this->guests as $guest ) {
            $lastName = ( $guest['name']['lastNamePrefix'] !== '' ? $guest['name']['lastNamePrefix'] . ' ' : '' ) . $guest['name']['lastName'];
            array_push( $data, array(
                'firstname' => $guest['name']['firstName'],
                'lastname'  => $lastName,
                'email'     => $guest['email'],
                'company'   => $guest['company_name'],
                'status'    => 'AT: ' . $guest['boolean']['isAttending'] . ' | AP: ' . $guest['boolean']['isApproved'],
                'createdAt' => gmdate( 'd-m-Y H:i:s', strtotime( $guest['dates']['createdAt']['date'] ) )
            ) );
        }

        return $data;
    }

    public function column_default($item, $column_name)
    {
        switch ( $column_name ) {
            case 'firstname':
            case 'lastname':
            case 'email':
            case 'company':
            case 'status':
            case 'createdAt':
                return $item[ $column_name ];
            default:
                return print_r( $item, true );
        }
    }

    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'createdAt';
        $order   = 'desc';
        // If orderby is set, use this as the sort column
        if ( ! empty( $_GET['orderby'] ) ) {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if ( ! empty( $_GET['order'] ) ) {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[ $orderby ], $b[ $orderby ] );
        if ( $order === 'asc' ) {
            return $result;
        }

        return - $result;
    }
}