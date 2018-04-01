function save_course_text(value,field) {
	if(!$("input[name=theme_id]").val() || $("input[name=theme_id]").val() == 0) return false;
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			value : value,
			field : field,
			course_id : $("input[name=theme_id]").val(),
			flag : '3'
		},
		success: function(data){
		}
	})
}
function load_themesAll() {
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			flag : '1'
		},
		success: function(data){
			if(data != "") {
				$(".real-course-list").append(data);
				$(".real-course-list").toggleClass('no-display');
				$(".no-courses-text").toggleClass('no-display');
			} else {
				alert("тем нет, если это не так, обратитесь к администратору");
			}
		}
	})
}
function create_theme() {
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			flag : '2'
		},
		success: function(data){
			if(data != "") {
				$(".real-course-list").append(data);
				if($(".real-course-list").hasClass('no-display')){
					$(".real-course-list").toggleClass('no-display');
					$(".no-courses-text").toggleClass('no-display');
				}
			} else {
				alert("Курс не создался, обратитесь к администратору");
			}
		}
	})
}
function load_theme(id) {
	$("input[name=theme_id]").val(id);
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			theme_id : id,
			flag : '4'
		},
		success: function(data){
			if(data['success'] != false) {
				if($(".main_block").hasClass('no-display')){
					$(".main_block").toggleClass('no-display');
					$(".message").toggleClass('no-display');
				}
				for(var id in data['course']) {
					if(id == 'theme_course' || id == 'theme_class' || id == 'theme_subject') continue;
					$("input[name = " + id + "]").val(data['course'][id]);
				}
				if(data['course']['theme_course']) {
					$('select[name = courses]').empty().append(data['course']['theme_course']);
				}
				if(data['course']['theme_class']) {
					$('select[name = classes]').empty().append(data['course']['theme_class']);
				}
				if(data['course']['theme_subject']) {
					$('select[name = subjects]').empty().append(data['course']['theme_subject']);
				}
			} else {
				alert("Не удается загрузить тему, обратитесь к администратору");
			}
		}
	})
}
function get_courses() {
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			flag : '5'
		},
		success: function(data){
			if(data != "") {
				$("select[name = course_list]").empty().append(data);
			} else {
				alert("Нет курсов, обратитесь к администратору, если это не так.");
			}
			get_subjects();
		}
	})
}
function get_classes() {
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			flag: '6'
		},
		success: function(data){
			if(data != "") {
				$("select[name = classes_list]").empty().append(data);
			} else {
				console.log("Нет классов, обратитесь к администратору, если это не так.");
			}
			get_subjects();
		},
		error: function(){
			$("select[name = classes_list]").empty()
		}
	})
}
function get_subjects() {
	var course = $("select[name = course_list]").val() || 0;
	var class_id = $("select[name = classes_list]").val() || 0;
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			course : course,
			class : class_id,
			flag: '7'
		},
		success: function(data){
			if(data != "") {
				$("select[name = subjects_list]").empty().append(data);
			} else {
				console.log("Нет предметов, обратитесь к администратору, если это не так.");
			}
		},
		error: function(){
			$("select[name = subjects_list]").empty()
		}
	})
}
function save_theme_filter(element_name, column_name, priority) {
	// priority - значение 1-3, с целью удаления фильтров для младших приоритетов
	if(column_name != 'theme_class') {
		var select_name = "select[name="+element_name+"]";
	} else {
		var select_name = "#" + element_name
	}
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			value : $(select_name).val(),
			column_name : column_name,
			priority : priority,
			theme_id : $("input[name=theme_id]").val(),
			flag : '8'
		},
		success: function(data){
			if(data['subjects']) {
				$("select[name = subjects]").empty().append(data['subjects']);
			}
		}
	})

}
function get_theme_list() {
	var course   = $("select[name = course_list]").val()   || 0;
	var class_id = $("select[name = classes_list]").val()  || 0;
	var subject  = $("select[name = subjects_list]").val() || 0;
	$.ajax({
		url : "../../tpl_php/ajax/themes.php",
		method : 'post',
		dataType : 'json',
		data : {
			course : course,
			class_id : class_id,
			subject : subject,
			flag : '9'
		},
		success: function(data){
			//alert(data['text'])
			$(".real-course-list").empty()
			if(data['text'] != "") {
				$(".real-course-list").append(data['text']);
				if($(".real-course-list").hasClass('no-display')){
					$(".real-course-list").toggleClass('no-display');
					$(".no-courses-text").toggleClass('no-display');
				}
			} else {
				alert("тем нет, если это не так, обратитесь к администратору");
			}
		}
	})
}
window.onload = function(){
	get_courses();
	load_themesAll();
	$(".hat-filter").change(function(){
		get_theme_list();
	})
}