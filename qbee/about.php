<?php
require_once '_/inc.php';
require_once '_/qbee.inc.php';
$subtitle = 'About';
require_once '_/qbee.header.php';

echo '<h1>' . $subtitle . '</h1>
<div class="about"></div>
<p class="center">My name is Tiffany and I like pixeling.</p>';
gallery(3);

echo '<section>
	<h1>Previous memberships</h1>
	<p>Pixels from the first two times I was in this club: in 2004 under alias Ami and in 2007.</p>';
	gallery(33);
	gallery(4);
	gallery(5);
echo '</section>';

require_once '_/qbee.footer.php';
