<?php
    /* Disable explicit error reporting */
    //error_reporting(0);

    /* User Constants */
    abstract class AuthStatus {
        const DB_FAIL = 0;
        const AUTH_OK = 1;
        const ALREADY_SIGNED_IN = 2;
        const INVALID_USER = 3;
        const INVALID_PW = 4;
        const INVALID_TOKEN = 5;
        const SESSION_TIMEOUT = 6;
        const DEAUTH_OK = 7;
        const DEAUTH_FAIL = 8;
    }

    /* Server Variables */
    const dbhost = 'localhost';
    const dbun = 'root';
    const dbpw = '';
    const db = 'anyshare';

    /* User Class */
    class User {
        var $username = '';
        var $password = '';
        var $screenname = '';
        var $status = '-1';
        var $token = '';
        var $existing_signin_details = array();

        /* constructor overloading isn't supported on PHP, so helper methods are defined here */
        public static function withUNPW($u, $p) {
            $i = new self();
            $i->username = $u;
            $i->password = $p;
            return $i;
        }

        public static function withToken($t) {
            $i = new self();
            $i->token = $t;
            return $i;
        }

        public function doAuthenticate() {
            if ($this->token == '' && $this->username != '' && $this->password != '') {
                try {
                    /* connect to sql server */
                    $conn = new mysqli(dbhost, dbun, dbpw, db);
                    if ($conn->connect_error) {
                        $this->status = AuthStatus::DB_FAIL;
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR emailid = ?");
                        $stmt->bind_param("ss", $this->username, $this->username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($this->username == $row['emailid']) {
                                    $this->username = $row['username'];
                                }
                                $sqlpw = $row['password'];
                                $subpw = hash('sha512', $this->password);
                                if ($sqlpw == $subpw) {
                                    // authentication success, check if already logged in
                                    $checkstmt = $conn->prepare("SELECT * FROM authenticatedusers WHERE username = ?");
                                    $checkstmt->bind_param("s", $this->username);
                                    $checkstmt->execute();
                                    $checkresult = $checkstmt->get_result();
                                    if ($checkresult->num_rows > 0) {
                                        // already logged in, check ip
                                        while ($crow = $checkresult->fetch_assoc()) {
                                            $lastip = $crow['ipadd'];
                                            $lastauthtoken = $crow['token'];
                                            $lastuseragent = $crow['useragent'];
                                            $lastos = $crow['os'];
                                            $currentip = $_SERVER['REMOTE_ADDR'];
                                            $currentuseragent = $_SERVER['HTTP_USER_AGENT'];
                                            $currentoperatingsystem = getOS($currentuseragent);
                                            if ($lastip == $currentip) {
                                                setcookie("anyshareAuth", $lastauthtoken, time() + 3600 * 24, "/");
                                                $sqlintime = new DateTime($crow['intime']);
                                                $currentintime = new DateTime(date("Y-m-d H:i:s"));
                                                $currentintimestring = $currentintime->format("Y-m-d H:i:s");
                                                $interval = $sqlintime->diff($currentintime)->format('%r%a');
                                                if ($interval > 0) {
                                                    // generate a new signin token
                                                    $newtoken = openssl_random_pseudo_bytes(16);
                                                    $newauthtoken = bin2hex($newtoken);
                                                    // update all parameters.
                                                    $reauthstmt = $conn->prepare("UPDATE authenticatedusers SET ipadd = ?, useragent = ?, os = ?, intime = ?, token = ? WHERE token = ?;");
                                                    $reauthstmt->bind_param("ssssss", $currentip, $currentuseragent, $currentoperatingsystem, $currentintimestring, $newauthtoken, $lastauthtoken);
                                                    $reauthstmt->execute();
                                                    $reauthresult = $reauthstmt->get_result();
                                                    $this->token = $newtoken;
                                                }
                                                $this->status = AuthStatus::AUTH_OK;
                                            } else {
                                                // check for time difference
                                                $sqlintime = new DateTime($crow['intime']);
                                                $currentintime = new DateTime(date("Y-m-d H:i:s"));
                                                $currentintimestring = $currentintime->format("Y-m-d H:i:s");
                                                $interval = $sqlintime->diff($currentintime)->format('%r%a');
                                                if ($interval > 0) {
                                                    // generate a new signin token
                                                    $newtoken = openssl_random_pseudo_bytes(16);
                                                    $newauthtoken = bin2hex($newtoken);
                                                    // update all parameters.
                                                    $reauthstmt = $conn->prepare("UPDATE authenticatedusers SET ipadd = ?, useragent = ?, os = ?, intime = ?, token = ? WHERE token = ?;");
                                                    $reauthstmt->bind_param("ssssss", $currentip, $currentuseragent, $currentoperatingsystem, $currentintimestring, $newauthtoken, $lastauthtoken);
                                                    $reauthstmt->execute();
                                                    $reauthresult = $reauthstmt->get_result();
                                                    $this->token = $newtoken;
                                                    $this->status = AuthStatus::AUTH_OK;
                                                } else {
                                                    $this->existing_signin_details['useragent'] = $lastuseragent;
                                                    $this->existing_signin_details['os'] = $lastos;
                                                    $this->existing_signin_details['time'] = $sqlintime->format("Y-m-d H:i:s")." IST";
                                                    $this->status = AuthStatus::ALREADY_SIGNED_IN;
                                                }
                                            }
                                        }
                                    } else {
                                        // add the username into authenticatedusers
                                        $authun = $this->username;
                                        $authip = $_SERVER['REMOTE_ADDR'];
                                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                                        $operatingsystem = getOS($useragent);
                                        $intime = date("Y-m-d H:i:s");

                                        // generate a signin token
                                        $token = openssl_random_pseudo_bytes(16);
                                        $authtoken = bin2hex($token);

                                        $authstmt = $conn->prepare("INSERT INTO authenticatedusers (username, ipadd, useragent, os, intime, token) VALUES(?, ?, ?, ? , ?, ?)");
                                        $authstmt->bind_param("ssssss", $authun, $authip, $useragent, $operatingsystem, $intime, $authtoken);
                                        $authstmt->execute();
                                        $this->status = AuthStatus::AUTH_OK;
                                        $this->token = $authtoken;
                                        setcookie("anyshareAuth", $this->token, time() + 3600 * 24, "/");
                                    }
                                } else {
                                    $this->status = AuthStatus::INVALID_PW;
                                }
                            }
                        } else {
                            $this->status = AuthStatus::INVALID_USER;
                        }
                    }
                } catch (Exception $e) {
                    echo $e;
                    $conn->close();
                    $this->status = AuthStatus::DB_FAIL;
                }
            } else if  ($this->token != '' && $this->username == '' && $this->password == '') {
                try {
                    /* connect to sql server */
                    $conn = new mysqli(dbhost, dbun, dbpw, db);
                    if ($conn->connect_error) {
                        $this->status = AuthStatus::DB_FAIL;
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM authenticatedusers WHERE token= ?;");
                        $stmt->bind_param("s", $this->token);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            // reset signin time if the difference is less or equal to one day
                            while ($row = $result->fetch_assoc()) {
                                $sqlintime = new DateTime($row['intime']);
                                $currentintime = new DateTime(date("Y-m-d H:i:s"));
                                $interval = $sqlintime->diff($currentintime)->format('%r%a');
                                if ($interval > 0) {
                                    // sign out
                                    setcookie("anyshareAuth", null, time() - 3600, "/");
                                    $deauthstmt = $conn->prepare("DELETE FROM authenticatedusers WHERE token= ?;");
                                    $deauthstmt->bind_param("s", $this->token);
                                    $deauthstmt->execute();
                                    $this->status = AuthStatus::SESSION_TIMEOUT;
                                } else {
                                    setcookie("anyshareAuth", $this->token, time() + 3600 * 24, "/");
                                    $this->status = AuthStatus::AUTH_OK;
                                }
                            }
                        } else {
                            $this->status = AuthStatus::INVALID_TOKEN;
                        }
                    }
                } catch (Exception $e) {
                    echo $e;
                    $conn->close();
                    $this->status = AuthStatus::DB_FAIL;
                }
            } else {
                $this->status = false;
            }
            $conn->close();
            return $this->status;
        }

        public function signOutUser() {
            try {
                // check for valid token
                if ($this->token != '') {
                    // make db changes to revoke authentication of user
                    /* connect to sql server */
                    $conn = new mysqli(dbhost, dbun, dbpw, db);
                    if ($conn->connect_error) {
                        $this->status = AuthStatus::DEAUTH_FAIL;
                    } else {
                        $stmt = $conn->prepare("DELETE FROM authenticatedusers WHERE token = ?");
                        $stmt->bind_param("s", $this->token);
                        $stmt->execute();
                        $this->status = AuthStatus::DEAUTH_OK;
                    }
                }
            } catch (Exception $e) {
                echo $e;
                $conn->close();
                $this->status = AuthStatus::DEAUTH_FAIL;
            }
            setcookie("anyshareAuth", null, time() - 3600, "/");
            $conn->close();
            return $this->status;
        }

        public function getUsername() {
            return $this->username;
        }
    }

    /* common methods */

    function getOS($userAgent) {
        // Create list of operating systems with operating system name as array key
        $oses = array (
            'iPhone'            => '(iPhone)',
            'Windows 3.11'      => 'Win16',
            'Windows 95'        => '(Windows 95)|(Win95)|(Windows_95)',
            'Windows 98'        => '(Windows 98)|(Win98)',
            'Windows 2000'      => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP'        => '(Windows NT 5.1)|(Windows XP)',
            'Windows 2003'      => '(Windows NT 5.2)',
            'Windows Vista'     => '(Windows NT 6.0)|(Windows Vista)',
            'Windows 7'         => '(Windows NT 6.1)|(Windows 7)',
            'Windows NT 4.0'    => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'Windows ME'        => 'Windows ME',
            'Open BSD'          => 'OpenBSD',
            'Sun OS'            => 'SunOS',
            'Linux'             => '(Linux)|(X11)',
            'Safari'            => '(Safari)',
            'Mac OS'            => '(Mac_PowerPC)|(Macintosh)',
            'QNX'               => 'QNX',
            'BeOS'              => 'BeOS',
            'OS/2'              => 'OS/2',
            'Search Bot'        => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
        );

        // Loop through $oses array
        foreach($oses as $os => $preg_pattern) {
            // Use regular expressions to check operating system type
            if ( preg_match('@' . $preg_pattern . '@', $userAgent) ) {
                // Operating system was matched so return $oses key
                return $os;
            }
        }

        // Cannot find operating system so return Unknown

        return 'n/a';
    }
?>
