<?php
    require('core.php');
    if (!isset($_COOKIE['anyshareAuth'])) {
        echo "nocookie";
    } else {
        $u = User::withToken($_COOKIE['anyshareAuth']);
        $u->doAuthenticate();
        $screenname = $u->screenname;
        $emailid = $u->getEmailID();

        // build filelist for user's drive
        $filelist = array();
        $conn = new mysqli(dbhost, dbun, dbpw, db);
        if ($conn->connect_error) {
            die("Can't connect to database!");
        }

        $flstmt = $conn->prepare("SELECT * FROM files WHERE username = ?");
        $flstmt->bind_param("s", $u->username);
        $flstmt->execute();
        $flresult = $flstmt->get_result();
        if ($flresult->num_rows > 0) {
            while($row = $flresult->fetch_assoc()) {
                $filelist[$row['fileid']]['name'] = $row['filename'];
                $filelist[$row['fileid']]['size'] = $row['size'];
                $filelist[$row['fileid']]['dlink'] = buildDirectDownloadLink($row['fileid']);
                $shareinfostmt = $conn->prepare("SELECT shareinfo FROM shares WHERE fileid = ?");
                $shareinfostmt->bind_param("s", $row['fileid']);
                $shareinfostmt->execute();
                $shareinforesult = $shareinfostmt->get_result();
                if ($shareinforesult->num_rows > 0) {
                    while ($srow = $shareinforesult->fetch_assoc()) {
                        $filelist[$row['fileid']]['shareinfojson'] = $srow['shareinfo'];
                    }
                }
            }
        }
        var_dump($filelist);
    }
?>
