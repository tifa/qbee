<?php
include('_/qbee.inc.php');
$subtitle = 'Quilt';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>
<p>Patches I traded with other members. <a href="/trade" id="test">Trade with me?</a>';

gallery(1);

include('_/qbee.footer.php');
