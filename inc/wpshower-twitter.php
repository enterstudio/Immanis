<?php
class wpShowerTwitter {
	public static function getFeed($username, $limit = 3) {
		$results = array();

		$remote = wp_remote_get('https://twitter.com/'.$username);
		if ($remote instanceof WP_Error || $remote['response']['code'] != 200) return $results;

		$preg = preg_match_all(
			'/<li class="js-stream-item(.*?)data-time="(.*?)"(.*?)class="js-tweet-text tweet-text">(.*?)<\/p>(.*?)<\/li>/s',
			$remote['body'],
			$matches
		);
		if (!$preg) return $results;

		$count = 0;
		foreach ($matches[2] as $i => $time) {
			$results[] = array(
				'time' => $time,
				'text' => str_replace('href="/', 'href="https://twitter.com/', $matches[4][$i])
			);
			$count++;
			if ($count == $limit) return $results;
		}

		return $results;
	}
}
