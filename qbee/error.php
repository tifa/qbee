<?php
require_once '/home/suiteane/_/inc.php';
$errors = array(
	404 => array(
		"Not found",
		"The page you are looking for was moved or doesn't exist"
	),
	401 => array(
		'Unauthorized',
		"You aren't authorized to view your requested page"
	),
	400 => array(
		'Bad request',
		"Your browser sent us a request that we couldn't understand"
	),
	403 => array(
		'Forbidden',
		"You don't have permission to access this page"
	),
	500 => array(
		'Internal server error',
		"Something went wrong with the server &ndash; please check back later"
	)
);

$error = array_key_exists($_SERVER['QUERY_STRING'], $errors) ? (int)$_SERVER['QUERY_STRING'] : 404;

header('HTTP/1.1 ' . $error . ' ' . $errors[$error][0]);

?>
<html lang="en">
	<head>
		<title><?php echo $error . ' ' . $errors[$error][0]; ?> | Stray Toy</title>
		<meta charset="utf-8" />
		<meta name="robots" content="noindex, nofollow">
		<link rel="alternate" type="application/rss+xml" href="/" title="" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="home" title="Home" href="/" />
		<?php
		$fonts = array(
			'Fauna One', // side text & h1
			'Roboto Slab',
		);
		if (count($fonts) > 0) {
			echo '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', implode('|', $fonts)) . '" />
			<!--[if IE]>';
				foreach ($fonts as $font) {
					echo "<link href=\"http://fonts.googleapis.com/css?family=" . str_replace(' ', '+', $font) . "\" rel=\"stylesheet\" type=\"text/css\" />";
				}
			echo '<![endif]-->';
		}

		ob_start();
		require_once ROOT . '/_/css-reset.php';
		$style = ob_get_contents();
		ob_end_clean();

		$style .= "
			body {
				font: 1em 'Fauna One', Arial, Helvetica, sans-serif;
				color: #666;
				line-height: 1.9em;
			}
			img {
				margin: 10px 0 0 30px;
				float: left;
			}
			div {
				float: left;
				margin: 30px;
			}
			h1 {
				font: 2em 'Roboto Slab', Georgia, serif;
				font-weight: bold;
				text-shadow: #ddd;
				color: #000;
			}
			p {
				margin: 20px 0;
			}
			a {
				font-size: 1.3em;
				color: #000;
				text-decoration: none;
			}
				a:hover {
					border-bottom: 1px solid #ddd;
				}
			div {
				min-width: 228px;
				overflow: auto;
			}
		";
		echo '<style>' . compressCSS($style) . '</style>';
		?>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="/static/js/escape.js"></script>
		<script src="/static/js/html5.js"></script>
		<!--[if IE lt 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	</head>
	<body>
		<img src="/img/error.png" alt>
		<div>
			<h1><?php echo $errors[$error][0]; ?></h1>
			<p><?php echo $errors[$error][1]; ?></p>
			<p>Return to <a href="http://<?php echo $_subdomain . getenv('HOSTNAME'); ?>"><?php echo $_subdomain . getenv('HOSTNAME'); ?> &raquo;</a></p>
		</div>
	</body>
</html>
