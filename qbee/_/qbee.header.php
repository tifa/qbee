<?php ob_start('ob_gzhandler'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<title><?php
			if (!empty($subtitle)) {
				echo $subtitle . ' - ';
			}
			echo 'Quilt 163';
		?></title>

		<meta name="msvalidate.01" content="08F9946B5D26A061056E1F208D609BDE" />
		<meta name="author" content="Tiffany Huang" />
		<meta name="description" content="Tiffany Huang's Quilting Bee quilt" />
		<meta name="pinterest" content="nopin" />
		<?php
		$keywords_1 = array(
			'tiffany huang',
			'the quilting bee quilt',
			'the quilting bee',
			'theqbee',
			'pixels',
			'pixel',
			'art',
		);
		$keywords_2 = array();
		foreach($keywords_1 as $keyword) {
			$split = explode(' ',$keyword);
			if (count($split) > 1) {
				foreach($split as $new) {
					$keywords_2[] = $new;
				}
			}
		}
		$keywords = array_filter(array_unique(array_merge($keywords_1,$keywords_2)));
		ksort($keywords);
		if (!empty($keywords)) {
			echo '<meta name="keywords" content="'.implode(',',$keywords).'" />';
		}
		?>

		<link rel="alternate" type="application/rss+xml" href="/" title="" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="home" title="Home" href="/" />
		<?php
		$fonts = array(
			"Open Sans:400italic,700italic,400,700", // general
			"Raleway:600,500", // h1
			'Alegreya Sans SC:300', // copyright text
		);
		if (count($fonts) > 0) {
			echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.str_replace(' ','+',implode('|',$fonts)).'" />';
		}
		echo '<!--[if IE]>';
			foreach($fonts as $font) {
				echo "<link href=\"https://fonts.googleapis.com/css?family=".str_replace(' ','+',$font)."\" rel=\"stylesheet\" type=\"text/css\" />\n";
			}
		echo '<![endif]-->';
		?>
		<link rel="stylesheet" href="/static/fontello.css">
		<link rel="stylesheet" href="/static/qbee.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="/static/escape.js"></script>
		<script src="/static/html5.js"></script>
		<!--[if IE lt 9]>
			<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div>
			<div id="top">
				<nav>
					<ul>
						<li class="home"><a href="/"><span>Home</span></a></li>
						<li class="about"><a href="/about"><span>About</span></a></li>
						<li class="quilt"><a href="/quilt"><span>Quilt</span></a></li>
						<li class="trade"><a href="/trade"><span>Trade</span></a></li>
						<li class="log"><a href="/log"><span>Log</span></a></li>
						<li class="activities"><a href="/activities"><span>Activities</span></a></li>
						<li class="special"><a href="/special"><span>Special</span></a></li>
						<li class="awards"><a href="/awards"><span>Awards</span></a></li>
						<li class="gifts"><a href="/gifts"><span>Gifts</span></a></li>
						<li class="thanks"><a href="/thanks"><span>Thanks</span></a></li>
						<li class="colonies"><a href="/colonies"><span>Colonies</span></a></li>
						<li class="donations"><a href="/donations"><span>Donations</span></a></li>
						<li class="graveyard"><a href="/graveyard"><span>Graveyard</span></a></li>
					</ul>
				</nav>
			</div>
			<article>
