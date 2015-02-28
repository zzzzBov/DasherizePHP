<?php
header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html>
<html>
<head>
    <title>Run Tests</title>
</head>
<body>
    <pre><?php

echo htmlentities(shell_exec('phpunit --bootstrap src/Dasherize.php tests/DasherizeTest.php'));

    ?></pre>
</body>
</html>
