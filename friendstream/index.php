<?php
require_once ('../include/friendstream.php');
ini_set('session.gc_maxlifetime', 3600);
session_cache_limiter ('private, must-revalidate');
session_cache_expire(60); // in minutes 
session_start();


$category = NULL;
if (isset($_GET['cat']))
   {
   $category = $_GET['cat'];
   if ($_SESSION['lastCategory'] != $category)
	  {
	  $_SESSION['lastCategory'] = $category;
	  }
   $_SESSION['loadCount'] = 0;	  
   }
else
   unset($_SESSION['lastCategory']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pintweet - Pinterest style twitter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Pinterest style twitter" />
    <meta name="keywords" content="twitter, weibo, pinterest, huaban, 花瓣, 美丽说, 微博, 新浪 " />
    <meta name="author" content="Ye Henry Tian" />
    <meta name="distribution" content="global" />
    <meta name="robots" content="follow, all" />
    <meta name="language" content="en" />
    <meta name="revisit-after" content="2 days" />
    <meta content="jaolb+4U3+k7xWefD1IT+pPv3Nevk/TJsQW8ZV3uXBI=" name="verify-v1" />
    <meta property="wb:webmaster" content="309eb8104fb27ab2" />
    <link rel="shortcut icon" href="../images/pinterest-icon.jpg" />
    <link href="fscss2.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../include/desandro-vanilla-masonry-1e41589/css/style.css" />
</head>

<body>

<script src="../include/desandro-vanilla-masonry-1e41589/masonry.min.js"></script>
<script type="text/javascript">
window.onload = function() {
  var wall = new Masonry( document.getElementById('container') );
  
};
//columnWidth: 100
</script>

<script type="text/javascript" language="javascript" src="../include/ajax2.js" charset="utf-8"></script>
<script type="text/javascript" src="../include/jQuery-Screw/examples/js/jquery.1.6.1.js"></script>
<script type="text/javascript" src="../include/jQuery-Screw/examples/js/jquery.screw.js"></script>
<script type="text/javascript">
// Initialize jQuery
jQuery(document).ready(function($){

// Call screw on the body selector  
$("body").screw({
    loadingHTML: '<div align="center"><img alt="Loading" src="../images/loadingBlack64.gif"></div>'
});

});
</script>



<!-- UJian Button BEGIN -->
<script type="text/javascript" src="http://v1.ujian.cc/code/ujian.js?type=slide"></script>
<!-- UJian Button END -->

<div id="friendstreamwrapper">
<div id="wrapper">

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_floating_style addthis_32x32_style" style="right:0px;top:150px;">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50610b553e7cadd5"></script>
<!-- AddThis Button END -->

<div id="head">
    <h1><a href="http://pintweet.tk"></a></h1>

<div id="search" align="right">
<form action="http://www.google.ca/cse" id="cse-search-box" target="_blank">
  <div>
    <input type="hidden" name="cx" value="partner-pub-6635598879568444:c8xv0514j98" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="30" />
    <input type="image" style="width:25px; height:25px;" name="sa" value="Search" src="../images/icon_search.png"/>
  </div>
</form>
<script type="text/javascript" src="http://www.google.ca/cse/brand?form=cse-search-box&amp;lang=en"></script>
</div>
</div>

<div id="navigationhome">
<nav role="navigation">
	<ul>
		<li><img src="../images/new-twitter-home.png" style="width:16px; height:16px; float:left;"/><a href="./">home</a></li>
		<li><a href="./about.html">about</a></li>
		<li><a href="http://twitter.com/yehenrytian" target="_blank">twitter</a></li>
	</ul>
</nav>
</div>


<div id="columns">
       
<ul id="column2" class="column">
  <li class="widget color-blue" id="intro">  
    <div class="widget-head">
      <h3>Pintweet(beta) - Pinterest style wall of tweets</h3>
    </div>
    <div class="widget-content">
    <?php
	$needlogin = false;
    $request_link = '';
	/* If access tokens are not available redirect to connect page. */
    if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
        $needlogin = true;

    // get the authorize connection link
    if ($consumer_key === '' || $consumer_secret === '') {
       echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
       exit;
    }
    /*
    Create a new TwitterOAuth object, and then get a request token. The request token will be used to build the link the user will use to authorize the application.
    You should probably use a try/catch here to handle errors gracefully
    */
    $to = new TwitterOAuth($consumer_key, $consumer_secret);
	$tok = $to->getRequestToken(OAUTH_CALLBACK);
    
	$_SESSION['twitterOAuth'] = $to;
	
    /*
    Save tokens for later  - we need these on the callback page to ask for the access tokens
    */
    $_SESSION['oauth_token'] = $token = $tok['oauth_token'];
    $_SESSION['oauth_token_secret'] = $tok['oauth_token_secret'];
	
	/* If last connection failed don't display authorization link. */
    switch ($to->http_code) {
      case 200:
        /* Build authorize URL and redirect user to Twitter. */
        $request_link = $to->getAuthorizeURL($tok);
        break;
      default:
        /* Show notification if something went wrong. */
        echo 'Could not connect to Twitter. Refresh the page or try again later.';
     }	
}
else {
     /* Get user access tokens out of the session. */
     $access_token = $_SESSION['access_token'];

     /* Create a TwitterOauth object with consumer/user tokens. */
	 if (!isset($_SESSION['twitter']))
	    {
        $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	    $_SESSION['twitter'] = $twitter;
		}
     }

	if ($needlogin)
	   {
	   echo '<div class="snaoauth"><span class="notice">Sign in via Twitter to see your recent tweets and much much more!!</span>';
	   echo '<p><a href="'.$request_link.'" title="Sign in with Twitter"><img style="padding-top:5px" src="../images/darker.png" alt="Sign in with Twitter"/></a></p></div>';
	   
	   //$tweetsList = get_tweets_pin('public');
	   echo ('<iframe frameborder="0" scrolling="no" src="http://pintweet.tk/twittersearch.html" width="33%" height="800"></iframe>');
	   
	   echo ('<iframe frameborder="0" scrolling="no" src="http://pintweet.tk/twittersearch3.html" width="33%" height="800"></iframe>');
	   
	   echo ('<iframe frameborder="0" scrolling="no" src="http://pintweet.tk/twittersearch2.html" width="33%" height="800"></iframe>');
	   
	   
	   }
	else
	   {
	   echo '<div class="snaoauth"><span style="float:right; margin-top:12px;"><a href="https://twitter.com/intent/tweet?button_hashtag=Pintweet" class="twitter-hashtag-button" data-related="yehenrytian">Tweet #Pintweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></span><p><img src="../images/t_small-c.png" alt="Sign out of Twitter"/><a class="button blue" href="../include/twitterapi/clearsession.php?twitter=clear">Sign out of Twitter</a> ';
	   
	   //echo '<img src="../images/adept_update.png" alt="refresh" width="25px" height="25px"/><a id="twitterrefresh" href="javascript:void(0);" onclick="getAjaxUpdates(\'get_tweets\', \'\', \'twitterlist\')" title="refresh tweets">refresh tweets</a> | ';
	   
	    echo '<img src="../images/adept_update.png" alt="refresh" width="25px" height="25px"/><a id="home" href="./?cat=home" title="Home" '. (($category == NULL || $category == 'home') ? 'class="button blue"' : '').'>Home</a> | <a id="me" href="./?cat=me" title="My Tweets" '.(($category == 'me') ? 'class="button blue"' : '').'>My Tweets</a> | <a id="public" href="./?cat=public" title="Public" '.(($category == 'public') ? 'class="button blue"' : '').'>Public</a> | <a id="mentions" href="./?cat=mentions" title="Mentions" '.(($category == 'mentions') ? 'class="button blue"' : '').'>Mentions</a> | <a id="replies" href="./?cat=replies" title="Replies" '.(($category == 'replies') ? 'class="button blue"' : '').'>Replies</a> | <span style="color:red; font-weight:bold;">Topics:</span> <a id="bnews" href="./?cat=searchnews" title="breaking news" '.(($category == 'searchnews') ? 'class="button blue"' : '').'>News</a> | <a id="funny" href="./?cat=searchfunny" title="funny" '.(($category == 'searchfunny') ? 'class="button blue"' : '').'>Funny</a> | <a id="stock" href="./?cat=searchstock" title="stock" '.(($category == 'searchstock') ? 'class="button blue"' : '').'>Stock</a> | <a id="china" href="./?cat=searchchina" title="china" '.(($category == 'searchchina') ? 'class="button blue"' : '').'>China</a> | <a id="canada" href="./?cat=searchcanada" title="canada" '.(($category == 'searchcanada') ? 'class="button blue"' : '').'>Canada</a> | <a id="usa" href="./?cat=searchusa" title="usa" '.(($category == 'searchusa') ? 'class="button blue"' : '').'>USA</a> | <a id="iphone" href="./?cat=searchiphone" title="iphone" '.(($category == 'searchiphone') ? 'class="button blue"' : '').'>Iphone</a> | <a id="photo" href="./?cat=searchphoto" title="photo" '.(($category == 'searchphoto') ? 'class="button blue"' : '').'>Photo</a> | <a id="video" href="./?cat=searchvideo" title="video" '.(($category == 'searchvideo') ? 'class="button blue"' : '').'>Video</a> | <a id="music" href="./?cat=searchmusic" title="music" '.(($category == 'searchmusic') ? 'class="button blue"' : '').'>Music</a> | <a id="movie" href="./?cat=searchmovie" title="Movie" '.(($category == 'searchmovie') ? 'class="button blue"' : '').'>Movie</a> | <a id="money" href="./?cat=searchmoney" title="money" '.(($category == 'searchmoney') ? 'class="button blue"' : '').'>Money</a> | <a id="job" href="./?cat=searchjob" title="job" '.(($category == 'searchjob') ? 'class="button blue"' : '').'>Job</a> | <a id="twitter" href="./?cat=searchtwitter" title="twitter" '.(($category == 'searchtwitter') ? 'class="button blue"' : '').'>Twitter</a> | <a id="pinterest" href="./?cat=searchpinterest" title="pinterest" '.(($category == 'searchpinterest') ? 'class="button blue"' : '').'>Pinterest</a></p></div>';
	   
	   //echo '<div id="refreshTweets" class="loading"></div>';
	   if (isset($category))
	      if (strncmp('search', $category, 6) == 0)
		     $tweetsList = echo_tweets_json(substr($category, 6));
		  else
	         $tweetsList = get_tweets_pin($category);
	   else
	      $tweetsList = get_tweets_pin();	   
	   
	   echo ($tweetsList);
	   }
	?> 
   </div>
   </li>
