<?php

function readEntry($type, $file)
{
	return eval('return ' .file_get_contents('data/' .$type. '/' .$file. '.dat.php', false, NULL, 14). ';');
}

function saveEntry($type, $file, $data)
{
	file_put_contents('data/' .$type. '/' .$file. '.dat.php', "<?php exit;?>\n" .var_export($data, true), LOCK_EX);
}

function deleteEntry($type, $file)
{
	unlink('data/' .$type. '/' .$file. '.dat.php');
}

function listEntry($type)
{
	return fdir('data/' .$type);
}

function isValidEntry($type, $file)
{
	return indir($file, 'data/' .$type, '.dat.php');
}

function newEntry()
{
	return date('Y-m-dHis').substr(uniqid(), -5);
}

?>