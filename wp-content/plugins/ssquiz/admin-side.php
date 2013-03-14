<?php

function users_page(){
	global $wpdb;
	$wpdb->show_errors();
	
	if($_POST['delete_them_all']=='Y'){
		if(!current_user_can('edit_pages'))
			die("current user doesn't have enough capabilities");
		if (!wp_verify_nonce($_POST['_wpnonce'], 'delete_them_all-nonce') ) 
			die("Security check");
		$wpdb->query("DELETE FROM ssquiz_users");
	}
	?>
	<div class="wrap">
		<h2><?php echo __("Quiz's Users", 'ssquiz') ?></h2>
		<form action="" method="post" id="delete-users-form" style="width:90%; text-align:right;">
			<input type="hidden" name="delete_them_all" value="Y" />
			<?php wp_nonce_field('delete_them_all-nonce'); ?>
    		<p><input type="submit" name="delete-users-submit" value="<?php echo __("Clear this list", 'ssquiz') ?>" class="delete-users-submit" /></p>
		</form>
		<div class="table_ssquiz">
		<table>
			<tr>
				<td><?php echo __("Name", 'ssquiz') ?></td>
				<td><?php echo __("E-Mail", 'ssquiz') ?></td>
               	<td><?php echo __("Quiz", 'ssquiz') ?></td> 
				<td><?php echo __("Answered", 'ssquiz') ?></td>
				<td><?php echo __("Correct", 'ssquiz') ?></td>
				<td><?php echo __("Time", 'ssquiz') ?></td>
				<td></td>
			</tr>
    <?php
	$users = $wpdb->get_results( "SELECT id, UNIX_TIMESTAMP(`date_stamp`) AS date, user_name, user_email, answered, correct, quiz FROM ssquiz_users ORDER BY date_stamp DESC LIMIT 50");
	foreach($users as $user) {
		$user=get_object_vars($user);
		$quiz_t=substr(stripslashes(esc_attr($user['quiz'])), 0, 20);
		if(strlen(stripslashes(esc_attr($user['quiz'])))>20)
			$quiz_t.="...";
		?>
		<tr id="<?php echo $user['id']; ?>">
			<td> <?php echo $user['user_name'] ?> </td>
			<td> <?php echo $user['user_email']?></td>
            <td> <?php echo $quiz_t ?></td>
			<td> <?php echo $user['answered']?></td>
			<td> <?php echo $user['correct']?></td>
			<td> <?php echo date("F j, Y, g:i a",$user['date'])?></td>
			<td><input type="submit" value="delete" class="delete_user" id="<?php echo $user['id']; ?>" /></td>
		</tr>
        <?php
	}
	?>
	</table>
	</div>
	</div>
	
    <?php
	get_ssfooter();
}

