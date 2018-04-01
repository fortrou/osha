function change_class(user_id,class_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			user_id : user_id,
			flag: '13'
		},
		success : function(data){
		}

	});
	render_students($("select[name = red_class]").val());
}
function render_students(class_id){
	//alert(class_id);
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			flag: '12'
		},
		success : function(data){
		var str = data[0];
		$(".hat_class").empty();
		$(".hat_class").append("класс: "+data[1]);
		$("#red_stud_list").empty();
		$("#red_stud_list").append(str);
		$("input[name = class_name]").val(data[1]);
		}

	});

}
function delete_student(student_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			student_id : student_id,
			flag: '15'
		},
		success : function(data){
		}

	});
	render_students($("select[name = red_class]").val());

}
function lock_unlock_student(student_id){

}
function update_manager(class_id, manager_id){
	//alert(class_id+ ' ' +manager_id)
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			manager_id : manager_id,
			flag: '14'
		},
		success : function(data){
		}

	});

}
function get_marked_subjects(class_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			flag: '36'
		},
		success : function(data){
			$("select[name = red_subject]").css("display","block");
			$("select[name = red_subject]").empty();
			$("select[name = red_subject]").append(data);
		}

	});
}
function rewrite_subjects(class_id,subjects_arr){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			subjects : subjects_arr,
			flag: '37'
		},
		success : function(data){
		}

	});
}
function change_name(class_id,name){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			class_name : name,
			flag: '38'
		},
		success : function(data){
		}

	});
	render_students(class_id);
}
function update_class_list(class_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			flag: '39'
		},
		success : function(data){
			$("select[name = red_class]").empty();
			$("select[name = red_class]").append(data);
		}

	});
}
function open_modal(){
	$("#pop_create").css("display","block");
	$("#pop_create").css("z-index","9999");
	
}
function close_modal(){
	$("#pop_create").css("display","none");
	$("#pop_create").css("z-index","-9999");
	$("#create_subj").val("");
}
function create_class(class_name,subjects,manager_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_name : class_name,
			subjects : subjects,
			manager : manager_id,
			flag: '40'
		},
		success : function(data){
		}
	});
}
function change_open_status(class_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			flag: '41'
		},
		success : function(data){
		}

	});
}

function delete_class(id_class){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_class : id_class,
			flag: '57'
		},
		success : function(data){
		}

	});
}
window.onload=function(){
	render_students($("select[name = red_class]").val());
	$("select[name = red_class]").change(function(){
		render_students($(this).val());
		get_marked_subjects($(this).val());
	})
	$("#red_subject").change(function(){
		rewrite_subjects($("select[name = red_class]").val(),$(this).val());
	})
	$("input[name = confirm_name]").click(function(){
		change_name($("select[name = red_class]").val(),$("input[name = class_name]").val())
		update_class_list($("select[name = red_class]").val());
	})
	$("input[name = create_ok]").click(function(){
		if($("input[name = create_name]").val() == "" || $("#create_subj").val().length == 0){
			alert("Не все поля заполнены, попробуйте еще раз");
		}
		else{
			create_class($("input[name = create_name]").val(),$("#create_subj").val(),$("select[name = create_manager]").val());
			close_modal();
		}

	})
	$("select[name = opened_class]").change(function(){
		change_open_status($(this).val());
	})
	$("input[name = del_cur_class]").click(function(){
		if($("select[name = red_class]").val()==0) return false;
		else{
			var tf_conf = confirm("Вы действительно хотите удалить класс?");
			if (tf_conf == true) {
				delete_class($("select[name = red_class]").val());
				update_class_list(0);
				$("select[name = red_subject]").css("display","none");
				//alert('утром закину удаление(эта надпись выскочит только при выбраном классе)');
			}
			else{
				return false;
			}
		}
	});
}