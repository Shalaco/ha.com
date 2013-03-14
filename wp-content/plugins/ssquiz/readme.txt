=== SS Quiz ===
Contributors: ssvadim
Donate link: http://100vadim.com/ssquiz/
Tags: quiz, quizzes, questions, answer, test, learning, education, tests
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 1.12.2

SS Quiz is naturally simple and, at the same time, very powerful. Loved by many, it let you create content rich quizzes in less time.

== Description ==

If you need quizzes with wide range of questions, this plugin is for you. Need multiple choices beneath a question? Add several correct and wrong answers. You want user to type answer on their own? Then add just one correct answer while editing. Very intuitive. On the page or post you can add parameters to the quiz shortcode to use timer, randomizer and some other features. On the admin side you would be able to track who answered your quizzes and edit templates. Check option in templates to get mail whenever somebody finish a quiz. And many more features!


Features include:

* Easy and fast quiz creation
* Multimedia in questions
* Multiple types of questions
* Timer
* Emailing to user or teacher
* Editable templates for email, welcome/finish screens
* History of usage
* Plugin API
* Localization ready (Currently added Russian translation)
* See FAQ for more

== Installation ==

You can install SSQuiz through "Plugins" menu of Wordpress dashboard.

Once installed, menu "SSQuiz" become available in dashboard. 

Quiz called "Back to school" shown as first quiz. You can delete it or play with it.

Also in menu "SSQuiz" there are "Quiz Users" and "Quiz templates" submenus.

To insert quiz on a page, use short code [ssquiz id='#'], where # replace by ID of the quiz. Also, you can use another attributes listed in FAQ.

== Screenshots ==

1. Managing questions
2. User is answering a question
3. Editing the question
4. Answering multiple questions

== Frequently Asked Questions ==

= How can I insert quiz into my page? =
Write '[ssquiz id="#"]' on your page, where "#" is quiz ID, i.e. 1

=  How can I delete answer? =

Leave it clear and save.

= How can I use your plugin in my development? =

For example, You can add following piece of code into your plugin:

// hook is triggered when SSQuiz is finished by an user

add_action('ssquiz_finished', 'after_quiz_done', 10, 3); 

// function is called with known percent , amount of answered questions and number of answered questions

function after_quiz_done($quiz_id, $percent, $right_answered, $questions_total){
	...
}

= What arguments can I use with short code? =

You can use following arguments:

* all - to show all questions on once
* not_correct - not showing correct answers after quiz is finished
* qrandom - to randomize questions
* arandom - to randomize answers
* name - to request user name at quiz start
* email - to request user email at quiz start and to send him email when quiz is done
* timer - set timer in seconds. For example: timer="12"
* one_chance - for registered users sets only one attempt to pass test. To start again you should delete their attempt from quiz's history.
* qmax - max number of questions to show (sometimes useful with qrandom)

= How can I create "fill-in-the-blank"/"multiple choice" test? =
If you write only one answer, then SSQuiz considers this test as "fill-in-the-blank".
Else, if you write many answers, but only one of those is correct, then it would be "multiple choice" test, and user Would be able to choose only one answer.
If you checks more than one correct answer to question, then it would be possible, to choose several answers at once.
					

== Changelog ==

= 1.12 =
* Some bugs and security issues solved
* Internationalized and russian language added
* Added 'one_chance' feature to shortcode
* Added wordpress editor to to some templates

= 1.11 =
* Bug fixed with Insert Media
* More compatibility with UTF8

= 1.10 =
* Fixed bug with email
* Input fields are now case insensitive

= 1.09 =
* Bug with multiple choices fixed
* Bug with API fixed

= 1.08 =
* Quiz can be placed anywhere on the page from now
* Other little improvements

= 1.07 =
* Bug with user name and email fixed
* Minor interface improvement
* Email header can be changed for email from now

= 1.06 =
* Timer improved
* New user's buttons
* Added ability to send emails to teachers
* Optional sending email to user
* Fixed bug with "exit button" in the start of quiz
* Loading animation added
* "OK" Button is called "Next" from now
* On the last question "Next" button is renamed to "Finish" now
* Other things fixed

= 1.05 =
* Fixed bug with editing questions in Firefox
* Fixed bug with right answers counting
* Fixed bug with ordering answers
* Layout Improvement
* Button "Clear History List" added in User's Page of Dashboard
* API added (see FAQ)

= 1.041 =
* Fixed bug with user's email

= 1.04 =
* Quiz now has no background
* Bug fixed with user's inputting information about them
* Email verification added
* Solved two errors with Internet Explorer
* Now users can be deleted from history
* Little improvements on admin side

= 1.03 =
* Bug fixed with adding media on another admin pages
* Showing correct answers is optional now

= 1.02 =
* Fixed reactivation bug

= 1.01 = 
* Fixed several bugs
* Ok button is disabled while loading

= 1.0 =
* Fixed some bugs
* Right answers are shown
* Optional Timer added

= 0.9 =
* Initial release
