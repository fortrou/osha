function escapeHtml(text, flag) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  if (flag == 2) {
  	map["'"] = '|';
  	map['"'] = '||';
  }
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
function close_docs_modal(){
	$("#docs").css("display","none");
	$("#docs").css("z-index",-9999);
}
function open_docs_modal(id){
	$("#docs").css("display","block");
	$("#docs").css("z-index",9999);
	$("input[name = user_id]").val(id);
	//get_doc_by_id(id);
	
}
function get_doc_by_id(id){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id : id,
			flag : '66'
		},
		success: function(data){
			$("#doc_list").empty();
			$("#doc_list").append(data["docs"]);
		}

	})
}
function attach_document(link,name){
	$("input[name = file_attached]").val(link);
	$("#attached").empty();
	$("#attached").append("<span class='attached_files_list'>Прикрепленные файлы:</span><br>"+name);
	close_docs_modal();
}
function reload_page(param){
	switch(param){
		case 1: 
			setTimeout(get_doc_by_id,5000,$("input[name = user_id]").val());
			break;
	}
}
function common_getMessages(id_chat){
	$("input[name = com_id_chat]").val(id_chat);
//alert(id_chat)
$.ajax({
	url : "../tpl_php/ajax/chat.php",
	method : 'post',
	dataType : 'json',
	data : {
	id_chat : id_chat,
	from : $("input[name = com_id_from]").val(),
		flag: '4'
	},
	success : function(data){
		var str = data['data'];
	
		$("#com_chat_field").empty();
		$("#com_chat_field").append(str);
		$("#com_chat_field").scrollTop(10000000);
		$("#com_to_chat").empty();
		$("#com_to_chat").append(data["name_s"]);
		$("#com_from_chat").empty();
		$("#com_from_chat").append(data["name_f"]);
		$("#second_ava").attr("src",data["avatar"]);
		console.log(data["chat_name"])
		$("#chat_name").empty().append(data["chat_name"])
		if (data['locked'] == 0) {
			$("input[name = com_send]").css("display","");
		}
		else{
			$("input[name = com_send]").css("display","none");
		}
		var a = setTimeout(make_read,2000);	
	}
	});
}
function common_getMessages1(id_chat){
	$("input[name = com_id_chat]").val(id_chat);
//alert(id_chat)
$.ajax({
	url : "../tpl_php/ajax/chat.php",
	method : 'post',
	dataType : 'json',
	data : {
	id_chat : id_chat,
	from : $("input[name = com_id_from]").val(),
		flag: '4'
	},
	success : function(data){
		var str = data['data'];
	
		$("#com_chat_field").empty();
		$("#com_chat_field").append(str);
		$("#com_chat_field").scrollTop(10000000);
		$("#com_to_chat").empty();
		$("#com_to_chat").append(data["name_s"]);
		$("#com_from_chat").empty();
		$("#com_from_chat").append(data["name_f"]);
		$("#second_ava").attr("src",data["avatar"]);
		
		}
	});

}
function easy_close(){
	$("#tabel_link_content").css("display","none");
}
function make_read(){
	$.ajax({
			url : "../tpl_php/ajax/chat.php",
			method : 'post',
			dataType : 'json',
		data : {
			from : $("input[name = com_id_from]").val(),
			id_chat : $("input[name = com_id_chat]").val(),
			flag : '6'
		},
		success: function(){
		},
		error: function(){
			common_getMessages1($("input[name = com_id_chat]").val());
		}
	});
	
}
function common_getMessages_lesson(id_chat){
	//alert(id_chat);
	$("input[name = com_id_chat]").val(id_chat);
	$.ajax({
		url : "../tpl_php/ajax/chat.php",
		method : 'post',
		dataType : 'json',
		data : {
		id_chat : id_chat,
		from : $("input[name = com_id_from]").val(),
			flag: '4'
		},
		success : function(data){
			var str = data['data'];
			$("#chat_name").empty();
			$("#chat_name").append(data['name_pupil']);
			$("#com_chat_field").empty();
			$("#com_chat_field").append(str);
			$("#com_chat_field").scrollTop(10000000);
			$("#com_to_chat").empty();
			$("#com_to_chat").append(data["name_s"]);
			$("#com_from_chat").empty();
			$("#com_from_chat").append(data["name_f"]);
			$("#second_ava").attr("src",data["avatar"]);
		}
	});
}
function common_send(){
	/*alert($("textarea[name = com_text_message]").val()+' '+$("input[name = com_id_from]").val()+' '
		+$("input[name = com_id_chat]").val()+' '+$("input[name = file_attached]").val());*/
	$.ajax({
			url : "../tpl_php/ajax/chat.php",
			method : 'post',
			dataType : 'json',
		data : {
			message : escapeHtml($("textarea[name = com_text_message]").val()),
			doc : $("input[name = file_attached]").val(),
			from : $("input[name = com_id_from]").val(),
			chat_id : $("input[name = com_id_chat]").val(),
			flag : '1'
		},
		success: function(){
				
		}
	});
	$("input[name = file_attached]").val("");
	$("#attached").empty();
	$("textarea[name = com_text_message]").val("");
	common_getMessages($("input[name = com_id_chat]").val());
	$("textarea[name = com_text_message]").focus();
}
function common_send_all(){

	$.ajax({
			url : "../tpl_php/ajax/chat.php",
			method : 'post',
			dataType : 'json',
		data : {
			message : escapeHtml($("textarea[name = com_text_message_all]").val()),
			doc :$("input[name = file_attached]").val(),
			id_lesson : $("input[name = com_lesson]").val(),
			from : $("input[name = com_id_from]").val(),
			flag : '5'
		},
		success: function(){
				
		}
	});
	$("input[name = file_attached]").val("");
	$("#attached").empty();
	$("textarea[name = com_text_message_all]").val("");
	$("textarea[name = com_text_message_all]").focus();
}

