<?php

global $wpquiz_db_version;
$wpquiz_db_version = "1.0";

global $wpdb;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function addTable ($tableName, $sql)
{
	global $wpdb;
	//$sql_check = "SELECT * FROM " . $tableName . " LIMIT 1;";
	
	//echo "<h2>$sql_check</h2>";
	//$tableRows = $wpdb->get_row($sql_check);
	
	//update_option("wpquiz_error" . $tableName, $wpdb->num_rows);
	
	/*if ($wpdb->num_rows <= 0)
	{
		$exists = -1;	
	}
	else
	{*/		
		dbDelta($sql);
		$exists = 1;
	//}

	return $exists;

}



//$installed_ver = get_option( $wpquiz_db_version );
//if( $installed_ver != $jal_db_version ) {

add_option("wpquiz_db_version", $wpquiz_db_version);
add_option("wpquiz_result_text", 'You scored {correct} out of {total}.');

$tbl_wpquiz_quiz = $wpdb->prefix . "wpquiz_quiz";
$tbl_wpquiz_question = $wpdb->prefix . "wpquiz_questions";

$result1 = addTable ($tbl_wpquiz_question, "CREATE TABLE " . $tbl_wpquiz_question . " (   
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `question` varchar(200) NOT NULL DEFAULT '',
  `answers` text NOT NULL,
  `correctAnswer` mediumint(9) NOT NULL,
  `quizid` mediumint(9) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

$result2 =  addTable ($tbl_wpquiz_quiz, "CREATE TABLE " . $tbl_wpquiz_quiz . " (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `quiztitle` varchar(200) NOT NULL DEFAULT '',
  `store_results` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
");

//if (($result1 == -1) && ($result2 == -1))
//{

	$testq_question = "How many sides in a triangle";
	$testq_answerid = 3;

	$rows_affected = $wpdb->insert( $tbl_wpquiz_quiz, array( 'quiztitle' => "Do you know your geometry") );
	$quizid = $wpdb->insert_id ;
	$rows_affected = $wpdb->insert( $tbl_wpquiz_question, array( 'question' => "How many sides in a triangle", 'answers' => "2\n3\n4\n5", 'correctAnswer' => "2", 'quizid' => $quizid ) );
	$rows_affected = $wpdb->insert( $tbl_wpquiz_question, array( 'question' => "How many sides in a rectangle", 'answers' => "2\n3\n4\n5", 'correctAnswer' => "3", 'quizid' => $quizid ) );
	$rows_affected = $wpdb->insert( $tbl_wpquiz_question, array( 'question' => "How many sides in a pentagon", 'answers' => "2\n3\n4\n5", 'correctAnswer' => "4", 'quizid' => $quizid ) );
//}

?>