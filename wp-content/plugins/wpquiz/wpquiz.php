<?php
/**
 * @package WPQuiz
 * @version 0.4.0
 */
/*
Plugin Name: WPQuiz
Plugin URI: http://bauc.net/blog/wpquiz
Description: Add a quiz/question to a post/page
Author: Bauc
Version: 0.4.0
Author URI: http://bauc.net/blog
*/

// used to store the correct results on a temp basis
$wpquiz_answers['correct'] = 0;
$wpquiz_answers['total'] = 0;
$wpquiz_answers['wrong'] = 0;
$wpquiz_answers['percent'] = 0;
//											end store

register_activation_hook(__FILE__,'wpquiz_install');

add_action( 'add_meta_boxes', 'wpquiz_add_meta_insert_quiz' );
add_action('init', 'wpquiz_add_js');
add_action( 'wp_print_styles', 'wpquiz_addstyle' );

add_filter('the_content', 'wpquiz_scoring_replace',1000); 

add_shortcode( 'wpquiz', 'wpquiz_tag' );
add_shortcode( 'wpquiztext', 'wpquiz_text' );

//////////////////////////////////////////////////////////////////////////
// Function wpquiz_add_meta_insert_quiz()
// -----------------------
// Purpose is to add a meta box on the post edit/page edit page allowing
// for easy inserting of a quiz
//
//////////////////////////////////////////////////////////////////////////

function wpquiz_add_meta_insert_quiz()
{
    add_meta_box( 
        'wpquiz_insert_quiz',
        __( 'WPQuiz: Insert quiz:', 'wpquiz_text' ),
        'wpquiz_insert_quiz',
        'post' 
    );
    add_meta_box(
        'wpquiz_insert_quiz',
        __( 'WPQuiz: Insert Quiz', 'wpquiz_text' ), 
        'wpquiz_insert_quiz',
        'page'
    );	
}

function wpquiz_insert_quiz() {

  wp_nonce_field( plugin_basename( __FILE__ ), 'wpquiz_noncename' );
  
  echo '<label for="wpquiz_add_quiz">';
       _e("Insert quiz:", 'wpquiz_text' );
  echo '</label> ';
  
  global $wpdb; 
  $table_name1 = $wpdb->prefix . "wpquiz_questions";
  $table_name1 = $wpdb->prefix . "wpquiz_quiz";
    
  $sql = "SELECT id, quiztitle FROM " . $table_name1. ";";
  
  $myrows = $wpdb->get_results( $sql );

  echo '<form name = "wpquiz_adder">';
  echo '<select name = "wpquiza" id = "wpquiza">';
  
  foreach ($myrows as $myrow)
  {	
	echo '<option value="' . $myrow->id  .  '">' . $myrow->quiztitle  .  '</option>';		
  	
  }
  
  echo '</select>';
  echo '<input type = "button" id = "wpquiz_select" name = "wpquiz_select" onclick = "addQuiz(this.id) ;" value = "Add to post"><br />';
  echo 'Scoring options: (these need to be between [wpquiztext][/wpquiztext] tags to work) <select name = "wpquiz_scoring" id = "wpquiz_scoring">';
  echo '<option value = "correct">[wpquiz_correct]</option>';
  echo '<option value = "wrong">[wpquiz_wrong]</option>';
  echo '<option value = "total">[wpquiz_total]</option>';
  echo '<option value = "percent">[wpquiz_percent]</option>';
  echo '</select>';
  echo '<input type = "button" id = "wpquiz_score" name = "wpquiz_score" onclick = "addScore(this.id) ;" value = "Add to post"><br />';
  echo '</form>';
}

// End function wpquiz_add_meta_insert_quiz() combo
// #######################################################################
//*/




//////////////////////////////////////////////////////////////////////////
// Function wpquiz_text
// -----------------------
// Purpose is to process the 'The Content' from a post which has the tag
// [wpquiz_*] with a paramter of the number of correct answers
//
//////////////////////////////////////////////////////////////////////////

