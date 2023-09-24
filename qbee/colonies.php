<?php
include('_/qbee.inc.php');
$subtitle = 'Colonies';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>
<p>Activity in the colones I joined.</p>
<section>
	<h1>Pixel*Lots: Little People</h1>';
	gallery(26);
echo '</section>
<section>
	<h1>Bee*Chefs: Recipe Challenge</h1>';
	gallery(27);
echo '</section>';

include('_/qbee.footer.php');
