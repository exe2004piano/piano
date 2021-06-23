<?php
// No direct access
defined('_JEXEC') or die;
$userID 		     = $params->get('user_id');
$accessToken 	  = $params->get('access_token');
$classNames      = $params->get('class_name');
$tagName 	     = $params->get('tag_name');
$limit		     = $params->get('limit');
$columns		     = $params->get('columns');
$hideProfile     = $params->get('profile_display');
$noBackground    = $params->get('noBackground');

if($noBackground != "yes"){
	$backgroundColor = $params->get('backgroundColor');
}else{
	$backgroundColor = 'transparent';
}

if($hideProfile != "yes"){
	$displayProfile = "block";
}else{
	$displayProfile = "none";
}
?>
<style>
	.jgram{
		background-color: <?php echo $backgroundColor; ?>;
	}

	.profile-pic, .user-info{
		display: <?php echo $displayProfile; ?>;
	}
</style>

<?php
echo '<div class="jgram-overlay"><a id="jgram-close" href="#">X</a><a id="jgram-prev" href="#">&#10094;</a><a id="jgram-next" href="#">&#10095;</a></div>';

if($params->get('get') == 'tagged'){
	include 'tagged.php';
}else if($params->get('get') == 'user'){
	include 'user.php';
}else if($params->get('get') == 'search'){
	include 'search.php';
}else{
	echo '<h2>You need to make photostream selection</h2>';
}

?>
