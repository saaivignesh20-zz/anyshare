<?php
    require('core.php');
    // authenticate with token
    $token = $_COOKIE['anyshareAuth'];
    $u = User::withToken($token);
    $u->doAuthenticate();
    echo $u->token."<br>";
    echo "Status: ".$u->status."<br>";
    echo "Username: ".$u->username."<br>";
    echo "Screen Name: ".$u->screenname."<br>";
?>
