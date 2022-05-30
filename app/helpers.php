<?php
//require_once(doc.'/webloit/vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php');
use Carbon\Carbon;

function filterStringPolyfill(string $string): string
{
    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
}

// This function formats and sanitize all parameters retrieved from user_input
function sanitize($var)
{
	if(!is_array($var))
	{
		//$str = mb_strtolower($var);
	    $trim = trim($var);
	    $fil = filterStringPolyfill($trim);
	    $add = addslashes($fil);
	}
	else
	{
		$add = [];
			
		for($i=0;$i<count($var);$i++)
		{
			//$str = mb_strtolower($var[$i]);
	        $trim = trim($var[$i]);
	        $fil = filterStringPolyfill($trim);
			$add []= addslashes($fil);
		}
	}
		
	return $add;
}



// This function cleans inputs then maintain new lines and capitalisations. Used mainly in textareas.
function new_lines($var)
{
	$trim = trim($var);
	$fil = filter_var($trim, FILTER_FLAG_NO_ENCODE_QUOTES);
	$add = addslashes($fil);
	$new_line = nl2br(htmlspecialchars($add));
	return $new_line;
}


function sanitize_html($html, $allowed_tags=null)
{
	if(!empty($allowed_tags))
	{
		$allowed_tags = strip_tags($html,$allowed_tags);
	}
	
	$config = HTMLPurifier_Config::createDefault();
	// configuration goes here:
    $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $config->set('HTML.Doctype', 'HTML 4.01 Transitional');	// replace with your doctype
	$config->set('Cache.DefinitionImpl', null);
    $purifier = new HTMLPurifier($config);
	$pure_html = $purifier->purify($html);
	
	return $pure_html;
}

//In an HTML document we will only allow links that point to Webloit especially when making bulk emails which can be easily changed from client side.
function confirm_link($html, $tag="a")
{
	switch($tag)
	{
		case "img":
		    $tag="img";
			$attr = "src";
			
			break;
			
		default:
		    $tag="a";
			$attr = "href";
	}
	
	$dom = new DomDocument();
    $dom->loadHTML($html);
	
	$tags = $dom->getElementsByTagName($tag);
	
    $output = [];
    
	foreach ($tags as $item) 
	{
		$output[] = $item->getAttribute($attr);
	/*
   $output[] = array (
      'str' => $dom->saveHTML($item),
      'href' => $item->getAttribute('href'),
      'anchorText' => $item->nodeValue
   );
   */
    }
	
    $output = array_unique($output);
	
	for($i=0;$i<count($output);$i++)
	{
		if(isset($output[$i]))
		{
			$url = parse_url($output[$i], PHP_URL_HOST);
		
		    if((empty($url)) or ($url != $_SERVER["HTTP_HOST"]))
		    {
				echo "<p class='alert alert-danger'>A link pointing to an external domain/website was detected. You can only use links to {$_SERVER["HTTP_HOST"]}</p>";
			    exit;
		    }
		}
	}
}


function count_img_tags($html)
{
	confirm_link($html, $tag="img");
	
	//Let's make sure the Merchant doesn't upload more than the allowed number of images. We do so by searching for the "<img>" tag. If user tries to be wise by trying to circumvent the screening by providing masked content then the images won't even render which is worthless.
	$noOfImage = preg_split("/<img/", $html);
	
	if(count($noOfImage) > 10)
	{
		echo "<p class='alert alert-danger'>You've exceeded the number of images (10) you can send. Reduce it and try again.</p>";
	    exit;
	}
}


function calculatePercentageProfit($costPrice, $sellingPrice)
{
	try
	{
		if($costPrice == 0)
	    {
			throw new Exception("Cannot divide by zero");// E_USER_NOTICE);
	    }
		
	    $percentProf = ($sellingPrice * 100) / $costPrice;
	
	    $actualPercentProf = $percentProf - 100;
	
	    return $actualPercentProf;
	}
	catch(Exception $e)
	{
		return 0;
	}
	
};
	


/* This function attempts to separate a string that has numbers inside to
* bring out just the number portion in the string that can be converted to integer. 
* It assumes that in the  string the only portion to remove to be able to convert 
* it to a real number is the first(0) item. It converts the string to an array,
* slice out from the second(1) part to the end, uses iteration to concat the sliced parts
* which now contains only the real number then returns it to controller.
* Example: #9038 will be converted to 9038, leaving out the # before it.
***********************************************************************************************/
function str_to_int($str)
{
	$ro = str_split($str);
    $r = array_slice($ro, 1);
    $p = "";
	for ($i=0;$i<count($r);$i++)
	{
		$p .= $r[$i];
	}
return $p;
}


// splits an array(even multidimensional) into chunks with indexes ONLY so that in_array can do its job.
// The first argument is the array, the second argument is the string/value you want to search for if it exists in $search
// This is my custom version of "in_array".
// The return value is boolean(true or false).
function search_string($search, $needle)
{
	$v = [];//initialises an empty array.
	for($i=0;$i<count($search);$i++)//loops through the array to be searched on
	{
		//The argument has a key(2nd one) that is constant(always 0). the 3rd key is the actual association
		foreach($search[$i][0] as $b=>$t)//iterates and brings out only the main key=>value
		{
			$v []= $t;	//stores it in the initialised variable above
		}
	}
	$c = in_array($needle,$v);//if the second argument of this function is found in this newly created array.
	return $c;
}

	

function limit_text($content,$link)
{
	// checks if the content we're receiving isn't empty, to avoid the warning
         if (empty( $content ) ) {
                 return;
             }
			 
	if(strlen($content) > 800)
	{

             // converts all special characters to utf-8
             $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
			 
			 $shortened = substr($content,0,609);

             // creating new document
             $doc = new DOMDocument('1.0', 'utf-8');

             //turning off some errors
             libxml_use_internal_errors(true);

             // it loads the content without adding enclosing html/body tags and also the doctype declaration
             $doc->LoadHTML($shortened, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

             // do whatever you want to do with this code now
			 $text = $doc->saveHTML()."<a href='$link'>...see more</a>";
			 return $text;
	}
	else
	{
		return $content;
	}
}


function generate_id($length=5)
{
	$random_generator = openssl_random_pseudo_bytes($length);
    $id = bin2hex($random_generator);// Actual identifier.
	return $id;
}
	


function extract_name_from_host($url)
{
	$host = parse_url($url, PHP_URL_HOST);
    $host_array = explode(".", $host);
	
	if($host_array[0] == "www")
	{
		$name = $host_array[1];
	}
	else
	{
		$name = $host_array[0];
	}
	return ucwords($name);
}

function validate_ad_url($ad_url)
{
	$scheme = parse_url($ad_url, PHP_URL_SCHEME);

	if((empty($scheme)) or ($scheme != 'https'))
	{
		echo "<p class='alert alert-danger'>Your landing page must start with 'HTTPS'</p>";
	    exit;
    }
}


function carbon($date_time)
{
	return new Carbon($date_time);
}

function greatest(...$values)
{
	return collect($values)->max();
}


function trimTrailingZeroes($nbr) 
{
    return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
}

function model($request){
	return $request->user() ?? $request->user('staff');
}

function rearrangeIndex(array &$array){
	$new_array = [];
	foreach($array as $element){
		$new_array []= $element;
	}
	$array = $new_array;

}
?>
		 