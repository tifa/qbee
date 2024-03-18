<?php

/* ---------------------------- */
/* --------- Settings --------- */
/* ---------------------------- */

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
spl_autoload_register(function($class) {
	require_once 'class/' . $class . '.php';
});

/* --------------------------------- */
/* --------- MySQL connect --------- */
/* --------------------------------- */

if (!$mysqli = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DB'))) {
	error(1, $mysqli->error);
} elseif (!$mysqli->set_charset('utf8')) {
    error(2, $mysqli->error);
}

/* ----------------------------- */
/* --------- Constants --------- */
/* ----------------------------- */

/* timezone */
$this_timezone = null;
if (!$result = $mysqli->query(<<<TAG
    SELECT timezone FROM `qbee`.`settings` LIMIT 1
TAG
)) {
    error(56, $mysqli->error);
} else {
    while ($row = $result->fetch_assoc()) {
        $this_timezone = $row['timezone'];
    }
    $result->close();
}
define('TIMEZONE', $this_timezone);

/* ------------------------------------ */
/* ---------- Error reporting --------- */
/* ------------------------------------ */

function error($code, $desc)
{
	global $mysqli;
	if ($stmt = $mysqli->prepare(<<<TAG
		INSERT INTO `qbee`.`errors`
			(code, description, location, time, ip, refer)
		VALUES
			(?, ?, ?, ?, ?, ?)
TAG
    )) {
        $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if ($desc == '') $desc = NULL;
		$date_time_sql = dateTimeSQL();
		$date_ip = getIp();
		if ($stmt->bind_param('isssss', $code, $desc, $url, $date_time_sql, $date_ip, $refer)) {
			return $stmt->execute();
		}
		$stmt->close();
	}
}

/* ------------------------------ */
/* ---------- Functions --------- */
/* ------------------------------ */

function googleFonts($list)
{
    if (!is_array($list)) $list = array();
    $display = '';
    if (count($list) > 0) {
        $display = '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', implode('|', $list)) . '" />';
        $display .= '<!--[if IE]>';
        foreach ($list as $font) {
            $display .= '<link href="http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', $font) . '" rel="stylesheet" type="text/css">' . "\n";
        }
        $display .= '<![endif]-->';
    }
    return $display;
}

function metaKeywords($list)
{
    if (!is_array($list)) $list = array();
    $keywords = array();
    foreach ($list as $keyword) {
        $split = explode(' ', $keyword);
        $keywords = array_merge($keywords, $split);
    }
    ksort(array_filter(array_unique($keywords)));
    return '<meta name="keywords" content="' . implode(',', $keywords) . '" />';
}

/**
 * BB Code to HTML
 * Note: bbComment in blog.inc.php
 */
function bbCode($text) {
    // [url=http://example.com]text[/url]   => <a href="http://example.com">text</a>
    // [b]text[/b]                          => <b>text</b>
    $search = array(
        '[/url]',
        '[b]',
        '[/b]',
        '[i]',
        '[/i]'
    );
    $replace = array(
        '</a>',
        '<b>',
        '</b>',
        '<em>',
        '</em>'
    );
    $text = str_replace($search, $replace, $text);
    $search = array(
        '/\[url=([^\]]*)\]/i'
    );
    $replace = array(
        '<a href="$1">'
    );
    $text = preg_replace($search, $replace, $text);
    return $text;
}

/**
 * Remove BB Code
 */
function unBBCode($text) {
    // [url=http://example.com]text[/url]   => text
    // [b]text[/b]                          => text
    $search = array('[/url]', '[b]', '[/b]', '[i]', '[/i]');
    $replace = array('', '', '', '', '');
    $text = str_replace($search, $replace, $text);
    $search = array(
        '/\[url=[^\]]*\]/i'
    );
    $replace = array(
        ''
    );
    $text = preg_replace($search, $replace, $text);
    return $text;
}

/**
 * UNIX_TIMESTAMP()
 */
