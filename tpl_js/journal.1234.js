function easy_close(){
	$("#tabel_link_content").css("display","none");
}
function open_print_modal(id){
	$("#print").css("display","block");
	$("input[name = id_journal]").val(id);
	get_docs_by_id(id);
}
function easy_unprint(){
	$("#print").css("display","none");
	$("input[name = id_journal]").val("");
}
function wait(){
	$(".green-system-field").toggleClass("no-display");
	$(".green-system-field").empty().append("<p>Оценка успешно удалена</p>");
}
function set_null_test(id_user, id_lesson) {
	var result = confirm('Вы уверены, что хотите удалить оценку ученика с id: ' + id_user + ' по уроку: ' + id_lesson);
	if(result) {
		$.ajax({
			url : "../../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
			data : {
				id_user   : id_user,
				id_lesson : id_lesson,
				flag : '71'
			},
			success: function(data) { 
				wait()
				setTimeout(wait, 3000);
				
			}
		})
	}
}
function update_test(id_journal, mark) {
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			mark       : mark,
			id_journal : id_journal,
			flag : '72'
		},
		success: function(data) { 
			//getResult($("select[name = students]").val(),$("select[name = subject]").val());
		}
	})
}
function get_docs_by_id(id){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id : id,
			flag : '59'
		},
		success: function(data){
			//alert(data['students']);
			if($("input[name = language]").val() == "ru") {
				var watchRes = "Посмотреть результаты";
			} else {
				var watchRes = "Подивитися результати";
			}
			$("#tab_down #results").empty();
			$("#tab_down #results").append("<a target='_blank' href='test_res.php?id="+id+"&type=5'>"+watchRes+"</a>");
			$("#tab_down .teacher_docs").empty();
			$("#tab_down .teacher_docs").append(data['teachers']);
			$("#tab_down .student_docs").empty();
			$("#tab_down .student_docs").append(data['students']);
		},
		error: function(){
			$("#tab_down #results").empty();
			$("#tab_down .teacher_docs").empty();
			$("#tab_down .student_docs").empty();
		}

	})
}
function getResult( pupid, subj)
	{
		var gif_string = '<tr><td colspan="7" style="text-align: center; padding: 30px;">\
								<img width="100px" height="100px" src="http://online-shkola.com.ua/tpl_img/loading-01.gif">\
								</td></tr>'
		if(!$(".student-data-block").hasClass("no-display")) {
			$(".student-data-block").toggleClass("no-display");
		}
		$(".student-data-block").empty();
		$(".rb_table tbody").empty().append(gif_string);
		//alert(subj);
		$.ajax({
			url : '../tpl_php/results.php' ,
			method : 'POST',
			dataType : 'json',
			data : {
				id : pupid,
				level : $("input[name = level]").val(),
				lang : $("input[name = language]").val(),
				subj: subj
			},
			success : function ( data ){
				if ( data )
				{
					var str = "";
					$(".rb_table tbody").empty();
					str += data['journal'];
					$(".rb_table tbody").append(str);
					if($("input[name = level]").val() > 1) {
						if($(".student-data-block").hasClass("no-display")) {
							$(".student-data-block").toggleClass("no-display");
						}
						$(".student-data-block").empty().append(data['user_data']);
						$(".student-data-block").append("<br> Выбранный предмет: " + $("select[name = subject] option:selected").text());
						$(".student-data-block").append("<br> Дата официального зачисления: " + data['date_reg']);
					}
				}
			},
			error: function(){
				
				//alert("Error in tpl_php/results.php");
			}
		});
	};
