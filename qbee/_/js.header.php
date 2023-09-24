<?php
header('Content-type: text/javascript');

require_once 'inc.php';

// Will compress javascript if it's mine
if (@$_mine) ob_start('compressJS');
