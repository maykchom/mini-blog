<?php
    if (count($errors)>0) {
        echo "<div class='error text-center mx-5 bg-danger p-3 text-white'>";
        foreach ($errors as $error) {
            echo "<i class='fas fa-exclamation-circle'></i> ".$error;
        }
        echo"</div>";
    }
?>