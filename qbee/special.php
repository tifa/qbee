<?php
include('_/qbee.inc.php');
$subtitle = 'Special patches';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>
<p>Patches pertaining to me and the q*bee.</p>';

gallery(19);

echo '<p>Issues of the Hive Herald I have read.</p>';

gallery(22);

include('_/qbee.footer.php');
