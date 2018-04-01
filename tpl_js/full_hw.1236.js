var hw_ajax;
var paginate_flag;
function escapeHtml(text, flag) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&#34;',
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
function make_read(id){
	$("#circled_"+id).css("display","none");
	$.ajax({
		url : "../../tpl_php/ajax/homeworks.php",
		method : 'post',
		dataType : 'json',
		data : {
			id : id,
			flag : '6'
		},
		success: function(data){
		}

	})
}
function open_docs_modal(id,type,id_u){

	$("#docs").css("display","block");
	$("#docs").css("z-index",9999);
	$("input[name = user_id]").val(id_u);
	$("input[name = hw_id]").val(id);
	//get_doc_by_id(id_u,id);
	
}
function get_doc_by_id(id,id_h){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id : id,
			id_h : id_h,
			flag : '35'
		},
		success: function(data){
			$("#doc_list").empty();
			$("#doc_list").append(data["docs"]);
		}

	})
}
function attach_document(id,link,name){
	//alert(link)
	$("#file_attached"+id).val(link);
	$("#attached"+id).empty();
	$("#attached"+id).append("<span class='attached_files_list'>Прикрепленные файлы:</span><br>"+name);
	close_docs_modal();
}
function reload_page(param){
	switch(param){
		case 1: 
			setTimeout(get_doc_by_id,5000,$("input[name = user_id]").val(),$("input[name = hw_id]").val());
			break;
	}
}
function track_max(id, max){
	if ($("#mark_"+id).val() > max || $("#mark_"+id).val() < 1) {
		$("#err_mark_"+id).empty();
		$("#err_mark_"+id).append("Введена некорректная оценка");
	}
	else{
		$("#err_mark_"+id).empty();
	}
}
function daysInMonth(year, month) {
	return 32 - new Date(year, month, 32).getDate();
};
function saveChanges( id_u, id, id_hw, textarea, mark, checkbox, type ){
	//alert(id + ' || '+ id_u + ' || '+ id_hw + ' || '+textarea+' || '+checkbox + ' || ' + type);
	if($("#file_attached"+id).val() == '') {
		if(!confirm("Файл не отправлен. Если вы просто выставили оценку\
		 или написали комментарий, то все в порядке. Если вы хотели\
		  отправить файл, то попробуйте снова. Файлы объемом более 5 Мб не загружаются.")) {
			return false;
		}
	}
	$.ajax({
		url : '../tpl_php/ajax/homeworks.php' ,
		method : 'POST' , 
		dataType : 'json' ,
		data : {
			id_u : id_u,
			id : id,
			id_hw : id_hw,
			comment : escapeHtml(textarea),
			mark : mark,
			status : checkbox,
			file : $("#file_attached"+id).val(),
			type : type,
			flag : '2'
		} ,
		success : function ( data ) {
		}
	});
	/*get_hw_list($("input[name = search]").val(),$("input[name = class]").val(),$("select[name = subject]").val(),$("input[name = date_s]").val(),$("input[name = date_do]").val(),
		$("select[name = show]").val(),$("select[name = status]").val(),$("input[name = id]").val(),$("input[name = level]").val(),
		$("input[name = language]").val());*/
	
	if($("input[name = level]").val()==1){
		get_hw_list($("input[name = search]").val(),$("input[name = class]").val(),$("select[name = subject]").val(),$("input[name = date_s]").val(),$("input[name = date_do]").val(),
			$("select[name = show]").val(),$("select[name = status]").val(),$("input[name = id]").val(),$("input[name = level]").val(),
			$("input[name = language]").val());
	}
	if($("input[name = level]").val()>1){
		get_hw_list($("input[name = search]").val(),$("select[name = class]").val(),$("select[name = subject]").val(),$("input[name = date_s]").val(),$("input[name = date_do]").val(),
		$("select[name = show]").val(),$("select[name = status]").val(),$("input[name = id]").val(),$("input[name = level]").val(),
		$("input[name = language]").val());
	}
}
function saveChanges_p( id_u, id, id_hw, type ){
	//alert(id + ' || '+ id_u + ' || '+ id_hw + ' || ' + type);
	if($("input[name = language]").val() == 'ru') {
		var text_data = "<b>Файл не отправлен!</b><br> Убедитесь, что вы его правильно \
		прикрепили и попробуйте снова. Инструкция по правильной загрузке ДЗ находится \
		<a target='_blank' href='http://online-shkola.com.ua/statics/watch.php?id=25#dz'>здесь</a>. \
		Файлы объемом более 5 Мб не загружаются. Поддерживаемые форматы файлов:\
		jpg, jpeg, png, bmp, doc, docx, xls, xlsx, ppt, pptx, pdf, rar, zip, txt, rtf, mp3, wma, gif"
	} else {
		var text_data = "<b>Файл не відправлений!</b><br>  \
		Переконайтеся, що ви його правильно прикріпили і спробуйте знову. \
		Інструкція по правильному завантаженню ДЗ знаходиться \
		<a target='_blank' href='http://online-shkola.com.ua/statics/watch.php?id=25#dz'>тут</a>\
		Файли об'ємом більше 5 Мб не завантажуються. Формати файлів, що підтримуються:\
		jpg, jpeg, png, bmp, doc, docx, xls, xlsx, ppt, pptx, pdf, rar, zip, txt, rtf, mp3, wma, gif"
	}
	if($("#file_attached"+id).val() != '') {
		$.ajax({
			url : '../tpl_php/ajax/homeworks.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				id_u : id_u,
				id : id,
				id_hw : id_hw,
					file : $("#file_attached"+id).val(),
				type : type,
				flag : '4'
			} ,
			success : function ( data ) {
			}
		});
		get_hw_list($("input[name = search]").val(),$("input[name = class]").val(),$("select[name = subject]").val(),$("input[name = date_s]").val(),$("input[name = date_do]").val(),
			$("select[name = show]").val(),$("select[name = status]").val(),$("input[name = id]").val(),$("input[name = level]").val(),
			$("input[name = language]").val());
	} else {
		alertion_window("grey-alert", text_data, 10, "open");
	}
}

