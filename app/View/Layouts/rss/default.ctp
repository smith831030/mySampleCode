<?php
if (!isset($channel)):
	$channel = array();
endif;
if (!isset($channel['title'])):
	//$channel['title'] = $title_for_layout;
	$channel['title'] = 'DH\'s BlackApple';
endif;

echo $this->Rss->document(
	$this->Rss->channel(
		array(), $channel, $this->fetch('content')
	)
);
?>