function draw_question_table(){
	global $wpdb;
	$wpdb->show_errors(); 
	
	// add/edit question
	if($_POST['hidden']=='Y'){
		if(!current_user_can('edit_pages'))
			die("current user doesn't have enough capabilities");
		if (!wp_verify_nonce($_POST['_wpnonce'], 'add_question-nonce') ) 
			die("Security check");
		$meta = "meta"; // meta information about question
		$quest = stripslashes(esc_attr($_POST['question']));
		$quiz = $_POST['quiz_id-hidden'];
		$q_id = $_POST['question_id'];
		
		if($_POST['answer'][0]==''){
			die(__('ERROR:You should write answer', 'ssquiz'));
		}
		
		if($quest==''){
			die(__('ERROR:You should write question', 'ssquiz'));
		}

		if(!isset($_POST['quiz_id-hidden'])){	// not quiz choosen
			die(__("No Quiz selected", 'ssquiz'));
		}

		$q = $wpdb->get_var("SELECT MAX(number) FROM ssquiz_questions WHERE quiz_id='".$quiz."' AND number!=99999");
		$q++;
		
		if($_POST['replace']=='Y'){
			$q=$wpdb->get_var("SELECT number FROM ssquiz_questions WHERE id='".$q_id."'");
			if($q<1)
				die(__("Error while replacing", 'ssquiz'));
			$wpdb->query("DELETE FROM ssquiz_questions WHERE id = '" . $q_id . "'");
			$wpdb->query("DELETE FROM ssquiz_answers WHERE question_id = '". $q_id ."'");
		}
		
		$wpdb->insert('ssquiz_questions', array('quiz_id'=>$quiz, 'number'=>$q, 'question'=>$quest, 'type'=>$meta), array('%d', '%d','%s','%s'));
		$lastid = $wpdb->insert_id;
		//$wpdb->insert('ssquiz_answers', array('question_id'=>$lastid, 'answer'=>$_POST['answer'], 'correct'=>1), array('%d', '%s', '%d'));

		$number=1;
		foreach($_POST['answer'] as $answer){
			if(trim($answer)=='')
				continue;
			$answer=stripslashes(esc_attr(strip_tags($answer)));
			$correct=isset($_POST['correct'][$number])?1:0;
			$wpdb->insert('ssquiz_answers', array('question_id'=>$lastid, 'answer'=>$answer,  'correct'=>$correct, 'number'=>$number), array('%d', '%s', '%d', '%d'));
			$number++;
		}
	}
	
	if($_POST['add_quiz-hidden']=='Y'){
		if(!current_user_can('edit_pages'))
			die("current user doesn't have enough capabilities");
		if (!wp_verify_nonce($_POST['_wpnonce'], 'add_quiz-nonce') ) 
			die("Security check");
		$title = strip_tags($_POST['quiz_title']);
		$description = strip_tags($_POST['quiz_description']);
		if($title==''){
			echo __('ERROR:You should write title', 'ssquiz'); 
			return;
		}
		if($description=='') {
			echo __('ERROR:You should write description', 'ssquiz');
			return;
		}
		$wpdb->insert('ssquiz_quizzes', array('title'=>$title, 'description'=>$description), array('%s', '%s'));
		$wpdb->insert('ssquiz_questions', array('number'=>99999, 'quiz_id'=>$wpdb->insert_id), array('%d', '%d')); //99999
		$wpdb->insert('ssquiz_answers', array('question_id'=>$wpdb->insert_id, 'answer'=>'UNDEFINED'), array('%d', '%d')); //99999
		$lastid_quiz = $wpdb->insert_id;
	}
	?>
    <div class="wrap">
    <h2><?php echo __("SSQuiz Manager", 'ssquiz') ?><a href="#" class="add-new-h2 add_quiz_a"><?php echo __("Add Quiz", 'ssquiz') ?></a></h2>
    
    <div class="sshidden">
    <form action="" method="post" id="add_quiz-form">
		<fieldset>
    		<label for="quiz_title"><?php echo __("New Quiz's title", 'ssquiz') ?>:</label> 
    		<br />
    		<input type="text" id="quiz_title" name="quiz_title" value="" size="40"/>
            <br  />
            <label for="quiz_description"><?php echo __("New Quiz's description", 'ssquiz') ?>:</label> 
            <br  />
            <textarea id="quiz_description" name="quiz_description" style="width:335px"></textarea>
            <input type="hidden" name="add_quiz-hidden" value="Y" />
            <?php wp_nonce_field('add_quiz-nonce'); ?>
    		<p><input type="submit" name="add_quiz-submit" value="<?php echo __("Add Quiz", 'ssquiz') ?>" class="add_quiz-submit" /></p>
            <br  />
    	</fieldset>
    </form>
    </div>
    
        <strong><label><?php echo __("View Quiz", 'ssquiz') ?>:</label></strong>
    <select name="select_quiz" class="select_quiz" id="select_quiz">
   	 <option value="-1"><?php echo __("All", 'ssquiz') ?></option>
    <?php
		$get_quizzes = $wpdb->get_results( "SELECT id, title FROM ssquiz_quizzes" );
		foreach($get_quizzes as $quiz) {
			$quiz=get_object_vars($quiz);
			$total_questions=$wpdb->get_var("SELECT COUNT(*) FROM ssquiz_questions WHERE quiz_id='" . $quiz['id'] . "' AND number!='99999'");
			echo '<option value="'.$quiz['id'].'"';
			if(get_transient("quiz_selected")==$quiz['id'])
				echo ' selected="selected"';
			echo '>'.$quiz['title']. '</option>';
		}
	?>
	</select>
    
    <div class="question_list">
    	<ul  id="sssortable">
        </ul>
    </div>
    </div>

    <?php
	get_ssfooter();
}

