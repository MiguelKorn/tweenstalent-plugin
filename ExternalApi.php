<?php

class ExternalApi
{
    private $key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXV0aC9sb2dpbiIsImlhdCI6MTUyNzU4NDE2NSwiZXhwIjoxNTI3NjIwMTY1LCJuYmYiOjE1Mjc1ODQxNjUsImp0aSI6InVDSXRnME1FcW5jT2ZRWXkiLCJzdWIiOjEsInBydiI6ImY5MzA3ZWI1ZjI5YzcyYTkwZGJhYWVmMGUyNmYwMjYyZWRlODZmNTUifQ.huF3MzEW9I_y8xtZCayIIyvU9ZQbN62z5T37xIlBWMs';
    private $apiUrl = 'http://localhost:8000';

    public function getEvent()
    {
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = \wp_remote_get( $this->apiUrl . '/events', $args );
        $response = json_decode( $response['body'], true );

        return $response['data'][0];
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

        var_dump($response);

        return $response['body']['message'] /*json_decode( $response, true)*/;
    }

    public function getGuests()
    {
        //TODO: same as other get reqs? create one function
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = \wp_remote_get( $this->apiUrl . '/guests', $args );
        $response = json_decode( $response['body'], true );

        return $response['data'];
    }
}