function get_chats_t(lesson,lang){
	$.ajax({
			url : "../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
		data : {
			id_lesson : lesson,
			lang : lang,
			flag : '20'
		},
		success: function(data){

			$("#chat_name").append(data[0]);
			$(".oc_spisok").append(data[1]);
		}
		
	});
	
}
function get_chats_p(lesson,lang,pup_id){
	$.ajax({
			url : "../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
		data : {
			id_lesson : lesson,
			id : pup_id,
			lang : lang,
			flag : '21'
		},
		success: function(data){
			$("#chat_name").append(data[0]);
			$(".oc_spisok").append(data[1]);
		}
		
	});
	
}

function getSubjects( class_id )
{
	$.ajax({
		url : '../tpl_php/subjects.php' ,
		method : 'POST' , 
		dataType : 'json' ,
		data : { 
			id : class_id,
			lang : $("input[name=lang]").val(),
			flag : '1' 
		} ,
		success : function ( data ) {
			if ( data['subjects'] )
			{
				var str = "";
				$("select[name = com_filter_subject]").empty();

				for ( var id in data['subjects'] )
				{
					str += "<option value='" + id + "'>" + data['subjects'][id] + "</option>";
				}
				$("select[name = com_filter_subject]").append(str);
			}
		}
	});
};

function get_chats_on_class(class_id){
	$.ajax({
			url : "../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
		data : {
			class_id : class_id,
			flag : '22'
		},
		success: function(data){
			//alert(data);
			$(".other_chats").empty();
			$(".other_chats").append(data);
		}
		
	});
}
function get_chats_on_class_and_subject(class_id,subject_id){
	$.ajax({
			url : "../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
		data : {
			class_id : class_id,
			subject_id : subject_id,
			flag : '23'
		},
		success: function(data){
			//alert(data);
			$(".other_chats").empty();
			$(".other_chats").append(data);
		}
		
	});
}
function get_chats_on_status(status_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			subject_id : subject_id,
			flag : '24'
		},
		success: function(data){
			//alert(data);
			$(".other_chats").empty();
			$(".other_chats").append(data);
		}
		
	});

}

