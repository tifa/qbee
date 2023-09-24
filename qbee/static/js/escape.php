<?php
$_mine = true;
require '../../_/js.header.php';
echo 'if(top.location != self.location) {
	top.location = self.location.href
}';
require '../../_/js.footer.php';
