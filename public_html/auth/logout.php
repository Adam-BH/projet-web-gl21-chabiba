<?php
session_start();
session_unset();
session_destroy();
header('Location: /projet-web-gl21-chabiba/public_html/index.php');
exit;
