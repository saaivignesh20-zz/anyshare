<?php
    if(isset($_GET['in']) && !empty($_GET['in'])) {
        echo sha1($_GET['in']);
    } else {
        echo "no args";
    }
?>
