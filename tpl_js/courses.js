/*function save_course_list(name,table_name,select_name) {
	console.log($("select[name = " + select_name + "").val());
	var table_name = table_name || 1;
	var name = name || 1;
	if (name == 1 || table_name == 1) {
		return false;
	}
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			value : $("select[name = " + select_name + "").val(),
			item_type : name,
			course_id : $("input[name=course_id]").val(),
			flag : '7'
		},
		success: function(data){
		}
	})
}*/
function save_subjects_course(select_name, class_name) {
	//alert($("select[name = " + select_name + "").val());
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			value : $("select[name = " + select_name + "").val(),
			class_name : class_name,
			course_id : $("input[name=course_id]").val(),
			flag : '7'
		},
		success: function(data){
		}
	})
}
function save_course_text(value,field) {
	if(!$("input[name=course_id]").val() || $("input[name=course_id]").val() == 0) return false;
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			value : value,
			field : field,
			course_id : $("input[name=course_id]").val(),
			flag : '2'
		},
		success: function(data){
		}
	})
}
function save_course_checkbox(value,field) {
	var checkStatus;
	if (value) checkStatus=1;
	else checkStatus=0;
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			value : checkStatus,
			field : field,
			course_id : $("input[name=course_id]").val(),
			flag : '5'
		},
		success: function(data){
		}
	})
}
function create_course() {
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			flag : '1'
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
function load_coursesAll() {
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			flag : '3'
		},
		success: function(data){
			if(data != "") {
				$(".real-course-list").empty().append(data);
				$(".real-course-list").toggleClass('no-display');
				$(".no-courses-text").toggleClass('no-display');
			} else {
				alert("Курсов нет, если это не так, обратитесь к администратору");
			}
		}
	})
}
function load_course(id) {
	$("input[name=course_id]").val(id);
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			course_id : id,
			flag : '4'
		},
		success: function(data){
			if(data['success'] != false) {
				if($(".main_block").hasClass('no-display')){
					$(".main_block").toggleClass('no-display');
					$(".message").toggleClass('no-display');
				}
				for(var id in data['course']) {
					if(id == 'is_onMain' || id == 'is_active') continue;
					if(id == 'subjects' || id == 'classes') continue;
					$("input[name = " + id + "]").val(data['course'][id]);
				}
				if(data['course']['is_onMain']==1) $("input[name = is_onMain]").attr("checked",true); 
				else $("input[name = is_onMain]").attr("checked",false);
				if(data['course']['is_active']==1) $("input[name = is_active]").attr("checked",true); 
				else $("input[name = is_active]").attr("checked",false);
				
				if(data['course']['subjects']) {
					$(".course-manager-body").empty().append(data['course']['subjects']);
				}
			} else {
				alert("Не удается загрузить курс, обратитесь к администратору");
			}
		}
	})
}
function delete_course() {
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			course_id : $("input[name=course_id]").val(),
			flag : '6'
		}
	})
	load_coursesAll();
	$(".real-course-list").toggleClass('no-display');
	$(".no-courses-text").toggleClass('no-display');
}
function clean_subjects_for_course_and_class(course, class_id) {
	$.ajax({
		url : "../../tpl_php/ajax/courses.php",
		method : 'post',
		dataType : 'json',
		data : {
			course_id : course,
			class_id : class_id,
			flag : '12'
		},
		success: function(data) {
			$(".course-manager-body").empty().append(data['subjects']);

		}
	})
}
window.onload = function(){
	load_coursesAll();
}