<?php
	session_start();
	if(isset($_SESSION['data']) && (!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0)) 
	    require_once '../tpl_php/autoload.php';
	else
	    require_once '../tpl_php/autoload_light.php';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	//var_dump($_SESSION['data']);
?>
<!DOCTYPE html> 
<head>  	
	<?php if($_COOKIE['lang'] == 'ru'): ?>	
		<title>Диалоги - Онлайн Школа</title>
	<?php endif; ?>
	<?php if($_COOKIE['lang'] == 'ua'): ?>	
		<title>Діалоги - Онлайн Школа</title>
	<?php endif; ?>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<script type="text/javascript" src="../tpl_js/common_chat.js"></script>
	
 
	<?php
		include ("../tpl_blocks/head.php");
	?>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	<?php
		
			printf("<form>
				<input type='hidden' name='com_id_chat' id='com_id_chat' value='%s'>
				<input type='hidden' name='com_id_from' id='com_id_from' value='%s'>
				<input type='hidden' name='com_level_from' id='com_level_from' value='%s'>
				</form>",(int)$_GET['id'],$_SESSION['data']['id'],$_SESSION['data']['level']);
		
	?>
	<script type='text/javascript'>
		var $com_chat_id = $("#com_id_chat");
		common_getMessages($com_chat_id.val());
	</script>
	<div class="content">
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=7";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=7",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(7, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div id="docs" >
			<div class="close"onclick="close_docs_modal()">X</div>
			
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>	
			<h2>Ваши документы</h2>
			<?php else: ?>
			<h2>Ваші документи</h2>
			<?php endif; ?>
			<form method="post" action="" enctype='multipart/form-data'>
			<script>
				$(function (){
					if($('#chose_file').length)
					{
						$('#chose_file').click(function(){
							$('#chose_file_input').click();
							return(false);
						});
						$('#chose_file_input').change(function(){
							$('#chose_file_text').html($(this).val());
						}).change(); // .change() в конце для того чтобы событие сработало при обновлении страницы
					}
				});
			</script>
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<a id="chose_file" href="">Прикрепить файл</a>
				<span id="chose_file_text"></span> 
				<input id="chose_file_input" type="file" name="file_upl"><br>
				<p>Чтобы добавить файл на сайт,<br />нажмите на него в списке файлов</p>
			<?php else: ?>
				<a id="chose_file" href="">Прикріпити файл</a>
				<span id="chose_file_text"></span> 
				<input id="chose_file_input" type="file" name="file_upl"><br>
				<p>Щоб додати файл на сайт,<br />натисніть на нього в списку файлів</p>
			<?php endif; ?>
			<input type="hidden" name="lang" value="<?php print($_COOKIE['lang']); ?>">
			<input type="hidden" name="user_id" value=""></input>
			<!--<input type="submit" name="upload_file" value="Залить файл">-->
		</form>
			<div id="doc_list"></div>
		</div>
		<div class="block0">
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>	
			<h1>Сообщения</h1>
			<?php else: ?>
			<h1>Повідомлення</h1>
			<?php endif; ?>
			<!-- ЧАТ УЧЕНИКА -->
			<!--<?php if($_SESSION['data']['level']==1):?>
			
			<?php endif; ?>-->
			<!-- ЧАТ УЧЕНИКА -->
			
			<?php
				if($_SESSION['data']['level']==1){
					require_once("req_mods/student_chat.php");
				}
				if ($_SESSION['data']['level']==2) {
					require_once("req_mods/teacher_chat.php");
				}
				if ($_SESSION['data']['level']==3) {
					require_once("req_mods/manager_chat.php");
				}
				if ($_SESSION['data']['level']==4) {
					require_once("req_mods/super_chat.php");
				}
			?>
			
			<!-- ЧАТ УЧИТЕЛЯ/АДМИНА -->
			<!--<?php if($_SESSION['data']['level']>1):?>
			
			<?php endif; ?>-->
			<!-- ЧАТ УЧИТЕЛЯ/АДМИНА -->
		</div>
	</div> 
	
<script type="text/javascript">
/* ajax files */
	var files;
	$('input[name = file_upl]').change(function(){
	    files = this.files;
	    var dataf = new FormData();
    $.each( files, function( key, value ){
        dataf.append( key, value );
    });
 
    // Отправляем запрос
 
    $.ajax({
	        url : '../tpl_php/ajax/homeworks.php?uploadfiles' ,
			type : 'POST' , 
			dataType : 'json' ,
	        data: dataf,
	        processData: false, // Не обрабатываем файлы (Don't process the files)
	        contentType: false, // Так jQuery скажет серверу что это строковой запрос
	        success: function( data){
	 
	            // Если все ОК
	 
	            
	                // Файлы успешно загружены, делаем что нибудь здесь
	 
	                // выведем пути к загруженным файлам в блок '.ajax-respond'
	 
	                var files_path = data;
		                
						var str = "";
						str += "<div class='simple_doc' onclick=\"attach_document('" + data["name"] + "','" + escapeHtml(data["real_name"], 2) 
						+ "')\">" + data["real_name"] + "</div>"
						//alert(data);
						$("#doc_list").empty();
						$("#doc_list").append(str);
						var html = '';
	                /*$.each( files_path, function( key, val ){ html += val +'<br>'; } )
	                $('.ajax-respond').html( html );*/
	        },
	        error: function(){
	            console.log('ОШИБКИ AJAX запроса ');
	        }
	    });
	    // Отправляем запрос
	    
	});
$('.submit .button').click(function( event ){
    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
 
    // Создадим данные формы и добавим в них данные файлов из files
 
    
 
});
/* ajax files */
window.onload=function(){
	
	/*$(".chat-show-hide .cat_small_hat").click(function(){
		$(".chat-show-hide p:not(.cat_small_hat):not(.no_displayed_chat)").toggleClass('no_displayed_chat');
		$recursed = $(this).closest(".chat-show-hide");
		$recursed.children("p:not(.cat_small_hat)").toggleClass('no_displayed_chat');
	})*/
	if($("input[name = com_level_from]").val() == 1){
		common_getChatsWithTeachers_student($("input[name = com_id_from]").val());
		//common_getMyChatWithManager_student($("input[name = com_id_from]").val());
		common_getChatsWithMe_student($("input[name = com_id_from]").val());
		common_getTechHelp_student($("input[name = com_id_from]").val());
	}
	if($("input[name = com_level_from]").val() == 2){
		common_getChatsWithPupils_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		common_getChatsWithMe_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
	}
	if($("input[name = com_level_from]").val() == 3){
		common_getChatsWithStudents_manager($("input[name = com_id_from]").val());
		common_getChatsWithoutMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		common_getChatsWithMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		common_getChatsOtherUsers_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
		
	}
	if($("input[name = com_level_from]").val() == 4){
		common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		common_getChatsHelp_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		
	}
	$("select[name = uroven_d]").change(function(){
		if($("input[name = com_level_from]").val() == 2){
			if($(this).val() == '1,2,3,4'){
				$(".chats_with_my_pupils").css("display","block");
				$(".chats_with_me_teacher").css("display","block");
				common_getChatsWithPupils_teacher($("input[name = com_id_from]").val());
				common_getChatsWithMe_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
			if($(this).val() == '1'){
				$(".chats_with_my_pupils").css("display","block");
				$(".chats_with_me_teacher").css("display","block");
				common_getChatsWithPupils_teacher($("input[name = com_id_from]").val());
				common_getChatsWithMe_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
			if($(this).val() == '2'){
				$(".chats_with_my_pupils").css("display","none");
				$(".chats_with_me_teacher").css("display","block");
				common_getChatsWithPupils_teacher($("input[name = com_id_from]").val());
				common_getChatsWithMe_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
			if($(this).val() == '3'){
				$(".chats_with_my_pupils").css("display","none");
				$(".chats_with_me_teacher").css("display","block");
				//common_getChatsWithPupils_teacher($("input[name = com_id_from]").val());
				common_getChatsWithMe_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
			if($(this).val() == '4'){
				$(".chats_with_my_pupils").css("display","none");
				$(".chats_with_me_teacher").css("display","block");
				//common_getChatsWithPupils_teacher($("input[name = com_id_from]").val());
				common_getChatsWithMe_teacher($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
		}
		if($("input[name = com_level_from]").val() == 3){
			if($(this).val() == '1,2,3,4'){
				$(".chats_with_pupils").css("display","block");
				$(".chats_other_users").css("display","block");
				$(".chats_with_me_manager").css("display","block");
				$(".chats_without_me_manager").css("display","block");
				common_getChatsWithStudents_manager($("input[name = com_id_from]").val());
				common_getChatsWithoutMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
				common_getChatsWithMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
				common_getChatsOtherUsers_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
			if($(this).val() == '1'){
				$(".chats_with_pupils").css("display","block");
				$(".chats_other_users").css("display","block");
				common_getChatsWithStudents_manager($("input[name = com_id_from]").val());
				common_getChatsWithoutMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
				$(".chats_with_me_manager").css("display","none");
				$(".chats_without_me_manager").css("display","none");
			}
			if($(this).val() == '2'){
				$(".chats_with_pupils").css("display","none");
				$(".chats_other_users").css("display","block");
				$(".chats_with_me_manager").css("display","block");
				$(".chats_without_me_manager").css("display","block");
				common_getChatsWithMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
				common_getChatsOtherUsers_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
				common_getChatsWithoutMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
			if($(this).val() == '3'){
				$(".chats_with_pupils").css("display","none");
				$(".chats_other_users").css("display","none");
				$(".chats_with_me_manager").css("display","block");
				$(".chats_without_me_manager").css("display","block");
				common_getChatsWithoutMe_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
				common_getChatsOtherUsers_manager($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val());
			}
		}
		if($("input[name = com_level_from]").val() == 4){
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsHelp_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		}
	})
	//alert($("input[name = com_id_from]").val());
	if($("input[name = com_level_from]").val() == 1){
		getMychats($("input[name = com_id_from]").val());
	}
	if($("input[name = com_level_from]").val() > 1){
		if($("input[name = com_level_from]").val()==2 || $("input[name = com_level_from]").val()==3){
			getMychats($("input[name = com_id_from]").val());
			getNotMychats($("input[name = com_id_from]").val());
			get_created_chats($("input[name = com_id_from]").val());
		}
		if($("input[name = com_level_from]").val()==4)
			get_created_chats($("input[name = com_id_from]").val());
	}
//alert(null == null);
	$("select[name = com_filter_class]").change(function(){
		//alert($(this).val());
		getSubjects($(this).val());
		if($("input[name = com_level_from]").val() == 2){
		}
		if($("input[name = com_level_from]").val() == 3){
		}
		if($("input[name = com_level_from]").val() == 4){
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsHelp_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		}
	})
	$("select[name = com_filter_subject]").change(function(){
		if($("input[name = com_level_from]").val() == 2){
		}
		if($("input[name = com_level_from]").val() == 3){
		}
		if($("input[name = com_level_from]").val() == 4){
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
			common_getChatsHelp_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val());
		}
	})
	$("select[name = com_create_type]").change(function(){
		//alert($(this).val());
		get_users($(this).val());
		if($("select[name = com_create_list]").val() == null){
			$("input[name = com_create_btn]").attr("disabled")
		}
		else{
			$("input[name = com_create_btn]").removeAttr("disabled")
		}
	})
	$("select[name = com_create_class]").change(function(){
		
		get_users_t_c($("select[name = com_create_type]").val(),$(this).val());
		if($("select[name = com_create_list]").val() == null){
			$("input[name = com_create_btn]").attr("disabled")
		}
		else{
			$("input[name = com_create_btn]").removeAttr("disabled")
		}
	})
	$("input[name = com_create_btn]").click(function(){
		create_chat($("input[name = com_id_from]").val(),$("#com_create_list").val(),$("input[name = com_create_name]").val())
	})
	if($("input[name = com_type]").val() == "com_lesson"){
		if($("input[name = com_level]").val() > 1)
			get_chats_t($("input[name = com_lesson]").val(),$("input[name = com_lang]").val())
		else
			get_chats_p($("input[name = com_lesson]").val(),$("input[name = com_lang]").val())
	}
	$("input[name = start_search]").click(function(){
		if($("input[name = com_level_from]").val() == 2){
		}
		if($("input[name = com_level_from]").val() == 3){
		}
		if($("input[name = com_level_from]").val() == 4){
			common_getChatsOtherUsers_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),
				$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val(),$("input[name = search]").val());
			common_getChatsWithMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),
				$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val(),$("input[name = search]").val());
			common_getChatsWithOutMe_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),
				$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val(),$("input[name = search]").val());
			common_getChatsHelp_admin($("input[name = com_id_from]").val(),$("select[name = uroven_d]").val(),
				$("select[name = com_filter_class]").val(),$("select[name = com_filter_subject]").val(),$("input[name = search]").val());
		}
	});
	//var pre_interv = setInterval(common_getMessages,120000,$("input[name = com_id_chat]").val());
	$("textarea[name = com_text_message]").on('focus',function(){
		common_getMessages($("input[name = com_id_chat]").val());
		var interv = setInterval(common_getMessages,4000,$("input[name = com_id_chat]").val());
		$(this).on('blur',function(){
			clearInterval(interv);
			
		})
	})
	$("select[name = com_create_list]").change(function(){
		get_all_names($("#com_create_list").val());
	})
}
			</script>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 