<?php
    /* Disable explicit error reporting */
    error_reporting(0);
    /* User Constants */
    const DB_FAIL = 0;
    const AUTH_OK = 1;
    const INVALID_USER = 2;
    const INVALID_PW = 3;
    /* User Class */
    class User {
        var $username = '';
        var $password = '';
        var $screenname = '';
        var $status = '-1';

        function __construct($u, $p) {
            $this->username = $u;
            $this->password = $p;
        }

        public function doAuthenticate() {
            /* connect to sql server */
            $dbhost = "den1.mysql6.gear.host";
            $dbun = "anyshare";
            $dbpw = "ags@123";
            $db = "anyshare";

            $conn = new mysqli($dbhost, $dbun, $dbpw, $db);
            if ($conn->connect_error) {
                return DB_FAIL;
            } else {
                return AUTH_OK;
            }
        }

        public function getUsername() {
            return $this->username;
        }
    }
?>
