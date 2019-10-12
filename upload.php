<?php
    require('core.php');
    $u = User::withToken($_COOKIE['anyshareAuth']);
    $u->doAuthenticate();

    if ($u->status == AuthStatus::AUTH_OK) {
        if(isset($_POST['submit'])){

            // Count total files
            $countfiles = count($_FILES['files']['name']);

            // Looping all files
            for($i=0; $i<$countfiles; $i++){
                $filename = $_FILES['files']['name'][$i];

                // Upload file
                if(!move_uploaded_file($_FILES['files']['tmp_name'][$i],'D:\\myfiles\\'.$filename)) {
                    echo "failed:" . $_FILES['files']['name'][$i];
                } else {
                    // create fileid:
                    $newfileid = sha1($_FILES['files']['name'][$i]);
                    $conn = new mysqli(dbhost, dbun, dbpw, db);
                    if ($conn->connect_error) {
                        echo "failed:" . $_FILES['files']['name'][$i];
                    } else {
                        // check if file exists
                        $checkstmt = $conn->prepare("SELECT * FROM files WHERE fileid = ?");
                        $checkstmt->bind_param("s", $newfileid);
                        $checkstmt->execute();
                        $checkresult = $checkstmt->get_result();
                        $filename = $_FILES['files']['name'][$i];
                        $filesize = $_FILES['files']['size'][$i];
                        $date = date("Y-m-d h:i:s");
                        if ($checkresult->num_rows > 0) {
                            echo "exists:" . $_FILES['files']['name'][$i];
                        } else {
                            $insertstmt = $conn->prepare("INSERT INTO files (fileid, username, filename, size, datemodified, cluster, access) VALUES (?, ?, ?, ?, ?, 'c1', 'private');");
                            $insertstmt->bind_param("sssis", $newfileid, $u->username, $filename, $filesize, $date);
                            $insertstmt->execute();
                            //var_dump($conn->error_list);
                            echo "success:" . $_FILES['files']['name'][$i] . ";";
                        }
                    }
                }

            }
        } else {
            echo "no args";
        }
    } else {
        echo "authfail";
    }
?>
