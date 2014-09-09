<?php

//call in the bridge lib
require_once('solr_class.php');

//instatiate solr
$solr = new Solr('http://patrickisgreat.me:8983/solr/privatelounge/');

//test query 
//$query = $solr->search('test', 20);

//print_r($query);


//$solr->commit();

$solr->post_docs();

?>
<html>
<head>
</head>
<title>
</title>
<body>
</body>
</html>