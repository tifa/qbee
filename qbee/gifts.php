<?php
include('_/qbee.inc.php');
$subtitle = 'Gifts';
include('_/qbee.header.php');
echo '<h1>' . $subtitle . '</h1>
<section>
	<h1>Patches received</h1>';
	gallery(20);
	gallery(30);
echo '<section>
<section>
	<h1>Patches made &amp; given</h1>
	<p>For members who have traded patches with me up \'til summer of 2012.</p>';
	gallery(25);
echo '</section>';
include('_/qbee.footer.php');
