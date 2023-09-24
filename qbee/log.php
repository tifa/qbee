<?php
include('_/qbee.inc.php');
$subtitle = 'Log';
include('_/qbee.header.php');

$error_no = 0;
$error_msg = '';
$first_year = 2012;
$latest_year = 2013; // date('Y');

if (!$mysqli->select_db('qbee')) {
	$error_no = 202;
	$error_msg = $mysqli->error;
} elseif (@$_GET['year'] != 0 && (@$_GET['year'] < 2012 || @$_GET['year'] > $latest_year)) {
	$year = false;
	$error_no = 247;
	$error_msg = 'Attempt to access year ' . $_GET['year'] . "\nReferred by: " . $_SERVER['HTTP_REFERER'];
} else {

	$min_year = @$_GET['year'] == '' ? $first_year : $_GET['year'];
	$max_year =	@$_GET['year'] == '' ? $latest_year : $_GET['year'];

	$time_min = new DateTime($min_year . '-01-01');
	$time_min = $time_min->format("Y-m-d H:i:s");
	$time_max = new DateTime($max_year . '-12-31 23:59:59');
	$time_max = $time_max->format("Y-m-d H:i:s");

	$type = '';
	if (@$_GET['type'] == 'trades') {
		$type = 'trades';
		if (!$stmt = $mysqli->prepare("
			SELECT
				name,
				bee_id,
				url,
				time,
				NULL AS content,
				CASE WHEN gallery_id=1 THEN NULL else 1 END AS required
			FROM `images`
			WHERE gallery_id IN (1, 2) AND time>=? AND time<=?
			ORDER BY time DESC
		")) {
			$error_no = 203;
			$error_msg = $mysqli->error;
		} elseif (!$stmt->bind_param('ss', $time_min, $time_max)) {
			$error_no = 204;
			$error_msg = $stmt->error;
			$stmt->close();
		}
	} elseif (@$_GET['type'] == 'journal') {
		$type = 'journal';
		if (!$stmt = $mysqli->prepare("
			SELECT
				NULL AS name,
				NULL AS bee_id,
				NULL AS url,
				time,
				content,
				NULL AS retired
			FROM `journal`
			WHERE time>=? AND time<=?
			ORDER BY time DESC
		")) {
			$error_no = 182;
			$error_msg = $mysqli->error;
		} elseif (!$stmt->bind_param('ss', $time_min, $time_max)) {
			$error_no = 183;
			$error_msg = $stmt->error;
			$stmt->close();
		}
	} elseif (!$stmt = $mysqli->prepare("
		SELECT * FROM (
			(
				SELECT
					name,
					bee_id,
					url,
					time,
					NULL AS content,
					CASE WHEN gallery_id=1 THEN NULL else 1 END AS retired
				FROM `images`
				WHERE gallery_id IN (1, 2) AND time>=? AND time<=?
			) UNION ALL (
				SELECT
					NULL AS name,
					NULL AS bee_id,
					NULL AS url,
					time,
					content,
					NULL AS retired
				FROM `journal`
				WHERE time>=? AND time<=?
			)
		) AS results
		ORDER BY time DESC
	")) {
		$error_no = 248;
		$error_msg = $mysqli->error;
	} elseif (!$stmt->bind_param('ssss', $time_min, $time_max, $time_min, $time_max)) {
		$error_no = 249;
		$error_msg = $stmt->error;
		$stmt->close();
	}
}

if ($min_year == $max_year) $year = $min_year;

echo '<h1>';
	if ($min_year == $max_year) echo $min_year . ' ';
	echo ($max_year != $latest_year ? 'Archives' : 'Log') . '</h1>';

if ($error_no) {
	echo '<p class="error">Unable to load log</p>';
	error($error_no, $error_msg);
} elseif (!$stmt->execute()) {
	echo '<p class="error">Unable to load log</p>';
	error(250, $stmt->error);
	$stmt->close();
} elseif (!$stmt->store_result()) {
	echo '<p class="error">Unable to load log</p>';
	error(251, $stmt->error);
	$stmt->close();
} elseif (!$stmt->bind_result($name, $bee_id, $url, $time, $content, $retired)) {
	echo '<p class="error">Unable to load log</p>';
	error(257, $stmt->error);
	$stmt->close();
} else {
	echo '<ul class="inline">
		<li>';
			if ($type != 'trades' && $type != 'journal') {
				echo '<strong>';
			} else {
				echo '<a href="/log/';
				if (@$year) echo $year . '/';
				echo '">';
			}
			echo 'All';
			echo ($type != 'trades' && $type != 'journal') ? '</strong>' : '</a>';
			echo '</li>
		<li>';
			if ($type == 'trades') {
				echo '<strong>';
			} else {
				echo '<a href="/log/trades/';
				if (@$year) echo $year . '/';
				echo '">';
			}
			echo 'Trades';
			echo $type == 'trades' ? '</strong>' : '</a>';
			echo '</li>
		<li>';
			if ($type == 'journal') {
				echo '<strong>';
			} else {
				echo '<a href="/log/journal/';
				if (@$year) echo $year . '/';
				echo '">';
			}
			echo 'Journal';
			echo $type == 'journal' ? '</strong>' : '</a>';
			echo '</li>
	</ul>';

	if ($stmt->num_rows == 0) {
		echo '<p class="center">No ';
			if ($type == 'trades') {
				echo 'trades';
			} elseif ($type == 'journal') {
				echo 'journal entries';
			} else {
				echo 'updates';
			}
		if (@$year) echo '  for ' . $year .' found</p>';
	} else {
		$list = array();
		$i = 0;
		while ($stmt->fetch()) {
			if ($bee_id > 0) {
				$temp = 'Traded <a href="/' . ($retired == 1 ? 'graveyard' : 'quilt') . '/">patches</a> with ';
				if ($url != '') $temp .= '<a href="' . $url . '">';
				$temp .= $name . ' #' . $bee_id;
				if ($url != '') $temp .= '</a>';
				$list[date('M j', strtotime($time))][$i] = $temp;
			} else {
				$list[date('M j', strtotime($time))][$i] = textToHTMLBr(bbJournal($content));
			}
			$i++;
		}
		echo '<table class="log">';
			foreach ($list as $time => $item) {
				echo '<tr>
					<td>' . $time . '</td>
					<td>' . implode('<br>', $item) . '</td>
				</tr>';
			}
		echo '</table>';
	}
	$stmt->close();

	if (!$stmt = $mysqli->prepare("
		SELECT DISTINCT year FROM (
			(
				SELECT
					DISTINCT YEAR(FROM_UNIXTIME(time)) AS year
				FROM `journal`
			) UNION ALL (
				SELECT
					DISTINCT YEAR(FROM_UNIXTIME(time)) AS year
				FROM `images`
				WHERE gallery_id IN (1, 2)
			)
		) AS results
		WHERE year<>?
		ORDER BY year DESC
	")) {
		error(252, $mysqli->error);
		echo '<section><h1>Archives</h1><p class="error">Unable to load archives</p></section>';
	} elseif (!$stmt->bind_param('i', $year)) {
		error(253, $stmt->error);
		echo '<section><h1>Archives</h1><p class="error">Unable to load archives</p></section>';
		$stmt->close();
	} elseif (!$stmt->execute()) {
		error(254, $stmt->error);
		echo '<section><h1>Archives</h1><p class="error">Unable to load archives</p></section>';
		$stmt->close();
	} elseif (!$stmt->store_result()) {
		error(255, $stmt->error);
		echo '<section><h1>Archives</h1><p class="error">Unable to load archives</p></section>';
		$stmt->close();
	} elseif (!$stmt->bind_result($year)) {
		error(256, $stmt->error);
	} elseif ($stmt->num_rows > 0) {
		echo '<section>
			<h1>Archives</h1>
			<ul class="list">';
				$archives = array();
				while ($stmt->fetch()) {
					$archives[] = $year;
				}
				if (!in_array($latest_year, $archives) && $year != $latest_year) {
					$archives = array_merge(array($latest_year), $archives);
				}
				foreach ($archives as $year) {
					echo '<li><a href="/log';
					if ($type) echo $type . '/';
					echo $year . '/">' . $year . '</a></li>';
				}
			echo '</ul>
		</section>';
	}
}

include('_/qbee.footer.php');
