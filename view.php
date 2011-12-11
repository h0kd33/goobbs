<?php

$template = 'main';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('topic') && isValidEntry('topic', $_GET['topic']))
{
	require 'include/parser.inc.php';
	require 'include/page.inc.php';
	
	$topicEntry = readEntry('topic', $_GET['topic']);
	$forumEntry = readEntry('forum', $topicEntry['forum']);

	//topic view++
	$topicEntry['view']++;
	saveEntry('topic', $_GET['topic'], $topicEntry);

	$out['subtitle'] = $topicEntry['title'];
	$out['content'] .= '<table>
	<tr class="th"><td colspan="2"><h1><a href="view.php?forum=' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a> » ' .$out['subtitle']. '</h1></td></tr>
	<tr><td class="w2"><p class="user">' .manageTopic($_GET['topic']).$topicEntry['trip']. '</p>
	<p>' .toDate($_GET['topic']). '</p></td>
	<td><p>' .content($topicEntry['content']). '</p>'.
	(!$topicEntry['locked']? '<p><a class="button" href="add.php?reply=' .$_GET['topic']. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
	hook('afterTopic', $_GET['topic']).'</td></tr>
	</table>';
	$total = totalPage($topicEntry['reply']);
	$p = pid($total);
	if($total > 0)
	{
		$out['content'] .= '<table>';
		foreach(viewPage($topicEntry['reply'], $p) as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$out['content'] .= '<tr id="' .$reply. '"><td class="w2"><p class="user">' .manageReply($reply).$replyEntry['trip']. '</p>
			<p>' .toDate($reply). '</p></td>
			<td><p>' .content($replyEntry['content']). '</p>'.
			(!$topicEntry['locked']? '<p><a class="button" href="add.php?reply=' .$_GET['topic']. '&amp;q=' .$reply. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
			hook('afterReply', $reply). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
	$out['content'] .= pageControl($p, $total, 'topic=' .$_GET['topic']).
	'<table>
	<tr class="th"><td>' .$lang['more'].$lang['topic']. '</td>
	<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
	<td class="w2">' .$lang['forum']. '</td></tr>';
	foreach(part('shuffle', listEntry('topic'), 4) as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= '<tr><td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
		<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
		<td><a href="view.php?forum=' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a></td></tr>';
	}
	$out['content'] .= '</table>';
}
else if(isGET('forum') && isValidEntry('forum', $_GET['forum']))
{
	require 'include/page.inc.php';
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $forumEntry['name'];
	$out['content'] .= '<table>
	<tr class="th"><td><h1>' .manageForum($_GET['forum']).$out['subtitle']. '</h1></td></tr>
	<tr><td><p>' .$forumEntry['info']. '</p>
	<p><a class="button" href="add.php?topic=' .$_GET['forum']. '">' .$lang['add'].$lang['topic']. '</a></p>'.
	hook('afterForum', $_GET['forum']).
	'</td></tr>
	</table>';
	$topics = array_merge($forumEntry['pinnedTopic'], array_reverse(array_diff($forumEntry['topic'], $forumEntry['pinnedTopic'])));
	$total = totalPage($topics);
	$p = pid($total);
	if($total > 0)
	{
		$out['content'] .= '<table>
		<tr class="th"><td>' .$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach(viewPage($topics, $p) as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$out['content'] .= '<tr><td>' .manageTopic($topic).(isset($forumEntry['pinnedTopic'][$topic])? '<span class="pinned">' .$lang['pinned']. '</span>':'').($topicEntry['locked']? '<span class="locked">' .$lang['locked']. '</span>':'').$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
			<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
			<td>' .toDate($topic). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
	$out['content'] .= pageControl($p, $total, 'forum=' .$_GET['forum']);
}
else if(isGET('plugin') && function_exists($_GET['plugin']. '_view'))
{
	$misc = $_GET['plugin']. '_view';
	$out['subtitle'] = strtolower($_GET['plugin']);
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>'.
	$misc();
}
else
{
	redirect('index.php?404');
}

require 'footer.php';

?>