function get_users(type){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			type : type,
			flag : '25'
		},
		success: function(data){
			//alert(data);
			$("select[name = com_create_list]").empty();
			$("select[name = com_create_list]").append(data);
		}
		
	});
}
function get_users_t_c(type,class_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			type : type,
			flag : '26'
		},
		success: function(data){
			//alert(data);
			$("select[name = com_create_list]").empty();
			$("select[name = com_create_list]").append(data);
		}
		
	});
}
function get_all_names(array){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			array : array,
			flag : '60'
		},
		success: function(data){
			//alert(data);
			$(".selected_list").empty();
			$(".selected_list").append(data);
		}
		
	});
}
function create_chat(from_id, target_id, chat_name){
	/*alert(from_id+' '+target_id+' '+chat_name)*/
	//alert("a");
	if(from_id == "" || from_id == null || chat_name == "" || chat_name == null)
		alert("введите все данные");
	else{
		$.ajax({
			url : "../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
			data : {
				from_id : from_id,
				target_id : target_id,
				chat_name : chat_name,
				flag : '27'
			},
			success: function(data){
				
			}
			
		});
		//get_created_chats(from_id);
		easy_close();
	}
}
function get_created_chats(from_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			from_id : from_id,
			flag : '28'
		},
		success: function(data){
			$(".created_chats").empty();
			$(".created_chats").append(data);
		}
		
	});
}
function getMychats(id_to){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_to : id_to,
			flag : '29'
		},
		success: function(data){
			$(".chats_with_me").empty();
			$(".chats_with_me").append(data);
		}
		
	});
}
function getNotMychats(id_to){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_to : id_to,
			flag : '32'
		},
		success: function(data){
			$(".chats_without_me").empty();
			$(".chats_without_me").append(data);
		}
		
	});
}
function common_getChatsWithTeachers_student(id_student){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_student : id_student,
			lang : $("input[name=lang]").val(),
			flag : '43'
		},
		success: function(data){
			$(".common_chats_with_teachers").empty();
			$(".common_chats_with_teachers").append(data);
		},
		error: function(){
			$(".common_chats_with_teachers").empty();
			$(".common_chats_with_teachers").append("нет данных");	
		}
		
	});	
}
function common_getMyChatWithManager_student(id_student){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_student : id_student,
			lang : $("input[name=lang]").val(),
			flag : '44'
		},
		success: function(data){
			$(".chat_with_manager").empty();
			$(".chat_with_manager").append(data);
		},
		error: function(){
			$(".chat_with_manager").empty();
			$(".chat_with_manager").append("не назначен куратор");	
		}
		
	});	
}
function common_getChatsWithMe_student(id_student){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_student : id_student,
			flag : '45'
		},
		success: function(data){
			$(".chats_with_me_stud").empty();
			$(".chats_with_me_stud").append(data);
		},
		error: function(){
			$(".chats_with_me_stud").empty();
			if($("input[name=lang]").val() == 'ru')
				$(".chats_with_me_stud").append("<p>таких чатов нет</p>");
			else
				$(".chats_with_me_stud").append("<p>таких чатів немає</p>");
		}
		
	});
}
function common_getTechHelp_student(id_student){

	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_student : id_student,
			flag : '70'
		},
		success: function(data){
			if (data != 0) {
				$("#support").css("display","");
				$("#support").empty().append(data);
			} else {
				$("#support").css("display","none");
			}
			console.log(data);
		},
		error: function(){
			$("#support").css("display","none");
		}
		
	});
}
function common_getChatsWithPupils_teacher(id_teacher){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_teacher : id_teacher,
			lang : $("input[name=lang]").val(),
			flag : '46'
		},
		success: function(data){
			$(".chats_with_my_pupils").empty();
			$(".chats_with_my_pupils").append(data);
		},
		error: function(){
			$(".chats_with_my_pupils").empty();
			$(".chats_with_my_pupils").append("нет данных");	
		}
		
	});	
}
function common_getChatsWithMe_teacher(id_teacher,level_access){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_teacher : id_teacher,
			level_access : level_access,
			flag : '47'
		},
		success: function(data){
			$(".chats_with_me_teacher").empty();
			$(".chats_with_me_teacher").append(data);
		},
		error: function(){
			$(".chats_with_me_teacher").empty();
			$(".chats_with_me_teacher").append("<p>Нет чатов с вашим участием</p>");	
		}
		
	});
}
function common_getChatsWithStudents_manager(id_manager){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_manager : id_manager,
			flag : '48'
		},
		success: function(data){
			$(".chats_with_pupils").empty();
			$(".chats_with_pupils").append(data);
		},
		error: function(){
			$(".chats_with_pupils").empty();
			$(".chats_with_pupils").append("<p>Нет чатов с вашим участием</p>");	
		}
		
	});
}
function common_getChatsWithoutMe_manager(id_manager,level_access){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_manager : id_manager,
			level_access : level_access,
			flag : '51'
		},
		success: function(data){
			$(".chats_without_me_manager").empty();
			$(".chats_without_me_manager").append(data);
		},
		error: function(){
			$(".chats_without_me_manager").empty();
			$(".chats_without_me_manager").append("<p>Нет чатов без вашего участия</p>");	
		}
		
	});
}
function common_getChatsWithMe_manager(id_manager,level_access){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_manager : id_manager,
			level_access : level_access,
			flag : '50'
		},
		success: function(data){
			$(".chats_with_me_manager").empty();
			$(".chats_with_me_manager").append(data);
		},
		error: function(){
			$(".chats_with_me_manager").empty();
			$(".chats_with_me_manager").append("<p>Нет других чатов с вами</p>");	
		}
		
	});
}
function common_getChatsOtherUsers_manager(id_manager,level_access){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_manager : id_manager,
			level_access : level_access,
			flag : '49'
		},
		success: function(data){
			$(".chats_other_users").empty();
			$(".chats_other_users").append(data);
		},
		error: function(){
			$(".chats_other_users").empty();
			$(".chats_other_users").append("<p>Нет других чатов с вами</p>");	
		}
		
	});
}
function common_getChatsOtherUsers_admin(id_admin,level_access,id_class,id_subj,name){
	name = name || "";
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_admin : id_admin,
			level_access : level_access,
			id_class : id_class,
			id_subj : id_subj,
			name : name,
			flag : '52'
		},
		success: function(data){
			$(".other_users_chats").empty();
			$(".other_users_chats").append(data);
		},
		error: function(){
			$(".other_users_chats").empty();
			$(".other_users_chats").append("<p>Нет чатов других пользователей, или возникла ошибка</p>");	
		}
		
	});
}
function common_getChatsWithMe_admin(id_admin,level_access,id_class,id_subj,name){
	name = name || "";
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_admin : id_admin,
			level_access : level_access,
			id_class : id_class,
			id_subj : id_subj,
			name : name,
			flag : '63'
		},
		success: function(data){
			$(".other_my_chats").empty();
			$(".other_my_chats").append(data);
		},
		error: function(){
			$(".other_my_chats").empty();
			$(".other_my_chats").append("<p>Нет чатов с Вами, или возникла ошибка</p>");	
		}
		
	});
}
function common_getChatsWithOutMe_admin(id_admin,level_access,id_class,id_subj,name){
	name = name || "";
	//alert("a");
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_admin : id_admin,
			level_access : level_access,
			id_class : id_class,
			id_subj : id_subj,
			name : name,
			flag : '65'
		},
		success: function(data){
			$(".chats_without_me_manager").empty();
			$(".chats_without_me_manager").append(data);
		},
		error: function(){
			$(".chats_without_me_manager").empty();
			$(".chats_without_me_manager").append("<p>Нет чатов с Вами, или возникла ошибка</p>");	
		}
		
	});
}
function common_getChatsHelp_admin(id_admin,level_access,id_class,id_subj,name){
	name = name || "";
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_admin : id_admin,
			level_access : level_access,
			id_class : id_class,
			id_subj : id_subj,
			name : name,
			flag : '69'
		},
		success: function(data){
			$(".our_chats").empty();
			$(".our_chats").append(data);
			//$(".chat-show-hide p:not(.cat_small_hat)").toggleClass('no_displayed_chat');
		},
		error: function(){
			$(".our_chats").empty();
			$(".our_chats").append("<p>Нет чатов техподдержки, или возникла ошибка</p>");	
		}
		
	});
}
function lesson_getChatWithLessonTeacher_student(id_student,id_lesson,lang){
	//alert("sad");
	//alert(id_student+' '+id_lesson+' '+lang)
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_student : id_student,
			id_lesson : id_lesson,
			lang : lang,
			flag : '54'
		},
		success: function(data){
			//alert(data['id'] + ' ' + data['chat_name']);
			$("input[name = com_id_chat]").val(data['id']);
			$("#chat_name").empty().append(data['fi']);
			common_getMessages(data['id']);
		},
		error: function(){
			//alert("У вас огромная ошибка, или учитель к данному уроку не назначен");
		}
		
	});
	
}
function lesson_getChatsWithLessonStudents_teacher(id_teacher,id_lesson,lang){
	//alert("sad");
	//alert(id_teacher+' '+id_lesson+' '+lang)
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_teacher : id_teacher,
			id_lesson : id_lesson,
			lang : lang,
			flag : '55'
		},
		success: function(data){
			$(".com_chat_usr_list").empty();
			$(".com_chat_usr_list").append(data);

		},
		error: function(){
			//alert("У вас огромная ошибка, или учеников в этом уроке не существует");
		}
		
	});
	//clearInterval(interv);
	//var pre_interv = setInterval(lesson_getChatsWithLessonStudents_teacher,60000,id_teacher,id_lesson,lang);
}
function lesson_getSoloChatWithStudent_teacher(id_chat){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_chat : id_chat,
			flag : '56'
		},
		success: function(data){
			$("input[name = com_id_chat]").val(id_chat)
			$(".solo_name").empty();
			$(".solo_name").append(data['fio']);
			$(".comonline_chat_onclick_uchen").css("display","none");
			$("#content1").css("display","block");
			common_getMessages(id_chat);
			$("#second_ava").attr("src",data['avatar'])


		},
		error: function(){
			//alert("У вас огромная ошибка, или учеников в этом уроке не существует");
		}
		
	});
	
}
function lock_unlock(id_chat){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_chat : id_chat,
			flag : '64'
		},
		success: function(data){
			common_getChatsWithStudents_manager($("input[name = com_id_from]").val());
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		},
		error: function(){
			common_getChatsWithStudents_manager($("input[name = com_id_from]").val());
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		}
		
	});
}
function delete_chat(id_chat){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id_chat : id_chat,
			flag : '68'
		},
		success: function(data){
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		},
		error: function(){
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		}
		
	});
}