function draw_question_list(){
	global $wpdb;
	$wpdb->show_errors(); 
	?>
    <div class="not_table_ssquiz">
	<ul  id="sssortable">
		<li class="ui-state-default ui-state-disabled">
        	<table>
            <tr class="question_stat">
        		<td width="1px"> </td>
            	<td width="50px"><?php echo __("Quiz", 'ssquiz') ?></td>
                <td width="50px"><?php echo __("Number", 'ssquiz') ?></td>
				<td width="60%"><?php echo __("Question", 'ssquiz') ?></td>
                <td width="20%"><?php echo __("Correct Answer", 'ssquiz') ?></td>
				<td width="50px"><?php echo __("Answers", 'ssquiz') ?></td>
                <td width="53px"></td>
                <td width="53px"></td>
            </tr>
		</table>
    <?php
	$quiz_id=intval($_POST['quiz_id']);
	set_transient("quiz_selected", $quiz_id);
	if($quiz_id=="-1")
		$questions = $wpdb->get_results( "SELECT * FROM ssquiz_questions ORDER BY quiz_id, number" );
	else
		$questions = $wpdb->get_results( "SELECT * FROM ssquiz_questions WHERE quiz_id='".$_POST['quiz_id']."'" );
		
	foreach($questions as $question) {
		$question=get_object_vars($question);
		if($quiz_id!=$question['quiz_id']){
			$quiz_title = $wpdb->get_var("SELECT title FROM ssquiz_quizzes WHERE id='".$question['quiz_id']."'");
			$quiz_desc = $wpdb->get_var("SELECT description FROM ssquiz_quizzes WHERE id='".$question['quiz_id']."'");
			if(strlen($quiz_desc)>50)
				$quiz_desc=substr($quiz_desc,0,50).'...';
			$total_quest=$wpdb->get_var("SELECT COUNT(*) FROM ssquiz_questions WHERE quiz_id='" . $question['quiz_id'] . "' AND number!='99999'");	
			if ($quiz_id!=-1)
				echo '<li class="ui-state-default ui-state-disabled2 ssno-pointer" style="background-color:#DDD; text-align:left" data-i="'.$question['quiz_id'].'"> ID#'.$question['quiz_id']. ' <span class="left"><strong>'.$quiz_title.'</strong> (<em>'.$quiz_desc.'</em>)</span> <span align="right" class="right"><strong>'.__("Total questions", 'ssquiz').': <em>'.$total_quest.'</em></strong><input type="submit" value="'.__("delete", 'ssquiz').'" class="delete_quiz" id="' . $question['quiz_id'] . '" /><input type="submit" value="'.__("edit", 'ssquiz').'" class="edit_quiz" id="' . $question['quiz_id'] . '" /><input type="submit" value="'.__("add question", 'ssquiz').'" class="add_quiz" id="' . $question['quiz_id'] . '" /></span></li>';
			else 
				echo '<li class="ui-state-default ui-state-disabled ssno-pointer" style="background-color:#DDD; text-align:left" data-i="'.$question['quiz_id'].'"> ID#' .$question['quiz_id']. ' <span class="left"><strong>'.$quiz_title.'</strong> (<em>'.$quiz_desc.'</em>)</span> <span align="right" class="right"><strong>'.__("Total questions", 'ssquiz').': <em>'.$total_quest.'</em></strong><input type="submit" value="'.__("delete", 'ssquiz').'" class="delete_quiz" id="' . $question['quiz_id'] . '" /><input type="submit" value="'.__("edit", 'ssquiz').'" class="edit_quiz" id="' . $question['quiz_id'] . '" /><input type="submit" value="'.__("add question", 'ssquiz').'" class="add_quiz" id="' . $question['quiz_id'] . '" /></span></li>';
		}
		$quiz_id=$question['quiz_id'];
		if($question['number']==99999) continue; //!!		

		$amount = $wpdb->get_var( "SELECT COUNT(*) FROM ssquiz_answers WHERE question_id='" .  $question["id"] . "'");
		$correct = $wpdb->get_col( "SELECT answer FROM ssquiz_answers WHERE question_id='" .  $question["id"] . "' AND correct=1 ORDER BY number");
		$correct_arr="";
		foreach($correct as $cor)
			if($correct_arr=='')
				$correct_arr.=$cor;
			else
				$correct_arr.=', '.$cor;
		?>
		<li class="row_div ui-state-default" id="<?php echo $question['id'] ?>">
        	<table>
        	<tr class="question_stat">
				<td width="1px"><?php //echo $question['type'] ?></td>
				<td width="50px"><?php echo $question['quiz_id'] ?></td>
                <td width="50px"><?php echo $question['number'] ?></td>
				<td width="60%">
					<?php echo substr($question['question'], 0, 50);
					   if(strlen($question['question'])>50) echo "..."; ?>
				</td>
				<td width="20%"><font color="#00CC33">
					<?php echo substr($correct_arr, 0, 10);
					if(strlen($correct_arr)>10) echo "..."; ?>
				</font></td>
				<td width="50px"><?php echo $amount ?></td>
				<td width="53px"><input type="submit" value="<?php echo __("edit", 'ssquiz')?>" class="ssrow_edit" id="<?php echo $question["id"] ?>" /></td>
                <td width="53px"> <input type="submit" value="<?php echo __("delete", 'ssquiz')?>" class="ssrow_delete" id="<?php echo $question["id"] ?>" /></td>
             </tr>
            </table>
        </li>
        <?php
	}
	echo '</ul>';
	echo '</div>';
	die();
}