function dateTimeSQL()
{
	$datetime = new DateTime('UTC');
	return $datetime->format('Y-m-d H:i:s');
}

/*
 * Read DATETIME from MySQL and convert to Chicago timezone
 */
function dateTimeRead($time)
{
    $datetime = new DateTime($time, new DateTimeZone('UTC'));
    return $datetime->setTimezone(new DateTimeZone('America/Chicago'));
}

/**
 * Text to a web-friendly name
 * e.g. "my dog's toy" -> my-dogs-toy
 */
function slug($var)
{
	$var = preg_replace('/[ \*\@\.\/]/', '-', $var);
	$var = preg_replace(array('/[^ a-z0-9\.\-\@*\/]/i','/(^[-\. ]+|[-\. ]+$)/'), '', $var);
	$var = preg_replace('/--+/', '-', $var);
	$var = strtolower($var);
	$var = str_replace('é', 'e', $var);
	return $var;
}

/**
 * Converts text to HTML with paragraph tags
 */
function textToHTML($var)
{
	$var = trim($var);
	$var = nl2br($var);
	$var = preg_replace('/[\t\r\n\f\v]/','', $var);
	$var = preg_replace('/  /',' ', $var);
	$var = preg_replace('/<br \/><br \/>(<br \/>)+/i','<br /><br />', $var);
	$var = '<p>'.preg_replace('/<br \/><br \/>/i','</p><p>', $var).'</p>';
	return $var;
}

/**
 * How long ago
 * e.g. 10 months, a long time, 1 second
 * @param $start object
 * @param $finish object
 */
function timeAgo($start, $finish = null)
{
	$timezone = $start->getTimezone();
	if (is_object($finish)) {
		$finish->setTimezone($timezone);
	} else {
		$finish = new DateTime($timezone->getName());
	}

	$from = $start->getTimestamp();
	$to = $finish->getTimestamp();

	$difference = $to - $from;

	if ($difference < 60) {
		$interval = 's'; // seconds ago
	} elseif ($difference < 60*60) {
		$interval = 'n'; // minutes ago
	} elseif ($difference < 60*60*24) {
		$interval = 'h'; // hours ago
	} elseif ($difference < 60*60*24*7) {
		$interval = 'd'; // days ago
	} elseif ($difference < 60*60*24*30) {
		$interval = 'ww'; // weeks ago
	} elseif ($difference < 60*60*24*365) {
		$interval = 'm'; // months ago
	} else {
		$interval = 'y'; // years ago
	}

	switch($interval) {
		case 'm':
			// the difference in months
			$months_difference = floor($difference / 60 / 60 / 24 / 29);

			$temp = new DateTime($timezone);
			$temp->setDate($start->format('Y'), $start->format('m') + $months_difference, $finish->format('d'));
			$temp->setTime($start->format('H'), $start->format('i'), $start->format('s'));

			while ($temp < $finish) {
				$months_difference++;
				$temp->setDate($start->format('Y'), $start->format('m') + $months_difference, $finish->format('d'));
			}
			$datediff = $months_difference;
			if ($datediff == 12) $datediff--;
			$res = ($datediff == 1) ? $datediff . ' month' : $datediff . ' months';
		break;
		case 'y':
			$datediff = floor($difference / 60 / 60 / 24 / 365);
			$res = ($datediff == 1) ? $datediff . ' year' : $datediff . ' years';
		break;
		case 'd':
			$datediff = floor($difference / 60 / 60 / 24);
			$res = ($datediff == 1) ? $datediff . ' day' : $datediff . ' days';
		break;
		case 'ww':
			$datediff = floor($difference / 60 / 60 / 24 / 7);
			$res = ($datediff == 1) ? $datediff . ' week' : $datediff . ' weeks';
		break;
		case 'h':
			$datediff = floor($difference / 60 / 60);
			$res = ($datediff == 1) ? $datediff . ' hour' : $datediff . ' hours';
		break;
		case 'n':
			$datediff = floor($difference / 60);
			$res = ($datediff == 1) ? $datediff . ' minute' : $datediff . ' minutes';
		break;
		case 's':
			$datediff = $difference;
			$res = ($datediff == 1) ? $datediff . ' second' : $datediff . ' seconds';
		break;
	}
	return $res;
}


