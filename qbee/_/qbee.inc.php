<?php
require_once 'inc.php';

function bbJournal($text) {
	$text = str_replace('[/url]', '</a>', $text);
	$text = preg_replace('/\[url=([^\]]+)\]/i', '<a href="$1">', $text);
	return $text;
}

function gallery($id) {
	global $mysqli;

	$error_no = 0;
	$error_msg = '';
	if (!$mysqli->select_db('qbee')) {
		error(234, $mysqli->error);
		echo '<p class="error">Unable to load data</p>';
		return;
	}

	// Gallery data
	if (!$stmt = $mysqli->prepare(
		"SELECT
			`galleries`.folder AS gallery_folder,
			`galleries`.clear_url,
			`galleries`.bg,
			`galleries`.ext,
			`galleries`.columns,
			`galleries`.spacing,
			`galleries`.border,
			`galleries`.reverse,
			`categories`.folder AS category_folder
		FROM `galleries`
		LEFT JOIN `categories`
			ON `galleries`.category_id=`categories`.id
		WHERE `galleries`.id=?")) {
		error(175, $mysqli->error);
		echo '<p class="error">Unable to load data</p>';
		return;
	} elseif (!$stmt->bind_param('i', $id)) {
		error(176, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		return;
	} elseif (!$stmt->execute()) {
		error(177, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif (!$stmt->store_result()) {
		error(246, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif ($stmt->num_rows != 1) {
		error(235, 'No gallery #' . $id . ' exists');
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif (!$stmt->bind_result($gallery_folder, $clear_url, $bg, $clear_ext, $columns, $spacing, $border, $reverse, $category_folder)) {
		error(178, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif (!$row = $stmt->fetch()) {
		error(179, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	}
	$stmt->close();

	$list = array();
	// First several patches for gallery id #1
	if ($id == 1) {
		if (!$result = $mysqli->query("SELECT qbee_official, qbee_mine, qbee_163 FROM `qbee`.`settings` LIMIT 1")) {
			error(240, $mysqli->error);
			echo '<p class="error">Unable to load data</p>';
			return;
		} elseif ($result->num_rows == 0) {
			error(241);
			echo '<p class="error">Unable to load data</p>';
			$result->close();
			return;
		}
		while ($row = $result->fetch_assoc()) {
			$list['0.1']['ext'] = 'patch-official.' . $row['qbee_official'];
			$list['0.2']['ext'] = 'patch.' . $row['qbee_mine'];
			$list['0.3']['ext'] = 'patch-163.' . $row['qbee_163'];
		}
		$list['0.1']['name'] = 'The Quilting Bee';
		$list['0.1']['url'] = 'https://theqbee.net/refer.php?beenum=163';
		$list['0.2']['name'] = 'My patch';
		$list['0.2']['url'] = '/';
		$list['0.3']['name'] = 'Bee no. 163';
		$list['0.3']['url'] = '/';
		$result->close();
	}

	// Images
	if (!$stmt = $mysqli->prepare("SELECT id, bee_id, name, url, ext FROM `images` WHERE gallery_id=? ORDER BY id " . ($reverse == 1 ? "DESC" : "ASC"))) {
		error(236, $mysqli->error);
		echo '<p class="error">Unable to load data</p>';
		return;
	} elseif (!$stmt->bind_param('i', $id)) {
		error(237, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif (!$stmt->execute()) {
		error(238, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif (!$stmt->store_result()) {
		error(245, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	} elseif ($stmt->num_rows == 0) {
		echo '<p class="center">None yet</p>';
		$stmt->close();
		return;
	} elseif (!$stmt->bind_result($image_id, $bee_id, $name, $url, $ext)) {
		error(239, $stmt->error);
		echo '<p class="error">Unable to load data</p>';
		$stmt->close();
		return;
	}
	while ($row = $stmt->fetch()) {
		$list[$image_id]['name'] = $name;
		if ($bee_id > 0) $list[$image_id]['name'] .= ' #' . $bee_id;
		$list[$image_id]['url'] = $url;
		$list[$image_id]['ext'] = $ext;
	}
	$stmt->close();
	echo '<div class="gallery';
		if ($spacing == 1) echo ' spacing';
		if ($border == 1) echo ' border';
		if ($bg != '') echo ' bg';
		echo '"';
		if ($bg != '') echo ' style="background:url(\'/img/' . $category_folder . '/' . $gallery_folder . '/bg.' . $bg . '\'); background-size: cover;"';
		echo '>';
		$i = 0;
		foreach ($list as $image_id => $item) {
			$pos = $i % $columns;
			if ($item['url'] != '') {
				echo '<a href="' . $item['url'] . '"';
				if (!preg_match('/^\//i', $item['url'])) echo ' rel="external nofollow"';
				echo '>';
			}
			echo '<img src="/img/';
				if ($image_id < 1) {
					echo $item['ext'];
				} else {
					echo $category_folder . '/' . $gallery_folder . '/' . $image_id . '.' . $item['ext'];
				}
				echo '" alt="' . $item['name'] . '" title="' . $item['name'] . '">';
			if ($item['url'] != '') echo '</a>';
			if ($pos == $columns - 1) echo '<br>';
			$i++;
		}
		// If there's space left and a clear patch exists
		if ($pos != $columns && $clear_ext != '') {
			$filler = '';
			if ($clear_url != '') $filler .= '<a href="' . $clear_url . '">';
			$filler .= '<img src="/img/' . $category_folder . '/' . $gallery_folder . '/0.' . $clear_ext . '" alt>';
			if ($clear_url != '') $filler .= '</a>';
			echo str_repeat($filler, $columns - $pos - 1);
		}
	echo '</div>';
}