function paginate(name,class_id,subj,from_d,to_d,show,status,id,level,lang,name,search){
	name = name || "";
	if(paginate_flag && paginate_flag.readyState != 4) paginate_flag.abort();
	paginate_flag = $.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { class_id : class_id,
				subj_id : subj,
				from_date : from_d,
				to_date : to_d,
				show : show,
				status : status,
				id : id,
				level : level,
				lang : lang,
				name : name,
				top_lim: $("input[name = cur_top_lim]").val(),
				bot_lim: $("input[name = cur_bot_lim]").val(),
				flag : '62'
			} ,
			success : function ( data ) {
				$("input[name = count_all]").val(data);
				render_pages($("input[name = cur_page]").val(),data,$("select[name = show]").val());
			}
		});
	

}
function update_hw_for_week(hw_id) {
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			hw_id : hw_id,
			flag : '74'
		},
		success: function(data){
			if(data == 'yep') alert("Срок увеличен")
			else alert("Что-то пошло не так")
		}
	})
}
function get_hw_list(name,class_id,subj,from_d,to_d,show,status,id,level,lang,search){
	name = name || "";
	lang = lang || "ru";
	//alert(name+" | " + class_id +" | "+ subj+" | "+from_d+" | "+to_d+" | "+show+" | "+status+" | "+" | "+level+" | "+lang)
	if(hw_ajax && hw_ajax.readyState != 4) hw_ajax.abort();
	hw_ajax = $.ajax({
			url : '../tpl_php/ajax/homeworks.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				class_id : class_id,
				subj_id : subj,
				from_date : from_d,
				to_date : to_d,
				show : show,
				status : status,
				id : id,
				level : level,
				lang : lang,
				name : name,
				//search : $("input[name = search]").val(),
				top_lim: $("input[name = cur_top_lim]").val(),
				bot_lim: $("input[name = cur_bot_lim]").val(),
				course : $("input[name = currentCourse]").val(),
				per_page: $("select[name = show]").val(),
				flag : '1'
			},
			success : function(data){
				if (lang == "ru") {
					var attach   	 = "Прикрепить";
					var send    	 = "Отправить";
					var reworkT 	 = "Отправлено на доработку";
					var checkT  	 = "Отправлено на проверку учителю";
					var yourMark     = "Ваша оценка";
					var stopAct      = "Отменить";
					var sentAnsw     = "Отправленный ответ";
					var checkedAnsw  = "Проверенное";
					var testHw		 = "Тестовое ДЗ";
					var createHw	 = "Творческое ДЗ";
					var toLesson  	 = "К уроку";
				} else {
					var attach  	 = "Прикріпити";
					var send    	 = "Надіслати";
					var reworkT 	 = "Відправлено на допрацювання";
					var checkT  	 = "Відправлено на перевірку вчителю";
					var yourMark 	 = "Ваша оцінка";
					var stopAct 	 = "Відмінити";
					var sentAnsw     = "Відправлена відповідь";
					var checkedAnsw  = "Перевірене";
					var testHw		 = "Тестове ДЗ";
					var createHw	 = "Творче ДЗ";
					var toLesson  	 = "До уроку";
				}
				var str = "";
				if (level > 1)
					str += "<table class='hat_table'><tr><td>ФИО</td><td>Класс</td><td>Неделя</td><td>Тема</td><td>Дата загрузки</td><td>Оценка</td></tr></table>";
				$(".homework_table").empty();
				for(var id in data){
					var links_s = "";
					var links_t = "";
					var hat = "";
					var change_status = "";
					
					/* uploaded file's block */
					var files_module = "";
					/* uploaded file's block */

					/* homework's block hat */
					var hw_hat = "";
					/* homework's block hat */

					var pre_month;
					var pre_year;
					var after_month;
					var after_year;
					//alert(data[id]['ldate']);
					var lesson_date = new Date(data[id]['ldate']);
					var month = lesson_date.getMonth()+1;
					var day = lesson_date.getDate();
					var year = lesson_date.getYear();
					var day_num = lesson_date.getDay();
					if(1*year<1000)
						year = 1*year+1900
					//console.log(day+" "+month+" "+year+" ||| "+day_num)
					if (day_num == 0) {
						day_num = 7;
					};

					var pre_day = 1*day-1*day_num;
					//console.log(pre_day);
					//console.log(year + ' ' + pre_year);
					var after_day = 1*day + 7 - 1*day_num;
					if(after_day > daysInMonth(year, month)){
						after_month = month + 1;
						if (after_month == 13) {
							after_month = 1;
							after_year = 1*year + 1;
						};
						after_day = 7 - 1*day_num;
					}
					else{
						after_month = month;
						after_year = year;
					}
					if (pre_day < 1) {
						//console.log("<1");
						pre_month = month - 1;
						if (pre_month == 0) {
							pre_month = 12;
							pre_year = 1*year - 1;
							//console.log(year + ' 11  ' + (1*year - 1));
						}
						else{
							pre_year = year;
						}

						//console.log(pre_month + ' ' + pre_year);
						pre_day = daysInMonth(pre_year, pre_month)+pre_day;
					}
					else{
						pre_month = month;
						pre_year = year;
					}
					
					//console.log(pre_day+" "+pre_month+" "+pre_year)
					var pre_date = pre_day+'.'+pre_month;
					var after_date = after_day+'.'+after_month;
					var classes = "";
					var classes_new = "";
					var at_link = "";
					var added_text = "";
					//console.log(pre_date+" "+after_date);
					//if(level == 4)
						var dates = "<span class='dates'>("+pre_date+" - "+after_date+")</span><br style='clear:both;'>";
					/*else{
						pre_date += '.'+pre_year;
						after_date += '.'+after_year;
						var dates = "<span class='dates'>Неделя с "+pre_date+" до "+after_date+"<br style='clear:both;'>";
					}*/
					//var mark_text = "";
					//alert(data[id]['fio'] + ' '+ data[id]['comment'] + ' '+ data[id]['mark']);
					/*if(level == 1 ){
						for(var link in data[id]["student"]){
							links_s += "<a href='../upload/hworks/"+data[id]["student"][link]+"'>"+data[id]["student"][link].substr(22,40)+"</a><br>";
						}
						for(var link in data[id]["teacher"]){
							links_t += "<a download href='../upload/hworks/"+data[id]["teacher"][link]+"'>"+data[id]["teacher"][link].substr(22,40)+"</a><br>";
						}
					}*/
					if(level >= 1){
						for(var link in data[id]["student"]){
							links_s += "<a download href='../upload/hworks/"+data[id]["student"][link]+"'>"+data[id]["student"][link].substr(22,40)+"</a>, ";
						}
						for(var link in data[id]["teacher"]){
							links_t += "<a download href='../upload/hworks/"+data[id]["teacher"][link]+"'>"+data[id]["teacher"][link].substr(22,40)+"</a>, ";
						}
						links_t += "<br>";
					}

					/*if(level > 1 && level != 3){
						links_t+="<br><span onclick=\"open_docs_modal("+data[id]["id"]+",'teacher',"+data[id]['id_u']+")\">Прикрепить</span>\
						<br><span class='atachik' id='attached"+data[id]["id"]+"'></span>";
					}*/
					if(data[id]['check_status'] == 4){
						hat += "<h2 class='rework'>"+reworkT+"</h2>";
					}
					if(data[id]['check_status'] == 3){
						hat += "<h2 class='rework'>"+checkT+"</h2>";
					}
					if(((/*data[id]['check_status'] == 4 || */data[id]["student"] != "" || data[id]["student"] != undefined) && level == 1) || (level > 1 && level != 3)){
						if (level > 1 && level != 3) {
							at_link ="<br><span class='hw_add_docs' onclick=\"open_docs_modal("+data[id]["id"]+",'teacher',"+data[id]['id_u']+")\">"+attach+"</span>\
						<br><span class='atachik' id='attached"+data[id]["id"]+"'></span>";
						}
						if(data[id]['lock_status'] != 'locked') {
							if (level == 1) {
								at_link ="<br><span class='hw_add_docs' onclick=\"open_docs_modal("+data[id]["id"]+",'student',"+data[id]['id_u']+")\">"+attach+"</span>\
							<br><span class='atachik' id='attached"+data[id]["id"]+"'></span>";
							}
						}
					}
						if(data[id]['last_hw_message'] != '') {
								added_text = data[id]['last_hw_message'];
						}
					var disabled_attr = '';
					if(data[id]['lock_status'] == 'locked') {
						disabled_attr = 'disabled';
					}
					if(data[id]['mark'] != 0 && data[id]['check_status'] != 4 && data[id]['check_status'] != 3 && data[id]['status'] != 4){
						if(data[id]['mark'] != "" && data[id]['mark'] != null && data[id]['mark'] != 0){
							
							if (data[id]["status"] == 3) {
								if (level == 1) {
									classes = "hw_stat_3";
								}
								else{
									classes = "hw_stat_3";
								}
							}
							else if(data[id]['status'] == 1){
								if (level == 1) {
									classes = "hw_stat_1";
								}
								else{
									classes = "hw_stat_1";
								}
							}
							else if(data[id]['status'] == 2){
								if (level == 1) {
									classes = "hw_stat_2";
								}
								else{
									classes = "hw_stat_2";
								}
							}
							else if(data[id]['status'] == 4){
								if (level == 1) {
									classes = "hw_stat_1";
								}
								else{
									classes = "hw_stat_1";
								}
							}
							hat += "<div class='mark_s "+classes+"'>"+yourMark+": "+data[id]['mark']+"</div><br>";
						}

					}
					//alert(links_t)
					if(data[id]['change_status'] == 1){
						//alert(data[id]['status'])
						if (level == 1 ) {
							if(data[id]["check_status"] == 2 || data[id]["check_status"] == 4){
								change_status += "<a onclick=\"make_read("+data[id]['id']+")\" class='hw_kurok' href=\"javascript:onoff2('div"+data[id]["id"]+"');\">"+createHw+"\
								<span class='circled' id=\"circled_"+data[id]["id"]+"\">1</span></a>";
							}
							else{
								change_status += "<a class='hw_kurok' href=\"javascript:onoff2('div"+data[id]["id"]+"');\">"+createHw+"</a>";
							}
						}
						if (level > 1 ) {
							if(data[id]["check_status"] == 3){
								change_status += "<a onclick=\"make_read("+data[id]['id']+")\" href=\"javascript:onoff('div"+data[id]["id"]+"');\">Оценить \
								<span class='circled' id=\"circled_"+data[id]["id"]+"\">1</span></a>";
							}
							else{
								change_status += "<a href=\"javascript:onoff('div"+data[id]["id"]+"');\">Оценить</a>";
							}
						}

					}
					else{
						if (level == 1) {
							change_status += "<a class='hw_kurok'href=\"javascript:onoff2('div"+data[id]["id"]+"');\">"+createHw+"</a>";
						}
						if (level > 1) {
							change_status += "<a href=\"javascript:onoff('div"+data[id]["id"]+"');\">Оценить</a>";
						}
					}
					//alert(change_status);
					var eld_hat = "";
					if(data[id]['check_status'] == 4){
						eld_hat += "<h2 class='rework'>"+reworkT+"</h2>";
					}
					if(data[id]['check_status'] == 3){
						eld_hat += "<h2 class='rework'>"+checkT+"</h2>";
					}
					if(data[id]['check_status'] != 3 && data[id]['check_status'] != 4){
						eld_hat += "<h2 class='rework'>"+yourMark+": "+data[id]['mark']+"</h2>";
					}
					//alert(data[id]["student"]);
					var more_text = "";
					//alert(level);
					if (data[id]["status"] == 3) {
						if (level == 1) {
							classes = "hw_zadanie_kontr user_hw_page";
							classes_new = "hw_zadanie_kontr hw_zadanie_block_in";
						}
						else{
							classes = "hw_zadanie_kontr admin_hw_page";
							classes_new = "hw_zadanie_kontr hw_zadanie_block_in";
						}
					}
					else if(data[id]['status'] == 1){
						if (level == 1) {
							classes = "hw_zadanie_norm user_hw_page";
							classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						}
						else{
							classes = "hw_zadanie_norm admin_hw_page";
							classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						}
					}
					else if(data[id]['status'] == 2){
						if (level == 1) {
							classes = "hw_zadanie_prov user_hw_page";
							classes_new = "hw_zadanie_prov hw_zadanie_block_in";
						}
						else{
							classes = "hw_zadanie_prov admin_hw_page";
							classes_new = "hw_zadanie_prov hw_zadanie_block_in";
						}
					}
					else if(data[id]['status'] == 4){
						if (level == 1) {
							classes = "hw_zadanie_norm user_hw_page";
							classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						}
						else{
							classes = "hw_zadanie_norm admin_hw_page";
							classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						}
					}
					if(level > 1 && level != 3){
						if (data[id]['mark_max'] != null && data[id]['mark_max'] != undefined) {
							var mark = "Оценка за ДЗ <input type='text' name='hw_mark' id='mark_"+data[id]['id']+"' value='"+data[id]['mark']+
							"' oninput=\"track_max("+data[id]['id']+","+data[id]['mark_max']+")\"><div id='err_mark_"+data[id]['id']+"'></div>";
						}
						else{
							var mark = "";
						}
						
						hat += "<h2>" + data[id]['fio'] + "</h2>";
						more_text = "<textarea placeholder='текст комментария' name='pholder'>"
						+data[id]["comment"]+"</textarea>"
						+"<input type='hidden' name='id_hw' value='"+data[id]['id_hw']+"'><table>\
							<tr>\
								<td>\
							<div class='hw_dorad_otp'><input type='checkbox' name='option5' value='a5'> отправить на доработку</div>\
							<input type='hidden' name='id_u' value='"+data[id]['id_u']+"' id='"+data[id]["id"]+"'>\
							<input type='hidden' name='attached_file' value='' id='file_attached"+data[id]["id"]+"'>\
								<td>"+
							mark+
							'<input type="button" class="hw_save" name="up_for_week" value="Продлить на неделю"\
							onclick="update_hw_for_week(' + data[id]["id"] + ')"/>'
							+"<input type='button' class='hw_save' value='Отправить'\
							onclick=\"saveChanges(this.form.id_u.value,"+data[id]["id"]+','+data[id]['id_hw']+",this.form.pholder.value,this.form.hw_mark.value,this.form.option5.checked,'teacher')\">\
							<input type='button' class='hw_otmena' value='Отменить'></td>\
							</tr>\
						</table>\
						";
					}
					else{
						more_text = "<div class='comment'>"+data[id]['comment']+"</div>";
						if ( level != 3 ){
							more_text += "<input type='hidden' name='id_hw' value='"+data[id]['id_hw']+"'><table>\
							<tr>\
								<td>\
							<input type='checkbox' name='option5' hidden value='a5'>\
							<input type='hidden' name='id_u' value='"+data[id]['id_u']+"'>\
							<input type='hidden' name='attached_file' value='' id='file_attached"+data[id]["id"]+"'>\
							<input type='text' name='hw_mark' hidden value='"+data[id]['mark']+"'>\
								<td>\
							<input type='button' class='hw_save' " + disabled_attr  + " value='"+send+"'\
							onclick=\"saveChanges_p(this.form.id_u.value,"+data[id]["id"]+','+data[id]['id_hw']+",'student')\">\
							<input type='button' class='hw_otmena' value='"+stopAct+"'></td>\
							</tr>\
						</table>\
						"
						}
					}
					
					if(level == 1){
						files_module += "<div class='by_stud'>\
							<p>"+sentAnsw+": </p>"+links_s+"\
						</div>\
						<div class='by_teach'>\
							<p>"+checkedAnsw+": </p>"+links_t+"\
						</div>\
						<div>"+at_link+"</div>";
						
						hw_hat += "<div class='"+classes+"'>"+
							hat + added_text
							+"<h3>Тема: "+data[id]['ltitle']+" </h3>"+
							dates+
							"<a class='hw_uc_testdz' target='_blank' href='http://online-shkola.com.ua/tests/completing.php?id="+data[id]['c_test_id']+"'>"+testHw+"</a>"+
							change_status+
							"<a class='hw_kurok idsd' href='../lessons/watch.php?id="+data[id]['lid']+"'>"+toLesson+"</a></div>";
					}
					if(level > 1){
						//alert(links_t);
						files_module += "<div class='by_stud'>\
							<p>Отправленный ответ: </p>"+links_s+"\
						</div>\
						<div class='by_teach'>\
							<p>Проверенное: </p>"+links_t+"\
						</div>\
						<div>"+at_link+"</div>";
						hw_hat += "<table class='hw_table_admin_s'>\
							<tr><td>"+data[id]['fio']+"</td><td>"+data[id]['class']+"</td><td>"+dates+"</td><td><h3>Тема: "+
							data[id]['ltitle']+" </h3></td><td>"+data[id]["date"]+"</td><td>"+added_text+change_status+eld_hat+"</td>"+
						"</tr></table>";
					}
					
					str += "<form method='post' action=''>"+hw_hat;
							str += "<div id='div"+data[id]["id"]+"' class='"+classes_new+"' style='display:none;'>\
								<!--<h4><a class='hw_uslovia' href='../lessons/lookhw.php?id="+data[id]["id"]+"'>Условия ДЗ</a></h4>-->"+data[id]['text_hw']+
								"<table>\
									<tr>\
										<td>"+
										files_module
									+"</td></tr>\
								</table>"+
									more_text
								+"</div></form>"
					
				}
				$(".homework_table").append(str);
			},
			error: function(){
				$(".homework_table").empty();
				//$(".homework_table").append("<table class='hat_table'><tr><td>ФИО</td><td>Класс</td><td>Неделя</td><td>Тема</td><td>Дата загрузки</td><td>Оценка</td></tr></table>");	
			}
		})
	}
