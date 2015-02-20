<?php

//call in the bridge lib
require_once('solr_class.php');

//instatiate solr
//$solr = new Solr('http://patrickisgreat.me:8983/solr/privatelounge/');
$solr = new Solr('http://patrickisgreat.me:8983/solr/privatelounge/', '162.243.217.180', 'pbennett', 'swacuGaKur2j', 'vbulletin');
$link = $solr->connect();
if (!$link) {
    die('Not connected : ' . mysql_error());
}

echo "<p>Running Query..</p><br />";
// get all the fields

$lastpostidquery = mysql_query('SELECT MAX(threadid) FROM `vbulletin`.`searchxml`');

$lastpostid = mysql_fetch_row($lastpostidquery);

if ($lastpostid[0] == null) {
	echo "it did set it";
	$lastpostid[0] = 1;
}

echo $lastpostid[0];
echo "<br />";
//incremental query
$incrementalResult = mysql_query('
	SELECT
		p.postid, 
		p.threadid, 
		p.parentid, 
		p.username, 
		p.title, 
		p.dateline, 
		p.pagetext, 
		p.allowsmilie,
		p.visible,
		pc.cat_id, 
		
		GROUP_CONCAT(DISTINCT pc.cat_id) as cat_ids,
		GROUP_CONCAT(DISTINCT pe.post_type) as post_type, 
		GROUP_CONCAT(DISTINCT pe.element_type) as element_type, 
		GROUP_CONCAT(DISTINCT pe.element) as element, 
		GROUP_CONCAT(DISTINCT pr.region_id) as region_id,

		th.threadid,
		th.forumid,
		th.title,
		th.lastpost,
		th.lastposter,
		th.lastpostid,
		th.similar,

		at.contentid,
		at.attachmentid,
		p.attach
	
	FROM thread AS th
		
	JOIN post as p ON p.threadid = th.threadid
	LEFT JOIN post_element AS pe ON pe.post_id = p.postid
	LEFT JOIN post_category AS pc ON pc.post_id = p.postid
	LEFT JOIN post_region AS pr ON pr.post_id = p.postid
	LEFT JOIN attachment AS at ON at.contentid = p.postid

	WHERE (th.threadid > "'.$lastpostid[0].'")
	GROUP BY p.postid
	ORDER BY th.threadid, p.postid DESC
	LIMIT 0, 2000');

if (!$incrementalResult) {
	die('Invalid query: ' . mysql_error());
}

$number = mysql_num_rows($incrementalResult);
$i = 0;
$data = array();
$thread = array();
$thread['threadid'] = 0;
$thread['pagetext'] = array();
while ($row = mysql_fetch_assoc($incrementalResult)) {
    //exclusion of hidden forums
  	if ($row['forumid'] == '2061' || $row['forumid'] == '2071' || $row['forumid'] == '2111' || $row['forumid'] == '2091' || $row['forumid'] == '2121' || $row['forumid'] == '2131' || $row['forumid'] == '2141' || $row['forumid'] == '2191' || $row['forumid'] == '2201' || $row['forumid'] == '3202' || $row['forumid'] == '2221' || $row['forumid'] == '2231' || 
  		$row['forumid'] == '2231' || $row['forumid'] == '2241' || $row['forumid'] == '2251' || $row['forumid'] == '2261' || $row['forumid'] == '2271' || $row['forumid'] == '2281' || $row['forumid'] == '2291' || 
  		$row['forumid'] == '2301' || $row['forumid'] == '2311' || $row['forumid'] == '2321' || $row['forumid'] == '2331' || $row['forumid'] == '2341' || $row['forumid'] == '2512' || $row['forumid'] == '3991' || 
  		$row['forumid'] == '3612' || $row['forumid'] == '3692' || $row['forumid'] == '3812' || $row['forumid'] == '4091' || $row['forumid'] == '2351' || $row['forumid'] == '2361' || $row['forumid'] == '2371' || 
  		$row['forumid'] == '3271' || $row['forumid'] == '3281' || $row['forumid'] == '1631' || $row['forumid'] == '1641' || $row['forumid'] == '1681' || $row['forumid'] == '1661' || $row['forumid'] == '1671' || 
  		$row['forumid'] == '1691' || $row['forumid'] == '1701' || $row['forumid'] == '1761' || $row['forumid'] == '1551' || $row['forumid'] == '1771' || $row['forumid'] == '1781' || $row['forumid'] == '3192' || 
  		$row['forumid'] == '1911' || $row['forumid'] == '1831' || $row['forumid'] == '1841' || $row['forumid'] == '1851' || $row['forumid'] == '1861' || $row['forumid'] == '1871' || $row['forumid'] == '1881' || 
  		$row['forumid'] == '3881' || $row['forumid'] == '1821' || $row['forumid'] == '1891' || $row['forumid'] == '1901' || $row['forumid'] == '1921' || $row['forumid'] == '1931' || $row['forumid'] == '2252' || 
  		$row['forumid'] == '3632' || $row['forumid'] == '3602' || $row['forumid'] == '3981' || $row['forumid'] == '1791' || $row['forumid'] == '1801' || $row['forumid'] == '1811' || $row['forumid'] == '1961' || 
  		$row['forumid'] == '1971' || $row['forumid'] == '3261' || $row['forumid'] == '3291' || $row['forumid'] == '3451' || $row['forumid'] == '3271' || $row['forumid'] == '3281' || $row['forumid'] == '3541' || $row['forumid'] == '3551') 
	{ 
		echo "continuation";
		continue;
	}
    //if it is the first or the last record store it in the tracking table
    if ($i == 0) {
    	$time = date('Y-m-d H:i:s',time());
    	$postid = $row['threadid'];
    	echo $postid;
    	echo "<br />";
    	echo $row['threadid'];
    	echo "<br />";
    	$insert = "INSERT INTO `vbulletin`.`searchindextracking` (`created`, `firstpostid`, `totalindexed` ) VALUES ('".$time."', '".$postid."', '".$number."')";
    	mysql_query($insert);
    	$id = mysql_insert_id();
    } 

    if (++$i == $number-1) {
    	$time = date('Y-m-d H:i:s',time());
    	$lastpostid = $row['threadid'];
    	echo $lastpostid;
    	$update = "UPDATE `vbulletin`.`searchindextracking` SET lastpostid = '".$lastpostid."' WHERE (id = '".$id."')";
    	mysql_query($update);
    }

    //store everything in a multi dimensional array
   if($thread['threadid']!=$row['threadid']){
   		if($thread['threadid']>0){
   			$solr->add_document($thread);
   		}
   		$threadid = $row['threadid'];
   		unset($thread);
		$thread = array();
		$thread['threadid'] = $row['threadid'];
		$thread['parentid'] = $row['parentid'];
		$thread['title'] = $row['title'];
		$thread['forumid'] = $row['forumid'];
		$thread['pagetext'] = array();
		//set language to english
		switch ($row['forumid']) { 
			case '1':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '10':
			case '11':
			case '12':
			case '13':
			case '14':
			case '15':
			case '16':
			case '17':
			case '18':
			case '21':
			case '22':
			case '23':
			case '24':
			case '25':
			case '26':
			case '27':
			case '28':
			case '31':
			case '32':
			case '34':
			case '35':
			case '37':
			case '39':
			case '40':
			case '41':
			case '43':
			case '45':
			case '46':
			case '47':
			case '48':
			case '49':
			case '50':
			case '51':
			case '52':
			case '53':
			case '54':
			case '55':
			case '56':
			case '57':
			case '58':
			case '59':
			case '60':
			case '61':
			case '62':
			case '63':
			case '64':
			case '65':
			case '67':
			case '70':
			case '71':
			case '72':
			case '73':
			case '74':
			case '75':
			case '76':
			case '77':
			case '78':
			case '79':
			case '81':
			case '82':
			case '83':
			case '84':
			case '85':
			case '86':
			case '87':
			case '88':
			case '89':
			case '90':
			case '91':
			case '92':
			case '93':
			case '94':
			case '95':
			case '96':
			case '97':
			case '98':
			case '99':
			case '100':
			case '101':
			case '111':
			case '122':
			case '131':
			case '141':
			case '151':
			case '161':
			case '171':
			case '181':
			case '191':
			case '201':
			case '211':
			case '221':
			case '231':
			case '232':
			case '362':
			case '422':
			case '462':
			case '722':
			case '732':
			case '771':
			case '871':
			case '872':
			case '881':
			case '891':
			case '901':
			case '911':
			case '921':
			case '931':
			case '951':
			case '961':
			case '981':
			case '1001':
			case '1051':
			case '1072':
			case '1082':
			case '1092':
			case '1102':
			case '1272':
			case '1281':
			case '1321':
			case '1331':
			case '1341':
			case '1351':
			case '1361':
			case '1371':
			case '1381':
			case '1391':
			case '1401':
			case '1411':
			case '1421':
			case '1431':
			case '1451':
			case '1461':
			case '1471':
			case '1481':
			case '1491':
			case '1501':
			case '1521':
			case '2381':
			case '2501':
			case '2511':
			case '3141':
			case '3151':
			case '3171':
			case '3181':
			case '3191':
			case '3211':
			case '3212':
			case '3231':
			case '3232':
			case '3241':
			case '3251':
			case '3441':
			case '3471':
			case '3481':
			case '3531':
			case '3541':
			case '3561':
			case '3571':
			case '3572':
			case '3591':
			case '3621':
			case '3622':
			case '3642':
			case '3662':
			case '3701':
			case '3712':
			case '3722':
			case '3741':
			case '3751':
			case '3762':
			case '3772':
			case '3821':
			case '3822':
			case '3831':
			case '3841':
			case '3851':
			case '3861':
			case '3871':
			case '3911':
			case '3921':
			case '3941':
			case '3961':
			case '3971':
			case '4001':
			case '4011':
			case '4031':
			case '4051':
			case '4061':
				$thread['lang'] = 1;
				$thread['frontend'] = 0;
			break;
			//set language to german
			case '242':
			case '252':
			case '262':
			case '272':
			case '282':
			case '292':
			case '302':
			case '312':
			case '322':
			case '332':
			case '342':
			case '352':
			case '372':
			case '382':
			case '392':
			case '402':
			case '432':
			case '442':
			case '452':
			case '472':
			case '482':
			case '492':
			case '502':
			case '512':
			case '522':
			case '532':
			case '542':
			case '552':
			case '562':
			case '572':
			case '582':
			case '592':
			case '602':
			case '612':
			case '622':
			case '632':
			case '642':
			case '712':
			case '742':
			case '751':
			case '761':
			case '772':
			case '792':
			case '802':
			case '812':
			case '822':
			case '832':
			case '842':
			case '852':
			case '861':
			case '862':
			case '941':
			case '991':
			case '1011':
			case '1021':
			case '1031':
			case '1041':
			case '1071':
			case '1112':
			case '1122':
			case '1132':
			case '1142':
			case '1152':
			case '1162':
			case '1172':
			case '1202':
			case '1212':
			case '1222':
			case '1232':
			case '1252':
			case '1262':
			case '1291':
			case '1301':
			case '1311':
			case '1511':
			case '1531':
			case '2391':
			case '2401':
			case '2411':
			case '2431':
			case '2451':
			case '2461':
			case '2471':
			case '2481':
			case '3161':
			case '3491':
			case '3511':
			case '3521':
			case '3551':
			case '3582':
			case '3601':
			case '3652':
			case '3711':
			case '3731':
			case '3782':
			case '3792':
			case '3891':
			case '3901':
			case '4021':
			case '4041':
				$thread['lang'] = 2;
			break;
			
			//else
			default: 
				$thread['lang'] = 1;
			break;
		
		}//switch
	}//if

   	if ($row['dateline'] != null) {
		$date = strtotime("@{$row['dateline']}");
		$new_date = gmDate("Y-m-d\TH:i:s\Z", $date); 
   		$row['dateline'] = $new_date;
   	
   	}

   	$row['element'] = explode(",", $row['element']);
   	$row['post_type'] = explode(",", $row['post_type']);
   	$row['element_type'] = explode(",", $row['element_type']);
   	$row['region_id'] = explode(",", $row['region_id']);
  	
   	//doooooooooooooooooooo... .
   	$thread['pagetext'][] = $row;
 //$solr->add_document($row);
$i++;
}
$solr->add_document($thread);
//$solr->post();
mysql_free_result($incrementalResult);
?>