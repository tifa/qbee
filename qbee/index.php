<?php
include('_/qbee.inc.php');
include('_/qbee.header.php');
?>
<h1>Welcome to my q*bee quilt!</h1>
<p>I'm an official <a href="http://theqbee.net/refer.php?beenum=163">Quilting Bee</a> member! If you are also a member
and would like to trade patches, please <a href="/trade">fill out the trading form</a> or
<a href="<?php echo htmlChars('mailto:qbee@' . getenv('HOSTNAME')); ?>">email me</a> with your patch and bee ID!</p>
<?php
$washed = 0;
if (!$mysqli->select_db('qbee')) {
	error(243, $mysqli->error);
	echo '<p class="error">Unable to load patches</p>';
} elseif (!$result = $mysqli->query("SELECT qbee_wash, qbee_official, qbee_mine, qbee_163, qbee_wash FROM `settings` LIMIT 1")) {
	error(180, $mysqli->error);
	echo '<p class="error">Unable to load patches</p>';
} elseif ($result->num_rows == 0) {
	error(242, 'No settings found');
	$result->close();
	echo '<p class="error">Unable to load patches</p>';
} else {
	while ($row = $result->fetch_assoc()) {
		echo '<p class="center">';
			echo '<a href="http://theqbee.net/refer.php?beenum=163"><img src="/img/patch-official.' . $row['qbee_official'] . '" alt="The Quilting Bee"></a>';
			echo '<img src="/img/patch.' . $row['qbee_mine'] . '" alt="My patch">';
			echo '<img src="/img/patch-163.' . $row['qbee_163'] . '" alt="Bee no. 163">';
		echo '</p>
		<p>For more info on the qbee or how to join, click on the first patch above!</p>';
		$washed = $row['qbee_wash'];
	}
	$result->close();
}

if (!$mysqli->select_db('qbee')) {
	error(244, $mysqli->error);
	echo '<p class="error">Unable to load patches</p>';
} elseif (!$result = $mysqli->query("SELECT * FROM (
		(
			SELECT name, bee_id, url, time, NULL AS content, CASE WHEN gallery_id=1 THEN NULL else 1 END AS retired FROM images WHERE gallery_id IN (1, 2) ORDER BY time DESC LIMIT 1
		) UNION ALL (
			SELECT NULL AS name, NULL AS bee_id, NULL AS url, time, content, NULL AS retired FROM journal ORDER BY time DESC LIMIT 1
		)
	) AS results
	ORDER BY time DESC")) {
	error(181, $mysqli->error);
	echo '<p class="error">Unable to load patches</p>';
} elseif ($result->num_rows > 0) {
	echo '<section>
		<h1>Latest updates</h1>
		<table class="log">';
			$list = array();
			$i = 0;
			while ($row = $result->fetch_assoc()) {
				if ($row['bee_id'] > 0) {
					$temp = 'Traded <a href="/' . ($row['retired'] == 1 ? 'graveyard' : 'quilt') . '">patches</a> with ';
					if ($row['url'] != '') $temp .= '<a href="' . $row['url'] . '">';
					$temp .= $row['name'] . ' #' . $row['bee_id'];
					if ($row['url'] != '') $temp .= '</a>';
					$list[date('j F Y', strtotime($row['time']))][] = $temp;
				} else {
					$list[date('j F Y', strtotime($row['time']))][] = bbJournal($row['content']);
				}
				$i++;
			}
			foreach ($list as $month => $items) {
				echo '<tr>
					<td>' . date('M j', strtotime($month)) . '</td>
					<td>' . implode($items) . '</td>
				</tr>';
			}
			echo '<tr>
				<td></td>
				<td><a href="/log">view all</a></td>
			</tr>
		</table>
	</section>';
}

if ($washed > 0) {
	echo '<p>
		<strong>Quilt washed:</strong> ' . date('F j, Y', strtotime($washed)) . '
	</p>';
}

include('_/qbee.footer.php');
