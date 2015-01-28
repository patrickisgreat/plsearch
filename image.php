<?

$getArray = $_GET;
reset($getArray);
$first_key = key($getArray);

if(is_int($first_key)){
	$lookupNum = $first_key;
}else{
	die('not a valid number');
}
//echo 'let me go get that for you: ' .$lookupNum;


//set up the connection obj
$link = mysql_connect('localhost', 'vb_admin', 'Vb4AmG$');
//$link = mysql_connect('blacqube.net', 'vb_admin', 'Vb4AmG$11');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

// lalalala
$db_selected = mysql_select_db('vbulletin', $link);
if (!$db_selected) {
    die ('Can\'t use vbulletin : ' . mysql_error());
}

$query = 'SELECT at.filedataid , at.attachmentid, fd.filedataid, fd.filedata, fd.extension FROM  attachment  AS at JOIN filedata as fd ON at.filedataid = fd.filedataid WHERE at.attachmentid = ' .$lookupNum .' LIMIT 0,1';

//echo $query;
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
		header("Content-Type: image/jpeg");
		echo $row['filedata'];
		break;
}

?>