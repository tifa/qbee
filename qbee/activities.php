<?php

$current = true;					// currently participating?
$current_month = 12;					// month of current event
$current_single_capital = 'Surfboard';		// singular of item (capitalized)
$current_single = 'surfboard';				// singular of item (lowercase)
$current_plural = 'surfboards';				// plural of item (lowercase)
$current_image = '/img/events//2013-spring-flowers/347.png'; // image url of item

$ask = ($current_month == date('n') && $current) ? '<p>Trade <a href="/activities/trade/">' . $current_plural . '</a>?</p>' : NULL;

$activities_list = array(
	2013 => array(
		array(5, 'Spring Flowers', 'spring-flowers', 'png'),
		array(3, 'Egg Hunt', 'egg-hunt', 'png'),
	),
	2012 => array(
		array(12, 'Advent Calendar', 'advent-calendar', 'gif'),
		array(6, 'Planets', 'planets', 'gif'),
		array(5, 'Spring Flowers', 'spring-flowers', 'gif'),
		array(4, 'Kite Flying', 'kite-flying', 'png'),
		array(3, 'Disco Party', 'disco-party', 'gif'),
		array(2, 'Lucky Dip', 'lucky-dip', 'gif'),
		array(1, 'Tasty Cakes', 'tasty-cakes', 'gif'),
	),
);

require_once '_/inc.php';
require_once '_/qbee.inc.php';

