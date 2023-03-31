<?php

$mysqli = new mysqli("localhost", "root", "", "curso");

if ($mysqli -> connect_errno) {
    echo "Algo salió mal";
}