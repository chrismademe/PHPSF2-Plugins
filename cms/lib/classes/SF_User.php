<?php

namespace CMS;
use Exception;
use PDO;
use PHPAuth\Config;
use PHPAuth\Auth;

class User {

    # Auth Object
    private $auth;

    # Error
    private $error;

    /**
     * Construct
     */
    public function __construct( PDO $pdo ) {

        # Setup Auth
        $config = new Config($pdo);
        $this->auth   = new Auth($pdo, $config);

    }

    /**
     * Login
     */
    public function login( $email, $password ) {

        # Attempt Login
        $login = $this->auth->login( $email, $password, false );

        # Errors
        if ( $login['error'] ) {

            # Store the message
            $this->error = $login['message'];

            # Return false
            return false;

        }

        # Get User ID
        if ( !$userID = $this->auth->getSessionUID( $login['hash'] ) ) {

            # Store error
            $this->error = 'Unable to get user ID.';

            # Return false
            return false;

        }

        # Get User Details
        if ( !$user = $this->auth->getUser( $userID ) ) {

            # Store error
            $this->error = 'Unable to get user info.';

            # Return false
            return false;

        }

        # Create Session
        $_SESSION[SESSION_ID]['user'] = array(
            'id'        => $user['uid'],
            'email'     => $user['email'],
            'isactive'  => $user['isactive']
        );

        # Success!
        return true;

    }

    /**
     * Register
     */
    public function register ( $email, $password ) {

        # Register
        $register = $this->auth->register($email, $password, $password, $params = array(), $captcha = NULL, $sendmail = false);

        # Error
        if ( $register['error'] ) {

            # Store message
            $this->error = $register['message'];

            # Return false
            return false;

        }

        # Success!
        return true;

    }

    /**
     * Get Error
     */
    public function get_error() {
        return $this->error;
    }

}