function del_theme(id){
	//alert("aaa");
	delete_theme(id)
	getResult($("select[name = students]").val(),$("select[name = subject]").val())
};
function delete_theme(id){
	$.ajax({
		url : '../tpl_php/ajax/notifs.php',
		method : 'POST',
		dataType : 'json',
		data :{
			id : id,
			flag : '4'
		},
		success : function(){
			
		}
	})
}

	///NOTIFICATIONS
	function get_notifs(lang){
		$.ajax({
			url : '../tpl_php/ajax/notifs.php',
			method : 'POST',
			dataType : 'json',
			data :{
				lang : lang,
				type : '1',
				flag : '2'
			},
			success : function(data){
				var str = "";
				var cnt = 1;
				for(var id in data){
					if(lang == "ru"){
						str += "<li> <span>Примечание "+cnt+":</span> "+data[id]['first'];
					} else {
						str += "<li> <span>Примітка "+cnt+":</span> "+data[id]['first'];
					}
					if($("input[name = level]").val() == 4)
						str += "<span class='delete' onclick=\"del_notif("+id+")\">удалить</span>";
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
		get_notifs($("input[name = language]").val());
	}
	///NOTIFICATIONS
window.onload = function ()
{
	var $class = $("select[name = class");
	var $subject = $("select[name = subject]");

	var $list = $(".tabel_left_b");
	var $list2 = $(".rb_table tbody");
	var $list1 = $("select[name = students]");
	var $level_user = $("input[name = level]");
	var $id_user = $("input[name = id]");
	var $fio = $("input[name = search]");
	var $id = $("input[name = id]");
	var $level = $("input[name = level]");
	var $notifs = $(".tabel_premich");
	var $ntext_ru = $("input[name = notif_ru]");
	var $ntext_ua = $("input[name = notif_ua]");
	var $nbtn = $("input[name = add_notif]");
	var $position = $("select[name = position]");
	var $set_t = $("input[name = set_t]");
	var $mark = $("input[name = mark]");
	var $lang = $("input[name = language]");
	var $name_ua = $("input[name = name_ua]");
	var $name_ru = $("input[name = name_ru]");

	//alert($id_user.val());
	//alert($lang.val());
	if($level_user.val() == 4){
		getSubjects($class.val());
		getList($class.val());
		getResult( $list1.val(),$subject.val());
	}
	if($level_user.val() == 1){
		getResult( $id_user.val(),$subject.val());
	}
	
	get_notifs($("input[name = language]").val());

	if($name_ru.val() == "" || $name_ua.val() == "" || $mark.val() == "" || $mark.val()>12 || $mark.val()<1){
		$set_t.attr('disabled',true);
	}

function getTsubjects(class_id,teacher_id){
	//alert(class_id);
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			teacher_id : teacher_id,
			lang : $lang.val(),
			flag : '30'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			//$("select[name = subjects]").empty();
			$("select[name = subject]").empty();
			for(var id in data){
				if(id == 0)
					continue;
				else
					str += "<option value='"+id+"'>"+data[id]+"</option>";
			}
			//$("select[name = subjects]").append(str);
			$("select[name = subject]").append(str);
			//catchAndDraw(reqDate.getMonth()+1, reqDate.getFullYear(),$cl.val(),daysInMonth(reqDate.getFullYear(),reqDate.getMonth()),language.val(),data[0]);
		}

	})

}
	function getSubjects( class_id )
	{
		$.ajax({
			url : '../tpl_php/subjects.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { 
				id : class_id,
				lang : $lang.val(),
				course : 0, 
				flag : '1' 
			} ,
			success : function ( data ) {
				if ( data['subjects'] )
				{
					var str = "";
					$subject.empty();

					for ( var id in data['subjects'] )
					{
						str += "<option value='" + id + "'>" + data['subjects'][id] + "</option>";
					}

					$subject.append(str);
				}
			}
		});
	};

	function getList( class_id )
	{
		$.ajax({
			url : '../tpl_php/journal.php' ,
			method : 'POST',
			dataType : 'json',
			data : {
				lang : $lang.val(),
				id : class_id
			},
			success : function ( data ){
				if ( data )
				{
					var str = "";
					$list1.empty();

					for ( var id in data )
					{
						/*str += "<a href='../cabinet/view.php?id=" + id 
							+ "' id='"  + id + "'>" +
							+ data[id]['first'] + ' ' 
							+ data[id]['second'] + ' ' 
							+ data[id]['third'] + "</a><br>";*/
						str += "<option value='" + id 
							+ "'>"
							+ data[id]['second'] + ' ' 
							+ data[id]['third'] + "</option>";

					}
					//alert(str);
					if(str == "")
					{
						//$list.empty();
						if($lang.val() == "ru")
							str = "<option disabled><span>Пока нет учеников<span></option>";
						else
							str = "<option disabled><span>Немає учнів</span></option>";
						$list1.append(str);
					}
					else{
						//$list.empty();
						//alert(str);
						$list1.append(str);
					}
				}
			}
		});
	};

	

	function search_u(fio){
		$.ajax({
			url : '../tpl_php/ajax/tab_search.php',
			method : 'POST',
			dataType : 'json',
			data :{
				fio : fio
			},
			success : function(data){
				var str = "";
					$list1.empty();
					$subject.empty();
				var str1 = "";
					for ( var id in data )
					{
						str += "<option value='" + id 
							+ "'>"
							+ data[id]['first'] + ' ' 
							+ data[id]['second'] + "</option>";
							var new_class = data[id]['forth'];
							for(var id1 = 0; id1 < data[id]['sixth'].length;id1++){
								str1 += "<option value='"+data[id]['sixth'][id1]+"'>"+data[id]['third'][id1]+"</option>";
							}
							//alert(str1);
					}
					//alert(str);
					if(str == "")
					{
						//$list.empty();
						str = "<option disabled><span>Пока нет учеников<span></option>";
						$list1.append(str);
					}
					else{
						//$list.empty();
						//alert(str);

						$class.val(new_class);
						$subject.append(str1);
						$list1.append(str);
					}
			}
		});
	}

	function add_notif(text_ru, text_ua){
		$.ajax({
			url : '../tpl_php/ajax/notifs.php',
			method : 'POST',
			dataType : 'json',
			data :{
				text_ru : text_ru,
				text_ua : text_ua,
				type : '1',
				flag : '1'
			},
			success : function(){}

		})

	}

	

	function add_thematic(pupid,subj,position,val,name_ua,name_ru,date_ru,date_ua){
		//alert( pupid+' '+subj+' '+position+' '+val)
		$.ajax({
			url : '../tpl_php/ajax/notifs.php',
			method : 'POST',
			dataType : 'json',
			data :{
				pupid : pupid,
				subj : subj,
				pos : position,
				val : val,
				name_ua : name_ua,
				name_ru : name_ru,
				date_ua : date_ua,
				date_ru : date_ru,
				flag : '3'
			},
			success : function(){
				
			}

		})
		easy_close();
	}

	

	function delete_d(){
		//e.preventDefault();
		var el = $(this);
		//delete_theme(el.attr('name'));
		alert('Атрибут name: ' + el.attr('name'));
		//el.closest('div').remove();
	}

	$name_ua.on("input",function(){
		if($name_ru.val() == "" || $name_ua.val() == "" || $mark.val() == "" || $mark.val()>12 || $mark.val()<1){
			$set_t.attr('disabled',true);
		}
		else{
			$set_t.removeAttr('disabled');
		}
	})
	$name_ru.on("input",function(){
		if($name_ru.val() == "" || $name_ua.val() == "" || $mark.val() == "" || $mark.val()>12 || $mark.val()<1){
			$set_t.attr('disabled',true);
		}
		else{
			$set_t.removeAttr('disabled');
		}
	})
	$mark.on("input",function(){
		if($name_ru.val() == "" || $name_ua.val() == "" || $mark.val() == "" || $mark.val()>12 || $mark.val()<1){
			$set_t.attr('disabled',true);
		}
		else{
			$set_t.removeAttr('disabled');
		}
	})

	$set_t.click(function(){
		//alert("a");
		add_thematic($list1.val(),$subject.val(),$position.val(),$mark.val(),$name_ua.val(),$name_ru.val(),$("input[name = date_ru]").val(),$("input[name = date_ua]").val());
		getResult( $list1.val(), $subject.val() );
		$name_ru.val('');
		$name_ua.val('');
		$mark.val('');

	});

	$class.change( function () {
		if($level.val() == 2)
			getTsubjects($(this).val(),$id_user.val());
		else
			getSubjects($(this).val());
		//getSubjects($(this).val());
		getList($(this).val());
		//alert('a');
	});

	$subject.change(function(){
		//temp_subj = $(this).val();
		if($level.val() > 1 && $level.val() < 5)
			getResult( $list1.val(),$(this).val());
		if($level.val() == 1)
			getResult( $id_user.val(),$(this).val());
		//alert($subject);
	});

	$list1.change( function(){
		getResult( $(this).val(),$subject.val());
		
	});

	$fio.on("input",function(){
		search_u($(this).val());
	});

	$(".tabel_left_b a").click( function () {
		var us_id = $(this).attr("id");

		$(".calendar_link").text("Табель ученика");
		$(".calendar_link").attr("href" , "tabel.php?id='" + us_id + "'");

		getData(us_id);
	});
	$nbtn.click(function(){
			add_notif($ntext_ru.val(),$ntext_ua.val());
			$ntext_ru.val('');
			$ntext_ua.val('');
			get_notifs($("input[name = language]").val());
		
	})
	
}
