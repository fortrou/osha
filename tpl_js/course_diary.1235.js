function get_notifsType() {
	if($("input[name = is_archieve]").val()) {
		return 2;
	} else {
		return 3;
	}
}
function get_lessonsOnThemes() {
	var gif_string = '<img width="200px" height="200px"  style="text-align: center; margin-left:30%;"\
	src="http://online-shkola.com.ua/tpl_img/loading-01.gif">'
	$("#main-part").empty().append(gif_string);
	var course_id = $("input[name = course]").val()   || 0;
	var class_id  = $("input[name = class]").val() 	  || 0;
	var id 		  = $("input[name = id]").val() 	  || 0;
	var subject   = $("select[name = subject]").val() || 0;
	$.ajax({
		url : '../tpl_php/ajax/course_diary.php' ,
		method : 'POST' ,
		dataType : 'JSON',
		data : {
				 lang	   : $("input[name = language-common]").val(),
		 		 course_id : course_id,
				 class_id  : class_id,
				 subject   : subject,
				 id 	   : id,
				 flag 	   : '1'
			   },
		success : function (data){
			$("#main-part").empty().append(data['result']);
			$(".timetable-subject-name-selector .progress-container").empty().append(data['percentage']);
			if($(".timetable-tab-container[data-open = 1]").length) {
				//$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-opened");
				//$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-closed");
				$(".timetable-tab-container[data-open = 1]").toggleClass("timetable-opened");
				$(".timetable-tab-container[data-open = 1]").toggleClass("timetable-closed");
			} else {
				$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-closed");
				$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-opened");
			}
		}
	})
}
function get_subjects() {
	if($("input[name = course]").val() == 0) {
		$.ajax({
			url : "../tpl_php/subjects.php",
			method : 'post',
			dataType : 'json',
			data : {
				lang : $("input[name = language-common]").val(),
				class_id : $("select[name = class]").val(),
				flag : '4'
			},
			success: function(data){
				$("#subject").empty().append(data)
			}
		})
	}
}
function add_notif(text_ru, text_ua){
	$.ajax({
		url : '../tpl_php/ajax/notifs.php',
		method : 'POST',
		dataType : 'json',
		data :{
			text_ru : text_ru,
			text_ua : text_ua,
			type : get_notifsType(),
			flag : '1'
		},
		success : function(){}
	})
}
function get_notifs(){
	$.ajax({
		url : '../tpl_php/ajax/notifs.php',
		method : 'POST',
		dataType : 'json',
		data :{
			lang : $("input[name = language-common]").val(),
			type : get_notifsType(),
			flag : '2'
		},
		success : function(data){
			var str = "";
			var cnt = 1;
			for(var id in data){
				if($("input[name = language-common]").val() == "ru"){
					str += "<li> <span>Примечание "+cnt+":</span> "+data[id]['first'];
				} else {
					str += "<li> <span>Примітка "+cnt+":</span> "+data[id]['first'];
				}
				if($("input[name = level]").val() == 4) {
					str += "<span class='delete' onclick=\"del_notif("+id+")\">удалить</span>";
				}
				str += "</li>";
				cnt++;
			}
			$(".tabel_premich").empty();
			$(".tabel_premich").append(str);
		}
	})
}
function del_notif(id){
	//alert(id);
	$.ajax({
		url : '../tpl_php/ajax/notifs.php',
		method : 'POST',
		dataType : 'json',
		data :{
			id : id,
			flag : '5'
		},
		success : function(){
		}
	})
	get_notifs();
}
function set_selected_class(id) {
	$("#course-students div").removeClass("course-journal-selected");
	$("#course-students div[data-rel = " + id + "]").toggleClass("course-journal-selected");
}
function get_lessonsOnThemes_Teacher(id) {
	var gif_string = '<tr><td colspan="7" style="text-align: center; padding: 30px;">\
							<img width="100px" height="100px" src="http://online-shkola.com.ua/tpl_img/loading-01.gif">\
							</td></tr>'
	if(!$(".student-data-block").hasClass("no-display")) {
		$(".student-data-block").toggleClass("no-display");
	}
	$(".student-data-block").empty();
	$("#main-part").empty().append(gif_string);
	
	$("input[name = user_id]").val(id);
	var id 		  = id || 0;
	if(id == 0) 	return false;
	var high_id	  = $("input[name = id]").val() 	  || 0;
	var course_id = $("input[name = course]").val()   || 0;
	var level 	  = $("input[name = level]").val() 	  || 0;
	var class_id  = $("select[name = class]").val()   || 0;
	var subject   = $("select[name = subject]").val() || 0;
	set_selected_class(id);
	$.ajax({
		url : '../tpl_php/ajax/course_diary.php' ,
		method : 'POST' ,
		dataType : 'JSON',
		data : {
				 lang	   : $("input[name = language-common]").val(),
		 		 course_id : course_id,
				 class_id  : class_id,
				 subject   : subject,
				 high_id   : high_id,
				 level	   : level,
				 id 	   : id,
				 flag 	   : '1'
			   },
		success : function (data){
			$("#main-part").empty().append(data['result']);
			$(".timetable-subject-name-selector .progress-container").empty().append(data['percentage']);
			if($(".student-data-block").hasClass("no-display")) {
				$(".student-data-block").toggleClass("no-display");
			}
			if($(".timetable-tab-container[data-open = 1]").length) {
				//$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-opened");
				//$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-closed");
				$(".timetable-tab-container[data-open = 1]").toggleClass("timetable-opened");
				$(".timetable-tab-container[data-open = 1]").toggleClass("timetable-closed");
			} else {
				$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-closed");
				$(".timetable-tab-container:nth-child(1)").toggleClass("timetable-opened");
			}
			$(".student-data-block").empty().append(data['student_data']);
			$(".student-data-block").append("<br> Выбранный предмет: " + $("select[name = subject] option:selected").text());
			$(".student-data-block").append("<br> Дата официального зачисления: " + data['date_reg']);
		}
	})
}
function get_usersOnCourse_Teacher() {
	var id 		  = $("input[name = id]").val() 	   || 0;
	var course_id = $("input[name = course]").val()    || 0;
	var class_id  = $("select[name = class]").val() || 0;
	var subject   = $("select[name = subject]").val()  || 0;
	$.ajax({
		url : '../tpl_php/ajax/course_diary.php' ,
		method : 'POST' ,
		dataType : 'JSON',
		data : {
		 		 course_id : course_id,
				 class_id  : class_id,
				 id 	   : id,
				 subject   : subject,
				 flag 	   : '3'
			   },
		success : function (data){
			$("#course-students").empty().append(data);
		}
	})
}
$(document).ready(function() {
	get_subjects();
	if($("input[name = level]").val() == 1)
		get_lessonsOnThemes();
	else 
		get_usersOnCourse_Teacher();

	$("select[name = subject]").change(function(){
		if($("input[name = level]").val() == 1)
			get_lessonsOnThemes();
		else 
			get_lessonsOnThemes_Teacher($("input[name = user_id]").val());
	})
	$("select[name = class]").change(function(){
		get_subjects();
		get_usersOnCourse_Teacher();
	})
	//if($("input[name = is_archieve]").val()) {
		get_notifs();
	//}
	$("input[name = add_notif]").click(function() {
		add_notif($("input[name = notif_ru]").val(), $("input[name = notif_ua]").val());
		$("input[name = notif_ru]").val('');
		$("input[name = notif_ua]").val('');
		get_notifs();
	})
	/*$(".theme-mark-input").on("input", function() {
		alert('a')
	})*/
})
function update_theme_mark(input) {
	var ajax_request;
	var subject_id = $(input).attr("data-subject");
	var class_id   = $(input).attr("data-class");
	var theme_id   = $(input).attr("data-theme");
	var user_id    = $(input).attr("data-user");
	var mark       = $(input).val();
	ajax_request = $.ajax({
		url : '../tpl_php/ajax/course_diary.php' ,
		method : 'POST' ,
		dataType : 'JSON',
		data : {
				 subject_id : subject_id,
				 class_id 	: class_id,
				 theme_id   : theme_id,
		 		 user_id    : user_id,
				 mark 		: mark, 
				 flag 	    : '4'
			   },
		success : function (data){
			alert("good");
		}
	})
}
function timetable_spoiler(id) {
	$("#timetable_container_" + id).toggleClass("timetable-closed");
	$("#timetable_container_" + id).toggleClass("timetable-opened");
}