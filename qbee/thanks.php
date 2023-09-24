<?php
include('_/qbee.inc.php');
$subtitle = 'Thank-you patches';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>
<section>
	<h1>Patches received</h1>
	<p>From the quilting bee</p>';
		gallery(7);
	echo '<p>From other members</p>';
		gallery(23);
echo '</section>
<section>
	<h1>Patches made &amp; given</h1>
	<p>For members who have traded patches with me.</p>';
	gallery(28);
echo '</section>';

include('_/qbee.footer.php');
