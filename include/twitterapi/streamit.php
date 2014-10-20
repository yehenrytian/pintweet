<?php
require_once ('jsonwrapper.php');
require_once ('twitterapi/config.php');
require_once ('twitterapi/twitter.lib.php');
require_once ('facebookapi/fbmain.php');
require_once ('buzz-php-client-1.0/src/buzz.php');
require_once ('weibo/config.php');
require_once ('weibo/weibooauth.php');

ini_set('session.gc_maxlifetime', 3600);
session_cache_limiter ('private, must-revalidate');
session_cache_expire(60); // in minutes 
session_start();
/*
$streamTwitter = ($_POST['twitter'] == 'show')? '1' : '0';
$streamFB = ($_POST['facebook'] == 'show')? '1' : '0';
$streamBuzz = ($_POST['buzz'] == 'show')? '1' : '0';
$streamWeibo = ($_POST['weibo'] == 'show')? '1' : '0';
$streamStatus = trim($_POST['happening']);
*/

$streamTwitter = isset($_GET['twitter']) ? '1' : '0';
$streamFB = isset($_GET['facebook']) ? '1' : '0';
$streamBuzz = isset($_GET['buzz']) ? '1' : '0';
$streamWeibo = isset($_GET['weibo']) ? '1' : '0';
$streamStatus = trim($_GET['post']);
//$streamStatus = ($_GET['post']);

// strip "/"
if (get_magic_quotes_gpc())
   $streamStatus = stripslashes($streamStatus);


// only parse the status content once   
//$shortStatus = parse_longurl($streamStatus);

//print_r ($streamStatus);

$shortStatus = $streamStatus;

// check for Google Buzz first, since it is a bit slow
if ($streamBuzz)
   {
   $buzzobj = $_SESSION['buzzobj'];
   
   if (!$buzzobj)
      {
      echo 'Session expired! Please refresh page to sign in again!';
	  return;
	  }
	  
   // publish status update
   try {
	   $buzzObject = new buzzObject($shortStatus);
       $post = buzzPost::createPost($buzzObject);
       $newPost = $buzzobj->createPost($post);
       } catch (Exception $e) {
         print_r($e);
       }
   }

// check for Facebook
if ($streamFB)
   {
   $facebook = $_SESSION['facebook']; 
   
   if (!$facebook)
      {
      echo 'Session expired! Please refresh page to sign in again!';
	  return;
	  }
	  
   // publish status update
   $session = $facebook->getSession();
   if ($session) {
      try {
	      $statusUpdate = $facebook->api('/me/feed', 'POST', array('message'=> $shortStatus, 'cb' => ''));
		  return;
          } catch (FacebookApiException $e) {
            print_r($e);
          }
      }
   }

// check for Twitter
if ($streamTwitter)
   {
   $twitter = $_SESSION['twitter'];
   
   if (!$twitter)
      {
      echo 'Session expired! Please refresh page to sign in again!';
	  return;
	  }
	  
   // publish status update
   try {
	   //print_r($shortStatus);
	   $parameters = array('status' => $shortStatus);
	   $rtstring = $twitter->post('statuses/update', $parameters);
       } catch (Exception $e) {
         print_r($e);
       }
   }

// check for Sina Weibo
if ($streamWeibo)
   {
   $weibo = $_SESSION['weibo'];
   
   if (!$weibo)
      {
      echo 'Session expired! Please refresh page to sign in again!';
	  return;
	  }
	  
   // publish status update
   try {
	   $rr = $weibo->update($shortStatus);	
       } catch (Exception $e) {
         print_r($e);
       }
   }   

//header('Location: ../friendstream/');

// function used to shorten URL in streamed post
function parse_longurl($t) 
  {
  // make sure to exclude already shorten url by sina t.cn service	  
  $t = " ".preg_replace( "/(([[:alnum:]]+:\/\/)|www\.)(?!t\.cn)([^[:space:]]*)".
	  "([[:alnum:]#?\/&=])/ie", "getShortUrl('\\1\\3\\4')", $t);
	   
  return trim($t);
  }

function getShortUrl($longUrl)
   {
   $ch = curl_init('http://api.t.sina.com.cn/short_url/shorten.xml?source=1550075579&url_long='.$longUrl);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_POST, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $surl = curl_exec($ch);      
   curl_close($ch);
   $surl_xml = new SimpleXMLElement($surl);
   return ($surl_xml->url[0]->url_short); 
   }

function getShortUrl2($api, $longUrl)
   {
   $surl = $api->http('http://api.t.sina.com.cn/short_url/shorten.xml?source=1550075579&url_long='.$longUrl, 'GET');
   $surl_xml = new SimpleXMLElement($surl);
   return ($surl_xml->url[0]->url_short); 
   }
   

?>