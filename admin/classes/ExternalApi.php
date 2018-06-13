<?php

class ExternalApi
{
    private $key;
    private $apiUrl = 'http://localhost:8000';

    public function __construct()
    {

        if ( isset( $_SESSION['tt-ea'] ) ) {
            if ( $_SESSION['tt-ea']['expire'] < time() ) {
                // key expired
                unset( $_SESSION['tt-ea'] );
                $this->getKey();
            }

            $this->key = $_SESSION['tt-ea']['key'];
        } else {
            $this->key = $this->getKey();
        }
    }

    public function getKey()
    {
        $request = array(
            'email'    => API_EMAIL,
            'password' => API_PASS
        );

        $args     = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body'    => json_encode( $request )
        );
        $response = wp_remote_post( $this->apiUrl . '/auth/login', $args );
        $response = json_decode( $response['body'], true );

        $_SESSION['tt-ea'] = array(
            'set'    => time(),
            'expire' => time() + $response['expires_in'],
            'key'    => $response['access_token']
        );

        return $response['access_token'];
    }

    private function getTrait($url)
    {
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = \wp_remote_get( $this->apiUrl . $url, $args );
        $response = json_decode( $response['body'], true );
        var_dump($response['data'][0]);

        return $response['data'];
    }

    public function getEvent()
    {
        return $this->getTrait( '/events' )[0];
    }

    public function patchEvent($id, $name, $streetName, $streetNumber, $postalCode, $city, $startDate, $endDate)
    {
        $request = array(
            'name'         => $name,
            'streetName'   => $streetName,
            'streetNumber' => $streetNumber,
            'postalCode'   => $postalCode,
            'city'         => $city,
            'startEvent'   => $startDate,
            'endEvent'     => $endDate,
            'createdBy'    => 1 //TODO; hardcode?
        );


        $args = array(
            'method'  => 'PATCH',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            ),
            'body'    => json_encode( $request )
        );

        $response = wp_remote_post( $this->apiUrl . '/events/' . $id, $args );

        return json_decode( $response['body'], true );
    }

    public function getGuests()
    {
        return $this->getTrait( '/guests' );
    }

    public function addGuest($firstName, $lastNamePrefix, $lastName, $email, $company, $job)
    {
        $request = array(
            'firstName'      => $firstName,
            'lastNamePrefix' => $lastNamePrefix,
            'lastName'       => $lastName,
            'email'          => $email,
            'companyName'    => $company,
            'jobTitle'       => $job,
            'isAttending'    => 0,
            'isApproved'     => 1,
            'guestTypeId'    => 1,
            'approvedAt'     => date( 'd-m-Y' ),
            'eventId'        => 1,
            'guestAssetId'   => 1
        );

        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            ),
            'body'    => json_encode( $request )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/guests', $args );

        return $response['response']['code'] === 200;
    }

    public function deleteGuest($id)
    {
        $args = array(
            'method'  => 'DELETE',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/guests/' . $id, $args );

        return $response['response']['code'] === 200;
    }

    public function getQuestions()
    {
        return $this->getTrait( '/questions?include=talent' );
    }

    public function patchQuestions($questions)
    {
        $errors   = array();
        $talent   = sanitize_text_field( $questions['talent'] );
        $talentId = sanitize_text_field( $questions[ $talent . '-id' ] );
        foreach ( $questions as $key => $value ) {
            if ( strpos( $key, 'question-' ) !== false ) {
                $id    = intval( str_replace( "question-", "", "$key" ) ) + 1;
                $value = sanitize_text_field( $value );

                $args = array(
                    'method'  => 'PATCH',
                    'headers' => array(
                        'Content-Type'  => 'application/json',
                        'Authorization' => 'Bearer ' . $this->key
                    ),
                    'body'    => json_encode( array( 'name' => $value, 'talentId' => $talentId ) )
                );

                $response = wp_remote_post( $this->apiUrl . '/questions/' . $id, $args );
                if ( $response['response']['code'] !== 200 ) {
                    array_push( $errors, array(
                        'id'       => $id,
                        'name'     => $value,
                        'talent'   => $talent,
                        'talentId' => $talentId,
                    ) );
                }
            }
        }

        return ( ! empty( $errors ) ) ? $errors : true;
    }

    public function getTalents()
    {
        return $this->getTrait( '/talents' );
    }

    public function getSchools()
    {
        return $this->getTrait( '/schools?include=schoolLevels' );
    }

    public function addSchool($name)
    {
        $request = array(
            'name'         => $name
        );

        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            ),
            'body'    => json_encode( $request )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/schools', $args );

        return $response['response']['code'] === 200;
    }

    public function patchSchool($id, $name)
    {
        $request = array(
            'name'         => $name
        );

        $args = array(
            'method'  => 'PATCH',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            ),
            'body'    => json_encode( $request )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/schools/' . $id, $args );

        return $response['response']['code'] === 200;
    }

    public function deleteSchool($id)
    {
        $args = array(
            'method'  => 'DELETE',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/schools/' . $id, $args );

        return $response['response']['code'] === 200;
    }

    public function getLevels()
    {
        return $this->getTrait( '/schools/levels' );
    }

    public function addLevel($name)
    {
        $request = array(
            'name'         => $name
        );

        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            ),
            'body'    => json_encode( $request )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/schools/levels', $args );

        return $response['response']['code'] === 200;
    }

    public function patchLevel($id, $name)
    {
        $request = array(
            'name'         => $name
        );

        $args = array(
            'method'  => 'PATCH',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            ),
            'body'    => json_encode( $request )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/schools/levels/' . $id, $args );

        return $response['response']['code'] === 200;
    }

    public function deleteLevel($id)
    {
        $args = array(
            'method'  => 'DELETE',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = $response = wp_remote_post( $this->apiUrl . '/schools/levels/' . $id, $args );

        return $response['response']['code'] === 200;
    }
}