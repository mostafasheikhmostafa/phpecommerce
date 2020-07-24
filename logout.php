<?php

session_start(); // start the Session

session_unset(); // Unset the data

session_destroy(); // Destroy the session

header('Location: index.php');

exit();
