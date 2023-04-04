<?php

// remove session and redirect to login.php
session_start();
session_unset();
session_destroy();

// remove all cookies
setcookie('user_id', '', time() - 3600);
setcookie('token', '', time() - 3600);
setcookie('token_browser', '', time() - 3600);

header("Location: login.php");
exit();
