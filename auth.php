<?php
    /* Disable explicit error reporting */
    //error_reporting(0);
    /* Import Core Module */
    require('core.php');
    $data = $_POST;

    if (isset($data['un']) && isset($data['pw'])) {
        $u = new User($data['un'], $data['pw']);
        $r = $u->doAuthenticate();
        if ($r == AuthStatus::DB_FAIL) {
            echo 'dbfail';
        } else if ($r == AuthStatus::AUTH_OK) {
            echo 'authok';
        } else if ($r == AuthStatus::ALREADY_SIGNED_IN) {
            echo 'alreadysignedin~'.$u->existing_signin_details['useragent'].'~'.$u->existing_signin_details['os'].'~'.$u->existing_signin_details['time'];
        } else if ($r == AuthStatus::INVALID_USER) {
            echo 'invaliduser';
        } else if ($r == AuthStatus::INVALID_PW) {
            echo 'invalidpw';
        }
    } else {
        echo 'argserror';
    }

?>
