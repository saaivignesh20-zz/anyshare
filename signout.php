<?php
    /* Sign out Script */
    ob_start();
    require('core.php');
    ob_end_clean();

    if (isset($_COOKIE['anyshareAuth'])) {
        $u = User::withToken($_COOKIE['anyshareAuth']);
        if ($u->signOutUser() == AuthStatus::DEAUTH_OK) {
            $result = "ok";
        } else {
            $result = "fail";
        }
    } else {
        header("Location: signin.php");
    }
?>
<!DOCTYPE html>
<html>
    <script>
        function checkResult() {
            var result = '<?php echo $result; ?>'
            if (result == "ok") {
                soflag.value = "true";
            } else {
                soflag.value = "false";
            }
            redirectorForm.submit();
        }
    </script>
    <body onload="checkResult();">
        <form method="POST" action="signin.php" id="redirectorForm">
            <input type="hidden" id="soflag" name="signout" value="" />
    </body>
</html>
