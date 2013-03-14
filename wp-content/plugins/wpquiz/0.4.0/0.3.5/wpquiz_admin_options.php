<?php

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	global $wpdb; 
		
	$tbl_wpquiz_question = $wpdb->prefix . "wpquiz_questions";
	$tbl_wpquiz_quiz = $wpdb->prefix . "wpquiz_quiz";
		
	$delno = htmlspecialchars ($_GET['delete']);
	$delcon = htmlspecialchars ($_GET['deleteconfirm']);
	$editno = htmlspecialchars ($_GET['edit']);
	
	if (is_null($delno)) {$delno = 0;}
	if (is_null($editno)) {$editno = 0;}
	
	if (($editno > 0) && ($delno == 0))
	{
		if ($_POST['bravo'] == 'x1')
		{		
			//Edit save					//Update quiz
			$i=1;			
			
			$questioncheck = htmlspecialchars($_POST['wpquiz_question' . $i]);
			$answercheck = htmlspecialchars($_POST['wpquiz_correctA' . $i]);
			$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i]); // BUG why was +1 added???  + 1);
						
			//$quiztitle = htmlspecialchars($_POST['wpquiz_quizname']);
			$quiztitle = $_POST['wpquiz_quizname'];
			$store_results = 0;
			$store_results_value = $_POST['wpquiz_store_results'];
			
			//die('<h2>'.$_POST['wpquiz_store_results'].'</h2>');
			//update_option("wpquiz_store", $store_results_value);
						
			if ($store_results_value == "store_yes") { $store_results = 1;}
			
			if (($questioncheck == "") || ($answercheck == "") || ($correctanswercheck == "") || ($quiztitle == "")) {
				if (isset($_GET['noheader']))
				require_once(ABSPATH . 'wp-admin/admin-header.php');
				echo '<p>Error with form please check and submit again;</p>';
			}
			else {
			
				//remove existing quiz question to replace, not ideal as should be updating but this is quick fix to get working
				//$sqlD1 = "DELETE FROM " . $tbl_wpquiz_quiz . " WHERE id = " . $editno;
				
				$quizid = $editno;
				
				//$wpdb->show_errors(); 
				
				/*$wpdb->update( 
							$tbl_wpquiz_quiz, 
							array( 
								'quiztitle' => 'value1',	// string
							), 
							array( 'id' => $quizid ), 
							array( 
								$quiztitle,	// value1								
							)
							);
							*/
							
				$sqlU1 = "UPDATE " . $tbl_wpquiz_quiz . " SET `quiztitle` = '". $quiztitle . "', `store_results` = '". $store_results ."' WHERE id = " . $quizid . ';';
				$wpdb->query($sqlU1);
				
				//update_option("wpquiz_error" . "quizR", $sqlU1);
			
				$res = $wpdb->query($sqlD1);
				//update_option("wpquiz_error" . "quizR", $res);
				$res = $sqlD2 = "DELETE FROM " . $tbl_wpquiz_question . " WHERE quizid = " . $editno;				
				$wpdb->query($sqlD2);
				//update_option("wpquiz_error" . "questionR", $res);
				
				//$rows_affected = $wpdb->insert( $tbl_wpquiz_quiz, array( 'quiztitle' => $quiztitle) );
				//$quizid = $wpdb->insert_id ;
				
				//$err ="";
				 
				$i = 0;
				do {
				
					$i++;
					
					$questioncheck = htmlspecialchars($_POST['wpquiz_question' . $i]);
					$answercheck = htmlspecialchars($_POST['wpquiz_correctA' . $i]);
					//$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i] + 1);
					$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i]); // BUG why was +1 added???  + 1);
					
					//$err .= "q: ".$i . ' corca: ' . $correctanswercheck;
										
					if (($questioncheck == "") || ($answercheck == "") || ($correctanswercheck == "")) { break; break;}
					
					$rows_affected = $wpdb->insert( $tbl_wpquiz_question, array( 'question' => $questioncheck, 'answers' => $answercheck, 'correctAnswer' => $correctanswercheck, 'quizid' => $quizid ) );
					
				
				} while(1);
				
				//update_option("wpquiz_error" . "quizR", $err);
				
				//wp_redirect ( admin_url("options-general.php?page=wpquiz_options&amp;edit=" . $quizid));
				wp_redirect ( admin_url("options-general.php?page=wpquiz_options"));
				
				//echo '<script type="text/javascript">window.location = "' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '";</script> ';

				/*/qad						-------------------------------------
				echo '<div class="wrap"><div id="icon-options-general" class="icon32"></div>';
			
				echo "<h2>WPQuiz - Edit 2</h2>";
				
				$sql = "SELECT id, quiztitle FROM " . $tbl_wpquiz_quiz. " WHERE id = $quizid";
				$quizdb = $wpdb->get_row($sql);
				
				echo '<h3>Edit Quiz: "' . $quizdb->quiztitle . '"</h3>';
		  
				wpquiz_output_form($quizid );
			
				echo '</div>';
				//endqad*/
					
				}
		}
		else {
			if (isset($_GET['noheader']))
				require_once(ABSPATH . 'wp-admin/admin-header.php');
			echo '<div class="wrap"><div id="icon-options-general" class="icon32"></div>';
			
			echo "<h2>WPQuiz Quiz</h2>";
			
			$sql = "SELECT id, quiztitle FROM " . $tbl_wpquiz_quiz. " WHERE id = $editno";
			$quizdb = $wpdb->get_row($sql);
			
			echo '<h3>Edit Quiz: "' . $quizdb->quiztitle . '"</h3>';
	  
			wpquiz_output_form($editno );
			
			echo '</div>';
			
			
			}			
			//////
		
	
	}
	
	elseif ($delno > 0)	
	{
		
		if ($delcon == "true")
		{
			if (isset($_GET['noheader']))
				require_once(ABSPATH . 'wp-admin/admin-header.php');
			$wpdb->query("DELETE FROM ". $tbl_wpquiz_quiz ." WHERE id = '". $delno ."'");
			$wpdb->query("DELETE FROM ". $tbl_wpquiz_question ." WHERE quizid = '". $delno ."'");						
			//wp_redirect ( admin_url("options-general.php?page=wpquiz_options"));
			echo "<p>Delete complete</p>";			
		}
		else
		{			
			if (isset($_GET['noheader']))
				require_once(ABSPATH . 'wp-admin/admin-header.php');
			echo '<div class="wrap"><div id="icon-options-general" class="icon32"></div>';
			
			echo "<h2>WPQuiz</h2>";
			
			echo "<h3>Confirm deletion</h3>";
				
			$sql = "SELECT id, quiztitle FROM " . $tbl_wpquiz_quiz. " WHERE id = $delno";
		  
			$quizdb = $wpdb->get_row($sql);
		  
			echo '<p>You want to delete quiz: "' . $quizdb->quiztitle . '"<br />';			
			echo '<a href = "' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '&amp;deleteconfirm=true' . '">Confirm Deletion</a>';			
			echo '</div>';
		}
	}
	else {
	
		if($_POST['bravo'] == 'x1') {  
			
			//		Insert new quiz
			
			$i=1;
			
			
			$questioncheck = htmlspecialchars($_POST['wpquiz_question' . $i]);
			$answercheck = htmlspecialchars($_POST['wpquiz_correctA' . $i]);
			//$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i] + 1);
			$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i]); // BUG why was +1 added???  + 1);
						
			$quiztitle = htmlspecialchars($_POST['wpquiz_quizname']);
			$store_results = 0;
			$store_results_value = $_POST['wpquiz_store_results'];
			
			if ($store_results_value == "store_yes") { $store_results = 1;}
			
			if (($questioncheck == "") || ($answercheck == "") || ($correctanswercheck == "") || ($quiztitle == "")) {
				if (isset($_GET['noheader']))
				require_once(ABSPATH . 'wp-admin/admin-header.php');
				echo '<p>Error with form please check and submit again;</p>';
			}
			else {
			
				
								
				$rows_affected = $wpdb->insert( $tbl_wpquiz_quiz, array( 'quiztitle' => $quiztitle, 'store_results' => $store_results) );
				$quizid = $wpdb->insert_id ;
				
				$i = 0;
				do {
				
					$i++;
					
					$questioncheck = htmlspecialchars($_POST['wpquiz_question' . $i]);
					$answercheck = htmlspecialchars($_POST['wpquiz_correctA' . $i]);
					//$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i] + 1);
					$correctanswercheck = htmlspecialchars($_POST['wpquiz_correctSA' . $i] + 1); // BUG why was +1 added???  + 1 as was reading wrong spot that's why);
										
					if (($questioncheck == "") || ($answercheck == "") || ($correctanswercheck == "")) { break; break;}
					
					$rows_affected = $wpdb->insert( $tbl_wpquiz_question, array( 'question' => $questioncheck, 'answers' => $answercheck, 'correctAnswer' => $correctanswercheck, 'quizid' => $quizid ) );
					
				
				} while(1);
				
				echo '<script type="text/javascript">window.location = "' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '";</script> ';

				}
			
		}
		else {
		
					if (isset($_GET['noheader']))
				require_once(ABSPATH . 'wp-admin/admin-header.php');
		echo '<div class="wrap"><div id="icon-options-general" class="icon32"></div>';		
		echo "<h2>WPQuiz</h2>";			
		
		$sql = "SELECT id, quiztitle FROM " . $tbl_wpquiz_quiz. " ORDER BY id DESC";	  
		$quizes = $wpdb->get_results ( $sql );
	  
		echo "<h3>Current quizzes</h3>";	  
		echo '<table class = "widefat fixed">';
		echo '<thead><tr>
			<th id = "lq1" manage-column column-columnname num>Quiz ID</th>
			<th id = "lq2">Quiz Title {Click name to Edit}</th>
			<th id = "lq3">Action</th>
		</tr> </thead>';
		echo '<tfoot><tr><th>Quiz ID</th> <th>Quiz Title {Click name to Edit}</th> <th>Action</th></tr> </tr></tfoot>';
		echo '<tbody>';

		foreach ($quizes as $quiz) { 
			echo '<tr>';
			echo '<td>' . $quiz->id . '</td>';
			echo '<td><a href = "' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '&amp;edit=' . $quiz->id . '">' . $quiz->quiztitle .  '</a></td>';
			echo '<td>' . '<a href = "' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '&amp;delete=' . $quiz->id . '">Delete</a>' .  '</td>';
			echo '</tr>';
			
		}
		
		echo '</tbody></table>';
		echo '<br >';
		echo '<hr />';
		echo '<h3>Create new quiz.</h3>';

			 wpquiz_output_form();

		}
	}
	
	
