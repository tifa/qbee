<?php
include('../../_/css.header.php');
include('../../_/css.reset.php');
?>
body {
	background: #a9a9a9 url('/img/layout/bg-strip.png') repeat-y center;
	font: 10px/18px 'Open Sans', Helvetica, Arial, sans-serif;
	color: #555;
}
body > div:not(.tipsy) {
	width: 398px;
	margin: 0 auto;
	position: relative;
	padding: 20px 0 0;
}
#top {
	width: 398px;
	height: 281px;
	background: url('/img/layout/top.png') no-repeat;
	position: relative;
}
	#top nav {
		position: absolute;
		top: 0;
		left: 0;
		width: 398px;
		height: 281px;
	}
		#top nav ul {
			position: absolute;
			top: 0;
			left: 0;
			width: 398px;
			height: 281px;
		}
		#top nav a {
			width: 100%;
			height: 100%;
			display: block;
		}
		#top nav span {
			display: none;
		}
		#top nav li {
			position: absolute;
			display: block;
			height: 17px;
		}
			#top nav li.home {
				width: 146px;
				height: 27px;
				left: 43px;
				top: 137px;
				transform:rotate(-16deg);
				-ms-transform:rotate(-16deg); /* IE 9 */
				-webkit-transform:rotate(-16deg); /* Safari and Chrome */
			}
			<?php
			$nav = array(
				// array(top, left, width)
				'about' => array(238, 15, 59),
				'quilt' => array(238, 74, 55),
				'trade' => array(238, 129, 61),
				'log' => array(238, 190, 47),
				'activities' => array(238, 237, 79),
				'special' => array(238, 316, 67),
				'awards' => array(255, 15, 57),
				'gifts' => array(255, 72, 45),
				'thanks' => array(255, 117, 56),
				'colonies' => array(255, 173, 64),
				'donations' => array(255, 237, 72),
				'graveyard' => array(255, 309, 74)
			);
			foreach ($nav as $link => $info) {
				echo "#top li." . $link . " {
					top: " . $info[0] . "px;
					left: " . $info[1] . "px;
					width: " . $info[2] . "px;
					background-image: url('');
				}
					#top li." . $link . ":hover {
						background: url('/img/layout/" . $link . ".png') no-repeat;
						cursor: pointer;
					}";
			}
			?>
