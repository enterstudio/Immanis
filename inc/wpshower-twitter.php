<?php
class wpShowerTwitter {
	public static function getFeed($username, $limit = 3) {
		$results = array();

		$html = file_get_contents('https://twitter.com/'.$username);
		if ($html === false) return $results;

		$preg = preg_match('/<ol class="stream-items js-navigable-stream" id="stream-items-id">(.*?)<\/ol>/s', $html, $matches);
		if (!$preg) return $results;

		$preg = preg_match_all(
			'/<li class="js-stream-item(.*?)data-time="(.*?)"(.*?)class="js-tweet-text tweet-text">(.*?)<\/p>(.*?)<\/li>/s',
			$matches[1],
			$matches_all
		);
		if (!$preg) return $results;

		$count = 0;
		foreach ($matches_all[2] as $i => $time) {
			$results[] = array(
				'time' => $time,
				'text' => str_replace('href="/', 'href="https://twitter.com/', $matches_all[4][$i])
			);
			$count++;
			if ($count == $limit) return $results;
		}

		return $results;
	}
}
