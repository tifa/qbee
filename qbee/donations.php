<?php
include('_/qbee.inc.php');
$subtitle = 'Donations';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>
<p>My donations to the q*bee community.</p>
<section>
	<h1>Free*bee: Interest Patches</h1>';
	gallery(8);
echo '</section>
<section>
	<h1>Bee*zaar: Accessorize It</h1>';
	gallery(9);
echo '</section>
<section>
	<h1>Bee*zaar: Bee Cosplay</h1>';
	gallery(10);
echo '</section>
<section>
	<h1>Bee*zaar: The Beauty Parlour</h1>';
	gallery(11);
echo '</section>';

include('_/qbee.footer.php');
