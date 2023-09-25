<?php
include('_/qbee.inc.php');
$subtitle = 'Trade with me!';
include('_/qbee.header.php');

echo '<h1>' . $subtitle . '</h1>';

$show = true;
$submitted = $focus = $success = $error = false;
if (isset($_POST['submit']) && $_POST['submit'] == 'Trade!') {
	$submitted = true;
	$fatal = 'An error occurred and your request could not be processed. Please <a href="' . htmlChars('mailto:' . getenv('EMAIL')) . '">email me</a> if you would still like to trade. Sorry for the inconvenience!';
	if ($_POST['patch_url'] == 'http://') {
		$_POST['patch_url'] = '';
	} elseif ($_POST['patch_url'] != '' && !preg_match('/^http:\/\//i', $_POST['patch_url'])) {
		$_POST['patch_url'] = 'http://' . $_POST['patch_url'];
	}
	$_POST['about'] = clean($_POST['about']);
	$_POST['name'] = clean($_POST['name']);
	$_POST['email'] = clean($_POST['email']);
	if ($_POST['name'] == '') {
		$error = 'What\'s your name?';
		$focus = 'name';
	} elseif (strlen($_POST['name']) > 30) {
		$error = 'Let\'s keep your name to 30 characters; your first name is all that\'s needed!';
		$focus = 'name';
	} elseif ($_POST['email'] == '') {
		$error = 'What\'s your email?';
		$focus = 'email';
	} elseif (strlen($_POST['email']) > 255) {
		$error = 'Please keep your email to 255 characters';
		$focus = 'email';
	} elseif ($_POST['bee_id'] == '') {
		$error = 'What\'s your bee ID?';
		$focus = 'bee_id';
	} elseif ($_POST['bee_id'] == 163) {
		$error = 'That\'s not your bee ID!';
		$focus = 'bee_id';
	} elseif ($_POST['bee_id'] < 1 || $_POST['bee_id'] > 300) {
		$error = 'Invalid bee ID';
		$focus = 'bee_id';
	} elseif (!$mysqli->select_db('qbee')) {
		error(258, $mysqli->error);
		$error = $fatal;
		$show = false;
	} elseif (!$stmt = $mysqli->prepare("SELECT COUNT(id) FROM `images` WHERE gallery_id=1 AND bee_id=? AND name=?")) {
		error(184, $mysqli->error);
		$error = $fatal;
		$show = false;
	} elseif (!$stmt->bind_param('is', $_POST['bee_id'], $_POST['name'])) {
		error(259, $stmt->error);
		$error = $fatal;
		$show = false;
		$stmt->close();
	} elseif (!$stmt->execute()) {
		error(260, $stmt->error);
		$error = $fatal;
		$show = false;
		$stmt->close();
	} elseif (!$stmt->bind_result($count_id)) {
		error(261, $stmt->error);
		$error = $fatal;
		$show = false;
		$stmt->close();
	} elseif ($stmt->fetch() === false) {
		error(262, $stmt->error);
		$error = $fatal;
		$show = false;
		$stmt->close();
	} elseif ($count_id > 0) {
		$error = 'Oops! Looks like we\'ve already traded patches (<a href="/quilt">see quilt</a>). If this is a mistake, please contact me!';
		$show = false;
	} elseif ($_FILES['patch']['name'] == '' && $_POST['patch_url'] == '') {
		$error = 'Please provide a patch (either upload or URL)';
		$focus = 'patch';
	} elseif ($_FILES['patch']['name'] != '' && $_POST['patch_url'] != '') {
		$error = 'You need only to provide one patch (via upload or URL)';
		$focus = 'patch';
	// If uploaded a patch
	} elseif ($_FILES['patch']['name'] != '') {
		$ext = getExt($_FILES['patch']['name']);
		if ($ext != 'png' && $ext != 'gif' && $_FILES['patch']['type'] != 'image/gif' && $_FILES['patch']['type'] != 'image/png') {
			$error = 'Your patch must be in png or gif format';
			$focus = 'patch';
		} else {
			list($width, $height) = getimagesize($_FILES['patch']['tmp_name']);
			if ($width != 40 && $height != 40) {
				$error = 'Your patch dimensions should be 40 by 40 pixels';
				$focus = 'patch';
			} elseif (filesize($_FILES['patch']['tmp_name'] > 1024*100)) {
				$error = 'Your patch cannot exceed 100 KB';
				$focus = 'patch';
			}
		}
	// If provided a patch URL
	} elseif (!isURL($_POST['patch_url']))  {
		$error = 'Your patch URL is invalid';
		$focus = 'patch_url';
	} elseif (strlen($_POST['patch_url']) > 255) {
		$error = 'Please keep your patch URL to 255 characters!';
		$focus = 'patch_url';
	}
	if (!$error) {
		if (strlen($_POST['about']) > 2000) {
			$error = 'Please keep your about blurb to 2,000 characters!';
			$focus = 'about';
		} else {
			$show = false;
			// require_once('/home/suiteane/_/mailer.php');
			$mail = new phpMailer();
			try {
				$mail->ContentType = 'text/plain';
				$mail->IsHTML(false);
				$mail->From = $_POST['email'];
				$mail->FromName = $_POST['name'] . ' #' . $_POST['bee_id'];
				$mail->AddReplyTo($_POST['email'], $_POST['name'] . ' #' . $_POST['bee_id']);
				$mail->AddAddress(getenv('EMAIL'));
				if ($_FILES['patch']['name'] != '') $mail->AddAttachment($_FILES['patch']['tmp_name'], $_POST['bee_id'] . '-' . slug($_POST['name']) . '.' . $ext);
				$mail->Subject = 'The Quilting Bee trade request';
				$msg = $_POST['about'] . "\n";
				if ($_POST['patch_url'] != '') {
					$msg .= "\n";
					$msg .= 'Patch #' . $_POST['bee_id'] . ' - ' . $_POST['patch_url'] . "\n";
				}
				$msg .= "\n";
				$msg .= "-\n";
				$msg .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . "\n";
				if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') $msg .= 'XFF: ' . $_SERVER['HTTP_X_FORWARDED_FOR'] . "\n";
				if (isset($_SERVER['HTTP_VIA']) && $_SERVER['HTTP_VIA'] != '') $msg .= 'Via: ' . $_SERVER['HTTP_VIA'] . "\n";
				if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] != '') $msg .= 'User agent: ' . $_SERVER['HTTP_USER_AGENT'];
				$mail->Body = $msg;
				$mail->Send();
				$success = true;
				$stmt->close();
			} catch (phpmailerException $e) {
				$error = $fatal;
				error(185, $e->errorMessage());
			} catch (Exception $e) {
				$error = $fatal;
				error(186, $e->getMessage());
			}
		}
	}
}

