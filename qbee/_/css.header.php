<?php
include('inc.php');
header('Content-type: text/css');

function radius($px, $px_top_right = NULL, $px_bottom_right = NULL, $px_bottom_left = NULL) {
	$radius = $px < 1 ? ($px*100).'%' : $px.'px';
	echo 'border-radius: '.$radius.';';
	echo '-webkit-border-radius: '.$radius.';';
	echo '-moz-border-radius: '.$radius.';';
	if ($px_top_right !== NULL || $px_bottom_right !== NULL || $px_bottom_left !== NULL) {
		$radius = $px < 1 ? ($px*100).'%' : $px.'px';
		echo 'border-top-left-radius: '.$radius.';';
		echo '-webkit-border-top-left-radius: '.$radius.';';
		echo '-moz-border-top-left-radius: '.$radius.';';
		$radius = $px_top_right < 1 ? ($px_top_right*100).'%' : $px_top_right.'px';
		echo 'border-top-right-radius: '.$radius.';';
		echo '-webkit-border-top-right-radius: '.$radius.';';
		echo '-moz-border-top-right-radius: '.$radius.';';
		$radius = $px_bottom_right < 1 ? ($px_bottom_right*100).'%' : $px_bottom_right.'px';
		echo 'border-bottom-right-radius: '.$radius.';';
		echo '-webkit-border-bottom-right-radius: '.$radius.';';
		echo '-moz-border-bottom-right-radius: '.$radius.';';
		$radius = $px_bottom_left < 1 ? ($px_bottom_left*100).'%' : $px_bottom_left.'px';
		echo 'border-bottom-left-radius: '.$radius.';';
		echo '-webkit-border-bottom-left-radius: '.$radius.';';
		echo '-moz-border-bottom-left-radius: '.$radius.';';
	}
}
function opacity($ratio) {
	echo 'filter: alpha(opacity='.($ratio*100).');';
	echo 'opacity: '.$ratio.';';
}

function gradientBG($top, $bottom) {
	echo 'background: #' . $bottom . ';';
	/* Safari 4-5, Chrome 1-9 */
	echo 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#' . $bottom . '), to(#' . $top . '));';
	/* Safari 5.1, Chrome 10+ */
	echo 'background: -webkit-linear-gradient(top, #' . $top . ', #' . $bottom . ');';
	/* Firefox 3.6+ */
	echo 'background: -moz-linear-gradient(top, #' . $top . ', #' . $bottom . ');';
	/* IE 10 */
	echo 'background: -ms-linear-gradient(top, #' . $top . ', #' . $bottom . ');';
	/* Opera 11.10+ */
	echo 'background: -o-linear-gradient(top, #' . $top . ', #' . $bottom . ');';
}

ob_start('compressCSS');