article {
	overflow: auto;
	padding: 10px 22px 5px 39px;
	width: <?php echo 398 - 17 - 34 - 10; // 337 ?>px;
	background: url('/img/layout/bg.png') repeat-y;
}
	div.about {
		width: 100%;
		height: 150px;
		margin-bottom: 20px;
		background: url('/img/bee-mee.gif') no-repeat center;
		<?php radius(15); ?>
	}
	form.form {
		overflow: auto;
		padding: 5px;
	}
		form.form span {
			display: block;
			width: 70px;
			margin: 0 15px 5px 30px;
			float: left;
		}
		form.form input:not([type='submit']),
		form.form textarea {
			border: 1px solid #ccc;
			width: <?php echo 212 - 8 - 15 - 10; ?>px;
			margin: 0 0 5px;
			font: 10px/18px 'Open Sans', Helvetica, Arial, sans-serif;
			padding: 4px;
			float: left;
			clear: right;
		}
		form.form textarea {
			height: 50px;
			resize: vertical;
		}
		form.form p.alone,
		form.form input.alone,
		form.form input[type='submit'] {
			clear: both;
			margin: 0 0 5px 116px;
		}
		form.form input[type='submit'] {
			width: auto;
			padding: 5px 7px;
			background: #1775ab;
			margin-top: 3px;
			color: #fff;
			font-weight: 600;
			font: 11px Raleway, Helvetica, Arial, sans-serif;
			border: 0;
			text-transform: lowercase;
		}
			form.form input[type='submit']:hover {
				background: #28285f;
				cursor: pointer;
			}
	article > h1 {
		font: 12px/20px Raleway, Helvetica, Arial, sans-serif;
		color: #000;
		font-weight: 600;
		padding-bottom: 5px;
		margin-bottom: 5px;
		text-align: center;
		text-transform: lowercase;
		border-bottom: 1px solid #eee;
	}
	em {
		font-style: italic;
	}
	article a {
		color: #468f4f;
		text-decoration: none;
		font-weight: bold;
	}
		article a:hover {
			color: #255d2c;
			text-decoration: underline;
		}
	p.error,
	p.success {
		padding: 10px;
		margin: 30px 10px;
		padding-left: 40px;
		<?php radius(7); ?>
	}
		p.error {
			background: #ffd7d7;
			color: #990000;
		}
		p.success {
			background: #b7ecc1;
			color: #1f6c2e;
		}
		p.error:before,
		p.success:before {
			position: relative;
			left: -20px;
		}
			p.error:before {
				content: url('/img/alert.gif');
			}
			p.success:before {
				content: url('/img/success.png');
			}
	p.center {
		text-align: center;
	}
	p {
		margin-bottom: 20px;
	}
	img.center {
		margin: 0 auto 20px;
		display: block;
	}
	div.gallery {
		line-height: 1px;
		overflow: auto;
		margin: 0 auto 20px;
		display: table;
	}
		div.gallery.spacing img {
			padding: 0 2px 2px;
		}
			div.gallery.spacing img.end {
				padding: 0 0 2px;
			}
		div.gallery.border img {
			border: 1px solid #ccc;
		}
		div.gallery.bg {
			padding: 20px;
			<?php radius(10); ?>
		}
		div.gallery a img:hover  {
			position: relative;
			top: 1px;
			left: 1px;
		}
	ul.inline {
		text-align: center;
		margin-bottom: 20px;
	}
		ul.inline li {
			display: inline-block;
		}
			ul.inline li+li:before {
				content: url('/img/leaf-2.gif'); /* TODO: missing */
				margin: 0 6px;
			}
	ul.list {
		margin: 0 0 20px 30px;
	}
		ul.list li {
			list-style: url('/img/leaf-1.gif'); /* TODO: missing */
		}
	table.log {
		margin-bottom: 20px;
	}
	table.log td {
		padding: 0 5px;
	}
		table.log tr td:first-child {
			white-space: nowrap;
			font-weight: bold;
		}
	table.activities {
		width: 100%;
	}
		table.activities td,
		table.activities th {
			padding: 0 5px;
		}
		table.activities th {
			font: 12px/20px Raleway, Helvetica, Arial, sans-serif;
			text-align: left;
			font-weight: 600;
		}
		table.activities tr + tr > th {
			padding-top: 20px;
		}
		table.activities tr td:first-child {
			width: 1%;
		}
		table.activities img {
			position: relative;
			top: 4px;
		}
	img.right {
		float: right;
		margin-left: 10px;
	}
	section h1 {
		margin: 30px auto 10px;
		font-weight: bold;
	}
	strong {
		font-weight: bold;
	}
	.tipsy {
		padding: 5px;
		font-size: 10px;
		position: absolute;
		z-index: 100000;
	}
		.tipsy-inner {
			padding: 5px 8px 4px 8px;
			background-color: black;
			color: white;
			max-width: 200px;
			text-align: center;
		}
		.tipsy-inner {
			border-radius: 3px;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
		}
		.tipsy-arrow {
			position: absolute;
			background: url('/img/tipsy.gif') no-repeat top left;
			width: 9px;
			height: 5px;
		}
		.tipsy-n .tipsy-arrow {
			top: 0;
			left: 50%;
			margin-left: -4px;
		}
		.tipsy-nw .tipsy-arrow {
			top: 0;
			left: 10px;
		}
		.tipsy-ne .tipsy-arrow {
			top: 0;
			right: 10px;
		}
		.tipsy-s .tipsy-arrow {
			bottom: 0;
			left: 50%;
			margin-left: -4px;
			background-position: bottom left;
		}
		.tipsy-sw .tipsy-arrow {
			bottom: 0;
			left: 10px;
			background-position: bottom left;
			}
		.tipsy-se .tipsy-arrow {
			bottom: 0;
			right: 10px;
			background-position: bottom left;
		}
		.tipsy-e .tipsy-arrow {
			top: 50%;
			margin-top: -4px;
			right: 0;
			width: 5px;
			height: 9px;
			background-position: top right;
		}
		.tipsy-w .tipsy-arrow {
			top: 50%;
			margin-top: -4px;
			left: 0;
			width: 5px;
			height: 9px;
		}

#bottom {
	width: 398px;
	height: 8px;
	background: url('/img/layout/bottom.png') no-repeat;
	margin-bottom: 5px;
}
footer {
	font: 10px/18px 'Alegreya Sans SC', Helvetica, Arial, sans-serif;
	text-align: center;
	color: #444;
}
	footer a {
		color: #444;
		text-decoration: none;
	}
		footer a:hover {
			text-decoration: underline;
		}
img.stats {
	display: none;
}

<?php include('../../_/css.footer.php');
