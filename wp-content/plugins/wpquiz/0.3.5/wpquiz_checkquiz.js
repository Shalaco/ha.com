function checkQuiz(x) {
	
	var y = document.getElementById(x);
}
	
function addQuiz (x)
{
						
	var y = document.getElementById ("wpquiza");
	
	text = "[wpquiztext]Thanks for taking the quiz you scored [wpquiz_correct] correctly and got [wpquiz_wrong] questions wrong, out of a total of [wpquiz_total] that makes it [wpquiz_percent] percent.[/wpquiztext]<br />\r\n\r\n[wpquiz id =" + y.value + "]";
					
		if ( typeof tinyMCE != 'undefined' && ( editorE = tinyMCE.activeEditor ) && !editorE.isHidden() ) {
			editorE.focus();
			text = '<div>' + text + editorE.selection.getContent() + '</div>';
			editorE.execCommand(tinymce.isGecko ? 'insertHTML' : 'mceInsertContent', false, text);
		} else {
			text += _spoiler.getRng(edCanvas);
			text += '';
			edInsertContent(edCanvas, text);
		}

}

function addScore (x)
{
						
	var y = document.getElementById ("wpquiz_scoring");
	text = "[wpquiz_" + y.value + "]";
					
		if ( typeof tinyMCE != 'undefined' && ( editorE = tinyMCE.activeEditor ) && !editorE.isHidden() ) {
			editorE.focus();
			text = '<div>' + text + editorE.selection.getContent() + '</div>';
			editorE.execCommand(tinymce.isGecko ? 'insertHTML' : 'mceInsertContent', false, text);
		} else {
			text += _spoiler.getRng(edCanvas);
			text += '';
			edInsertContent(edCanvas, text);
		}

}