function get_hw_list_new(id_lesson,id,level,lang){
	$.ajax({
			url : '../tpl_php/ajax/homeworks.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				id_lesson : id_lesson,
				id : id,
				level : level,
				lang : lang,
				flag : '3'
			},
			success : function(data){
				var str = "";
				$(".homework_table").empty();
				for(var id in data){
					var links_s = "";
					var links_t = "";
					var hat = "";
					var change_status = "";
					var pre_month;
					var pre_year;
					var after_month;
					var after_year;
					//alert(data[id]['ldate']);
					var lesson_date = new Date(data[id]['ldate']);
					var month = lesson_date.getMonth()+1;
					var day = lesson_date.getDate();
					var year = lesson_date.getYear();
					var day_num = lesson_date.getDay();
					if(1*year<1000)
						year = 1*year+1900
					//console.log(day+" "+month+" "+year+" ||| "+day_num)
					if (day_num == 0) {
						day_num = 7;
					};

					var pre_day = 1*day-1*day_num;
					//console.log(pre_day);
					//console.log(year + ' ' + pre_year);
					var after_day = 1*day + 7 - 1*day_num;
					if(after_day > daysInMonth(year, month)){
						after_month = month + 1;
						if (after_month == 13) {
							after_month = 1;
							after_year = 1*year + 1;
						};
						after_day = 7 - 1*day_num;
					}
					else{
						after_month = month;
						after_year = year;
					}
					if (pre_day < 1) {
						//alert(pre_day);
						pre_month = month - 1;
						if (pre_month == 0) {
							pre_month = 12;
							pre_year = 1*year - 1;
							//alert(year + ' ' + (1*year - 1));
						}
						else{
							pre_month = pre_month;
							pre_year = year;
						}

						//alert(pre_day);
					}
					
					pre_day = daysInMonth(pre_year, pre_month)+pre_day;
					//alert(pre_day+" "+pre_month+" "+pre_year)
					var pre_date = pre_day+'.'+pre_month+'.'+pre_year;
					var after_date = after_day+'.'+after_month+'.'+after_year;
					//alert(pre_date+" "+after_date);
					var dates = "<span class='dates'>Неделя с "+pre_date+" до "+after_date+"<br style='clear:both;'>";

					//var mark_text = "";
					//alert(data[id]['fio'] + ' '+ data[id]['comment'] + ' '+ data[id]['mark']);
					for(var link in data[id]["student"]){
						links_s += "<a href='../upload/hworks/"+data[id]["student"][link]+"'>"+data[id]["student"][link].substr(22,40)+"</a><br>";
					}
					for(var link in data[id]["teacher"]){
						links_t += "<a download href='../upload/hworks/"+data[id]["teacher"][link]+"'>"+data[id]["teacher"][link].substr(22,40)+"</a><br>";
					}
					if(level > 1 && level != 3){
						links_t+="<br><span onclick=\"open_docs_modal("+data[id]["id"]+",'teacher',"+data[id]['id_u']+")\">Прикрепить</span>\
						<br><span class='atachik' id='attached"+data[id]["id"]+"'></span>";
					}
					if(data[id]['check_status'] == 4){
						hat += "<h2 class='rework'>Отправлено на доработку</h2>";
					}
					if(data[id]['check_status'] == 3){
						hat += "<h2 class='rework'>Отправлено на проверку учителю</h2>";
					}
					if((data[id]['check_status'] == 4 || data[id]["student"] == "" || data[id]["student"] == undefined) && level == 1){
						links_s+="<br><span onclick=\"open_docs_modal("+data[id]["id"]+",'student',"+data[id]['id_u']+")\">Прикрепить</span>\
						<br><span class='atachik' id='attached"+data[id]["id"]+"'></span>";
					}
					if(data[id]['mark'] != 0 && data[id]['check_status'] != 4 && data[id]['status'] != 4){
						if(data[id]['mark'] != "" && data[id]['mark'] != null && data[id]['mark'] != 0)
							hat += "<div class='mark_s'>Ваша оценка: "+data[id]['mark']+"</div>";

					}
					if(data[id]['status'] == 1){
						//alert(data[id]['status'])
						if (level > 1)
							change_status += "<span class='circled'>1</span>";
						if (level == 1)
							change_status += "<span class='circled' onclick=\"make_read("+data[id]["id"]+")\">1</span>";
					}
					//alert(data[id]["student"]);
					var more_text = "";
					//alert(level);
					if(level > 1 && level != 3){
						hat += "<h2>" + data[id]['fio'] + "</h2>";
						more_text = "<textarea placeholder='текст комментария' name='pholder'>"
						+data[id]["comment"]+"</textarea>"
						+"<input type='hidden' name='id_hw' value='"+data[id]['id_hw']+"'><table>\
							<tr>\
								<td>\
							<div class='hw_dorad_otp'><input type='checkbox' name='option5' value='a5'> отправить на доработку</div>\
							<input type='hidden' name='id_u' value='"+data[id]['id_u']+"' id='"+data[id]["id"]+"'>\
							<input type='hidden' name='attached_file' value='' id='file_attached"+data[id]["id"]+"'>\
								<td>\
							Оценка за ДЗ <input type='text' name='hw_mark' value='"+data[id]['mark']+"'>\
							<input type='button' class='hw_save' value='Отправить'\
							onclick=\"saveChanges(this.form.id_u.value,"+data[id]["id"]+','+data[id]['id_hw']+",this.form.pholder.value,this.form.hw_mark.value,this.form.option5.checked,'teacher')\">\
							<input type='button' class='hw_otmena' value='Отменить'></td>\
							</tr>\
						</table>\
						";
					}
					else{
						more_text = "<div class='comment'>"+data[id]['comment']+"</div>";
						if ( level != 3 ){
							more_text += "<input type='hidden' name='id_hw' value='"+data[id]['id_hw']+"'><table>\
							<tr>\
								<td>\
							<div class='hw_dorad_otp'><input type='checkbox' name='option5' value='a5'> отправить на доработку</div>\
							<input type='hidden' name='id_u' value='"+data[id]['id_u']+"'>\
							<input type='hidden' name='attached_file' value='' id='file_attached"+data[id]["id"]+"'>\
								<td>\
							Оценка за ДЗ <input type='text' name='hw_mark' value='"+data[id]['mark']+"'>\
							<input type='button' class='hw_save' value='Отправить'\
							onclick=\"saveChanges(this.form.id_u.value,"+data[id]["id"]+','+data[id]['id_hw']+",this.form.pholder.value,this.form.hw_mark.value,this.form.option5.checked,'student')\">\
							<input type='button' class='hw_otmena' value='Отменить'></td>\
							</tr>\
						</table>\
						"

						}
					}
					
					str += "<form method='post' action=''>\
					<div class='hw_zadanie_norm'>"+
					hat
					+"<h3>Тема:  "+data[id]['ltitle']+" </h3>"+
					dates+
					"<a class='hw_uc_testdz' href='http://online-shkola.com.ua/tests/completing.php?id="+data[id]['c_test_id']+"'>Тестовое ДЗ</a>"+
					"<a class='hw_kurok' href=\"javascript:onoff2('div"+data[id]["id"]+"');\">Творческое ДЗ"+change_status+"</a>"+
					"<a class='hw_kurok' href='../lessons/watch.php?id="+data[id]['lid']+"'>К уроку</a></div>";
							str += "<div id='div"+data[id]["id"]+"' class='hw_cooooont' style=''>\
								<h4><a class='hw_uslovia' href='../lessons/lookhw.php?id="+data[id]["id"]+"'>Условия ДЗ</a></h4>"+data[id]['text_hw']+
								"<table>\
									<tr>\
										<td>\
										<table><tr>\
											<td>\
												<p>Отправленное учеником: </p>"+links_s+"\
											</td>\
											<td>\
												<p>Отправленное учителем: </p>"+links_t+"\
											</td>\
										</table>\
									</tr>\
								</table>"+
									more_text
								+"</div></form>"
					
				}
				$(".homework_table").append(str);
			},
			error: function(){
				$(".homework_table").empty();	
			}
		})

}

