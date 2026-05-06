<?php
$passwords = ["1234", "5678", "4321", "8765"];

foreach ($passwords as $pass) {
    echo $pass . " => " . password_hash($pass, PASSWORD_DEFAULT) . "\n";
}