function wpquiz_text ($att, $content)
{
	// checks if quiz has been submitted, if so then check all questions
	// been answered
	if ($_POST['charlie'] == "x1") {
		$intt = $_POST['foxtrot'];
		$citt=0;
		$tt ="";
		
		// Loops through all the questions checking an answer has been 
		// entered before returning the tag text
		foreach($_POST as $name => $value)
		{ 
			
			if (strrpos($name, "quizqS") === false)
			{
				$pos = strrpos($name, "quizq");
				if ($pos === false) { // note: three equal signs
				}else
				{
					$citt++;					
				}
			}			
		}
	
	if ($citt == $intt) { return $content;}
			
	}
}
// End function wpquiz_text
// #######################################################################


function wpquiz_addstyle()
{

	$myStyleUrl = WP_PLUGIN_URL . '/wpquiz/wpquiz_m1.css';
	$myStyleFile = WP_PLUGIN_DIR . '/wpquiz/wpquiz_m1.css';
	if ( file_exists($myStyleFile) ) {	
		wp_register_style('wpquiz_m1', $myStyleUrl);
		wp_enqueue_style( 'wpquiz_m1');
	}


}
 
// function replaces the "fake" shortcode tags used in editing the quiz
// with the answers array populated during wpquiz tag
function wpquiz_scoring_replace($content) { 

	global $wpquiz_answers;
	  
	$content = str_replace('[wpquiz_correct]' ,$wpquiz_answers['correct'] , $content);
	$content = str_replace('[wpquiz_wrong]' ,$wpquiz_answers['wrong'] , $content);
	$content = str_replace('[wpquiz_total]' ,$wpquiz_answers['total'] , $content);
	$content = str_replace('[wpquiz_percent]' ,$wpquiz_answers['percent'] , $content);
	return $content;

}