function render_pages(page,count_all,show){
	var i = Math.ceil(count_all/show);
	var amount = 11;
	var half = Math.floor(amount/2);
	var start = 1;
	var end = i;
	//alert(i);
	var str = "";
	str += "<span class='angle-left' onclick=\"change_page(1,"+show+")\"></span>";
	if(page != 1) {
		str += "<span class='solo-angle-left' onclick=\"change_page("+(page-1)+","+show+")\"></span>";
		str += "<span class='alt-ruby'>&diams;</span>";
	}
	if(i <= amount) {
		start = 1;
		end = i;
	} else if(page <= half) {
		start = 1;
		end = amount;
	} else if(page >= (i-half)) {
		start = i - amount;
		end = i;
	} else {
		start = page - half;
		end = page + half;
	}
 	for(var j = start; j <= end; j++){
		if(page == j)
		str += "<span onclick=\"change_page("+j+","+show+")\" class='selected'>"+j+"</span>";
		if(page != j)
		str += "<span onclick=\"change_page("+j+","+show+")\">"+j+"</span>";
	}
	if(page != i) {
		str += "<span class='alt-ruby'>&diams;</span>";
		str += "<span class='solo-angle-right' onclick=\"change_page("+(page+1)+","+show+")\"></span>";
	}
	str += "<span class='angle-right' onclick=\"change_page("+i+","+show+")\"></span>";
	$(".paginate").empty();
	$(".paginate").append(str);
}
function change_page(page,show){
	var top_lim = page*show;
	var bot_lim = top_lim-show;
	//alert(top_lim+" "+bot_lim)
	$("input[name = cur_bot_lim]").val(bot_lim);
	$("input[name = cur_top_lim]").val(top_lim);
	$("input[name = cur_page]").val(page);
	if($("input[name = level]").val()==1)
		get_hw_list($("input[name = search]").val(), $("input[name = class]").val(),$("select[name = subject]").val(),$("input[name = date_s]").val(),$("input[name = date_do]").val(),$("select[name = subject]").val(),$("select[name = status]").val(),$("input[name = id]").val(),$("input[name = level]").val(),$("input[name = language]").val());
	if($("input[name = level]").val()>1)
		get_hw_list($("input[name = search]").val(),$("select[name = class]").val(),$("select[name = subject]").val(),$("input[name = date_s]").val(),$("input[name = date_do]").val(),$("select[name = subject]").val(),$("select[name = status]").val(),$("input[name = id]").val(),$("input[name = level]").val(),$("input[name = language]").val());
	
	render_pages(page,$("input[name = count_all]").val(),$("select[name = show]").val());
}
function change_lim(page,show){
	$("input[name = cur_page]").val(page);
	var top_lim = page*show;
	var bot_lim = top_lim-show;
	$("input[name = cur_bot_lim]").val(bot_lim);
	$("input[name = cur_top_lim]").val(top_lim);
}