if ($show) {
	echo '<p>If you would like to trade patches with me, fill out the form below. You can alternatively email me at <a href="' . htmlChars('mailto:' . getenv('EMAIL')) . '">' . htmlChars(getenv('EMAIL')) . '</a> with your name, bee ID, and patch. :3</p>';
}

if ($error) {
	echo '<p class="error">' . $error . '</p>';
} elseif ($success) {
	echo '<p class="success">Thanks for trading! I will get back to you as soon as I can. In the meantime, here\'s a thank-you patch for your quilt!</p>';
	if (!$result = $mysqli->query("SELECT qbee_thanks FROM `qbee`.`settings` LIMIT 1")) {
		error(187, $mysqli->error);
	} elseif (!$ext = $result->fetch_row()) {
		error(263, mysqli_error($mysqli));
	} else {
		echo '<img src="/img/patch-thanks.' . $ext[0] . '" alt class="center">';
	}
}

if ($show) {
	echo '<form method="post" enctype="multipart/form-data" class="form">
		<label for="name">
			<span>Name*</span>
			<input type="text" name="name" id="name" value="' . @htmlspecialchars($_POST['name']) . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="off" required';
				if (!$submitted || $focus == 'name') echo ' autofocus';
				echo '>
		</label>
		<label for="email">
			<span>Email*</span>
			<input type="email" name="email" id="email" value="' . @htmlspecialchars($_POST['email']) . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="off" required';
				if ($focus == 'email') echo ' autofocus';
				echo '>
		</label>
		<label for="bee_id">
			<span>Bee ID*</span>
			<input type="number" name="bee_id" id="bee_id" value="' . @htmlspecialchars($_POST['bee_id']) . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="off" min="1" max="300" required';
				if ($focus == 'bee_id') echo ' autofocus';
				echo '>
		</label>
		<label for="patch">
			<span>Patch*</span>
			<input type="file" name="patch" id="patch" accept="image/*"';
				if ($focus == 'patch') echo ' autofocus';
				echo '>
		</label>
			<p class="alone"><strong>OR</strong> enter patch URL</p>
			<input type="url" name="patch_url" id="patch_url" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="off" class="alone" value="' . (@$_POST['patch_url'] == '' ? 'http://' : htmlspecialchars($_POST['patch_url'])) . '"';
				if ($focus == 'patch_url') echo ' autofocus';
				echo '>
		<label for="about">
			<span>About</span>
			<textarea name="about" id="about" spellcheck="off" autocorrect="off" autocapitalize="off" autocomplete="off"';
				if ($focus == 'about') echo ' autofocus';
				echo '>' . @htmlspecialchars($_POST['about']) . '</textarea>
		</label>
		<input type="submit" name="submit" value="Trade!">
	</form>';
}
include('_/qbee.footer.php');