function ssdelete_user(){
		if(!current_user_can('edit_pages'))
			die("current user doesn't have enough capabilities");
		global $wpdb;
		$wpdb->show_errors();
		$id = intval($_POST['user_id']);
		$wpdb->query("DELETE FROM ssquiz_users WHERE id = '" . $id . "'");
		die('delete SUCCESSED');
}

function ssdelete_answer($id) {
		if(!current_user_can('edit_pages'))
			die("current user doesn't have enough capabilities");
		global $wpdb;
		$wpdb->show_errors();
		$id = intval($_POST['question_id']);
		$quiz_id = $wpdb->get_var("SELECT quiz_id FROM ssquiz_questions WHERE id = '". $id ."'");
		$pos = $wpdb->get_var("SELECT number FROM ssquiz_questions WHERE id = '". $id ."'");
		$wpdb->query("DELETE FROM ssquiz_questions WHERE id = '" . $id . "'");
		$wpdb->query("DELETE FROM ssquiz_answers WHERE question_id = '". $id ."'");
		$wpdb->get_results("UPDATE ssquiz_questions SET number = number-1 WHERE quiz_id = '".$quiz_id."' AND number>'".$pos."' AND number!='99999'");
		die('delete SUCCESSED');
	}

function template_creator(){
		if(!current_user_can('edit_pages'))
			die("current user doesn't have enough capabilities");
		global $wpdb;
		$wpdb->show_errors(); 
		$start = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='start_screen'");
		$result = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='result_screen'");
		$email = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='email_screen'");
		
		$user_recieve = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='user_recieve'");
		$teachers_template = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='ssteachers_template'");
		$teachers_email = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='ssteachers_email'");
		$teacher_should_recieve = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='should_recieve'");
		
		$users_header = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='users_header'");
		$teachers_header = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='teachers_header'");
		?>
       <div class="wrap">
	<h2><?php echo __("Quiz Templates", 'ssquiz')?></h2>
    <form action="" id="sswelcome-form" class="ssscreen_form">
    	<h3><label for="sswelcome"><?php echo __("Welcome Screen", 'ssquiz')?>: </label></h3>
    	
    	<?php wp_editor(stripslashes($start), "sswelcome"); ?>
    	
        <!--<textarea maxlength="1000" id="sswelcome" name="sswelcome" style="height:200px; width:50%"><?php //echo stripslashes(esc_attr($start)) ?></textarea> -->
        
        <table class="ssscreen">
        	<tr><td>%%TITLE%%</td><td><?php echo __("Title of the quiz", 'ssquiz')?></td></tr>
            <tr><td>%%DESCRIPTION%%</td><td><?php echo __("Description of the quiz", 'ssquiz')?></td></tr>
        	<tr><td>%%QUESTIONS%%</td><td><?php echo __("Total number of questions", 'ssquiz')?></td></tr>
        </table>
		<p id="sssubmit_but_welcome">
        <input type="submit" id="sssubmit_welcome" value="<?php echo __("Submit", 'ssquiz')?>"/></p>
	</form>
    <hr />
    <br />
    <form action="" id="ssresult-form" class="ssscreen_form">
    	<h3><label for="ssresult"><?php echo __("Result Screen", 'ssquiz')?>: </label></h3>
    	
    	<?php wp_editor(stripslashes($result), "ssresult"); ?>
    	
        <!--<textarea maxlength="1000" id="ssresult" name="ssresult" style="height:200px; width:50%"><?php //echo stripslashes(esc_attr($result))?> </textarea> -->
        <table class="ssscreen">
		<tr><td>%%NAME%%</td><td><?php echo __("User's name", 'ssquiz')?></td></tr>
		<tr><td>%%NUMBER%%</td><td><?php echo __("Quiz's ID", 'ssquiz')?></td></tr>
        	<tr><td>%%TITLE%%</td><td><?php echo __("Title of the quiz", 'ssquiz')?></td></tr>
            <tr><td>%%DESCRIPTION%%</td><td><?php echo __("Description of the quiz", 'ssquiz')?></td></tr>
        	<tr><td>%%TOTAL%%</td><td><?php echo __("Number of answered questions", 'ssquiz')?></td></tr>
        	<tr><td>%%CORRECT%%</td> <td><?php echo __("Number of correct answers", 'ssquiz')?></td></tr>
            <tr><td>%%PERCENT%%</td> <td><?php echo __("Percent of correct answered over total questions", 'ssquiz')?></td></tr>
        </table>
		<p id="sssubmit_but_result">
        <input type="submit" id="sssubmit_result"  value="<?php echo __("Submit", 'ssquiz')?>"/></p>
	</form>
    <hr />
    <br />
    <form action="" id="ssemail-form" class="ssscreen_form">
    	<h3><label for="ssusers_header"><?php echo __("Email Template to user", 'ssquiz')?>: </label></h3>
		<label for="ssusers_email"><?php echo __("Subject", 'ssquiz')?>: </label>
		<input type="text" style="margin-bottom: 6px;" id="ssusers_header" name="ssusers_header" size="40" value="<?php echo stripslashes(esc_attr($users_header)); ?>"/>
		<br />
        <textarea maxlength="1000" id="ssemail" name="ssemail" style="height:200px; width:50%"><?php echo stripslashes(esc_attr($email)); ?></textarea>
		<br  />
		<label for="user_recieve"><?php echo __("User will receive email?", 'ssquiz')?></label>
		<input type="checkbox" id="user_recieve" name="user_recieve" <?php echo $user_recieve=='checked'? 'checked' : '' ?>/>
        <table class="ssscreen">
        	<tr><td>%%TITLE%%</td><td><?php echo __("Title of the quiz", 'ssquiz')?></td></tr>
            <tr><td>%%DESCRIPTION%%</td><td><?php echo __("Description of the quiz", 'ssquiz')?></td></tr>
        	<tr><td>%%TOTAL%%</td><td><?php echo __("Number of answered", 'ssquiz')?></td></tr>
        	<tr><td>%%CORRECT%%</td> <td><?php echo __("Number of correct answers", 'ssquiz')?></td></tr>
            <tr><td>%%PERCENT%%</td> <td><?php echo __("Percent of correct answered over total questions", 'ssquiz')?></td></tr>
        </table>
		<p id="sssubmit_but_email">
        <input type="submit" id="sssubmit_email"  value="Submit"/></p>
	</form>

    <hr />
    <br />
    <form action="" id="ssteachers-form" class="ssscreen_form">
    	<h3><label for="ssteachers_template"><?php echo __("Teacher's Template", 'ssquiz')?>: </label></h3>
		<label for="ssteachers_email"><?php echo __("Subject", 'ssquiz')?>: </label>
		<input type="text" style="margin-bottom: 6px;" id="ssteachers_header" name="ssteachers_header" size="40" value="<?php echo stripslashes(esc_attr($teachers_header)); ?>"/>
		<br />
        <textarea maxlength="1000" id="ssteachers_template" name="ssteachers_template" style="height:200px; width:50%"><?php echo stripslashes(esc_attr($teachers_template)); ?></textarea>
		<br />
		<label for="ssteachers_email"><?php echo __("Teacher's email", 'ssquiz')?></label>
		<input type="text" id="ssteachers_email" name="ssteachers_email" size="40" value="<?php echo stripslashes(esc_attr($teachers_email)); ?>"/>
		<label for="should_recieve"><?php echo __("Teacher will receive email?", 'ssquiz')?></label>
		<input type="checkbox" id="should_recieve" name="should_recieve" <?php echo $teacher_should_recieve=='checked'? 'checked' : '' ?>/>
        <table class="ssscreen">
        	<tr><td>%%NAME%%</td><td><?php echo __("User's name", 'ssquiz')?></td></tr>
            <tr><td>%%EMAIL%%</td><td><?php echo __("User's email", 'ssquiz')?></td></tr>
        	<tr><td>%%TITLE%%</td><td><?php echo __("Title of the quiz", 'ssquiz')?></td></tr>
            <tr><td>%%DESCRIPTION%%</td><td><?php echo __("Description of the quiz", 'ssquiz')?></td></tr>
        	<tr><td>%%TOTAL%%</td><td><?php echo __("Number of answered", 'ssquiz')?></td></tr>
        	<tr><td>%%CORRECT%%</td> <td><?php echo __("Number of correct answers", 'ssquiz')?></td></tr>
            <tr><td>%%PERCENT%%</td> <td><?php echo __("Percent of correct answered over total questions", 'ssquiz')?></td></tr>
        </table>
		<p id="sssubmit_but_teacher">
        <input type="submit" id="sssubmit_teacher"  value="<?php echo __("Submit", 'ssquiz')?>"/></p>
	</form>

	</div>

    <?php
	get_ssfooter();
}

