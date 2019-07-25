<?php
    /* Disable explicit error reporting */
    error_reporting(0);
    /* Import Core Module */
    require('core.php');

    $u = new User('test', 'test123');
    echo $u->getUsername()."<br>";
    $r = $u->doAuthenticate();
    echo $r;

?>
