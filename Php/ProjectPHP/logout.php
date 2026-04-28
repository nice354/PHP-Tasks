<?php
require_once 'functions.php';

startSecureSession();

destroyUserSession();

header('Location: index.php');
exit;