function refresh_settings() {
	if(!current_user_can('edit_pages'))
		die("current user doesn't have enough capabilities");
	global $wpdb;
	$wpdb->show_errors(); 
	$temp=$_POST["data"]; //!
	if($_POST["type"]=="ssresult"){
		$wpdb->update( 'ssquiz_settings', array( 'value' => $temp), array( 'name' => 'result_screen'), array( '%s'));
	} 
	else if($_POST["type"]=="sswelcome") {
		$wpdb->update( 'ssquiz_settings', array( 'value' => $temp), array( 'name' => 'start_screen'), array( '%s'));
	}
	else if($_POST["type"]=="user_settings") {
		$wpdb->update( 'ssquiz_settings', array( 'value' => $temp), array( 'name' => 'email_screen'), array( '%s'));
		$wpdb->update( 'ssquiz_settings', array( 'value' => $_POST["user_recieve"]), array( 'name' => 'user_recieve'), array( '%s'));
		$wpdb->update( 'ssquiz_settings', array( 'value' => $_POST["ssusers_header"]), array( 'name' => 'users_header'), array( '%s'));
	}
	else if($_POST["type"]=="ssteachers_template") {
		$wpdb->update( 'ssquiz_settings', array( 'value' => $temp), array( 'name' => 'ssteachers_template'), array( '%s'));
		$wpdb->update( 'ssquiz_settings', array( 'value' => $_POST["ssteachers_email"]), array( 'name' => 'ssteachers_email'), array( '%s'));
		$wpdb->update( 'ssquiz_settings', array( 'value' => $_POST["should_recieve"]), array( 'name' => 'should_recieve'), array( '%s'));
		$wpdb->update( 'ssquiz_settings', array( 'value' => $_POST["ssteachers_header"]), array( 'name' => 'teachers_header'), array( '%s'));
	}
	else
		die("UNKNOWN REQUEST");

	die("DONE");
}

