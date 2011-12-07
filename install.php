<?php

require 'include/flatfile.inc.php';
require 'include/ui.inc.php';

if(!isValidEntry('config', 'config'))
{
	mkdir('data');
	mkdir('data/topic');
	mkdir('data/reply');
	mkdir('data/forum');
	mkdir('data/plugin');
	mkdir('data/config');

	$config['title'] = 'goobbs demo';
	$config['theme'] = 'classic';
	$config['lang'] = 'en';
	$config['admin'] = hide('demo');
	$config['worker'] = array();
	saveEntry('config', 'config', $config);

	session_start();
	$_SESSION['role'] = 'admin';
}

redirect('index.php?forum');

?>
