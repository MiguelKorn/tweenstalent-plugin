<?php

class ExternalApi
{
    private $key;
    private $apiUrl = API_URL;

    /**
     * ExternalApi constructor.
     */
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

    /**
     * @return mixed
     */
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

    /**
     * @param $url
     *
     * @return mixed
     */
    private function getTrait($url)
    {
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $response = \wp_remote_get( $this->apiUrl . $url, $args );
        $response = json_decode( $response['body'], true );

        return $response['data'];
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->getTrait( '/events' )[0];
    }

    /**
     * @param $id
     * @param $name
     * @param $streetName
     * @param $streetNumber
     * @param $postalCode
     * @param $city
     * @param $startDate
     * @param $endDate
     *
     * @return array|mixed|object
     */
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

    /**
     * @return mixed
     */
    public function getGuests()
    {
        return $this->getTrait( '/guests' );
    }

    /**
     * @param $firstName
     * @param $lastNamePrefix
     * @param $lastName
     * @param $email
     * @param $company
     * @param $job
     * @param bool $register
     *
     * @return bool
     */
    public function addGuest($firstName, $lastNamePrefix, $lastName, $email, $company, $job, $register = false)
    {
        $request = array(
            'firstName'      => $firstName,
            'lastNamePrefix' => $lastNamePrefix,
            'lastName'       => $lastName,
            'email'          => $email,
            'companyName'    => $company,
            'jobTitle'       => $job,
            'isAttending'    => $register,
            'isApproved'     => 1,
            'guestTypeId'    => 1,
            'approvedAt'     => date( 'd-m-Y' ),
            'eventId'        => 1,
            'guestAssetId'   => 1,
            'isInvited'      => $register ? 0 : 1
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

        if ( $response['response']['code'] === 200 ) {
            $fullLastName = ! empty( $lastNamePrefix ) ? "{$lastNamePrefix} {$lastName}" : $lastName;
            $url          = get_home_url() . '/?action=add-to-agenda';
            $search       = [ '{gebruiker}', '{voornaam}', '{achternaam}', '{email}', '{toevegen-aan-agenda}' ];
            $replace      = [
                "{$firstName} {$fullLastName}",
                $firstName,
                $fullLastName,
                $email,
                "<a href='{$url}' target='_blank'>Toevoegen aan agenda</a>"
            ];
            $message      = str_replace( $search, $replace, get_option( 'register_email_message' ) );

            wp_mail(
                "{$firstName} {$fullLastName} <{$email}>",
                'Registratie TweensTalent',
                $message
            );

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return bool
     */
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

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->getTrait( '/questions?include=talent' );
    }

    /**
     * @param $questions
     *
     * @return array|bool
     */
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

    /**
     * @return mixed
     */
    public function getTalents()
    {
        return $this->getTrait( '/talents' );
    }

    /**
     * @return mixed
     */
    public function getSchools()
    {
        return $this->getTrait( '/schools?include=schoolLevels' );
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function addSchool($name)
    {
        $request = array(
            'name' => $name
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

    /**
     * @param $id
     * @param $name
     *
     * @return bool
     */
    public function patchSchool($id, $name)
    {
        $request = array(
            'name' => $name
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

    /**
     * @param $id
     *
     * @return bool
     */
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

    /**
     * @return mixed
     */
    public function getLevels()
    {
        return $this->getTrait( '/schools/levels' );
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function addLevel($name)
    {
        $request = array(
            'name' => $name
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

    /**
     * @param $id
     * @param $name
     *
     * @return bool
     */
    public function patchLevel($id, $name)
    {
        $request = array(
            'name' => $name
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

    /**
     * @param $id
     *
     * @return bool
     */
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

    public function getStudents()
    {
        return $this->getTrait( '/students?include=talents,school' );
    }

    public function parseStudentsData($students)
    {
        $studentsSchools = array();
        $countGender     = array();
        foreach ( $students as $student ) {
            $schoolId = $student['school']['data']['id'];
            $talents  = $student['talents']['data'];
            if ( array_key_exists( $schoolId, $studentsSchools ) ) {
                $studentsSchools[ $schoolId ]['students'] ++;
            } else {
                $studentsSchools[ $schoolId ] = array(
                    'students' => 1,
                    'talents'  => array()
                );
            }

            foreach ( $talents as $talent ) {
                $talentId = $talent['id'];
                if ( array_key_exists( $talentId, $studentsSchools[ $schoolId ]['talents'] ) ) {
                    $studentsSchools[ $schoolId ]['talents'][ $talentId ] ++;
                } else {
                    $studentsSchools[ $schoolId ]['talents'][ $talentId ] = 1;
                }
            }

            $gender = $student['gender'];
            if ( array_key_exists( $gender, $countGender ) ) {
                $countGender[ $gender ] ++;
            } else {
                $countGender[ $gender ] = 1;
            }
        }

        return array(
            'studentSchools' => $studentsSchools,
            'countGender'    => $countGender
        );
    }
}