</ul>

</div> <!--columns-->



<div id="columns"> <!--footer-->
<ul id="column-footer" class="column">
  <li class="widget color-black" id="intro">
   <div class="widget-head">
    <h3></h3>
   </div>
   <div class="widget-content">
   <div class="columnleft" style="width:25%;">
   <h3>Site partners</h3>
	<ul>
    <li><a target="_blank" href="http://pinamazon.tk"><strong>Pinamazon.tk</strong></a> - Pinterest style amazon mall</li>
    <li><a target="_blank" href="http://pinweibo.tk"><strong>Pinweibo.tk</strong></a> - Pinterest style weibo wall</li>
    <li><a target="_blank" href="http://friendstream.ca"><strong>Friendstream.ca</strong></a> - Your realtime social network updates</li>
    <li><a target="_blank" href="http://firsttimer.ca"><strong>Firsttimer.ca</strong></a> - Get things done yourself</li>
    <li><a target="_blank" href="http://desandro.com/"><strong>David DeSandro</strong></a> - Revels in the creative process</li>
    <!-- <li><a target="_blank" href="http://net.tutsplus.com/"><strong>Nettuts+</strong></a> - Web development tutorials</li>-->
    </ul>
</div>

<div class="columnleft" style="width:10%;">
   <h3>Links</h3>
	<ul>
    <li><a target="_blank" href="http://pinweibo.tk"><strong>Pinweibo</strong></a></li>		
    <li><a target="_blank" href="http://twitter.com"><strong>Twitter</strong></a></li>				
	<li><a target="_blank" href="http://www.pinterest.com/"><strong>Pinterest</strong></a></li>
	<li><a target="_blank" href="http://www.huaban.com/"><strong>花瓣网</strong></a></li>
    <li><a target="_blank" href="http://www.meilishuo.com/+"><strong>美丽说</strong></a></li>
    <!--<li><a target="_blank" href="http://www.duitang.com/"><strong>堆糖</strong></a></li>			-->
	</ul>
