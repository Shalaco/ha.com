var timer1=0;
var seconds_left=0;
jQuery(document).ready(function($) {
	
	if($(".sstimer").length){
		timer_update();
	}

	var start_but=$("a.start");
	var flag_fill_forms=0; ///!!!
	if($('#select_quiz').length)
		select_changed($('#select_quiz'));
	var counter_ans=1;
	
	var idd=$("input.select_quiz:first").attr("id");
	$("input.quiz_id-hidden").attr("value", idd);
	$("input.select_quiz:first").attr("value", "selected");
	$("input.select_quiz:first").addClass("selected");

	$("h3.correct_ans").append('<input type="submit" class="add_but" value="'+Trans.add_answer+'" />');

	$("a.exit_but").click(function () {
			var session=$(".quiz").attr("id");
			var idd=$(this).attr("id");
			$.post(MyAjax.ajaxurl, {
					action:"exit_quiz",
					session:session,
					question_id: $(this).attr("id")
			}, function (data) {
					//$("button.exit_but").css("display", "none");
					//$("button.next").css("display", "none");
					$("div.quiz_area").fadeOut(100, function (){
						$("div.quiz_area").html(data);
						$("div.quiz_area").fadeIn(100);
					})
				}
			);
		return false;
	}); 

	$(document).delegate("input.sssubmit_question", "click", function () {
			if($("#first_question-options-form input[type=text]:first").attr("value").length<1) {
				temp_message($(this).parent(), "<font color='red'>"+Trans.you_must_write+"</font>");
				return false;
			}
		
			if($("#first_question-options-form input:checked").size()<1) {
				temp_message($(this).parent(), "<font color='red'>"+Trans.you_must_choose+"</font>");
				return false;
			}
			if($("#first_question-options-form #question").val().length<2) {
				temp_message($(this).parent(), "<font color='red'>"+Trans.questions_too+"</font>");
				return false;
			}
			return true;
	});
	
	$(".add_quiz-submit").click( function(){
			if($("#add_quiz-form #quiz_title").attr("value").length<2) {
				temp_message($(this).parent(), "<font color='red'>"+Trans.you_must_write_title+"</font>");
				return false;
			}

			if($("#add_quiz-form #quiz_description").attr("value").length<2) {
				temp_message($(this).parent(), "<font color='red'>"+Trans.you_must_write_description+"</font>");
				return false;
			}
			return true;
	});
	
	$(".ssscreen_form #sssubmit_welcome").click(function () {
		var parent=$(this).parent();
		console.log($("#sswelcome_ifr"));
		$.post(MyAjax.ajaxurl, {
				action:"refresh_settings",
				type: $("#sswelcome").attr("name"),
				data: $("#sswelcome_ifr").contents().find("body").html()
				//type: $(this).parent().siblings("textarea").attr("name"),
				//data: $(this).parent().siblings("textarea").attr("value")
		}, function (data) {
			if(data=="DONE")
				temp_message(parent, Trans.saved)
			else
				temp_message(parent, "<font color=\"red\"> "+Trans.error+"</font>");
			}
		);
	return false;
	}); 

	$(".ssscreen_form #sssubmit_result").click(function () {
		var parent=$(this).parent();
		//console.log($("#sswelcome"));
		$.post(MyAjax.ajaxurl, {
				action:"refresh_settings",
				type: $("#ssresult").attr("name"),
				data: $("#ssresult_ifr").contents().find("body").html()
		}, function (data) {
			if(data=="DONE")
				temp_message(parent, Trans.saved)
			else
				temp_message(parent, "<font color=\"red\"> "+Trans.error+"</font>");
			}
		);
	return false;
	}); 

	$(".ssscreen_form #sssubmit_email").click(function () {
		var parent=$(this).parent();
			$.post(MyAjax.ajaxurl, {
					action:"refresh_settings",
					type: "user_settings",
					data: $(this).parent().siblings("textarea").attr("value"),
					ssusers_header: $(this).parent().siblings("#ssusers_header").attr("value"),
					user_recieve: $(this).parent().siblings("#user_recieve").attr("checked")
			}, function (data) {
				if(data=="DONE")
					temp_message(parent, Trans.saved)
				else
					temp_message(parent, "<font color=\"red\"> "+Trans.error+"</font>");
				}
			);
	return false;
	}); 

	$(".ssscreen_form #sssubmit_teacher").click(function () {
		var parent=$(this).parent();
			$.post(MyAjax.ajaxurl, {
					action:"refresh_settings",
					type: "ssteachers_template",
					data: $(this).parent().siblings("textarea").attr("value"),
					ssteachers_header: $(this).parent().siblings("#ssteachers_header").attr("value"),
					ssteachers_email: $(this).parent().siblings("#ssteachers_email").attr("value"),
					should_recieve: $(this).parent().siblings("#should_recieve").attr("checked")
			}, function (data) {
				if(data=="DONE")
					temp_message(parent, Trans.saved)
				else
					temp_message(parent, "<font color=\"red\"> "+Trans.error+"</font>");
				}
			);
	return false;
	}); 

	function temp_message(element, text){
		var temp_mes = $("<span class=\"temp_message\"> "+text+"</span>");
		if(element.find(".temp_message").length>0)
			return;
		element.append(temp_mes);
		setTimeout(function () {
			temp_mes.fadeOut(500, function () {temp_mes.remove()});
		},1000);
	}

	function timer_update(){
		var time_format="";
		if(Math.floor(seconds_left/60)<10)
			time_format+="0"
		time_format+=Math.floor(seconds_left/60);
		time_format+=":";
		if(seconds_left%60<10)
			time_format+="0"
		time_format+=seconds_left%60;
		$(".sstimer #sstimer").html(time_format); 
	}

	function tick_tack(){
		seconds_left--;
		if(seconds_left<0){ //timeOut
			if($("div.question_body").length>0) // all questions
				start_but.trigger("click");
			else
				$("a.exit_but").trigger("click");
		}else{
			if(seconds_left<10)
				$(".sstimer").css("color", "red").css("font-weight", "bold");
			timer_update();
			timer1 = setTimeout(function(){tick_tack();}, 1000);
		}
}

    start_but.click( function () {
		var email_verify = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		//checking user credentials if they're visible
		if(($('input#ssuser_name').length>0 && $("input#ssuser_name").attr("value")=="") || ($('input#ssemail').length>0 && $("input#ssemail").attr("value")=="")) {
			if(flag_fill_forms==0){
				$("div.quiz_area").append("<br /><font color='red'><em>"+Trans.please_fill_forms+"</em></font>");
				flag_fill_forms=1;
			}
			else {
				$("div.quiz_area font em").text(Trans.please_fill_forms);
			}
			return false;
		}
		else if($('input#ssemail').length>0 && !email_verify.test($("input#ssemail").attr("value"))) {
			if(flag_fill_forms==0){
				$("div.quiz_area").append("<br /><font color='red'><em>"+Trans.input_correct_email+"</em></font>");
				flag_fill_forms=1;
			}
			else {
				$("div.quiz_area font em").text(Trans.input_correct_email);
			}
			return false;
        }

		if($(this).hasClass("noclick"))
			return false;
		start_but.addClass("noclick");
		var session=$(".quiz").attr("id");
		if(start_but.hasClass("start")) { // quiz just started

			$.post(MyAjax.ajaxurl, {
					action:"start_quiz",
					session:session,
					user_name: $("input#ssuser_name").attr("value"),
					user_email: $("input#ssemail").attr("value")
			}, function (data) {
					start_but.removeClass("start");
					start_but.addClass("next");
					//$("a.exit_but").css("display", "");
					start_but.removeClass("noclick");
					start_but.trigger("click");
					
					if($(".sstimer").length){
						seconds_left++; // 1 second leg-up
						tick_tack();
					}
					
				}
			);
		}
		else if($("div.question_body").length>0) { //all questions at once
			$.post(MyAjax.ajaxurl, {
				action:"do_all",
				session:session,
				dataType: "html",
				answers: $("input.answer:checked, input.answer[type=text]").serializeArray()
			}, function (data) {
					$("div.quiz_area").fadeOut(100, function (){
						$("div.quiz_area").html(data);
						$("div.quiz_area").fadeIn(100, function(){start_but.removeClass("noclick");});
						//$('html, body').animate({scrollTop: $(".quiz").offset().top}, 500); //!
					})
				}
			);
		}
		else{ // questions by one
			if ($("input.answer:checked").length){
    			var answ=$("input.answer:checked").map(function() {return this.value;}).get().join('|');
			}
				//var answ=$("input:checked").attr("value");
			else if ($("input.answer[type=text]").length && $("input.answer[type=text]").val().length){
				var answ=$("input.answer[type=text]").val();
			}
			else 
				var answ="NA";
			
			$.post(MyAjax.ajaxurl, {
					action:"quiz_a",
					session:session,
					answer:answ
			}, function (data) {
					$("div.quiz_area").fadeOut(100, function (){
						$("div.quiz_area").html(data);
						$("div.quiz_area").fadeIn(100, function(){start_but.removeClass("noclick");});
					})
					
				}
			);
		}
		$("div.quiz_area").html("<div class='ssloading' style=''><img style='position:relative;top:47%;left:47%;' src='"+MyAjax.images+"loader.gif'/></div>");
	return false;
	});


    $("input.select_quiz").click( function () {
			var idd=$(this).attr("id");
			$("input.selected").attr("value", Trans.select_quiz);
			$("input.selected").removeClass("selected");
			$("input.quiz_id-hidden").attr("value", idd);
			$(this).attr("value", "selected");
			$(this).addClass("selected");
	return false;
	});
	
	$(document).delegate("input.delete_quiz", "click", function () {
			var idd=$(this).attr("id");
			var elem = $(this).parent().parent();
			if (confirm(Trans.delete_quiz)==true){
				$.post(MyAjax.ajaxurl, {
					action:"ssdelete_quiz",
					quiz_id: idd
				}, function (data) {
					var question_pos=elem.nextUntil(".ssno-pointer");
					elem.slideUp(500);
					question_pos.slideUp(500);
					elem.remove();
					question_pos.remove();
					$("#select_quiz option[value='"+idd+"']").hide();
					}
				);

			}
			return false;
	});

	$(".delete-users-submit").click(function(){
		if (confirm(Trans.delete_all_users)==true)
			return true;	
		else
			return false;
	});

	function go_history(option){
			var session=$(".quiz").attr("id");
			$.post(MyAjax.ajaxurl, {
					action:"view_answer",
					session:session,
					option:option
			}, function (data) {
					$(".quiz_area").fadeOut(100, function(){
					$(".sshistory").html(data);
					$(".sshistory").fadeIn(100);
					});
				}
			);
	}

	$(document).delegate("a.ssanswer_history", "click", function () {
			var session=$(".quiz").attr("id");
			var number=$(this).html();
			if ($(this).hasClass("prev"))
				number=-1;
			if($(this).hasClass("forw"))
				number=-2;
			go_history(number);
			return false;
	});

	$(document).delegate("a.ssback", "click", function () {
		$(".sshistory").fadeOut(100, function(){
			$(".quiz_area").fadeIn(100);
		});
		return false;
});

	$(document).delegate("input.edit_quiz", "click", function () {
			var idd=$(this).attr("id");
			$("input.add_quiz").attr("value", Trans.add_question);
			$("input.add_quiz").css("color", "#000");
			$(".ssedit_addbox").remove();
			if($(this).attr("value")==Trans.cancel){
				$(this).attr("value", Trans.edit);
				$(this).css("color", "#000");
				$(this).closest("li").children(".ssedit_qbox").slideUp(300, 
					function(){$(".ssedit_qbox").remove();});
				return false;
			}
			$(".ssedit_addbox").remove();
			$(".ssedit_qbox").remove();
			$(".ssrow_edit").attr("value", Trans.edit);
			var id=$(this).attr("id");
			var parent=$(this).closest("li");
			parent.append("<div class=\"ssedit_qbox\" id=\""+idd+"\"><div>");
			$(".ssedit_qbox").slideDown(300);
			$(".ssedit_qbox").html(Trans.loading+"…");
			$.post(MyAjax.ajaxurl, {
					action:"ssedit_quiz",
					quiz_id: id
			}, function (data) {
					$(".ssedit_qbox").html(data);
				}
			);

			$(this).attr("value", Trans.cancel);
			$(this).css("color", "#0C3");
			return false;
	});

	$(document).delegate("input.add_quiz", "click", function () {
			var idd=$(this).attr("id");
			$(".edit_quiz").attr("value", Trans.edit);
			$(".ssedit_qbox").remove();
			if($(this).attr("value")==Trans.cancel){
				$(this).attr("value", Trans.add_question);
				$(".ssedit_addbox").slideUp(300, 
					function(){$(".ssedit_addbox").remove();});
				return false;
			}
			$(".ssrow_edit[value='"+Trans.cancel+"']").trigger('click');
			$(".ssedit_qbox").remove();
			$(".ssedit_addbox").remove();
			$(".ssrow_edit").attr("value", Trans.edit);
			$(".add_quiz").attr("value", Trans.add_question);
			var parent=$(this).closest("li");
			parent.append("<div class=\"ssedit_addbox\" id=\""+idd+"\"><div>");
			$(".ssedit_addbox").slideDown(300);
			$(".ssedit_addbox").html(Trans.loading+"…");
			$.post(MyAjax.ajaxurl, {
					action:"ssadd_quiz",
					quiz_id: idd
			}, function (data) {
					$(".ssedit_addbox").css("height", "inherit");
					$(".ssedit_addbox").html(data);
					$("h3.correct_ans").append('<input type="submit" class="add_but" value="'+Trans.add_answer+'" />');
				}
			);

			$(this).attr("value", Trans.cancel);
			//$(this).css("color", "#0C3");
			return false;
	});

$(document).delegate(".update_quiz-submit", "click", function () {
		current_button=$(this);
		var id=$(this).parent().parent().parent().parent().parent().attr("data-i");
		var title=$(this).parent().siblings("#quiz_title").attr("value");
		var descr=$(this).parent().siblings("#quiz_description").val();
		title=title.replace(/<[^>]+>/ig,"");
		//descr=descr.replace(/<[^>]+>/ig,""); // all html tags allowed!
		var parent = $(this).parent();
		$(this).parent().parent().parent().parent().parent().children("span.left").html("<strong>"+title+"</strong> ("+descr+")");
		
		$("input.edit_quiz[value='"+Trans.cancel+"']").trigger('click');
			$.post(MyAjax.ajaxurl, {
					action:"ssedit_quiz",
					question_id: id,
					title:title,
					description:descr,
					edit:true
			}, function (data) {
				}
			);
		return false;
	});

$(".add_quiz_a").click(function(){
			if($(this).html()==Trans.cancel){
				$(".sshidden").slideUp(300);
				$(this).html(Trans.add_quiz);
				return false;
			}
			$(".sshidden").slideDown(300);
			$(this).html(Trans.cancel);
});

	$(document).delegate("input.add_but", "click", function () {
		$("h3.last_ans").removeClass("last_ans");
		counter_ans++;
		$("<h3 class=\"last_ans\" style=\"display:none\"><label for=\"aanswer\">"+Trans.another_answer+": </label> <input  size=\"40\" type=\"text\" id=\"answer[]\" name=\"answer[]\"  value=\"\" /><input type=\"checkbox\" name=\"correct["+counter_ans+"]\" /></h3>").insertBefore($("p#submit_but"));
		$("h3.last_ans").append(this);
		$("h3.last_ans").slideDown(200);
		return false;
	});
	
	function select_changed(elem){
		var idd=elem.attr("id");
		$("div.question_list").html(Trans.loading+"…");
		$.post(MyAjax.ajaxurl, {
			action:"draw_question",
			quiz_id: elem.attr("value")
		}, function (data) {
				$("div.question_list").html(data);
				ssinitialize_question_list();
			}
		);
	}
	
	$('select.select_quiz').change(function() {
		select_changed($(this));
	});
	
	
	function ssinitialize_question_list(){
		$("ul#sssortable").sortable({
        	start: function (event, ui) {
            	ui.placeholder.html('<!--[if IE]><table>&nbsp;</table><![endif]-->');
        	},
			items: "li.ui-state-default:not(.ui-state-disabled)",
			cancel: ".ui-state-disabled2, li div",
			update: function(event, ui){
					var questions = $("#sssortable > li");
					var quiz_old=ui.item.find("tr td:nth-child(2)").html();
					var pos_old=ui.item.find("tr td:nth-child(3)").html();
					var quiz =ui.item.prevAll(".ssno-pointer").attr("data-i");
					//ui.item.prevAll(".ssno-pointer").children("span.right").children("strong").html("Total questions: "+); 
					ui.item.find("tr td:nth-child(2)").html(quiz);
					question_pos=ui.item.prevUntil(".ssno-pointer").size()+1;
					ui.item.find("tr td:nth-child(3)").html(question_pos);
					questions.each( function(index, domEle) {
						if(!$(domEle).hasClass('ui-state-disabled') && !$(domEle).hasClass('ui-state-disabled2')){
							question_pos=$(domEle).prevUntil(".ssno-pointer").size()+1;
							$(domEle).find("tr td:nth-child(3)").html(question_pos);
						}
					});
					if(quiz!=quiz_old){
						var n1=parseInt($("li[data-i='"+quiz+"']").children("span.right").children("strong").children("em").html())+1;
						var n2=parseInt($("li[data-i='"+quiz_old+"']").children("span.right").children("strong").children("em").html())-1;
						$("li[data-i='"+quiz+"']").children("span.right").children("strong").children("em").html(n1); 
						$("li[data-i='"+quiz_old+"']").children("span.right").children("strong").children("em").html(n2); 
					}
					question_pos=ui.item.prevUntil(".ssno-pointer").size()+1;
					$.post(MyAjax.ajaxurl, {
						action:"ssupdate_questions",
						quiz_id: quiz,
						quiz_old:quiz_old,
						pos_old:pos_old,
						question_id: ui.item.attr("id"),
						question_pos: question_pos
					});

				}
		});
		
		//$("ul#sssortable").disableSelection();

		$("input.ssrow_delete").click( function () {
			var id=$(this).attr("id");

			$.post(MyAjax.ajaxurl, {
					action:"ssdelete",
					question_id: id
			}, function (data) {
					$("#"+id).slideUp(300, function(){
						$("#"+id).remove();
						var questions = $("#sssortable > li");
						questions.each( function(index, domEle) {
						if(!$(domEle).hasClass('ui-state-disabled') && !$(domEle).hasClass('ui-state-disabled2')){
							question_pos=$(domEle).prevUntil(".ssno-pointer").size()+1;
							$(domEle).find("tr td:nth-child(3)").html(question_pos);
						}
						});
					});
				}
			);
			return false;
		});

	
    	$(".ssrow_edit").click( function () {
			if($(this).attr("value")==Trans.cancel){
				$(this).attr("value", Trans.edit);
				$(this).closest("li").children(".ssedit_box").slideUp(300, 
					function(){
						$(".ssedit_box").remove();
						$("ul#sssortable").sortable("enable");
					});
				return false;
			}
			$(".add_quiz[value='"+Trans.cancel+"']").trigger('click');
			$(".ssedit_box").remove();
			$(".ssrow_edit").attr("value", Trans.edit);
			var id=$(this).attr("id");
			var parent=$(this).closest("li");
			//var parent=$(this).parent().parent().parent().parent().parent();
			parent.append("<div class=\"ssedit_box\" id=\""+idd+"\"><div>");
			$("ul#sssortable").sortable("disable");
			//$(".ssedit_box").html("loading...");
			$.post(MyAjax.ajaxurl, {
					action:"ssedit_question",
					question_id: id
			}, function (data) {
					$(".ssedit_box").html(data);
					$(".ssedit_box").slideDown(300);
				}
			);

			$(this).attr("value", Trans.cancel);
			return false;
		});
	
	}

//---------------------	
	$('#upload_media').live("click", function() {
 		formfield = $('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=file&amp;TB_iframe=true');
		return false;
	});

if($('.question_list').length){
	window.send_to_editor = function(html) {
		var pattern_url=/ href=('|")(.+)('>|">)/;
		imgurl = pattern_url.exec(html)[2];
		var pattern_image=/(jpg|png|gif|bmp)$/g;
		var pattern_video=/(avi|mp4|mov)$/g;
		var pattern_audio=/(mp3|wav|ogg)$/g;
		var temp;
	  	if(pattern_image.test(imgurl)) {
			$('#question').val($('#question').val()+"<img src=\""+imgurl+"\"/>");
		}
		else if(pattern_video.test(imgurl)){
			temp = "<video width=\"320\" height=\"240\" controls=\"controls\">";
			temp += "<source src=\""+imgurl+"\" type=\"video/mp4\" />";
			temp += "<object width=\"320\" height=\"240\" src=\""+imgurl+"\">";
			temp += "<embed width=\"320\" height=\"240\" src=\""+imgurl+"\" ></embed></object></video>";
			insertAtCaret("question", temp);
		}
		else if(pattern_audio.test(imgurl)){
			temp = "<audio controls=\"controls\">";
			temp += "<source src=\""+imgurl+"\"/>";
			temp += "<object src=\""+imgurl+"\">";
			temp += "<embed src=\""+imgurl+"\" ></embed></object></audio>";
			insertAtCaret("question", temp);
		}
		else { // unknown format
			$('#question').val($('#question').val()+"<a href=\""+imgurl+"\">FILE</a>");
		}
		tb_remove();
	}
}
	
function insertAtCaret(areaId,text) {
	var txtarea = document.getElementById(areaId);
	var scrollPos = txtarea.scrollTop;
	var strPos = 0;
	var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
		"ff" : (document.selection ? "ie" : false ) );
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		strPos = range.text.length;
	}
	else if (br == "ff") strPos = txtarea.selectionStart;
	
	var front = (txtarea.value).substring(0,strPos);  
	var back = (txtarea.value).substring(strPos,txtarea.value.length); 
	txtarea.value=front+text+back;
	strPos = strPos + text.length;
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		range.moveStart ('character', strPos);
		range.moveEnd ('character', 0);
		range.select();
	}
	else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}
	txtarea.scrollTop = scrollPos;
}


$("input.delete_user").live("click", function(){
	$(this).closest("tr").slideUp(200, function(){
		$.post(MyAjax.ajaxurl, {
			action:"ssdelete_user",
			user_id:$(this).attr("id")
		}, function (data) {
				$(this).closest("tr").remove();
			}
		);
	});
})

});


