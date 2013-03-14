<?php

function quiz_body_creater($atts){
	global $wpdb;
	$wpdb->show_errors(); 
	
	ob_start();
	
	if($atts["id"]<1) {
		echo "<h2>".__("Quiz is not selected! Please, read readme.txt", 'ssquiz')."</h2>";
		$output_string=ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
	$session=rand();
	$quiz_title = $wpdb->get_var("SELECT title FROM ssquiz_quizzes WHERE id='" . $atts["id"] . "'");
	$quiz_description = $wpdb->get_var("SELECT description FROM ssquiz_quizzes WHERE id='" . $atts["id"] . "'");
	$total = $wpdb->get_var("SELECT COUNT(*) FROM ssquiz_questions WHERE quiz_id='" . $atts["id"] . "' AND number!=99999");
	if(in_array("not_correct", $atts)) set_transient('not_correct_answers'.$session, 'true', 9999);
	if(in_array("all", $atts)) set_transient('all_questions'.$session, 'true', 9999);
	if(in_array("qrandom", $atts)) set_transient('qrandom'.$session, 'true', 9999);
	if(in_array("arandom", $atts)) set_transient('arandom'.$session, 'true', 9999);
	if(in_array("email", $atts)) set_transient('email'.$session, 'true', 9999);
	if(in_array("name", $atts)) set_transient('name'.$session, 'true', 9999);
	if($atts["timer"]>0) set_transient('timer'.$session, 'true', 9999);
	set_transient('quiz_id'.$session, $atts["id"], 9999);
	set_transient('quiz_title'.$session, $quiz_title, 9999);
	set_transient('quiz_description'.$session, $quiz_description, 9999);
	set_transient('questions_counter'.$session, -1, 9999);
	set_transient('questions_right'.$session, 0, 9999);
	
	set_transient('total_questions_in_quiz'.$session, $total, 9999);
	if(intval($atts['qmax'])>0){
		set_transient('total_questions'.$session, intval($atts['qmax']), 9999);
		$total = intval($atts['qmax']);
	}
	else
		set_transient('total_questions'.$session, $total, 9999);

	set_transient('answer_sheet'.$session, array());
	
	$welcome = $wpdb->get_var("SELECT value FROM ssquiz_settings WHERE name='start_screen'");
	$welcome = str_replace("%%TITLE%%", $quiz_title, $welcome);
	$welcome = str_replace("%%DESCRIPTION%%", $quiz_description, $welcome);
	$welcome = str_replace("%%QUESTIONS%%", $total, $welcome);
	?>
    <div class="quiz" id="<?php echo $session;?>">
		<div class="ssquiz_header">
			<div>
				<h2 align='center'><?php echo $quiz_title; ?></h2>
			</div>
			<?php 
			if(get_transient("timer".$session)) {
				echo '<script>seconds_left='.$atts['timer'].';</script>';
				echo '<div id="timer_icon"></div><div class="sstimer"><span>'.__("Time left", 'ssquiz').': </span><span id="sstimer"></span></div>';
			}
			?>
		</div> <!-- end of ssquiz_header -->
		
		<?php
		// does user have chance to take this quiz?
		if(in_array("one_chance", $atts)) {
			global $current_user;
			get_currentuserinfo();
			$attempts = $wpdb->get_var( "SELECT count(*) FROM ssquiz_users WHERE user_name='".$current_user->user_login."' AND quiz='".$quiz_title."'");
			if($attempts>0){
				echo "<h2 align='center'>".__("You already took this quiz", 'ssquiz')."</h2>";
				echo '</div>';
				$output_string=ob_get_contents();
				ob_end_clean();
				return $output_string;
			}
		}
		?>
		
		<div class="quiz_area">
			<?php echo stripslashes(html_entity_decode($welcome)); ?>
			<?php echo stripslashes(html_entity_decode($myrows)) ?>
			<br />
			<br />
			<?php 
			if(get_transient("name".$session)) {
				echo '<label for="user_name">'.__("Name", 'ssquiz').': </label>';
				echo '<input type="text" id="ssuser_name" name="user_name" value="" />';
				echo '<br />';
			}
			if(get_transient("email".$session)) {
				echo '<label for="email">'.__("E-Mail", 'ssquiz').': </label> ';
				echo '<input type="text"  id="ssemail" name="email" value="" />';
			} ?>
		</div> <!-- end of quiz_area -->
		
		<div class="sshistory"></div>
		
		<a href="#ssheader" class="start user_button" id="next_button"><?php echo __("Start", 'ssquiz') ?></a>
		<br  />
		<a href="#" class="exit_but user_button" id="exit_but" style="display:none"><?php echo __("Exit", 'ssquiz') ?></a>
    </div>
 	<?php
	
	$output_string=ob_get_contents();
	ob_end_clean();
	return $output_string;
}

function tag_replace($result, $session) {
	$temp_var=intval(get_transient('total_questions'.$session));
	if($temp_var==0) $temp_var=-1;
	$percent=intval(strval(100*intval(get_transient('questions_right'.$session))/$temp_var));
	
	$result=str_replace("%%TITLE%%", get_transient('quiz_title'.$session), $result);
	$result=str_replace("%%DESCRIPTION%%", get_transient('quiz_description'.$session), $result);
	$result=str_replace("%%TOTAL%%", get_transient('total_questions'.$session), $result);
	$result=str_replace("%%CORRECT%%", get_transient('questions_right'.$session), $result);
	$result=str_replace("%%PERCENT%%", $percent, $result);
	
	return $result;
}

function exit_quiz($session=0, $button_clicked=true){
		global $wpdb;
		$wpdb->show_errors(); 
		if(isset($_POST["session"]))
			$session=intval($_POST["session"]);
		$result = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='result_screen' LIMIT 1");
		echo '<script>document.getElementById("next_button").style.display="none";document.getElementById("exit_but").style.display="none";clearTimeout(timer1); </script>';
		if(isset($_POST['question_id']) && $button_clicked){
			next_question(true);
		}

		if(get_transient('not_correct_answers'.$session)==false){
			$answer_sheet=get_transient('answer_sheet'.$session);
			//echo '</br>';
			echo '<div class="sshistory_list">';
			foreach($answer_sheet as $name=>$value) {
				if($value["correct"])
					echo '<a href="#" class="ssanswer_history sshistory_number" style="color:green" id="'.$value["question_id"].'">'.$name.'</a> ';
				else
					echo '<a href="#" class="ssanswer_history sshistory_number" style="color:red" id="'.$value["question_id"].'">'.$name.'</a> ';
			}
			echo '</div>';
			//echo '</div>';
		}

		global $current_user;
		get_currentuserinfo();
		
		if(get_transient("name".$session))
			$users_name = get_transient("user_name".$session);
		else if(strlen($current_user->display_name)>3)
			$users_name = $current_user->display_name;
		else
			$users_name = "UNKNOWN";
	
		$result=str_replace("%%NAME%%", $users_name, $result);
		$result=str_replace("%%NUMBER%%", get_transient('quiz_id'.$session), $result);
		echo stripslashes(html_entity_decode(tag_replace($result, $session)));

		if(get_transient("email".$session))
			$users_email = get_transient("user_email".$session);
		else if(strlen($current_user->user_email)>5)
			$users_email = $current_user->user_email;
		else
			$users_email = "UNKNOWN";

		// API
		$temp_var=intval(get_transient('total_questions'.$session));
		if($temp_var==0) $temp_var=-1;
		$percent=intval(strval(100*intval(get_transient('questions_right'.$session))/$temp_var));
		do_action( 'ssquiz_finished', get_transient('quiz_id'.$session), $percent, get_transient('questions_right'.$session), get_transient('total_questions'.$session));

		//Sending email to user
		$test_query = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='user_recieve'");
		if($test_query=='checked') {
			if($users_email != "UNKNOWN") {
				$email = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='email_screen' LIMIT 1");
				$message = stripslashes(html_entity_decode(tag_replace($email, $session)));
				$header = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='users_header' LIMIT 1");
				wp_mail($users_email, $header, $message);
			}
		}

		//Sending email to teacher
		$test_query = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='should_recieve'");
		if($test_query=='checked') {
			$teachers_email = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='ssteachers_email'");
			if(strlen($teachers_email)>5) {
				$email = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='ssteachers_template' LIMIT 1");
				$message = stripslashes(html_entity_decode(tag_replace($email, $session)));
				$message=str_replace("%%NAME%%", $users_name, $message);
				$message=str_replace("%%EMAIL%%", $users_email, $message);
				$header = $wpdb->get_var( "SELECT value FROM ssquiz_settings WHERE name='teachers_header' LIMIT 1");
				wp_mail($teachers_email, $header, $message);
			}
		}

		if($users_name=="UNKNOWN") $users_name="";
		if($users_email=="UNKNOWN") $users_email="";
		$wpdb->insert("ssquiz_users", 
			array('user_name'=>$users_name, 
			'user_email'=>$users_email, 
			'answered'=>get_transient('questions_counter'.$session), 
			'correct'=>get_transient('questions_right'.$session),
			'quiz'=>get_transient('quiz_title'.$session)));
		die();
}

function draw_history($number, $session){
	global $wpdb;
	$wpdb->show_errors();
	
	$temp=get_transient('answer_sheet'.$session);
	
	$question = $wpdb->get_var("SELECT question FROM ssquiz_questions WHERE id=".intval($temp[$number]["question_id"]));
	$correct = $wpdb->get_col("SELECT answer FROM ssquiz_answers WHERE question_id='".intval($temp[$number]["question_id"])."' AND correct='1'");
	
	echo '<div class="question"><strong>'.__("Question", 'ssquiz').' #'.$number.':</strong><br  />'; 
	echo stripslashes(stripslashes(html_entity_decode($question))); 
	echo '</div><br />';
	
	echo '<font color="green">'.__("Correct answer", 'ssquiz').': '.implode(" ", $correct). '</font><br/>';
	if($temp[$number]["correct"]==false){
		if(strlen($temp[$number]["answer"])>0 && $temp[$number]["answer"]!='NA')
			echo '<font color="red">'.__("Your answer", 'ssquiz').': '.$temp[$number]["answer"]. '</font>';
		else
			echo "<font color='red'>".__("You didn't answer to this question.", 'ssquiz')."</font>";
	}
	else
		echo '<font color="green">'.__("You were right!", 'ssquiz').'</font>';
}

function view_answer() {
	$session=intval($_POST["session"]);
	$option=intval($_POST["option"]);

	if($option==-1)
		$number=get_transient("history_view".$session)-1;
	else if ($option==-2)
		$number=get_transient("history_view".$session)+1;
	else if ($option>0)
		$number=$option;
	 else
		die("Internal error 45");
	set_transient("history_view".$session, $number, 9999);
	draw_history($number, $session);
	echo '<br /><center>';
	if($number>1)
		echo '<a class="ssanswer_history prev"><<</a>';
	echo '<a class="ssback">'.__("Back", 'ssquiz').'</a>';
	if($number<get_transient('questions_counter'.$session))
		echo '<a class="ssanswer_history forw">>></a>';
	echo '</center>';
	die();
}

function do_all(){
		global $wpdb;
		$wpdb->show_errors();
		$session=intval($_POST["session"]);
		$temps=array();
		$answer_sheet=array();
		$counter=1;
		if($_POST["answers"]!=NULL && $_POST["answers"]!='NA'){
			foreach ($_POST["answers"] as $answer) {
				if(!isset($temps[$answer['name']])) $temps[$answer['name']]=array();
				array_push($temps[$answer['name']], $answer['value']);
			}
			foreach ($temps as $name=>$value) {
					$answer_sheet[$counter]["question_id"]=$name;
					$answer_sheet[$counter]["answer"]=implode("|", $value);
					$answer_sheet[$counter]["correct"]=false;
					$row = $wpdb->get_col("SELECT answer FROM ssquiz_answers WHERE question_id='". $name ."' AND correct='1'");
					if(array_map('strtolower',$row)==array_map('strtolower',$value)){
					//if($row==$value){ //case sensitive
						set_transient('questions_right'.$session, get_transient('questions_right'.$session)+1, 9999);
						$answer_sheet[$counter]["correct"]=true;
					}
					$counter++;
				}
		}
		set_transient('answer_sheet'.$session, $answer_sheet, 9999);
		exit_quiz($session);
		die();
}

function start_quiz(){
		$session=intval($_POST["session"]);
		$user_email=stripslashes(esc_attr(strip_tags($_POST["user_email"])));
		$user_name=stripslashes(esc_attr(strip_tags($_POST["user_name"])));
		set_transient('user_email'.$session, $user_email, 9999);
		set_transient('user_name'.$session, $user_name, 9999);
		echo 'TEST STARTED';
		die();
}

function all_questions($session){
		global $wpdb;
		$wpdb->show_errors();
		$questions = $wpdb->get_results("SELECT * FROM ssquiz_questions WHERE quiz_id='".get_transient('quiz_id'.$session)."' AND number!=99999 ORDER BY number");
		//set_transient('questions_counter'.$session, count($questions), 9999);
		$qconter=1;
		echo '<script>document.getElementById("exit_but").style.display="none";</script>';
		if ($questions== NULL){
			echo '<script>document.getElementById("next_button").style.display="none";</script>';
			die(__("There is no tests in quiz", 'ssquiz')." <strong>". get_transient('quiz_title'.$session)."</strong>");
		}
		if(get_transient('qrandom'.$session)=='true')
			shuffle($questions);
		$amount = get_transient('total_questions'.$session);
		foreach($questions as $question){
			if($qconter>$amount) 
				break;
			$question=get_object_vars($question);
			$answers = $wpdb->get_results( "SELECT * FROM ssquiz_answers WHERE question_id=" . $question['id'] . " ORDER BY number");
			$correct = $wpdb->get_var("SELECT COUNT(*) FROM ssquiz_answers WHERE question_id='".$question['id']."' AND correct='1'");
			
			echo '<div class="question_body">';
			echo '<div class="question"><strong>'.__("Question", 'ssquiz').' #' . $qconter++ . ':</strong><br  />';
			echo stripslashes(stripslashes(html_entity_decode($question['question']))); 
			//set_transient('questions_counter'.$session, get_transient('questions_counter'.$session)+1, 9999);
			echo '</div><br />';
        	//--------checking for multianswer test
			if(count($answers)>1) {
				if(get_transient('arandom'.$session)=='true') 
					shuffle($answers);
				set_transient('answer'.$session, "", 9999);
				foreach($answers as $answer) {
					$answer=get_object_vars($answer);
					if($correct>1)
						echo '<input type="checkbox" name="'.$question['id'].'" class="answer" value="'. $answer['answer'] .'">'. $answer['answer'] . '<br>';
					else
						echo '<input type = "radio" name="'.$question['id'].'" class="answer" value="'. $answer['answer'] .'">'. $answer['answer'] . '<br>';
					
					if($answer['correct']=='1')
						set_transient('answer'.$session, get_transient('answer'.$session)."|".$answer['answer'], 9999);
				}
				//dummy radio checked
				if($correct==1)
					echo '<input type="radio" checked name="'.$question['id'].'" class="answer" value="NA" style="display:none">';
			}
			else 
				echo '<input type="text" class="answer" name="'.$question['id'].'" value=""  />';

			echo '</div>';
			if($question!=end($questions))
				echo '<hr /><br />';
        }
        set_transient('questions_counter'.$session, $qconter-1, 9999);
		echo '<script>jQuery(document).ready(function($){$("#next_button").html("'.__("Finish", 'ssquiz').'");});</script>';
		die();
}

function next_question($last=false){
		global $wpdb;
		$wpdb->show_errors();
		$session=intval($_POST["session"]);

		if(get_transient('questions_counter'.$session)>=0){
			$temp_array=get_transient('answer_sheet'.$session);
			$temp_array[get_transient('questions_counter'.$session)+1]["correct"]=false;
			$temp2=explode("|", $_POST["answer"]);
			if(array_map('strtolower',get_transient('answer'.$session))==array_map('strtolower',$temp2)){
			//if(get_transient('answer'.$session)==$temp2){ // case sensitive
				set_transient('questions_right'.$session, get_transient('questions_right'.$session)+1, 9999);
				$temp_array[get_transient('questions_counter'.$session)+1]["correct"]=true;
			}

			$temp_array[get_transient('questions_counter'.$session)+1]["question_id"]=get_transient('question_id'.$session);
			$temp_array[get_transient('questions_counter'.$session)+1]["answer"]=$_POST["answer"];
			set_transient('answer_sheet'.$session, $temp_array, 9999);
		}else {
			// first time function called?
			echo '<script>jQuery(document).ready(function($){$("#next_button").html("'.__("Next", 'ssquiz').'"); $("#exit_but").show();});</script>';
		}

		set_transient('questions_counter'.$session, get_transient('questions_counter'.$session)+1, 9999);
		if(get_transient('questions_counter'.$session)>get_transient('total_questions'.$session)-1 || $last) {
			exit_quiz($session, false);
		} else if (get_transient('all_questions'.$session)=="true") {
			all_questions($session);
			return;
		} else {
			if(get_transient('qrandom'.$session)) {
				if(!get_transient('qr'.$session)) {
					$random = range(1, intval(get_transient('total_questions_in_quiz'.$session)));
					shuffle($random);
					array_splice($random, intval(get_transient('total_questions'.$session)));
					set_transient('qr'.$session, $random, 9999);
				} else {
					$random=get_transient('qr'.$session);
				}
				$question = $wpdb->get_row("SELECT * FROM ssquiz_questions WHERE quiz_id='".get_transient('quiz_id'.$session)."' AND number='". $random[get_transient('questions_counter'.$session)] . "'", ARRAY_A);
			} else
				$question = $wpdb->get_row("SELECT * FROM ssquiz_questions WHERE quiz_id='".get_transient('quiz_id'.$session)."' AND number='". (get_transient('questions_counter'.$session)+1) . "'", ARRAY_A);
		$answers = $wpdb->get_results("SELECT * FROM ssquiz_answers WHERE question_id='".$question['id']."' ORDER BY number");
		$correct = $wpdb->get_var("SELECT COUNT(*) FROM ssquiz_answers WHERE question_id='".$question['id']."' AND correct='1'");

		if ($question== NULL) {
			echo '<script>document.getElementById("next_button").style.display="none";document.getElementById("exit_but").style.display="none";</script>';
			die(__("There is no tests in quiz", 'ssquiz')." <strong>". get_transient('quiz_title'.$session)."</strong>");
		}

		// The last question?
		if(get_transient('questions_counter'.$session)==get_transient('total_questions'.$session)-1) {
			echo '<script>jQuery(document).ready(function($){$("#next_button").html("'.__("Finish", 'ssquiz').'"); $("#exit_but").hide();});</script>';
		} 

		set_transient('question_id'.$session, $question["id"], 9999); 
		//set_transient('answer'.$session, $answer, 9999);
		echo '<div class="question"><strong>'.__("Question", 'ssquiz').' #';
		echo get_transient('questions_counter'.$session)+1; echo ':</strong><br  />'; 
		echo stripslashes(stripslashes(html_entity_decode($question["question"]))); 
		echo '</div><br  />';
		$temp=array();
        //--------checking for multianswer test
		//$myrows = $wpdb->get_col( "SELECT answer FROM ssquiz_answers WHERE question_id=" .  $question["id"] . "ORDER BY number");
		if(count($answers)>1) {
			//array_push($myrows, $question["answer"]);
			if(get_transient("arandom".$session)) 
				shuffle($answers);
			set_transient('answer'.$session, "", 9999);
			foreach($answers as $answer) {
				$answer=get_object_vars($answer);
				if($correct>1)
					echo '<input type="checkbox" name="'.$question['id'].'" class="answer" value="'. $answer['answer'] .'">'. $answer['answer'] . '<br>';
				else
					echo '<input type = "radio" name="'.$question['id'].'" class="answer" value="'. $answer['answer'] .'">'. $answer['answer'] . '<br>';
					
				if($answer['correct']=='1')
					array_push($temp, $answer['answer']);
			}
			set_transient('answer'.$session, $temp, 9999);
		}
		else {
			echo '<input type = "text" class="answer" value=""  />';
			$answers=get_object_vars($answers[0]);
			array_push($temp, $answers['answer']);
			set_transient('answer'.$session, $temp, 9999);
		}
    }
	die();
}
add_action('wp_ajax_view_answer', 'view_answer');
add_action('wp_ajax_nopriv_view_answer', 'view_answer');
add_action('wp_ajax_exit_quiz', 'exit_quiz');
add_action('wp_ajax_nopriv_exit_quiz', 'exit_quiz');
add_action('wp_ajax_do_all', 'do_all');
add_action('wp_ajax_nopriv_do_all', 'do_all');
add_action('wp_ajax_start_quiz', 'start_quiz');
add_action('wp_ajax_nopriv_start_quiz', 'start_quiz');
add_action('wp_ajax_quiz_a', 'next_question');
add_action('wp_ajax_nopriv_quiz_a', 'next_question');
?>