function ssupdate_questions(){
	if(!current_user_can('edit_pages'))
		die("current user doesn't have enough capabilities");
	global $wpdb;
	$wpdb->show_errors(); 
	$quiz_old=intval($_POST['quiz_old']);
	$quiz_id=intval($_POST['quiz_id']);
	$pos_old=intval($_POST['pos_old']);
	$question_id=intval($_POST['question_id']);
	$question_pos=intval($_POST['question_pos']);
	$wpdb->get_results("UPDATE ssquiz_questions SET number = number-1 WHERE quiz_id = '".$quiz_old."' AND number>'".$pos_old."' AND number!='99999'"); //collapse
	$wpdb->get_results("UPDATE ssquiz_questions SET number = number+1 WHERE quiz_id = '".$quiz_id."' AND number>='".$question_pos."' AND number!='99999'"); //expand
	$wpdb->get_results("UPDATE ssquiz_questions SET number = '".$question_pos."', quiz_id = '".$quiz_id."' WHERE id = '".$question_id."'"); //change
	die('OK');
}

function ssedit_question(){
	if(!current_user_can('edit_pages'))
		die("current user doesn't have enough capabilities");
	$question_id=intval($_POST['question_id']);
	global $wpdb;
	$wpdb->show_errors(); 
	$answers = $wpdb->get_results( "SELECT * FROM ssquiz_answers WHERE question_id='" . $question_id . "' ORDER BY number");
	$question = $wpdb->get_row( "SELECT * FROM ssquiz_questions WHERE id='" . $question_id . "'");
	$question= get_object_vars($question);
	?>
		<form action="" method="post" id="first_question-options-form">
        <label for="quiz_id-hidden">Quiz:</label>
        <select name="quiz_id-hidden">
        	<?php
			$get_quizzes = $wpdb->get_results("SELECT id, title FROM ssquiz_quizzes");
			foreach($get_quizzes as $quiz) {
				$quiz=get_object_vars($quiz);
				$total_questions=$wpdb->get_var("SELECT COUNT(*) FROM ssquiz_questions WHERE quiz_id='" . $quiz['id'] . "' AND number!='99999'");
				echo '<option value="'.$quiz['id'].'"';
				if($question['quiz_id']==$quiz['id'])  echo ' selected="selected"';
				echo '>'.$quiz['title']. '</option>';
			}
			?>
        </select>
        <br />
        <label for="question"><b><?php echo __("Question", 'ssquiz')?>:</b></label>
        <br />
    	<textarea id="question"  style="min-width:500px; min-height:150px" name="question"><?php echo $question['question']; ?></textarea>
    	<br />
       <a href="#" id="upload_media"><?php echo __("Insert Media", 'ssquiz')?></a>
       
       <?php
	   foreach($answers as $answer){
		   if($answer==end($answers))
		   		$last='true';
		   $answer=get_object_vars($answer);
       		if($last=='true'){
            	echo '<h3 class="last_ans">';
            }
			else
				echo '<h3 class="correct_ans">';
		?>	
       		<label for="answer"><?php echo __("Answer is", 'ssquiz')?>:</label>
    		<input type="text" id="answer[]" name="answer[]" value="<?php echo $answer['answer']?>" size="40"/>
    		<label for="correct"><em><?php echo __("correct", 'ssquiz')?></em></label>  
    		<input type="checkbox" name="correct[<?php echo $answer['number'] ?>]" <?php echo $answer['correct']=='1'? 'checked' : '' ?>/>
    	<?php
			if($last=='true') {
				echo '<input type="submit" class="add_but" value="'.__("Add answer", 'ssquiz').'" />';	
			}
			echo '</h3>';
		}
		?> 
	    
		<p id="submit_but">
        <input type="hidden" name="hidden" value="Y" />
        <input type="hidden" name="replace" value="Y" />
        <?php wp_nonce_field('add_question-nonce'); ?>
        <input type="hidden" name="question_id" value="<?php echo $question_id ?>" />
    	<input type="submit" name="submit" value="<?php echo __("Save", 'ssquiz')?>" class="sssubmit_question"/></p>
    	</form>
		<?php
	die();
}

