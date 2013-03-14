<?php
/*
Plugin Name: SS Quiz
Description: With this plugin you can make quizzes really fast. Add questions/quizzes, rearrange questions, edit answers, insert multimedia in questions, - all of this can be done on single page within several seconds. Also one can edit welcome/finish/email templates using html if it's needed.  To insert quiz into page, use short code [ssquiz id='#']. Quiz automatically determines what type of test user creates (choose-correct, fill-blank or question with several correct answers)
Author: SSVadim
Plugin URI: http://100vadim.com/ssquiz/
Author URI: http://100vadim.com
Version: 1.12.2
License: GPL2
*/

/*  Copyright 2012  Vadim Storozhev

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

//-----Installing / Uninstalling Plugin

function quiz_plugin_menu(){
	add_menu_page('SSQuiz', 'SSQuiz', 'manage_options', 'ssquiz_1', 'draw_question_table', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/quiz.png"));
	add_submenu_page('ssquiz_1', __('Quiz\'s Users', 'ssquiz'),__('Quiz\'s Users', 'ssquiz'), 'manage_options', 'ssquiz_2', 'users_page');
	add_submenu_page('ssquiz_1', __('Quiz Templates', 'ssquiz'),__('Quiz Templates', 'ssquiz'), 'manage_options', 'ssquiz_3', 'template_creator'); 
}

function ssquiz__styles() { 
	wp_enqueue_style('thickbox');
	//header('<LINK REL=StyleSheet HREF="'.path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/quiz.css").'" TYPE="text/css">');
	wp_register_style("ssupload_css", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/quiz.css"));
	wp_enqueue_style('ssupload_css');
}  

function ssquiz_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script( "ssupload_js", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/quiz.js"), array('jquery', 'jquery-ui-sortable'));
	wp_localize_script("ssupload_js", "MyAjax", array('ajaxurl'=>admin_url("admin-ajax.php"), 'images'=>plugins_url("ssquiz/")));
	wp_localize_script("ssupload_js", "Trans", array(
		'add_answer'=>__("Add answer", 'ssquiz'), 
		'you_must_write'=>__("You must write at least one answer!", 'ssquiz'),
		'you_must_choose'=>__("You must choose correct answer!", 'ssquiz'),
		'questions_too'=>__("Question too short!", 'ssquiz'),
		'you_must_write_title'=>__("You must write title!", 'ssquiz'),
		'you_must_write_description'=>__("You must write description!", 'ssquiz'),
		'error'=>__("Error", 'ssquiz'),
		'saved'=>__("Saved", 'ssquiz'),
		'please_fill_forms'=>__("Please fill the forms", 'ssquiz'),
		'input_correct_email'=>__("Please input a correct email", 'ssquiz'),
		'select_quiz'=>__("Select Quiz", 'ssquiz'),
		'delete_quiz'=>__("Do you really want to delete quiz and all its questions?", 'ssquiz'),
		'delete_all_users'=>__("Do you really want to delete all users from SSQuiz's database", 'ssquiz'),
		'add_question'=>__("add question", 'ssquiz'),
		'cancel'=>__("cancel", 'ssquiz'),
		'edit'=>__("edit", 'ssquiz'),
		'loading'=>__("loading", 'ssquiz'),
		'add_quiz'=>__("Add Quiz", 'ssquiz'),
		'another_answer'=>__("Another answer is", 'ssquiz')
	));
}

function ssquiz_activate(){
	global $wpdb;
	$table_name='ssquiz_quizzes';
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . $table_name . '"') != $table_name ){
		$wpdb->get_results('CREATE TABLE ' . $table_name . '( 
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				title VARCHAR (255),
				description VARCHAR (255),
				PRIMARY KEY  (id) )
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');

	$wpdb->insert($table_name, array('title'=>"Back to school", 'description'=>"Some questions about geography"));
	//$wpdb->insert($table_name, array('title'=>"Test quiz #2", 'description'=>"And this is quiz#2."));

	}

	$table_name='ssquiz_questions';
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . $table_name . '"') != $table_name ){
		// type = meta info
		$wpdb->get_results('CREATE TABLE ' . $table_name . '( 
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				question MEDIUMTEXT,
				type VARCHAR (3),
				quiz_id INTEGER(10), 
				number INTEGER(10),
				PRIMARY KEY  (id) )
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');
	
	$wpdb->insert($table_name, array('question'=>"Highest Mountain?", 'type'=>"A1", 'quiz_id'=>1, 'number'=>1));
	$wpdb->insert($table_name, array('question'=>"Capital of Russia?", 'type'=>"A2", 'quiz_id'=>1, 'number'=>2));
	$wpdb->insert($table_name, array('question'=>"Capital of Japan?", 'type'=>"A3", 'quiz_id'=>1, 'number'=>3));
	$wpdb->insert($table_name, array('question'=>"Deepest Ocean?", 'type'=>"A4", 'quiz_id'=>1, 'number'=>4));
	$wpdb->insert($table_name, array('question'=>"Capital of USA?", 'type'=>"B1", 'quiz_id'=>1, 'number'=>5));
	$wpdb->insert($table_name, array('number'=>99999, 'quiz_id'=>1), array('%d', '%d')); //99999
	//$wpdb->insert($table_name, array('number'=>99999, 'quiz_id'=>2), array('%d', '%d')); //99999
	}

	$table_name='ssquiz_answers';
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . $table_name . '"') != $table_name ){
		$wpdb->get_results('CREATE TABLE ' . $table_name . '( 
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				question_id INTEGER(10),
				correct BOOL,
				answer VARCHAR (255),
				number INTEGER(10),
				PRIMARY KEY  (id) )
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');
	
	$wpdb->insert($table_name, array('question_id'=>1, 'correct'=>1, 'answer'=>"Everest", 'number'=>1));
	$wpdb->insert($table_name, array('question_id'=>2, 'correct'=>1, 'answer'=>"Moscow", 'number'=>1));
	$wpdb->insert($table_name, array('question_id'=>"3", 'correct'=>0, 'answer'=>"Beijing", 'number'=>1));
	$wpdb->insert($table_name, array('question_id'=>"4", 'correct'=>0, 'answer'=>"Atlantic", 'number'=>1));
	$wpdb->insert($table_name, array('question_id'=>"4", 'correct'=>0, 'answer'=>"Indian", 'number'=>3));
	$wpdb->insert($table_name, array('question_id'=>"3", 'correct'=>1, 'answer'=>"Tokio", 'number'=>2));
	$wpdb->insert($table_name, array('question_id'=>"4", 'correct'=>1, 'answer'=>"Pacific", 'number'=>2));
	$wpdb->insert($table_name, array('question_id'=>"5", 'correct'=>1, 'answer'=>"Washington", 'number'=>1));
	}	

	$table_name="ssquiz_settings";
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . $table_name . '"') != $table_name ){
		$wpdb->get_results('CREATE TABLE ' . $table_name . '( 
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				name VARCHAR (255),
				value MEDIUMTEXT,
				PRIMARY KEY  (id) )
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');
	
		$wpdb->insert($table_name, array('name'=>"start_screen", 'value'=>"<h2 align='center'>Let's Start the quiz  \"%%TITLE%%\"!</h2><br /><em>Something about this quiz: </em>%%DESCRIPTION%%<br />You will be asked %%QUESTIONS%% questions"));

		$wpdb->insert($table_name, array('name'=>"result_screen", 'value'=>"<h2 class=\"intro\">Quiz is finished! Your correctly answered to %%PERCENT%%% of  %%TOTAL%% questions.</h2><br>"));
		
		$wpdb->insert($table_name, array('name'=>"email_screen", 'value'=>"You've just done quiz. You correctly answered to %%PERCENT%%% of  %%TOTAL%% questions."));
	
		$wpdb->insert($table_name, array('name'=>"ssteachers_template", 'value'=>"User %%NAME%% correctly answered to %%CORRECT%% of %%TOTAL%% questions. His email: <%%EMAIL%%>."));
	}	

	$table_name="ssquiz_users";
	
	//quiz VARCHAR (255) - not good
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . $table_name . '"') != $table_name ){
		$wpdb->get_results('CREATE TABLE ' . $table_name . '( 
				id INTEGER(10) UNSIGNED AUTO_INCREMENT,
				user_name VARCHAR (255),
				user_email VARCHAR (255),
				answered INTEGER(10),
				correct INTEGER(10),
				quiz VARCHAR (255), 
				date_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id) )
				DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');
	
	
	$wpdb->insert($table_name, array('user_name'=>"test", 'user_email'=>"test@test.com", 'answered'=>4, 'correct'=>2, 'quiz'=>'Test quiz'));
	}
	
	// updates 1.06
	$test_query = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='ssteachers_email'");
	if($test_query==NULL)
		$wpdb->query('INSERT INTO ssquiz_settings (name, value) VALUES ("ssteachers_email", ""), ("should_recieve", ""), ("user_recieve", "");');
		
	// update 1.07
		//$users_header = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='users_header'");
		//$teachers_header = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='teachers_header'");
	$test_query = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='users_header'");
	if($test_query==NULL)
		$wpdb->query('INSERT INTO ssquiz_settings (name, value) VALUES ("users_header", "Quiz is done"), ("teachers_header", "Quiz is done");');
}

function ssquiz_uninstall(){
	global $wpdb;
	$wpdb->query( "DROP TABLE ssquiz_questions");
	$wpdb->query( "DROP TABLE ssquiz_answers");
	$wpdb->query( "DROP TABLE ssquiz_users");
	$wpdb->query( "DROP TABLE ssquiz_settings");
	$wpdb->query( "DROP TABLE ssquiz_quizzes");
}

add_action('admin_menu', 'quiz_plugin_menu');
add_action('init', 'ssquiz__styles');
add_action('wp_print_scripts', 'ssquiz_scripts');

register_activation_hook(__FILE__, 'ssquiz_activate');
register_uninstall_hook(__FILE__, 'ssquiz_uninstall');

//-----------------

require_once("client-side.php");
require_once("admin-side.php");
add_shortcode( 'ssquiz', 'quiz_body_creater' );

//Localisation
function ssquiz_init() {
 $plugin_dir = basename( dirname( __FILE__ ) ) . '/languages';
 load_plugin_textdomain( 'ssquiz', false, $plugin_dir );
}
add_action('plugins_loaded', 'ssquiz_init');

?>