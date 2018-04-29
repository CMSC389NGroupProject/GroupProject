<?php
session_start();
unset($_COOKIE['login']);
header("Location: index.html");
?>