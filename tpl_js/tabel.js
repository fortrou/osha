function easy_close(){
	$("#tabel_link_content_1").css("display","none");
}
function easy_open(){
	//alert("a");
	$("#tabel_link_content_1").css("display","block");
}
function hard_open(type,subject,mark){
	easy_open();
	$("select[name = type] [value='"+type+"']").attr("selected","selected");
	$("select[name = subjects] [value='"+subject+"']").attr("selected","selected");
	$("input[name = mark]").val(mark);
}
function delete_tabel_mark(type, subject) {
	var result = confirm('Вы уверены, что хотите удалить оценку с типом ' + type + '? \n' +
                          'Существующие типы: \n ' +
						  'first_s - первый семестр \n second_s - второй семестр \n year - год \n gia - ГИА \n final - итог');
	if(result) {
		$.ajax({
			url : '../tpl_php/ajax/notifs.php',
			method : 'POST',
			dataType : 'json',
			data :{
				type : type,
				subj : subject,
				class_id : $("select[name = class]").val(),
				user_id : $("select[name = students]").val(),
				flag : '73',
			},
			success : function ( data ){
				if(data == 'yep') {
					alert('Оценка удалена, обнови табель')
				} else {
					alert('Оценка не была удалена')
				}
			}
		});
	}
}
window.onload = function(){
	var $class = $("select[name = class]");
	var $subject = $("select[name = subjects]");
	var $type = $("select[name = type]");
	var $list1 = $("select[name = students]");
	var $list = $(".tabel tbody");
	var $upd = $("input[name = update]");
	var $mark = $("input[name = mark]");
	var $fio = $("input[name = search]");
	var $id = $("input[name = id]");
	var $level = $("input[name = level]");

	
	if($level.val() == 1){
		getTabel($id.val());
	}
	else{
		getList( $class.val());
		getTabel($list1.val());
		getSubjects($class.val());
		showVal($type.val(),$subject.val(),$class.val(),$list1.val(),1,0);
		
	}
	function getList( class_id )
	{
		$.ajax({
			url : '../tpl_php/journal.php' ,
			method : 'POST',
			dataType : 'json',
			data : {
				id : class_id
			},
			success : function ( data ){
				if ( data )
				{
					var str = "";
					$list1.empty();
					//console.log(data);
					

					var count = 2;
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
							count++;
					}
					if (count != 0) {
						//console.log(data.length);
						$("select[name = students]").attr("size",count);
					};
					//console.log(str);
					if(str == "")
					{
						//$list.empty();
						str = "<option disabled><span>Пока нет учеников<span></option>";
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

	function getTabel( user){
		//alert(user);
		$.ajax({
			url : '../tpl_php/tabel.php' ,
			method : 'POST',
			dataType : 'json',
			data : {
				user_id : user,
				lang    : $("input[name=lang]").val(),
				self_id : $("input[name = id]").val(),
				level   : $("input[name = level]").val()
			},
			success : function ( data ){
				var str = "";
				//alert("a");
				$list.empty();
				var first_s_redacted = '';
				var second_s_redacted = '';
				var year_redacted = '';
				var first_s_delete = '';
				var second_s_delete = '';
				var year_delete = '';
				var gia_delete = '';
				var final_delete = '';
				
				var span_grid_close = '</span>';
				if ($level.val() == 4 || $level.val() == 2) {
					for(var id in data){
						first_s_redacted = '';
						second_s_redacted = '';
						year_redacted = '';
						if($level.val() == 4) {
							if(data[id]['first_s_redacted'] == 1) {
								first_s_redacted = '<span class="tabel-redact">(ред)</span>';
							}
							if(data[id]['second_s_redacted'] == 1) {
								second_s_redacted = '<span class="tabel-redact">(ред)</span>';
							}
							if(data[id]['year_redacted'] == 1) {
								year_redacted = '<span class="tabel-redact">(ред)</span>';
							}
							second_s_delete = '<div class="tabel-delete" onclick="delete_tabel_mark(\'second_s\', ' + data[id]['subj_id'] + ')">x</div>';
							first_s_delete = '<div class="tabel-delete" onclick="delete_tabel_mark(\'first_s\', ' + data[id]['subj_id'] + ')">x</div>';
							final_delete = '<div class="tabel-delete" onclick="delete_tabel_mark(\'final\', ' + data[id]['subj_id'] + ')">x</div>';
							year_delete = '<div class="tabel-delete" onclick="delete_tabel_mark(\'year\', ' + data[id]['subj_id'] + ')">x</div>';
							gia_delete = '<div class="tabel-delete" onclick="delete_tabel_mark(\'gia\', ' + data[id]['subj_id'] + ')">x</div>';
						}
						str += "<tr><td>" + data[id]['subject'] + "</td><td>" + first_s_delete + "<span class='grid-element' onclick=\"hard_open('first_s','" 
						+ data[id]['subj_id'] + "','" + data[id]['first_s'] +"');\">" + data[id]['first_s'] + span_grid_close + first_s_redacted 
						+ "</td><td>" + second_s_delete + "<span class='grid-element' onclick=\"hard_open('second_s','" + data[id]['subj_id']+"','" 
						+ data[id]['second_s'] +"');\">" + data[id]['second_s'] + span_grid_close + second_s_redacted +
						"</td><td>" + year_delete + "<span class='grid-element' onclick=\"hard_open('year','" + data[id]['subj_id'] + "','" 
						+ data[id]['year'] +"');\">" + data[id]['year'] + span_grid_close + year_redacted + "</td><td>" + gia_delete 
						+ "<span class='grid-element' onclick=\"hard_open('gia','" + data[id]['subj_id']+"','"+ data[id]['gia']+"');\">" 
						+ data[id]['gia'] + span_grid_close + "</td><td>" + final_delete + "<span class='grid-element' onclick=\"hard_open('final','"
						+ data[id]['subj_id']+"','"+ data[id]['final'] +"');\">" + data[id]['final'] + span_grid_close + "</td></tr>";
					}
					//alert('a');
				}
				else{
					for(var id in data){
						str += "<tr><td>" + data[id]['subject'] + "</td><td>"
						+ data[id]['first_s'] + "</td><td>" + data[id]['second_s'] +
						"</td><td>" + data[id]['year'] + "</td><td>" + data[id]['gia']
						+ "</td><td>" + data[id]['final'] + "</td></tr>"
					}
				}
				//alert(str);
				$list.append(str);
			}
		});
	};

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

	function showVal(type, subj, class_id, user_id, state, mark){
		//alert(type + " " + subj + " " + class_id + " " + user_id + " " + state);
		$.ajax({
			url : '../tpl_php/valshu.php',
			method : 'POST',
			dataType : 'json',
			data :{
				type : type,
				subj : subj,
				class_id : class_id,
				user_id : user_id,
				state : state,
				mark : mark
			},
			success: function(data){
				var str = "";
				
				if(state == 1){
					str += data;
					$mark.val(str);
				}
				if(state == 2){
					str += data;
					$mark.val(str);	
				}
				//alert(str);

			}

		});
		//easy_close();
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

					for ( var id in data )
					{
						str += "<option value='" + id 
							+ "'>"
							+ data[id]['first'] + ' ' 
							+ data[id]['second'] + "</option>";
							getSubjects(data[id]["forth"]);
							$class.val(data[id]["forth"]);
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
						$list1.append(str);
					}
			}
		});
	}

	$class.change( function () {
		getList($(this).val());
		getSubjects($(this).val());
		//alert($subject);
	});
	$list1.change( function(){
		getTabel($(this).val());
		
	});
	$type.change( function(){
		showVal($(this).val(),$subject.val(),$class.val(),$list1.val(),1,0);
	});
	$subject.change( function(){
		showVal($type.val(),$(this).val(),$class.val(),$list1.val(),1,0);
	});
	$fio.on("input",function(){
		if($fio.val() == ''){
			$list1.empty();
			$list1.append("<option disabled><span>Пока нет учеников<span></option>")
		}
		else{
			search_u($(this).val());
		}
	});
	$mark.on("input",function(){
		if(1*$mark.val() < 0 || 1*$mark.val() > 12) $mark.val(1);
		
	});
	$("input[name = update_tabel]").click(function(){
		showVal($type.val(),$subject.val(),$class.val(),$list1.val(),2,$mark.val());
		getTabel($list1.val());
		easy_close();
	})
}