function wpquiz_output_form($quizid = 0 )
{
	global $wpdb; 
	
					
	$tbl_wpquiz_question = $wpdb->prefix . "wpquiz_questions";
	$tbl_wpquiz_quiz = $wpdb->prefix . "wpquiz_quiz";

	$wpdb->flush();
	
	$quiz_name = "";
	
	if ($quizid > 0) { 	
	
		$sql = "SELECT `id`, `quiztitle`, `store_results` FROM " . $tbl_wpquiz_quiz. " WHERE id = $quizid";
		$quizdb = $wpdb->get_row($sql);
		$quiz_name = $quizdb->quiztitle;
		$store_results = $quizdb->store_results;
		
		$sql = "SELECT id, question, answers, correctAnswer FROM " . $tbl_wpquiz_question. " WHERE quizid = $quizid";
		$quizes = $wpdb->get_results ( $sql );
		
	}
		
		echo '<div class = "wrap">';
		echo '<form name="wpquiz_admin_form" method="post" action="' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '&amp;noheader=true">';
		echo '<table id = "wpquiz_tb1" name = "wpquiz_tb1">';
					
		echo '<tr><td><label for="wpquiz_quizname">Quiz Name<span> *</span>: </label></td>        <td>' .
		'<input type = "text" id="wpquiz_quizname" maxlength="200" size="50" name="wpquiz_quizname" value="' . $quiz_name . '" /></td></tr>';
		
		echo '<tr><td><label for="wpquiz_store_results">Save results: <span> *</span>: </label></td>        <td>' .
		'<input type = "checkbox" id="wpquiz_store_results" name="wpquiz_store_results" value = "store_yes" ';
		
		if ($store_results == 1) { echo ' checked = "checked" ' ;}
		
		echo '/> - Tick this box if you wish to save the submitted quiz answers.</td></tr>';
		
		echo '<tr><td>&nbsp;</td><td></td></tr>';
		
		$num_rows = 3 - 1;
		
		if (($wpdb->num_rows > 0) && (!is_null($quizes))) {
		
			$num_rows = $wpdb->num_rows - 1;
			$i = 0;
			foreach ($quizes as $q ){				
				
				$i++;
				$lq = $q->question;
				$la = $q->answers;
				echo '<tr><td><label for="wpquiz_question' . $i .'">Question ' . $i . '<span> *</span>: </label></td><td>' . 
					 '<input id="wpquiz_question'. $i .'" maxlength="200" size="50" name="wpquiz_question'. $i .'" value="' . $lq . '" /></td></tr>';
				echo '<tr><td><label for="wpquiz_correctA' . $i .'">Answers <span> *</span>: </label></td><td>' . 
					 '<textarea name = "wpquiz_correctA' . $i .  '" id = "correctA' . $i .  '" rows="5" cols="50" onchange="changeCorrect(this.id)">' . $la . '</textarea></td></tr>';
				echo '<tr><td><label for="wpquiz_correctSA' . $i .'">Correct answer: <span> *</span>: </label></td><td>';
				echo '<select name = "wpquiz_correctSA' . $i .  '" id = "correctSA' . $i .  '">';
				
				$c = 0;
				foreach (explode("\n", $la) as $lan)
				{
					$c++;
					if ($c == $q->correctAnswer)
					{
						echo '<option value="' . $c .'" selected = "selected">' . $lan . '</option>';
					}
					else
					{
						echo '<option value="' . $c .'">' . $lan . '</option>';
					}
				}
				echo '</select></td></tr>';
				echo "<tr><td><hr></td><td></td></tr>" . "\n";
						
			}
		}
		else
		{
			for ($i = 1; $i < $num_rows; $i++) 
			{
				$lq = "";
				$la = "";			
				echo '<tr><td><label for="wpquiz_question' . $i .'">Question ' . $i . '<span> *</span>: </label></td><td>' . 
					 '<input id="wpquiz_question'. $i .'" maxlength="200" size="50" name="wpquiz_question'. $i .'" value="' . $lq . '" /></td></tr>';
				echo '<tr><td><label for="wpquiz_correctA' . $i .'">Answers <span> *</span>: </label></td><td>' . 
					 '<textarea name = "wpquiz_correctA' . $i .  '" id = "correctA' . $i .  '" rows="5" cols="50" onchange="changeCorrect(this.id)">' . $la . '</textarea></td></tr>';
				echo '<tr><td><label for="wpquiz_correctSA' . $i .'">Correct answer: <span> *</span>: </label></td><td><select name = "wpquiz_correctSA' . $i .  '" id = "correctSA' . $i .  '"><option value=""""></option></select></td></tr>';
				echo "<tr><td><hr></td><td></td></tr>" . "\n";
						
			}
			
		}
				
		echo '<tr>';		
		echo '</tr>';

		echo '<tr><td>';
		
		echo '</td>';
		echo '<td></td></tr>';
					
		echo '<input type = "hidden" name = "bravo" value = "x1" />';
		echo '<tr><td><br /><hr /><br />';
		//echo '<input type="submit" name="Submit" class = "button-primary" value="' . _e('Save Quiz', 'wpquiz_m' ) . '" />';
		
		echo '</td><td></td></tr>';
		echo '</table>';
		echo '<div id = "addq" stlye = "display:none"></div>';
		echo '<input type = "button" onclick = "addInputx(this.id);" value ="Insert Another Question" />';
		echo '<input type="submit" name="Submit" class = "button-primary" value="Save Quiz" />';
		echo '</form>';
		//echo '</div>';
				
		echo '</div>';
		echo '<hr />';

}
	
?>