</div>

<div class="columnleft" style="width:25%;">
     <a href="https://twitter.com/yehenrytian" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @yehenrytian</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
     <p>Developed by: <a target="_blank" href="http://about.me/yehenrytian">Ye Henry Tian</a></p>
     <h4>&copy; copyright 2012 - 2013 <a href="http://pintweet.tk">Pintweet.tk</a></h4>
     <a href="//affiliates.mozilla.org/link/banner/27789"><img src="//affiliates.mozilla.org/media/uploads/banners/910443de740d4343fa874c37fc536bd89998c937.png" alt="Download: Fast, Fun, Awesome" /></a>
</div>

<div class="columnleft" style="float:right;width:25%;">      
  <a href="http://s03.flagcounter.com/more/EzF"><img src="http://s03.flagcounter.com/count/EzF/bg=FFFFFF/txt=075FF7/border=0C9FCC/columns=2/maxflags=12/viewers=0/labels=1/pageviews=1/" alt="free counters" border="0"></a>
</div>

   </div>
   </li>
</ul>
</div> <!--footer-->

</div> <!--wrapper-->
</div> <!--friendstreamwrapper-->

 <!--<script type='text/javascript' src='../include/jquery-1.4.4.min.js?ver=1.4.4'></script>-->
 <!--<script type="text/javascript" src="jquery-ui-personalized-1.6rc2.min.js"></script>-->
 <script type="text/javascript" src="inettuts.js"></script>
    
<style type='text/css'>@import url('http://getbarometer.s3.amazonaws.com/install/assets/css/barometer.css');</style>
<script type="text/javascript" charset="utf-8">
  BAROMETER.load('f17xCQkSM27COTrmjXKrE');
</script>
 
</body>
</html>