function wpquiz_tag( $atts ) {
	
	global $wpdb;
	$table_name1 = $wpdb->prefix . "wpquiz_questions";
	$table_name2 = $wpdb->prefix . "wpquiz_quiz";

	extract( shortcode_atts( array(
		'id' => '1',
		'tweet_this' => 'false',
		'tweet_hashtag' => '',
	), $atts ) );
	
	$quizno = $id;	
		
	$sql2 = "SELECT quiztitle FROM " . $table_name2. " WHERE id = $id";		
	$sql = "SELECT id, question, answers, correctAnswer FROM " . $table_name1. " WHERE quizid = $id ORDER BY id ASC";		
	$quizdb = $wpdb->get_row($sql2);	
	$myrowss = $wpdb->get_results( $sql );
	
	if ($_POST['charlie'] == "x1")
	{
		$c = 0;		
		$score_correct = 0;
		$score_wrong = 0;
		$score_total = 0;
		foreach ($myrowss as $myrows)
		{
			
			$qcount++;
			$id = $myrows->id;
			$question = trim(stripslashes($myrows->question));
			$answers = trim(stripslashes($myrows->answers));
			$correct = stripslashes(trim($myrows->correctAnswer));
			
			$ansS = explode("\n", $answers);
			
			$tt = '<ul>';
			
			$answerQ = htmlspecialchars($_POST['quizq' . $id]);
			$sortQ = htmlspecialchars($_POST['quizqS' . $id]);			
			$sortQa = explode('|', $sortQ);			
			$ans = array();
			$cc = 0;

			foreach ($sortQa as $value1)
			{	
				if ($value1 != "")
				{			
					$value = $value1;  // +1;
					$ans[$cc] = $ansS[$value];
					
					$cc++;
				}
			}
			
			ksort($ans);
			$answerdb = array();
						
			$i = 0;

			$answerdbc[$i] = false;
			if ($answerQ == "") {$answerdbc[$id] = true; $c++;}
				
			$answerQ = trim(stripslashes($answerQ));
			$answerQ = trim(stripslashes($answerQ));
			$answerQ = str_replace (array("\r\n", "\n", "\r"), ' ', $answerQ);
			
			
			foreach ($ans as $v)
			{				
				$i = stripslashes(trim($v));			
				
				if ($i === $correct)
				{	
					$tt = $tt . '<li class = "wpquizlicorrect">' . $v . '' . $yip . '</li>';					
				}
				elseif ($i === $answerQ)
				{					
					$tt = $tt . '<li class = "wpquizliwrong">' . $v . '' . $yip . '</li>';					
				}
				else
				{					
					$tt = $tt . '<li class = "wpquizli">' . $v . '' . $yip . '</li>';
				}
			}

			// calculate test scores
			$num_rows++;
			$score_total++;
		
			if ($answerQ == $correct)
			{
				$score_correct++;
			}
			else
			{
				$score_wrong++;
			}

			$quiz = $quiz . '<p>' . $question . '</p>' . $tt . '</ul>';
			
			if ($c > 0) {
				$answerdbc[-1] = $qcount;			
				$quiz = wpquiz_loadquestion($quizno, $answerdbc);
			}
		
		}
		
		$score_percent = round($score_correct / $score_total * 100,2);
		$score_debug = '';
				
		global $wpquiz_answers;
		
		$wpquiz_answers['correct'] = $score_correct;
		$wpquiz_answers['wrong'] = $score_wrong;
		$wpquiz_answers['total'] = $score_total;
		$wpquiz_answers['percent'] = $score_percent;
		
		//																									###  End score
		
		if (isset($tweet_this))		
		{
			if ($tweet_this == 'true'){
		
			$wpquiz_tweet_url = get_permalink(); //'http://scubacheck.net';
			
			$tweet_text = 'I scored ' . $score_percent . '&#37; at ';
			$wpquiz_tweet_textpre = $tweet_text;
			
			if ((isset($tweet_text)) && ($tweet_text == ''))			
			{
				$tweet_text = 'I just took the quiz - ';
			}
			
			$wpquiz_tweet_text = $wpquiz_tweet_textpre . get_the_title(); //'Testing test';
						
			$wpquiz_tweet_hashtag = '';
			if (isset($tweet_this_hashtag))
			{
				if ($tweet_this_hashtag != '')
				{
					$wpquiz_tweet_hashtag = 'data-hashtags="'.$tweet_this_hashtag.'"';
				}
			}
				
			//$wpquiz_tweet_this_button = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="'.$wpquiz_tweet_url.'" data-text="'.$wpquiz_tweet_text.'" '.$wpquiz_tweet_hashtag.' data-dnt="true">Tweet</a>
			$wpquiz_tweet_this_button = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-text="'.$wpquiz_tweet_text . ' ' . $wpquiz_tweet_url.'" data-hashtags="'.$wpquiz_tweet_hashtag.'" data-dnt="true">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
';		
			}
		}
		
		$quiz_return = $quiz . $wpquiz_tweet_this_button; //$quiz_stat . $quiz;			
		
		return $quiz_return;
	}	// quiz has been answered/processes
	
	else {

		$quiz = wpquiz_loadquestion($quizno);
		return $quiz;
				
	}
	
}

