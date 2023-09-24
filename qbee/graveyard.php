<?php
include('_/qbee.inc.php');
$subtitle = 'Graveyard';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>
<p>The graveyard consists of old patches from the <a href="/quilt">main quilt</a>, including patches from retired bees,
retired patches, as well as those of my own.</p>';

gallery(2);

include('_/qbee.footer.php');