function ssdelete_quiz(){
	if(!current_user_can('edit_pages'))
		die("current user doesn't have enough capabilities");
	global $wpdb;
	$wpdb->show_errors(); 
	$quiz_id=intval($_POST['quiz_id']);
	//!
	$wpdb->query("DELETE ssquiz_quizzes, ssquiz_questions, ssquiz_answers FROM ssquiz_quizzes INNER JOIN ssquiz_questions INNER JOIN ssquiz_answers WHERE ssquiz_quizzes.id = '" . $quiz_id . "' AND quiz_id=ssquiz_quizzes.id AND question_id=ssquiz_questions.id");
	//$wpdb->query("DELETE FROM ssquiz_questions WHERE question_id = '". $_POST['question_id'] ."'");
	//$wpdb->query("DELETE FROM ssquiz_answers WHERE question_id = '". $_POST['question_id'] ."'");
	die('quiz deleted');
}

function ssedit_quiz(){
	if(!current_user_can('edit_pages'))
		die("current user doesn't have enough capabilities");
	global $wpdb;
	$wpdb->show_errors(); 
	if(isset($_POST['edit'])) {
		$quiz_id = intval($_POST['question_id']);
		$title=stripslashes(esc_attr(strip_tags($_POST['title'])));
		$description=stripslashes(esc_attr(strip_tags($_POST['description'])));
		$wpdb->get_results("UPDATE ssquiz_quizzes SET title = '".$title."', description = '".$description."' WHERE id = '".$quiz_id."'");
		die("Done");
	}
	
	$quiz_id=intval($_POST['quiz_id']);
	$quiz_title = $wpdb->get_var("SELECT title FROM ssquiz_quizzes WHERE id='".$quiz_id."'");
	$quiz_desc = $wpdb->get_var("SELECT description FROM ssquiz_quizzes WHERE id='".$quiz_id."'");
	?>
	<form action="" method="post" id="update_quiz-form">
		<fieldset>
        <br />
    		<label for="quiz_title"><?php echo __("Quiz's title", 'ssquiz')?>:</label> 
            <br />
    		<input type="text" id="quiz_title" name="quiz_title" value="<?php echo $quiz_title ?>" style="width:335px; margin-left:0px"/>
            <br  />
            <label for="quiz_description"><?php echo __("Quiz's description", 'ssquiz')?>:</label> 
            <br  />
            <textarea id="quiz_description" name="quiz_description" style="width:335px"><?php echo $quiz_desc ?></textarea>
    		<p><input type="submit" name="update_quiz-submit" value="OK" class="update_quiz-submit"  style="margin-left:0px"/></p>
            <br  />
    	</fieldset>
    </form>
    <?php
	die();
}

