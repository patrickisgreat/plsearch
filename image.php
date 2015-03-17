<?php

$attachmentDIR = '/opt/lampp/vb_attachment';

$getArray = $_GET;
reset($getArray);
$first_key = key($getArray);

if(is_int($first_key)){
	$lookupNum = $first_key;
}else{
	blankImg();
	//die('not a valid number');
}
//echo 'let me go get that for you: ' .$lookupNum;


//set up the connection obj
$link = mysql_connect('localhost', '', '');
if (!$link) {
	blankImg();
    //die('Not connected : ' . mysql_error());
}

// lalalala
$db_selected = mysql_select_db('vbulletin', $link);
if (!$db_selected) {
	blankImg();
    //die ('Can\'t use vbulletin : ' . mysql_error());
}

$query = 'SELECT at.attachmentid , at.contentid, at.userid, at.filedataid, at.state, at.filename, fd.filedataid, fd.extension FROM  attachment  AS at JOIN filedata as fd ON at.filedataid = fd.filedataid WHERE at.attachmentid = ' .$lookupNum .' LIMIT 0,1';
//$query = 'SELECT attachmentid, contentid, userid, filedataid, state, filename FROM  attachment  WHERE attachmentid = ' .$lookupNum .' LIMIT 0,1';

$result = mysql_query($query);

if (!$result) {
    die('Invalid query: ' . mysql_error());
}
if (mysql_num_rows($result) == 0) {
    echo "No rows found, nothing to print so am exiting";
    exit;
}
$row = mysql_fetch_assoc($result);
switch($row['extension']){
	case 'jpg':
		$path = fetch_attachment_path($row['userid'], $row['attachmentid'], $attachmentDIR );
		
		if(file_exists($path) && is_file($path))
		{
			header("Content-Type: image/jpeg");
			readfile($path);
		}else{
			blankImg();
		}

		break;
}
function blankImg(){
	$im = imagecreatetruecolor(1, 1);
	header("Content-Type: image/jpeg");
	imagejpeg($im);
}

function fetch_attachment_path($userid, $attachmentid = 0, $attachmentDIR ='')
{
	$filepath =& $attachmentDIR;
	$path = $filepath . '/' . implode('/', preg_split('//', $userid,  -1, PREG_SPLIT_NO_EMPTY));
	$path .= '/' . $attachmentid . '.attach';

	return $path;
}
?>