if (@$_GET['year'] == 2013 && $_GET['activity'] == 'egg-hunt') {
	$subtitle = 'Activity: Egg Hunt (March 2013)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>';
	gallery(34);
} elseif (@$_GET['year'] == 2013 && $_GET['activity'] == 'spring-flowers') {
	$subtitle = 'Activity: Spring Flowers (May 2013)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>';
	gallery(31);
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'advent-calendar') {
	$subtitle = 'Activity: Advent Calendar (December 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>
	<p>Couldn\'t collect all pixels. :(</p>
	<p class="center">"<em>Jingle bells! Jingle bells!</em>"</p>';
	gallery(29);
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'planets') {
	$subtitle = 'Activity: Planets (June 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>
	<p class="center">"<em>To infinity and BEYOND! Honey went on a space journey back in 2010 and discovered many incredible things in the bee-universe! Now she\'s on a special mission to find life on other planets, but she doesn\'t know where to start!</em>"</p>';
	gallery(24);
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'spring-flowers') {
	$subtitle = 'Activity: Spring Flowers (May 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>';
	gallery(21);
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'kite-flying') {
	$subtitle = 'Activity: Kite Flying (April 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>
	<p class="center">"<em>Spring is around the bend for the hive and what a better way to enjoy the lovely weather than to fill the blue sky with lots of beautiful flying kites!</em>"</p>';
	gallery(18);
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'disco-party') {
	$subtitle = 'Activity: Disco Party (March 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>
	<!-- TODO: Find the base image -->
	<!--<img src="/img/2012-disco-party-base.gif" alt class="right">-->
	<p class="center">"<em>Ah ah ah ah, stayin\' aliiiiiiiiiiiiiiiiive! Honey has been invited to a disco party and really wants to look extra shiny for the event!</em>"</p>
	<!-- TODO: Find the base image -->
	<!--<p>A base (shown right right) was use in creating this pixel.</p>-->';
	gallery(17);
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'lucky-dip') {
	$subtitle = 'Activity: Lucky Dip (February 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle .'</h1>
	<img src="/img/2012-lucky-dip-record.png" alt="my progress" class="right">
	<p>For every pixel we made, we\'d receive either a candy heart or another member\'s pixel. It took me 96 pixels to collect all ten candy hearts.</p>
	<p class="center">"<em>Honey is wanting to send out some extra love around the hive this year in celebration of all the wonderful new members that have recently joined us! :D</em>"</p>
	<section>
		<h1>Gifts I received from members</h1>';
		gallery(14);
	echo '</section>
	<section>
		<h1>Gifts I made</h1>';
		gallery(13);
	echo '</section>
	<section>
		<h1>Candy hearts I received</h1>';
		gallery(15);
	echo '</section>
	<section>
		<h1>Gifts I collected from members</h1>';
		gallery(16);
	echo '</section>';
} elseif (@$_GET['year'] == 2012 && $_GET['activity'] == 'tasty-cakes') {
	$subtitle = 'Activity: Tasty Cakes (January 2012)';
	require_once '_/qbee.header.php';
	echo '<h1>' . $subtitle . '</h1>
	<p class="center">"<em>What better event to ring in the new year than a delicious round of baking! :D Honey\'s new year\'s resolution is to bake more tasty treats (which is a very important goal to have) that everyone can enjoy all year long!</em>"</p>';
	gallery(12);
} elseif ($current && $current_month == date('n') && $_SERVER['QUERY_STRING'] == 'trade') {
	$subtitle = 'Trade ' . $current_plural;
	require_once '_/qbee.header.php';
	$show = true;
	$submitted = $focus = $error = false;
	if ($_POST['submit'] == 'Trade!') {
		$submitted = true;
		if ($_POST['image_url'] == 'http://') {
			$_POST['image_url'] = false;
		} elseif ($_POST['image_url'] != '' && !preg_match('/^http:\/\//i', $_POST['image_url'])) {
			$_POST['image_url'] = 'http://' . $_POST['image_url'];
		}
		foreach ($_POST as $key => $var) ${'action_' . $key} = ${'display_' . $key} = ${'form_' . $key} = stripslashes(trim(utf8_encode($var)));
		$action_ext = NULL;
		if ($form_bee_id == '') {
			$error = 'Please fill in your bee number';
			$focus = 'bee_id';
		} elseif (!preg_match('/^[0-9]{1,3}$/i', $form_bee_id)) {
			$error = 'That is not a valid bee number';
			$focus = 'bee_id';
		} elseif ($form_bee_id == 163) {
			$error = 'That\'s not your bee number!';
			$focus = 'bee_id';
		} elseif ($form_name == '') {
			$error = 'Please fill in your name';
			$focus = 'name';
		} elseif (strlen($form_name) > 30) {
			$error = 'Please keep your name to 30 characters &ndash; your first name is sufficient!';
			$focus = 'name';
		} elseif ($form_email == '') {
			$error = 'Please fill in your email';
			$focus = 'email';
		} elseif (strlen($form_email) > 255) {
			$error = 'Please provide an email that\'s less than 255 characters long';
			$focus = 'email';
		} elseif (!verifyEmail($form_email)) {
			$error = 'Please provide a valid email';
			$focus = 'email';
		} elseif ($_FILES['image']['name'] == '' && $form_image_url == '') {
			$focus = 'image';
			$error = 'Please upload or provide a link for your ' . $current_single;
		} elseif ($_FILES['image']['name'] != '' && $form_image_url != '') {
			$error = 'You only need to upload <strong>OR</strong> provide a link for your ' . $current_single;
			$focus = 'image';
		} elseif ($_FILES['image']['name'] != '') {
			$action_ext = getExt($_FILES['image']['name']);
			list($action_width, $action_height) = getimagesize($_FILES['image']['tmp_name']);
			if (!verifyExt($action_ext, 1)) {
				$error = 'Your image extension should be png or gif';
				$focus = 'image';
			} elseif ($action_width > 400 || $action_height > 400) {
				$error = 'Your image is too big!';
				$focus = 'image';
			} elseif (!verifyType($_FILES['image']['type'], 1)) {
				$error = 'Your image must be in png or gif format';
				$focus = 'image';
			} elseif (filesize($_FILES['image']['tmp_name']) > 1024*100) {
				$error = 'Your image cannot exceed 100 KB';
				$focus = 'image';
			}
		} elseif (!verifyURL($form_image_url)) {
			$error = 'Your image link is invalid';
			$focus = 'image_url';
		} elseif (strlen($form_image_url) > 255) {
			$error = 'Your image URL cannot exceed 255 characters';
			$focus = 'image_url';
		}
		if (!$error) {
			$show = false;
			$fatal = 'An error occurred and your request could not be processed at this time';
			require_once('/home/suiteane/static/mailer.php');
			$mail = new phpMailer();
			$mail->ContentType = 'text/plain';
			$mail->IsHTML(false);
			$mail->From = $display_email;
			$mail->FromName = $display_name . ' #' . $display_bee_id;
			$mail->AddReplyTo($display_email, $display_name . ' #' . $display_bee_id);
			$mail->AddAddress(getenv('EMAIL'));
			if ($_FILES['image']['name'] != '') $mail->AddAttachment($_FILES['image']['tmp_name'], $display_bee_id . '-' . webName($display_name) . '.' . $action_ext);
			$mail->Subject = 'The Quilting Bee ' . $current_single . ' trade request';
			$msg = $current_single_capital . ' #' . $display_bee_id;
			if ($form_image_url != '') {
				$msg .= "\n\n" . $display_image_url;
			}
			$msg .= "\n";
			$msg .= "-\n";
			$msg .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . "\n";
			if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '') $msg .= 'XFF: ' . $_SERVER['HTTP_X_FORWARDED_FOR'] . "\n";
			if ($_SERVER['HTTP_VIA'] != '') $msg .= 'Via: ' . $_SERVER['HTTP_VIA'] . "\n";
			if ($_SERVER['HTTP_USER_AGENT'] != '') $msg .= 'User agent: ' . $_SERVER['HTTP_USER_AGENT'];
			$mail->Body = $msg;
			if (!$mail->Send()) {
				$error = $fatal;
			} else {
				$success = true;
			}
		}
	}
	echo '<h1>' . $subtitle . '</h1>';
	if ($show) {
		echo '<p>If you have a <em>q*bee approved</em> ' . $current_single . ' and woud like to trade with me, fill out this form or email me with the same information at <a href="' . htmlChars('mailto:' . getenv('EMAIL')) . '">' . htmlChars(getenv('EMAIL')) . '</a>! :D</p>';
	}
	if ($error) {
		echo '<p class="error">' . $error . '</p>';
	} elseif ($success) {
		echo '<p class="success">Thanks for trading! You may go ahead and take this ' . $current_single . ':</p>
		<img src="' . $current_image . '" class="center">';
	}
	if ($show) {
		echo '<form method="post" enctype="multipart/form-data" class="form">
			<label for="bee_id">
				<span>Bee ID</span>
				<input type="number" name="bee_id" id="bee_id" value="' . $display_bee_id . '" required';
					if (!$submitted || $focus == 'bee_id') echo ' autofocus';
					echo '>
			</label>
			<label for="name">
				<span>Name</span>
				<input type="text" name="name" id="name" value="' . $display_name . '" required spellcheck="off" autocomplete="off" autocorrect="off"';
					if ($focus == 'name') echo ' autofocus';
					echo '>
			</label>
			<label for="email">
				<span>Email</span>
				<input type="email" name="email" id="email" value="' . $display_email . '" required spellcheck="off" autocomplete="off" autocorrect="off" autocapitalize="off"';
					if ($focus == 'email') echo ' autofocus';
					echo '>
			</label>
			<label for="image">
				<span>' . $current_single_capital . '</span>
				<input type="file" name="image" id="image" accept="image/*"';
					if ($focus == 'image') echo ' autofocus';
					echo '>
			</label>
				<p class="alone"><strong>OR</strong> enter a ' . $current_single . ' URL</p>
				<input type="text" name="image_url" id="image_url" value="' . ($display_image_url == '' ? 'http://' : $display_image_url) . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="off" class="alone"';
					if ($focus == 'image_url') echo ' autofocus';
					echo '>
			<input type="submit" name="submit" value="Trade!">
		</form>';
	}
} else {
	$subtitle = 'Activities';
	require_once '_/qbee.header.php';

	echo '<h1>' . $subtitle . '</h1>
	<p>The Quilting Bee holds special pixel events every month, or so. More often than not it involves submitting a pixel entry and trading it with other members\' entries.</p>';

	echo '<section>
		<table class="activities">';
			foreach ($activities_list as $year => $activities) {
				echo '<tr>
					<th colspan="3">' . $year . '</th>
				</tr>';
				foreach ($activities as $item) {
					echo '<tr>
						<td><img src="/' . $year . '-' . $item[2] . '.' . $item[3] . '" alt></td>
						<td><a href="/activities/' . $year . '/' . $item[2] . '/">' . $item[1] . '</a></td>
						<td>' . date('F', mktime(0, 0, 0, $item[0], 1, $year)) . '</td>
					</tr>';
				}
			}
		echo '</table>
	</section>';
}

require_once '_/qbee.footer.php';