function ssadd_quiz(){
	if(!current_user_can('edit_pages'))
		die("current user doesn't have enough capabilities");
	global $wpdb;
	$wpdb->show_errors(); 
	$quiz_id=intval($_POST['quiz_id']);

	?>
		<form action="" method="post" id="first_question-options-form">
		<h3><b><?php echo __("add question", 'ssquiz')?>:</b></h3>
    	<textarea id="question"  style="min-width:500px; min-height:150px" name="question"></textarea>
    	<br />
       <a href="#" id="upload_media"><?php echo __("Insert Media", 'ssquiz')?></a>

       <!-- --------------->    
       <h3 class="correct_ans"><label for="answer"><?php echo __("Answer is", 'ssquiz')?>:</label>

    <input type="text" id="answer[]" name="answer[]" value="" size="40"/>
    <label for="correct"><em><?php echo __("correct", 'ssquiz')?></em></label>  
    <input type="checkbox" name="correct[1]" /></h3>
	<p id="submit_but">
    <input type="hidden" name="hidden" value="Y" />
    <input type="hidden" name="quiz_id-hidden" value="<?php echo $quiz_id ?>" />
    <?php wp_nonce_field('add_question-nonce'); ?>
    <input type="submit" name="submit" value="<?php echo __("add question", 'ssquiz')?>" class="sssubmit_question"/></p>
    </form>
    <?php
	die();
}

function get_ssfooter(){
	?>
	<div class="prom_footer">
		<p><?php echo __("If you have questions, suggestions or want to know about my upcoming projects, visit", 'ssquiz')?> <a href="http://www.100vadim.com" alt="Developer's home page">100vadim.com</a>
		</p>
	</div>
	<?php
}

add_action('wp_ajax_ssadd_quiz', 'ssadd_quiz');
add_action('wp_ajax_nopriv_ssadd_quiz', 'ssadd_quiz');
add_action('wp_ajax_ssedit_quiz', 'ssedit_quiz');
add_action('wp_ajax_nopriv_ssedit_quiz', 'ssedit_quiz');
add_action('wp_ajax_ssdelete_quiz', 'ssdelete_quiz');
add_action('wp_ajax_nopriv_ssdelete_quiz', 'ssdelete_quiz');
add_action('wp_ajax_ssedit_question', 'ssedit_question');
add_action('wp_ajax_nopriv_ssedit_question', 'ssedit_question');
add_action('wp_ajax_ssupdate_questions', 'ssupdate_questions');
add_action('wp_ajax_nopriv_ssupdate_questions', 'ssupdate_questions');
add_action('wp_ajax_draw_question', 'draw_question_list');
add_action('wp_ajax_nopriv_draw_question', 'draw_question_list');
add_action('wp_ajax_ssdelete', 'ssdelete_answer');
add_action('wp_ajax_nopriv_ssdelete', 'ssdelete_answer');
add_action('wp_ajax_refresh_settings', 'refresh_settings');
add_action('wp_ajax_nopriv_refresh_settings', 'refresh_settings');
add_action('wp_ajax_ssdelete_user', 'ssdelete_user');
add_action('wp_ajax_nopriv_ssdelete_user', 'ssdelete_user');
?>