function wpquiz_loadquestion($quizno, $answerdbc = "")
{
		
	global $wpdb;
	$table_name1 = $wpdb->prefix . "wpquiz_questions";
	$table_name2 = $wpdb->prefix . "wpquiz_quiz";

	$id = $quizno;		
	$sql2 = "SELECT quiztitle FROM " . $table_name2. " WHERE id = $id";		
	$sql = "SELECT id, question, answers, correctAnswer FROM " . $table_name1. " WHERE quizid = $id ORDER BY id";		
	$quizdb = $wpdb->get_row($sql2);
	
	$myrowss = $wpdb->get_results( $sql );
		
		$t1 = '<p>' . $quizdb->quiztitle .'</p>';
		$t1 = $t1 . '<form name="wpquiz_form" method="post" action="' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '">';		
		$quiz = $t1;
		
		$numcc= 0;
		
		foreach ($myrowss as $myrows)
		{
				
			$id = $myrows->id;
			$numcc++;
			$question = stripslashes($myrows->question);
			$answers = stripslashes($myrows->answers);
			$correct = $myrows->correctAnswer;
			
			$ans = explode("\n", $answers);
			
			$tt = '<p>';
			
			if ($answerdbc[$id] === true) { $tt = $tt ."<em>Error:</em> - Please select an answer<br />";}
					
			$na = 0;
			$checked = "";
			$cid = 'quizq' . $id;
			if ($_POST[$cid] > 0) {$checked = $_POST[$cid];}
			
			$sortAns = $ans;
			shuffle($ans);
						
			$ansShuffle = '';
			foreach ($ans as $v)
			{				
				//$na++;
				$na = $v;
				
				$ansShuffle = $ansShuffle . array_search($na, $sortAns) . '|'; //trim($v) . '|';
				
				if ($na == $checked)
				{
					$tt = $tt . '' .  '<input type = "radio" name = "quizq' . $id .'" id = "quiz' . $id . '.' . $na .'" onchange="checkQuiz(this.id)" value = "' . $na .'" checked = "yes"> ' . '<label for ="quiz' . $id . '.' .  $na .'">' . $v . '</label><br />' . "\n";
				}
				else
				{
					$tt = $tt . '' .  '<input type = "radio" name = "quizq' . $id .'" id = "quiz' . $id . '.' . $na .'" onchange="checkQuiz(this.id)" value = "' . $na .'"> ' . '<label for ="quiz' . $id . '.' .  $na .'">' . $v . '</label><br />' . "\n";
				}
		
			}
			
			$tt = $tt. '<input type = "hidden" name = "quizqS'.$id.'"value = "'.$ansShuffle.'" />';
					
			$quiz = $quiz . '<p>' . $question . '</p>' . $tt . '</p>';
		}
		
		$debug = "";
		
		return $quiz . '<input type = "hidden" name = "charlie" value = "x1">' . 	'<input type = "hidden" name = "foxtrot" value = "'. $numcc .'">' .	
		'<input type = "submit" name = "Submit" value = "Answer Quiz"></form>';

}


function wpquiz_add_js ()
{
	
    wp_register_script( 'wpquiz_checkquiz', plugins_url( 'wpquiz/wpquiz_checkquiz.js' , dirname(__FILE__) ));
    wp_enqueue_script( 'wpquiz_checkquiz' );

}




///////////////////////////////////////////////
///INSTALL
///////////////////////////////////////////////


global $wpquiz_db_version;
$wpquiz_db_version = "1.0";

function wpquiz_install() {

   include('wpquiz_install.php');
   
}

//////////////////////////////////////////////

//admin page

add_action('admin_menu', 'wpquiz_admin_screen');
add_action( 'admin_menu', 'wpquiz_admin_enqueue_js' ); 

function wpquiz_admin_enqueue_js()
{ 
    wp_enqueue_script( 'wpquiz_admin_js', plugins_url( 'wpquiz/wpquiz_admin.js' , dirname(__FILE__) ) ); 
} 


function wpquiz_admin_screen()
{
	add_options_page('WPQuiz', 'WPQuiz', 'manage_options', 'wpquiz_options', 'wpquiz_admin_options');
}

function wpquiz_admin_options()
 {
	include('wpquiz_admin_options.php');
}

// Add the settings link
function wpquiz_settings_link($links, $file) 
{
	if ($file == plugin_basename(__FILE__)){
		$settings_link = '<a href="options-general.php?page=wpquiz_options">'.(__("Settings", "WPQuiz")).'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'wpquiz_settings_link', 10, 2 );

////////////////////////////////////////////

?>