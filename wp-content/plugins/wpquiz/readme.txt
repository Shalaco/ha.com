=== WPQuiz ===
Contributors: bauc
Tags: quiz, question, quizzes
Requires at least: 3.2.1
Tested up to: 3.5.1
Stable tag: 0.4.0
License: GPLv2 or later

WPQuiz allows you to add a simple quiz/questions to any post/page

== Description ==

WPQuiz allows you to create a quiz (one or more questions/answers) and add it to any post/page using the built-in tags [wpquiz id=?] and [wpquiz_text][/wpquiz_text]. After installing, you can create a new quiz or edit the sample one by accessing it from the admin Dashboard->Settings->WPQuiz. Once you've created a quiz there you can easily add it to
a post/page from the Add/Edit post/page editor's WPQuiz meta box. The plugin stores its questions/answers in two tables in the database.

The answers are not stored, only checked and shown back to user. Storing and/or giving more feedback is planned for the next release, comments welcome.

== Installation ==

1. From the 'Plugins' menu in WordPress click 'Add New' then upload and then using the form upload 'wpquiz.zip' or you can search for WPQuiz in the search box and install from there.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the new menu from the 'Dashboard'->'Settings'->'WPQuiz' to create a new quiz or create a new post and use the new meta box to automatically insert the quiz tags.

== Frequently Asked Questions ==

= How to I create a new quiz: =

- From WordPress Admin Dashboard, click on Settings menu, you should then see a menu option named 'WPQuiz', click on that. Follow the on screen form.

= How do I add a quiz to a post/page: =

- If the plugin is enabled there is a new box on the post/page edit screen named "WPQuiz: Insert quiz:" click on the quiz name and add post will add it to the current post, alternatively you can simply type in "[wpquiz id = x]" where x is the quiz name show on the WPQuiz page in the settings menu.

= How to I show the score: =

- You can use the optional tags in your post, these include:
	* [wpquiz_correct] for the number of correctly answered questions
	* [wpquiz_wrong] for the number of incorrectly answered questions
	* [wpquiz_total] for the total number of questions
	* [wpquiz_percent] for the percentage scored calculated as correct divided by total multiplied by total
	
= How do I show/hide the tweet this button: =

- By default the insert this quiz button will add the tweet this button, to hide the button simply remove or change the tweet_this paramater to false

== Screenshots ==

1. Sample quiz in post 
2. Sample quiz after being answered, all the text shown is controlled from the post editor
3. Adding the sample quiz in post editor
	
== Changelog ==

= 0.4.0 = 
* Added in randomising of the answers to the questions
* Added Twitter tweet this button on results page
* Fixed several other minor bugs
* Note database change - table wpquiz_questions - correctAnswer from mediumint to varchar 100
* Please note there is a format change to the way correct answers are stored, to update your quiz please edit it from Dashboard->Settings->WPQuiz page and reselect the correct answers and save, this should update to the new format

= 0.3.5 = 
* Fixed bug that on creation of quiz was selecting wrong correct answer

= 0.3.4 =
* Fixed bug that stopped new quiz creation due to different SQL schemas

= 0.3.3 =
* Bug fix when editing existing quiz lead to incorrect correct answer being stored
* Laid foundation for storing answers in quiz results

= 0.3.2 =
* Moved scoring result to post/page entry to allow formatting and more flexibility
* Fixed a layout bug causing text to overlay incorrectly

= 0.3.1 =
* Adjusted existing installs to populate score text with default text

= 0.3.0 =
* Added score marking system to show how many correct, wrong, total and percent. New section in admin area

= 0.2.5 =
* Fixed critical bug stopping tables being created

= 0.2.4 =
* Improved updating of existing quiz and fixed a layout error in quiz edit

= 0.2.3 =
* Fixed bug so editing quiz is now possible
* Fixed JavaScript bug when reducing number of answers

= 0.2.2 =
* Fixed JavaScript bug preventing adding additional questions to existing quiz and to fix error in selecting the correct answer for quiz question 10 and above

= 0.1 =
* Initial Release.