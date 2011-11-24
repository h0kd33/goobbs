<?php

if(!isset($template))
{
	exit;
}

if($template === 'main')
{
	$out['content'] .= '<div id="jump"><ul>
	<li>' .$lang['count']. ' : ' .count(array_merge(listEntry('topic'), listEntry('reply'))). '</li>';
	foreach(listEntry('forum') as $forum)
	{
		$forumEntry = readEntry('forum', $forum);
		$out['content'] .= '<li><a href="view.php?forum=' .$forum. '">' .$forumEntry['name']. '</a></li>';
	}
	$out['content'] .= '</ul></div>';
}

require 'theme/' $config['theme']. '/' .$template. '.tpl.php';

?>