function limitBlurb($text, $length = 250)
{
    if ($length < 5) $length = 5;
	if (strlen($text) > $length) {
		$text = preg_replace('/[^ ]+$/i', '', substr($text, 0, $length));
		if (strlen($text) <= $length-5) {
			$text .= '[...]';
		}
	}
	return $text;
}

/**
 * Check if page is referred from my domains
 * $from = domain.com
 */
function isReferred($from)
{
	$referer = parse_url($_SERVER['HTTP_REFERER']);
	return $from == $referer['host'];
}

/**
 * Check if user is a bot
 */
function isBot()
{
	if (preg_match('/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer|T8Abot|Syntryx|WinHttp|WebBandit|nicebot|bot|crawl|slurp|spider)/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/^[\s]*$/i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	}
	return false;
}

/**
 * Valid email
 */
function isEmail($email)
{
	return preg_match(<<<TAG
/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i
TAG
        , $email);
}

/**
 * Contains email
 */
function hasEmail($text) {
    return preg_match(<<<TAG
/(^| )[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?( |$)/i
TAG
    , $text);
}

/**
 * Valid image extension
 */
function isImageExt($ext, $pixel = false)
{

	if (!$pixel) {
		return $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' && $ext == 'png';
	}

    return $ext == 'gif' || $ext == 'png';
}

/**
 * Valid image type
 */
function isImageType($type, $pixel = false)
{
    if (!$pixel) {
        return $type == 'image/jpeg' || $type == 'image/gif' || $type == 'image/png';
    }

    return $type == 'image/png' || $type == 'image/gif';
}

/**
 * Valid URL
 */
function isURL($url)
{
	return preg_match(<<<TAG
/^(?!.*\.\.)(https?:\/\/[a-z0-9]([a-z0-9\._-]*[a-z0-9])*\.[a-z]{2,6}(\/+[a-z0-9_\.\-&=;@\+$,\?#%]*)*)$/i
TAG
        , $url);
}

/**
 * Contains URL
 */
function hasURL($text) {
    return preg_match(<<<TAG
/(^| )(?!.*\.\.)(https?:\/\/[a-z0-9]([a-z0-9\._-]*[a-z0-9])*\.[a-z]{2,6}(\/+[a-z0-9_\.\-&=;@\+$,\?#%]*)*)( |$)/i
TAG
        , $text);
}

/**
 * Valid integer
 */
function isInt($int, $neg = true)
{
	return ($neg && preg_match('/^-?[0-9]+$/', $int)) || (!$neg && preg_match('/^[0-9]+$/', $int));
}

/**
 * Valid decimal
 */
function isDec($decimal, $neg = true)
{
	return preg_match('/^-?([0-9]+(\.[0-9]*)?|\.[0-9]*)$/', $decimal) && (($neg && $decimal <= 0) || (!$neg && $decimal >= 0));
}

/**
 * Valid price
 */
function isPrice($price, $neg = true)
{
	return preg_match('/^([0-9]+(\.[0-9]{0,2})?|\.[0-9]{0,2})$/', $price) && (($neg && $price <= 0) || (!$neg && $price >= 0));
}

/**
 * Converts an object to an array
 */
function objectToArray($object)
{
	if (is_object($object)) {
		$object = get_object_vars($object);
	}
	if (is_array($object)) {
		return array_map(__FUNCTION__, $object);
	} else {
		return $object;
	}
}

/**
 * Clean $_POST values for form processing
 */
function clean($var)
{
	$var = stripslashes(trim($var));
	$search = array(
		"/  +/",	// multiple spaces
		"/\t\t+/",	// multiple tabs
		"/\r\r+/",	// multiple carriage return
		"/\n\n+/",	// multiple new line
		"/\v\v+/",	// multiple vertical tab
		"/\f\f+/",	// multiple form feed
	);
	$replace = array(
		" ",		// multiple spaces
		"\t",		// multiple tabs
		"\r",		// multiple carriage return
		"\n",		// multiple new line
		"\v",		// multiple vertical tab
		"\f",		// multiple form feed
	);
	return preg_replace($search, $replace, $var);
}

/**
 * Get IP address
 */
function getIP()
{
	$list = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	foreach ($list as $key) {
		if (array_key_exists($key, $_SERVER)) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				$ip = trim($ip);
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
					return $ip;
				}
			}
		}
	}
}

/**
 * Pagination
 * $page = current page
 * $pages = total number of pages
 * $path = path up to the page number
 * $num = number of pages to be shown
 */
function paginate($page, $pages, $path = '', $num = 5)
{
	/*
	<div class="paginate">
		<div>
			<a>&lt; Previous</a>
			<a>1</a>
			<a>2</a>
			<span>3</span>
			<a>4</a>
			<a>5</a>
			<a>Next &gt;</a>
		</div>
	</div>
	*/

	// Number shown of pages shown must be an odd number
	if ($num % 2 == 0) $num++;

	$page = (int)$page;
	$pages = (int)$pages;
	if ($pages <= 1) return false;

	// Say there's 30 pages total...

	$list = array();

	// Add "previous" if necessary
	if ($page > 1) {
		$var = '<a href="' . $path;
		if ($page - 1 != 1) {
			$var .= ($page - 1);
		}
		$var .= '">&lt; Previous</a>';
		$list[0] = $var;
	}

	// Start at...
	if ($page <= ceil($num / 2)) {
		$i = 1;
	} elseif ($pages - ($num - 1) > 0 && $page > $pages - floor($num / 2)) {
		$i = $pages - ($num - 1);
	} else {
		$i = $page-floor($num/2);
	}

	// @param $j = maximum of 5 links allowed
	$j = 0;
	while ($j < $num && $i <= $pages) {
		if ($page == $i) {
			$var = '<span>';
		} else {
			$var = '<a href="' . $path;
				if ($i > 1) {
					$var .= $i;
				}
			$var .= '">';
		}
			$var .= $i;
		$var .= $page == $i ? '</span>' : '</a>';
		$list[$i] = $var;
		$i++;
		$j++;
	}

	// Add next is necessary
	if ($page < $pages) {
		$list[$pages+2] = '<a href="' . $path . ($page + 1) . '">Next &gt;</a>';
	}

	// Add 1 if necessary
	if (!array_key_exists(1, $list)) {
		$list[1] = '<a href="' . $path . '">1</a>';
		// Add ... between 1 and 3+ if necessary
		if (!array_key_exists(2, $list)) {
			$list[2] = '...';
		}
	}

	// Add $pages if necessary
	if (!array_key_exists($pages, $list)) {
		$list[$pages+1] = '<a href="' . $path . $pages . '">' . $pages . '</a>';
		if (!array_key_exists($pages-1, $list)) {
			$list[$pages] = '...';
		}
	}

	ksort($list);

	return '<div class="paginate"><div>' . implode(' ',$list) . '</div></div>';
}

/**
 * List all files and folders of a folder
 * For purging
 */
function listFolderFiles($dir)
{
	$list = array();
	$ffs = scandir($dir);
	foreach ($ffs as $ff) {
		if ($ff != '.' && $ff != '..') {
			$list[] = $dir . '/' . $ff;
			if(is_dir($dir . '/' . $ff)) $list = array_merge(listFolderFiles($dir . '/' . $ff), $list);
		}
	}
	return $list;
}

/**
 * Get extensions
 */
function getExt($var)
{
	return preg_replace('/^.+\.([^\.]+)$/i', '$1', $var);
}


function roundUp($value, $precision = 0)
{
	$value = (int)$value;
	$power = pow(10, $precision);
	return ceil($value * $power) / $power;
}

/**
 * Implodes a {key => value} array
 * Keeps the variables but gets rid of the keys
 */
// Implode for key => values
function implodeVar($separator, $array)
{
	$list = array();
	foreach ($array as $key => $var) {
		$list[] = $var;
	}
	return implode($separator, $list);
}

/**
 * Implodes a {key => value} array
 * Keeps the variables but gets rid of the keys
 */
// Implode for key => values
function implodeKey($separator, $array)
{
	$list = array();
	foreach ($array as $key => $var) {
		$list[] = $key;
	}
	return implode($separator, $list);
}

/**
 * Compresses JavaScript
 */
function compressJS($script)
{
	$packer = new jsPacker($script, 'Normal', true, false);
	return $packer->pack();
}

/**
 * Compresses CSS
 */
function compressCSS($script)
{
	/* remove comments */
	$script = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $script);
	/* remove // comments */
	// $script = preg_replace('/\/\/[^(\n)]*/i','', $script);
	/* remove tabs, spaces, newlines, etc. */
	$script = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $script);
	/* remove space */
	$script = preg_replace('/([a-z0-9\)])\s*{/i','\\1{', $script);
	$script = preg_replace('/:\s*([a-z0-9#"\-\'])/i',':\\1', $script);
	$script = preg_replace('/,\s*/',',', $script);
	$script = preg_replace('/;\s*}/','}', $script);
	$script = preg_replace('/  +/',' ', $script);
	$script = preg_replace('/\s*>\s*/','>', $script);
	return $script;
}

/**
 * Ordinal number in words (up to "100th")
 */
function ordinalWord($num)
{
	if ($num >= 0 && $num <= 100) {
		$ones = $num % 10;
		$tens = (($num-$ones)/10);
		$bank_1 = array(
			'',
			'first',
			'second',
			'third',
			'fourth',
			'fifth',
			'sixth',
			'seventh',
			'eighth',
			'ninth',
		);
		$bank_2 = array(
			'zeroth',
			'tenth',
			'twentieth',
			'thirtieth',
			'fortieth',
			'fiftieth',
			'sixtieth',
			'seventieth',
			'eightieth',
			'ninetieth',
			'one-hundredth'
		);
		$bank_3 = array(
			'',
			'eleventh',
			'twelfth',
			'thirteenth',
			'fourteenth',
			'fifteenth',
			'sixteenth',
			'seventeenth',
			'eighteenth',
			'nineteenth'
		);
		if (is_float($num)) {
			return $num > 0 ? numWord((int)$num).' and one-half' : 'one-half';
		}
		// Tens - 0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100
		if ($ones == 0) {
			return $bank_2[$tens];
		}
		// Teens - 11, 12, 13, 14, 15, 16, 17, 18, 19
		if ($num >= 11 && $num <= 19) {
			return $bank_3[$ones];
		}
		// 1, 2, 3, 4, 5, 6, 7, 8, 9
		if ($num <= 9) {
			return $bank_1[$ones];
		}
		// Something-something
		return numWord($tens*10).'-'.$bank_1[$ones];
	} else {
		return false;
	}
}

/**
 * Create a bit.ly URL
 */
function bitLyShort($url)
{
    $api_user = 'tiffanyhuang';
    $api_key = 'R_5ea506030151ab571849197904ac33db';
	$short = '';
	$i = 0;
	// try a maximum of ten times
    for ($i = 0; $i < 10; $i++) {
		$short = cURL('http://api.bitly.com/v3/shorten?login=' . $api_user . '&apiKey=' . $api_key . '&longUrl=' . urlencode($url) . '&format=txt');
        if (isURL($short)) return trim($short);
	}
	return false;
}

/**
 * Get URL's contents
 */
function cURL($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

/**
 * Save image to file
 */
function saveImage($url, $to)
{
	$picture = cURL($url);
	$fh = fopen($to, 'wb');
	$write = fwrite($fh, $picture);
	fclose($fh);
	if ($write) {
		return true;
	}
	return false;
}

/**
 * Random string
 */
function random($length = 9)
{
	$bank_length = strlen($bank = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
	$var = '';
	while (strlen($var) < $length) {
		$var .= $bank[rand(0, $bank_length-1)];
	}
	return $var;
}

/**
 * Array to referenced
 */
function makeReferenced(&$arr) {
	$refs = array();
	foreach($arr as $key => $value) {
		$refs[$key] = &$arr[$key];
	}
	return $refs;
}

/**
 * Text to HTML number/names (for emails, etc)
 */
function htmlChars($var)
{
	$letter = array(
		'/A/','/B/','/C/','/D/','/E/','/F/','/G/','/H/','/I/','/J/','/K/','/L/','/M/','/N/','/O/','/P/','/Q/','/R/','/S/','/T/','/U/','/V/','/W/','/X/','/Y/','/Z/','/a/','/b/','/c/','/d/','/e/','/f/','/g/','/h/','/i/','/j/','/k/','/l/','/m/','/n/','/o/','/p/','/q/','/r/','/s/','/t/','/u/','/v/','/w/','/x/','/y/','/z/',
		'/@/','/\./','/=/','/!/','/ /','/\?/','/:/','/\*/','/\'/','/\+/'
	);
	$html = array(
		'&#65;','&#66;','&#67;','&#68;','&#69;','&#70;','&#71;','&#72;','&#73;','&#74;','&#75;','&#76;','&#77;','&#78;','&#79;','&#80;','&#81;','&#82;','&#83;','&#84;','&#85;','&#86;','&#87;','&#88;','&#89;','&#90;','&#97;','&#98;','&#99;','&#100;','&#101;','&#102;','&#103;','&#104;','&#105;','&#106;','&#107;','&#108;','&#109;','&#110;','&#111;','&#112;','&#113;','&#114;','&#115;','&#116;','&#117;','&#118;','&#119;','&#120;','&#121;','&#122;',
		'&#64;','&#46;','&#61;','&#33;','&#32;','&#63;','&#58;','&#42;','&#39;','&#43;'
	);
	$var = preg_replace($letter, $html, $var);
	$number = array('/0(?!\d*;)/','/1(?!\d*;)/','/2(?!\d*;)/','/3(?!\d*;)/','/4(?!\d*;)/','/5(?!\d*;)/','/6(?!\d*;)/','/7(?!\d*;)/','/8(?!\d*;)/','/9(?!\d*;)/');
	$html = array('&#48;','&#49;','&#50;','&#51;','&#52;','&#53;','&#54;','&#55;','&#56;','&#57;');
	$var = preg_replace($number, $html, $var);
	return $var = preg_replace($letter, $html, $var);
}

/**
 * Converts number ot word format
 * e.g. "123" to "one hundred and twenty-three"
 */
class numberToWord
{

	public function convert($num)
	{
		list($num, $dec) = explode(".", $num);
		$output = "";
		if ($num[0] == "-") {
			$output = "negative ";
			$num = ltrim($num, "-");
		} elseif ($num[0] == "+") {
			$output = "positive ";
			$num = ltrim($num, "+");
		}
		if ($num[0] == "0") {
			$output .= "zero";
		} else {
			$num = str_pad($num, 36, "0", STR_PAD_LEFT);
			$group = rtrim(chunk_split($num, 3, " "), " ");
			$groups = explode(" ", $group);
			$groups2 = array();
			foreach ($groups as $g) {
				$groups2[] = $this->threeDigit($g[0], $g[1], $g[2]);
			}
			for ($z = 0; $z < count($groups2); $z++) {
				if ($groups2[$z] != "") {
					$output .= $groups2[$z]
						. $this->group(11 - $z)
						. ($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11][0] == '0' ? " and " : ", ");
				}
			}
			$output = rtrim($output, ", ");
		}
		if ($dec > 0) {
			$output .= " point";
			for ($i = 0; $i < strlen($dec); $i++) {
				$output .= " ".$this->digit($dec[$i]);
			}
		}
		return $output;
	}

	private function group($index)
	{
		switch($index) {
			case 11: return " decillion";
			case 10: return " nonillion";
			case 9: return " octillion";
			case 8: return " septillion";
			case 7: return " sextillion";
			case 6: return " quintrillion";
			case 5: return " quadrillion";
			case 4: return " trillion";
			case 3: return " billion";
			case 2: return " million";
			case 1: return " thousand";
			case 0: return "";
		}
	}

	private function threeDigit($dig1, $dig2, $dig3)
	{
		$output = "";
		if ($dig1 == "0" && $dig2 == "0" && $dig3 == "0") return "";
		if ($dig1 != "0") {
			$output .= $this->digit($dig1)." hundred";
			if ($dig2 != "0" || $dig3 != "0") {
				$output .= " and ";
			}
		}
		if ($dig2 != "0") {
			$output .= $this->twoDigit($dig2, $dig3);
		} elseif ($dig3 != "0") {
			$output .= $this->digit($dig3);
		}
		return $output;
	}

	private function twoDigit($dig1, $dig2)
	{
		if ($dig2 == "0") {
			switch($dig1) {
				case "1": return "ten";
				case "2": return "twenty";
				case "3": return "thirty";
				case "4": return "forty";
				case "5": return "fifty";
				case "6": return "sixty";
				case "7": return "seventy";
				case "8": return "eighty";
				case "9": return "ninety";
			}
		} elseif ($dig1 == "1") {
			switch($dig2) {
				case "1": return "eleven";
				case "2": return "twelve";
				case "3": return "thirteen";
				case "4": return "fourteen";
				case "5": return "fifteen";
				case "6": return "sixteen";
				case "7": return "seventeen";
				case "8": return "eighteen";
				case "9": return "nineteen";
			}
		} else {
			$temp = $this->digit($dig2);
			switch($dig1) {
				case "2": return "twenty-$temp";
				case "3": return "thirty-$temp";
				case "4": return "forty-$temp";
				case "5": return "fifty-$temp";
				case "6": return "sixty-$temp";
				case "7": return "seventy-$temp";
				case "8": return "eighty-$temp";
				case "9": return "ninety-$temp";
			}
		}
	}

	private function digit($digit)
	{
		switch($digit) {
			case "0": return "zero";
			case "1": return "one";
			case "2": return "two";
			case "3": return "three";
			case "4": return "four";
			case "5": return "five";
			case "6": return "six";
			case "7": return "seven";
			case "8": return "eight";
			case "9": return "nine";
		}
	}
}

/**
 * Converts HTML to text
 */
function htmlToText($var)
{
	$var = preg_replace('/(^<p>|<\/p>$)/i','', $var);
	$var = preg_replace("/(.*?)<\/p><p>/","$1\n\n", $var);
	return $var = preg_replace("/<br \/>/","\n", $var);
}

/**
 * Converts text to HTML with line-break tags
 */
function textToHTMLBr($var)
{
	$var = trim($var);
	$var = nl2br($var);
	$var = preg_replace('/[\t\r\n\f\v]/','', $var);
	$var = preg_replace('/  /',' ', $var);
	$var = preg_replace('/<br \/><br \/>(<br \/>)+/i','<br /><br />', $var);
	return $var;
}

/**
 * Converts HTML with paragraph tags to HTML to line break tags
 */
function htmlToHTMLBr($var)
{
	$var = preg_replace('/<p>(.*?)<\/p>/i','$1<br /><br />', $var);
	$var = preg_replace('/<br \/><br \/>$/i','', $var);
	return $var;
}

/**
 * Converts HTML with line-break tags to HTML to paragraph tags
 */
function htmlBrToHTML($var)
{
	$var = preg_replace('/<p>(.*?)<\/p>/i','$1<br /><br />', $var);
	$var = preg_replace('/<br \/><br \/>$/i','', $var);
	return $var;
}