window.onload=function(){
	var $beg_d = $("input[name = date_s]");
	var $end_d = $("input[name = date_do]");
	var $id = $("input[name = id]");
	var $show = $("select[name = show]");
	var $status = $("select[name = status]");
	var $level = $("input[name = level]");
	var $lang = $("input[name = language]");
	var $hw_table = $(".homework_table");
	if($level.val()==1)
		var $class_id = $("input[name = class]");
	if($level.val()>1)
		var $class_id = $("select[name = class]");

	if($level.val() == 2)
		getTsubjects($class_id.val(),$id.val());
	if($level.val() == 3 || $level.val() == 4)
		getSubjects($class_id.val());

	var $subj = $("select[name = subject]");
	//alert($subj.val());
	paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
	$("select[name = show]").change(function(){
		change_lim($("input[name = cur_page]").val(),$(this).val());
		change_page(1,$("select[name = show]").val());
		get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
	})
	if($("input[name = get]").val() == "nope"){
		get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
	}
	//get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
	//get_subjects_ua($class_id.val());

	function get_subjects_ua(class_id){
		$.ajax({
			url : '../tpl_php/subjects.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { 
				id : class_id,
				lang : $lang.val(),
				flag : '1' 
			} ,
			success : function ( data ) {
				if ( data['subjects'] )
				{
					var str = "";
					$subj.empty();

					for ( var id in data['subjects'] )
					{
						str += "<option value='" + id + "'>" + data['subjects'][id] + "</option>";
					}

					$subj.append(str);
				}
			}
		});
	}
	
	function getSubjects(class_id){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			lang : $lang.val(),
			flag : '18'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			str += "<option value='0'> \
				Все предметы </option>";
			//$("select[name = subjects]").empty();
			$("select[name = subject]").empty();
			for(var id in data){
				str += "<option value='"+id+"'>"+data[id]+"</option>";
			}
			//$subj.val("1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33");
			//$("select[name = subjects]").append(str);
			$("select[name = subject]").append(str);

		}

	})
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
					str +="<option value='"+data[id]+"' selected>Все предметы</option>";
				else
					str += "<option value='"+/*data[*/id/*]["id"]*/+"'>"+data[id]+"</option>";
			}
			$("select[name = subject]").val(data[0]);
			//$("select[name = subjects]").append(str);
			$("select[name = subject]").append(str);
			//get_hw_list($("input[name = search]").val(),$class_id.val(),$("select[name = subject]").val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
			
		}

	})

	//get_hw_list($("input[name = search]").val(),$class_id.val(),$("select[name = subject]").val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());

}
	$("div.homework_table.table_hw form").submit(function(){
		if(this.attached_file.value == '') {
			if($("input[name = level]").val() == 1) {
				if($("input[name = language]").val() == 'ru') {
					var text_data = "<b>Файл не отправлен!</b><br> Убедитесь, что вы его правильно \
					прикрепили и попробуйте снова. Инструкция по правильной загрузке ДЗ находится \
					<a target='_blank' href='http://online-shkola.com.ua/statics/watch.php?id=25#dz'>здесь</a>. \
					Файлы объемом более 5 Мб не загружаются. Поддерживаемые форматы файлов:\
					jpg, jpeg, png, bmp, doc, docx, xls, xlsx, ppt, pptx, pdf, rar, zip, txt, rtf, mp3, wma, gif"
				} else {
					var text_data = "<b>Файл не відправлений!</b><br>  \
					Переконайтеся, що ви його правильно прикріпили і спробуйте знову. \
					Інструкція по правильному завантаженню ДЗ знаходиться \
					<a target='_blank' href='http://online-shkola.com.ua/statics/watch.php?id=25#dz'>тут</a>\
					Файли об'ємом більше 5 Мб не завантажуються. Формати файлів, що підтримуються:\
					jpg, jpeg, png, bmp, doc, docx, xls, xlsx, ppt, pptx, pdf, rar, zip, txt, rtf, mp3, wma, gif"
				}
				alertion_window("grey-alert", text_data, 10, "open");
				return false;
			} else {
				if(!confirm("Файл не отправлен. Если вы просто выставили оценку\
				 или написали комментарий, то все в порядке. Если вы хотели\
				  отправить файл, то попробуйте снова. Файлы объемом более 5 Мб не загружаются.")) {
					return false;
				}
			}
		}
	})
	if($("input[name = local_hw]").val()!=0 && $("input[name = local_hw]").val()!="" && $("input[name = local_hw]").val()!=null && $("input[name = local_hw]").val()!=undefined){
		get_hw_list_new($("input[name = local_hw]").val(),$id.val(),$level.val(),$lang.val())
	}
	$subj.change(function(){
		get_hw_list($("input[name = search]").val(),$class_id.val(),$(this).val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		change_lim(1,$show.val());

	});
	$beg_d.change(function(){
		get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$(this).val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		change_lim(1,$show.val());
	})
	$end_d.change(function(){
		get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$(this).val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		change_lim(1,$show.val());
	})
	$status.change(function(){
		get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$(this).val(),$id.val(),$level.val(),$lang.val());
		paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		change_lim(1,$show.val());
	})
	$class_id.change(function(){
		if($level.val() == 2){
			getTsubjects($(this).val(),$id.val());
			paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
			get_hw_list($("input[name = search]").val(),$(this).val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		}
		else{
			getSubjects($(this).val());
			get_hw_list($("input[name = search]").val(),$(this).val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
			paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		}
		change_lim(1,$show.val());
	})
	$("input[name = start_search]").click(function(){
		get_hw_list($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val());
		paginate($("input[name = search]").val(),$class_id.val(),$subj.val(),$beg_d.val(),$end_d.val(),$show.val(),$status.val(),$id.val(),$level.val(),$lang.val(),$("input[name = search]").val());
		change_lim(1,$show.val());
	})



}