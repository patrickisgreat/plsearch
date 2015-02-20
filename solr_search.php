<?php
//call in the bridge lib
require_once('solr_class.php');

//instatiate solr
$solr = new Solr('http://patrickisgreat.me:8983/solr/privatelounge/', '162.243.217.180', 'pbennett', 'swacuGaKur2j', 'vbulletin');
$link = $solr->connect();
if (!$link) {
    die('Not connected : ' . mysql_error());
}
$solr->post(0);
?>
<html>
<head>
</head>
<title>
</title>
<body>
</body>
</html>