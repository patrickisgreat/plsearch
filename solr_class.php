<?php
	class Solr {
 
		function __construct($url){
			$this->url = $url;
			$this->httppost = TRUE;
		}
 
		
		public function stripBBCode($text_to_search) {
 			$pattern = '|[[\/\!]*?[^\[\]]*?]|si';
 			$replace = '';
 			return preg_replace($pattern, $replace, $text_to_search);
		}

		public function containsTLD($string) {
		  preg_match(
		    "/(AC($|\/)|\.AD($|\/)|\.AE($|\/)|\.AERO($|\/)|\.AF($|\/)|\.AG($|\/)|\.AI($|\/)|\.AL($|\/)|\.AM($|\/)|\.AN($|\/)|\.AO($|\/)|\.AQ($|\/)|\.AR($|\/)|\.ARPA($|\/)|\.AS($|\/)|\.ASIA($|\/)|\.AT($|\/)|\.AU($|\/)|\.AW($|\/)|\.AX($|\/)|\.AZ($|\/)|\.BA($|\/)|\.BB($|\/)|\.BD($|\/)|\.BE($|\/)|\.BF($|\/)|\.BG($|\/)|\.BH($|\/)|\.BI($|\/)|\.BIZ($|\/)|\.BJ($|\/)|\.BM($|\/)|\.BN($|\/)|\.BO($|\/)|\.BR($|\/)|\.BS($|\/)|\.BT($|\/)|\.BV($|\/)|\.BW($|\/)|\.BY($|\/)|\.BZ($|\/)|\.CA($|\/)|\.CAT($|\/)|\.CC($|\/)|\.CD($|\/)|\.CF($|\/)|\.CG($|\/)|\.CH($|\/)|\.CI($|\/)|\.CK($|\/)|\.CL($|\/)|\.CM($|\/)|\.CN($|\/)|\.CO($|\/)|\.COM($|\/)|\.COOP($|\/)|\.CR($|\/)|\.CU($|\/)|\.CV($|\/)|\.CX($|\/)|\.CY($|\/)|\.CZ($|\/)|\.DE($|\/)|\.DJ($|\/)|\.DK($|\/)|\.DM($|\/)|\.DO($|\/)|\.DZ($|\/)|\.EC($|\/)|\.EDU($|\/)|\.EE($|\/)|\.EG($|\/)|\.ER($|\/)|\.ES($|\/)|\.ET($|\/)|\.EU($|\/)|\.FI($|\/)|\.FJ($|\/)|\.FK($|\/)|\.FM($|\/)|\.FO($|\/)|\.FR($|\/)|\.GA($|\/)|\.GB($|\/)|\.GD($|\/)|\.GE($|\/)|\.GF($|\/)|\.GG($|\/)|\.GH($|\/)|\.GI($|\/)|\.GL($|\/)|\.GM($|\/)|\.GN($|\/)|\.GOV($|\/)|\.GP($|\/)|\.GQ($|\/)|\.GR($|\/)|\.GS($|\/)|\.GT($|\/)|\.GU($|\/)|\.GW($|\/)|\.GY($|\/)|\.HK($|\/)|\.HM($|\/)|\.HN($|\/)|\.HR($|\/)|\.HT($|\/)|\.HU($|\/)|\.ID($|\/)|\.IE($|\/)|\.IL($|\/)|\.IM($|\/)|\.IN($|\/)|\.INFO($|\/)|\.INT($|\/)|\.IO($|\/)|\.IQ($|\/)|\.IR($|\/)|\.IS($|\/)|\.IT($|\/)|\.JE($|\/)|\.JM($|\/)|\.JO($|\/)|\.JOBS($|\/)|\.JP($|\/)|\.KE($|\/)|\.KG($|\/)|\.KH($|\/)|\.KI($|\/)|\.KM($|\/)|\.KN($|\/)|\.KP($|\/)|\.KR($|\/)|\.KW($|\/)|\.KY($|\/)|\.KZ($|\/)|\.LA($|\/)|\.LB($|\/)|\.LC($|\/)|\.LI($|\/)|\.LK($|\/)|\.LR($|\/)|\.LS($|\/)|\.LT($|\/)|\.LU($|\/)|\.LV($|\/)|\.LY($|\/)|\.MA($|\/)|\.MC($|\/)|\.MD($|\/)|\.ME($|\/)|\.MG($|\/)|\.MH($|\/)|\.MIL($|\/)|\.MK($|\/)|\.ML($|\/)|\.MM($|\/)|\.MN($|\/)|\.MO($|\/)|\.MOBI($|\/)|\.MP($|\/)|\.MQ($|\/)|\.MR($|\/)|\.MS($|\/)|\.MT($|\/)|\.MU($|\/)|\.MUSEUM($|\/)|\.MV($|\/)|\.MW($|\/)|\.MX($|\/)|\.MY($|\/)|\.MZ($|\/)|\.NA($|\/)|\.NAME($|\/)|\.NC($|\/)|\.NE($|\/)|\.NET($|\/)|\.NF($|\/)|\.NG($|\/)|\.NI($|\/)|\.NL($|\/)|\.NO($|\/)|\.NP($|\/)|\.NR($|\/)|\.NU($|\/)|\.NZ($|\/)|\.OM($|\/)|\.ORG($|\/)|\.PA($|\/)|\.PE($|\/)|\.PF($|\/)|\.PG($|\/)|\.PH($|\/)|\.PK($|\/)|\.PL($|\/)|\.PM($|\/)|\.PN($|\/)|\.PR($|\/)|\.PRO($|\/)|\.PS($|\/)|\.PT($|\/)|\.PW($|\/)|\.PY($|\/)|\.QA($|\/)|\.RE($|\/)|\.RO($|\/)|\.RS($|\/)|\.RU($|\/)|\.RW($|\/)|\.SA($|\/)|\.SB($|\/)|\.SC($|\/)|\.SD($|\/)|\.SE($|\/)|\.SG($|\/)|\.SH($|\/)|\.SI($|\/)|\.SJ($|\/)|\.SK($|\/)|\.SL($|\/)|\.SM($|\/)|\.SN($|\/)|\.SO($|\/)|\.SR($|\/)|\.ST($|\/)|\.SU($|\/)|\.SV($|\/)|\.SY($|\/)|\.SZ($|\/)|\.TC($|\/)|\.TD($|\/)|\.TEL($|\/)|\.TF($|\/)|\.TG($|\/)|\.TH($|\/)|\.TJ($|\/)|\.TK($|\/)|\.TL($|\/)|\.TM($|\/)|\.TN($|\/)|\.TO($|\/)|\.TP($|\/)|\.TR($|\/)|\.TRAVEL($|\/)|\.TT($|\/)|\.TV($|\/)|\.TW($|\/)|\.TZ($|\/)|\.UA($|\/)|\.UG($|\/)|\.UK($|\/)|\.US($|\/)|\.UY($|\/)|\.UZ($|\/)|\.VA($|\/)|\.VC($|\/)|\.VE($|\/)|\.VG($|\/)|\.VI($|\/)|\.VN($|\/)|\.VU($|\/)|\.WF($|\/)|\.WS($|\/)|\.XN--0ZWM56D($|\/)|\.XN--11B5BS3A9AJ6G($|\/)|\.XN--80AKHBYKNJ4F($|\/)|\.XN--9T4B11YI5A($|\/)|\.XN--DEBA0AD($|\/)|\.XN--G6W251D($|\/)|\.XN--HGBK6AJ7F53BBA($|\/)|\.XN--HLCJ6AYA9ESC7A($|\/)|\.XN--JXALPDLP($|\/)|\.XN--KGBECHTV($|\/)|\.XN--ZCKZAH($|\/)|\.YE($|\/)|\.YT($|\/)|\.YU($|\/)|\.ZA($|\/)|\.ZM($|\/)|\.ZW)/i",
		    $string,
		    $M);
		  $has_tld = (count($M) > 0) ? true : false;
		  return $has_tld;
		}

		public function url_cleaner($url) {
		  $U = explode(' ',$url);

		  $W =array();
		  foreach ($U as $k => $u) {
		    if (stristr($u,".")) { //only preg_match if there is a dot    
		      if (self::containsTLD($u) === true) {
		      unset($U[$k]);
		      return self::url_cleaner( implode(' ',$U));
		    	}      
		    }
		  }
		  return implode(' ',$U);
		}

		// Linkify youtube URLs which are not already links.
		function extractYoutubeID($text) {
		    $text = preg_replace('~
		        # Match non-linked youtube URL in the wild. (Rev:20130823)
		        https?://         # Required scheme. Either http or https.
		        (?:[0-9A-Z-]+\.)? # Optional subdomain.
		        (?:               # Group host alternatives.
		          youtu\.be/      # Either youtu.be,
		        | youtube         # or youtube.com or
		          (?:-nocookie)?  # youtube-nocookie.com
		          \.com           # followed by
		          \S*             # Allow anything up to VIDEO_ID,
		          [^\w\s-]       # but char before ID is non-ID char.
		        )                 # End host alternatives.
		        ([\w-]{11})      # $1: VIDEO_ID is exactly 11 chars.
		        (?=[^\w-]|$)     # Assert next char is non-ID or EOS.
		        (?!               # Assert URL is not pre-linked.
		          [?=&+%\w.-]*    # Allow URL (query) remainder.
		          (?:             # Group pre-linked alternatives.
		            [\'"][^<>]*>  # Either inside a start tag,
		          | </a>          # or inside <a> element text contents.
		          )               # End recognized pre-linked alts.
		        )                 # End negative lookahead assertion.
		        [?=&+%\w.-]*        # Consume any URL (query) remainder.
		        ~ix', 
		        '<a href="http://www.youtube.com/watch?v=$1">YouTube link: $1</a>',
		        $text);
		    return $text;
		}


		function arr_to_solr_doc($doc){
			$count = count($doc['pagetext']);
		    $count = $count-1;
			$sending = false;
			$fields = '';
			$pageTextImageURL = false;
			$pageTextImagePath = false;
			$frontEndImagePath = false;

			//print_r($doc);

			$postArray = array();

				// Start XML file, create parent node
				$dom = new DOMDocument("1.0");
				$dom->load('storage.xml');
				$dom->formatOutput = true;
				
				// get the add node from the file
				$base_node = $dom->getElementsByTagName('add')->item(0);
				//$base_node = $dom->createElement("add");
				//$base_node = $dom->appendChild($base_node);
				
				// create doc node
				$node = $dom->createElement("doc");

				//if posted by AMG Info, AMG_Info, AMG Information, AMG_Information	
				//boost it in the index
				$username = $doc['pagetext'][$count]['username'];
				
				if ($username == 'AMG Info' || $username == 'AMG_Info' || $username == 'AMG Information' || $username == 'AMG_Information') {
					$node->setAttribute("boost", "12.5");
				}

			foreach ($doc as $field_name => $value){
				if ($field_name == 'pagetext') continue;

		    	$newnode = $dom->createElement('field');
		    	$newnode->setAttribute("name", $field_name);
		    	$newnode->nodeValue = $doc[$field_name];
		    	$node->appendChild($newnode);
		    	$base_node->appendChild($node);
		    }

		    //vbulletin stores junk data in attachmentid so we need to collect it then verify with the attach field if there is something actually useful ther.
		    $attachmentid = 0;
		    
		    foreach ($doc['pagetext'] as $pagetext){

				foreach ($pagetext as $field_name => $value){

					//if ($field_name != 'pagetext') continue;
					switch($field_name){
						case 'cat_id':
							$highestCat = max(explode(",", $value));
							switch($highestCat){
								case 1303:
								case 2300:
								case 3011:
									$catPath = 'c_row_lg';
									break;
								case 3100:
									//could use something here to know if its a video????
								case 1103:
								case 1400:
								case 3000:
								case 3200:
								case 3210:
								case 3220:
								case 3230:
									$catPath = 'hero_ss';
									break;
								default: 
									$catPath = 'hero_sm';

							}
							//echo $highestCat;
							break;
						case 'element':
							//  Check type for nested arrays
			    			if (is_array($value)){

			    				//http://www.mercdes-amg.com/privatelounge/' .$value .'h2ero_sm/01.jpg';

			    				if($value[0]!=''){
			        				$frontEndImagePath = 'http://www.mercedes-amg.com/privatelounge/'.$value[0]  . $catPath .'/01.jpg';
			        			}
			        			//  Scan through inner loop

			        			//print_r($value);


			        			foreach ($value as $k => $v) {
			            			
			            			//set values accordingly before they go off to solr
									$value .= $v." ";

									//strip Array from the values here
									$re1='(Array)';

									$value =preg_replace($re1, "", $value);
									//echo $value .'hero_sm/01.jpg';				
			        			}

			        			// ==== \/
			        			// this where I left off.. only need to extract the youtube id then set it below
			        			/*
			        			//find the string
			        			echo $this->extractYoutubeID($value);
			        			//set it to the xml
			        			if(array_key_exists(1,$value)){
							    	$newPageTextNode = $dom->createElement('field');
							    	$newPageTextNode->setAttribute("name", 'video_url');
							    	$newPageTextNode->nodeValue = $value[1];
						    		$node->appendChild($newPageTextNode);
			        			}
			        			*/
			        			//above is where the youtube id is added to the xml document

			        			// ==== /\

			        		} else {
			        			if($value!=''){
			        				//echo ('running up here');
								$frontEndImagePath = 'http://www.mercdes-amg.com/privatelounge/'.$value . $catPath .'/01.jpg';
								}
			        		}
							
						break;
						case 'pagetext':
							preg_match_all("/(\[ATTACH\](?<digit>[0-9]+)\[\/ATTACH\])/", $value, $matches);
							if(isset($matches['digit'][0])){
								$pageTextImageURL = 'http://162.243.217.180/privatelounge/image.php?' .$matches['digit'][0];
							}
							if($pageTextImageURL){
								preg_match_all("/\[IMG\](?<image>https?:\/\/.*\.(?:png|jpg))\[\/IMG\]/", $value, $matches);
								if(isset($matches['image'][0])){
									$pageTextImagePath = 'http://162.243.217.180/privatelounge/image.php?' .$matches['image'][0];
								}
							}
						break;
						// save for later use if attach = 1
						case 'attachmentid':
							$attachmentid = $value;
							break;
						case 'attach':
							if($value==0){
								if($frontEndImagePath){
									$value = $frontEndImagePath;
								}elseif($pageTextImageURL){
									$value = $pageTextImageURL;
								}elseif($pageTextImagePath){
									$value = $pageTextImagePath;
								}
							}elseif($value==1){
								$value = 'http://162.243.217.180/privatelounge/image.php?' .$attachmentid;
							}

						break;
						case 'element_type':
						case 'post_type':
						case 'region_id':
							$value = implode(",", $value);
						break;
						default:
						//echo $field_name;
				} 


					//$fields .= sprintf('<field name="%s">%s</field>'."\n\r",$field_name, $value);
					//create new node for each field in $doc
			    	
			    	//solr needs very strict encoding and escaping
			    	$value = htmlspecialchars($value);
			    	$value = utf8_encode($value);

			    	if($field_name == 'pagetext'){
				    	$value = self::stripBBCode($value);
			    		$value = self::url_cleaner($value);
				    	$newPageTextNode = $dom->createElement('field');
				    	$newPageTextNode->setAttribute("name", $field_name);
				    	$newPageTextNode->nodeValue = $value;
			    		$node->appendChild($newPageTextNode);
			    	}else{
			    		if(isset($postArray[$field_name])){
			    			if(strlen($postArray[$field_name]) < strlen($value)){
			    				$postArray[$field_name] = $value;
			    			}
			    		}else{
			    			$postArray[$field_name] = $value;
			    		}
			    	}
			    	
		}//foreach

	}//foreach 

		//this should be happier here
		//this only strips the bbcode out of threadpagetext
	    //but it may be necessary to do this after this field has been checked for images.
	    $sanitaryPageText = $doc['pagetext'][$count]['pagetext'];
	    $sanitaryPageText = self::stripBBCode($sanitaryPageText);
	    $sanitaryPageText = self::url_cleaner($sanitaryPageText);
	    $sanitaryPageText = htmlspecialchars($sanitaryPageText);
	    $sanitaryPageText = utf8_encode($sanitaryPageText);

	    //create the threadpagetext field
    	$newnode = $dom->createElement('field');
    	$newnode->setAttribute("name", 'threadpagetext');
    	$newnode->nodeValue = $sanitaryPageText;
    	$node->appendChild($newnode);

    	//create a pagetext count field for constructing pagination links
    	$newnode = $dom->createElement('field');
    	$newnode->setAttribute("name", 'threadcount');
    	$newnode->nodeValue = $count;
    	$node->appendChild($newnode);
			
			//$node->appendChild($newnode);
	    	//$base_node->appendChild($node);

			foreach ($postArray as $field_name => $value){

				switch($field_name){

					case 'username';
					case 'postid':
					case 'lastpost':
					case 'forumid':
					case 'dateline':
					case 'attach':
					case 'attachmentid':
					case 'contentid':
					case 'similar':
					case 'lastpostid':
					case 'lastposter':
					case 'lastpost':
					case 'forumid':
					case 'region_id':
					case 'element':
					case 'element_type':
					case 'post_type':
					case 'visible':
					case 'allowsmilie':
				    	
				    	
				    	$value = self::stripBBCode($value);
			    		$value = self::url_cleaner($value);
			    		$value = htmlspecialchars($value);
			    		$value = utf8_encode($postArray[$field_name]);
				    	$newnode = $dom->createElement('field');
				    	$newnode->setAttribute("name", $field_name);
				    	$newnode->nodeValue = $value;
				    	$node->appendChild($newnode);
				    	$base_node->appendChild($node);
			    		break;
		    	}

		    }
		    //print_r($dom);
			
			//gimme the xml -- building on each execution
			umask();

			$dom->save('storage.xml');
			
			//DEPRECATED
			//print_r($dom);
			die();
			//echo ("<br><br><br><br><br>\n\r\n\r\n\r\n\r\n\r");
			//return sprintf('<add><doc>%s</doc></add>',$fields);
			//$sending = true;
}
 
		private function post($xml){
			$ch = curl_init();
			$post_url = $this->url.'update';
 
			$header = array("Content-type:text/xml; charset=utf-8");
			curl_setopt($ch, CURLOPT_URL, $post_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
 
			$data = curl_exec($ch);
 
			if (curl_errno($ch)) {
			   throw new Exception ( "curl_error:" . curl_error($ch) );
			} else {
			   curl_close($ch);
			   return TRUE;
			}
		}
 
		function fetch_data($query, $rows){		
			$search_url = $this->url.'select';
 			$rows = $rows;
 			//http://patrickisgreat.me:8983/solr/privatelounge/select?q=fulltext%3Amercedes&wt=json&indent=true
 			//http://patrickisgreat.me:8983/solr/privatelounge/select?q=fulltext%3Amercedes&wt=xml
 			//http://patrickisgreat.me:8983/solr/privatelounge/select?q=fulltext%3Ahello&rows=200&wt=json&indent=true

			$querystring = "?q=fulltext%3A".trim(urlencode($query))."&rows=".$rows."&wt=json&indent=true";

			$search_url = $search_url.$querystring;

			$header[] = "Accept: text/json,application/json,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
			$header[] = "Accept-Language: en-us,en;q=0.5";
			$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
 
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $search_url); // set url to post to
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_ENCODING,"");          
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
			curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
 
			if ($this->httppost) {
				curl_setopt($ch, CURLOPT_POST, 1 );
				curl_setopt($ch, CURLOPT_POSTFIELDS,$querystring);
			}
 
			$data = curl_exec($ch);
 
			if (curl_errno($ch)) {
				throw new Exception(curl_error($ch));
			} else {
				curl_close($ch);
				if ( strstr ( $data, '"status":0,')) {
					return $data;
				}
				else
					return FALSE;
			} 
		}
 
		function handle_response($data) {
			if ($data) {
				
				$json = $data;
 				
 				//for now lets just spit out the results
 				$results = $data;

				//$results = json_decode($data)
				//here we can run any kind of manipulation on the array if need be and return it

			} else {
				$results=false;
			}
			
			return $results;
		}
 
		function search($query, $rows){
			$xml = $this->fetch_data( $query, $rows );
			return $this->handle_response($xml);
		}
 
		function commit(){
			$this->post('<commit/>');
		}
 
		function optimize(){
			$this->post('<optimize/>');
		}
 
		function add_document($document){
			$xml = $this->arr_to_solr_doc($document);
		}

		function post_docs() {
			//$cmd = "curl -X POST 'http://patrickisgreat.me:8983/solr/privatelounge/update?commit=true' -H 'Content-Type: text/xml' -d @storage.xml";
			$cmd = "curl -X POST 'http://patrickisgreat.me:8983/solr/pl2/update?commit=true' -H 'Content-Type: text/xml' -d @storage.xml";
			$output = shell_exec($cmd);
				echo "<pre>$output</pre>";

				//cleanup the xml file after we've sent it off
				/*if ($output) {
					echo "output true";
					$dom = new DOMDocument("1.0");
					$dom->load('storage.xml');
					$dom->formatOutput = true;

					// get the add node from the file
					$base_node = $dom->getElementsByTagName('add')->item(0);
					$docs = $base_node->childNodes;
					while ($docs->length > 0) {
						$base_node->removeChild($docs->item(0));
					}
					umask();
					$dom->save('storage.xml');
				}*/
		}
 
		function delete_by_id($id){
			$this->post( sprintf('<delete><id>%s</id></delete>', $id) );
		}
 
	}
 
	//example
	/*$solr = new Solr('http://localhost:8080/solr/');
	$doc = array(
			'id' => '9',
			'title' => 'cartman',
			'description' => 'yeah what evaaa i do what i want',
			'tags' => 'cartman',
			'popularity' => '4',
			'space' => '14'
		);	
	$solr->add_document($doc);
	$solr->delete_by_id('3');
	$solr->commit();
	print_r( $solr->search('cartman') );
	*/
 
?>