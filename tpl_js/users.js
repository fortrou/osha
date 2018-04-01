var arrCurItems = [];
function redir_rule() {
	if($("input[name = date_end]").val() == '0000-00-00') {
		location.href = "http://online-shkola.com.ua/cabinet/pay_edu.php";
	}
}
function addItem(obj)
{
    if(isInArr(arrCurItems, obj.selectedIndex))
    {
        rmArrItem(arrCurItems, obj.selectedIndex)
    }
    else
    {
        arrCurItems[arrCurItems.length] = obj.selectedIndex;
    }
    for(var i=0; i<obj.options.length; i++)
    {
        obj.options[i].selected = false;
    }
    for(var i=0; i<arrCurItems.length; i++)
    {
        obj.options[arrCurItems[i]].selected = true;
    }
    if(arrCurItems.length > 3){
		$("#pay2").css("display","none");
		$("#failed_update").empty();
		$("#failed_update").append("Вы выбрали более 3х предметов, так нельзя");
	}
	if(arrCurItems.length <= 3){
		$("#failed_update").empty();
		$("#pay2").css("display","");
	}
	
	for(var i=0; i<arrCurItems.length; i++)
    {
    	obj.options[arrCurItems[i]].value;
    }
    select_cost();
    //$("#pay2").attr("href",$("#pay2").attr("href").slice(0,$("#pay2").attr("href").length-1));
    //console.log($("#pay2").attr("href"));
}

function isInArr(arr, str)
{
    for(var i=0; i<arr.length; i++)
    {
        if(arr[i] == str)
            return true;
    }    
    return false;
}
function rmArrItem(arr, str)
{
    for(var i=0; i<arr.length; i++)
    {
        if(arr[i] == str)
            arr.splice(i, 1);
    }
    return arr;  
}
function get_people_pm(name){
	$.ajax({
		url : '../tpl_php/ajax/notifs.php',
		method : 'POST',
		dataType : 'json',
		data : {
			name : name,
			flag : '31'
		},
		success: function(data){
			var str = "";
			for(var id in data){
				str += "<tr><td><a target='_blank' href='preview.php?id="+data[id]['seven']+"'>"+
				data[id]["first"]+" "+data[id]["second"]+" "+data[id]["third"]+"</a></td><td>"+
				data[id]["fourth"]+"</td><td>"+data[id]["fifth"]+"</td><td>"+data[id]["sixth"]+
				"</td></tr>";
			}
			$("#pay_manager_users tbody").empty();
			$("#pay_manager_users tbody").append(str);
		}
	});
}
function get_people_course_pm(name, insert_field) {
	$.ajax({
		url : '../tpl_php/ajax/courses.php',
		method : 'POST',
		dataType : 'json',
		data : {
			name : name,
			flag : '8'
		},
		success: function(data){
			$("#" + insert_field + " tbody").empty().append(data);
		}
	});
}
	function in_array(needle, haystack, strict) {	// Checks if a value exists in an array
	// 
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

	var found = false, key, strict = !!strict;

	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}

	return found;
}

function get_people_pm_class_and_type(class_id,type,status){
	$.ajax({
			url : '../tpl_php/ajax/notifs.php',
			method : 'POST',
			dataType : 'json',
			data : {
				class_id : class_id,
				type : type,
				status : status,
				flag : '42'
			},
			success: function(data){
				var str = "";
				for(var id in data){
					str += "<tr><td><a target='_blank' href='preview.php?id="+data[id]['seven']+"'>"+
					data[id]["first"]+" "+data[id]["second"]+" "+data[id]["third"]+"</a></td><td>"+
					data[id]["fourth"]+"</td><td>"+data[id]["fifth"]+"</td><td>"+data[id]["sixth"]+
					"</td></tr>";
				}
				$("#pay_manager_users tbody").empty();
				$("#pay_manager_users tbody").append(str);
			},
			error: function(){
				$("#pay_manager_users tbody").empty();
			}
		});
}
function open_date_modal(id){
	$("#dark_bg").css("display","block");
	$("#dark_bg").css("z-index",9999);

	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id : id,
			flag : '33'
		},
		success: function(data){
			$("input[name = red_edu_type]").val(data['edu_type']);

			$("input[name = red_pay_class]").val(data["class"]);

			$("input[name = red_pay_id]").val(id);
			$("#date_end").empty();
			if(data['date_end']!="0000-00-00")
				$("#date_end").append("Оплачен до <span class='date_true'>"+data['date_end']+"</span>");
			else
				$("#date_end").append("Не оплачен!</span>");

			
			//alert(data['cost']);
		}

	})
}

function close_date_modal(){
	$("#dark_bg").css("display","none");
	$("#dark_bg").css("z-index",-9999);
}
function open_type_modal(id){
	$("#edu_type_assign").css("display","block");
	$("#edu_type_assign").css("z-index",9999);
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			id : id,
			lang : $("input[name = lang]").val(),
			flag : '33'
		},
		success: function(data){
			//alert(data['subjects']);
			$("select[name = red_pay_edu_type]").empty();
			$("select[name = red_pay_edu_type]").append(data['types']);
			$("input[name = red_pay_class]").empty();
			$("input[name = red_pay_class]").val(data["class"]);
			$("input[name = red_pay_id]").empty();
			$("input[name = red_pay_id]").val(id);

			$("#date_end").empty();
			if(data['date_end']!="0000-00-00")
				$("#date_end").append("Оплачен до <span class='date_true'>"+data['date_end']+"</span>");
			else
				$("#date_end").append("Не оплачен!</span>");

			if ($("select[name = red_pay_edu_type]").val() == 3) {
				$("#red_pay_subjects").empty();
				$("#red_pay_subjects").append("<p>Текущие предметы пользователя:</p>"+data['subjects']);
				$("#red_pay_subjects").css("display","block");
			}
			else{
				$("#red_pay_subjects").css("display","none");
			}
			if($("select[name = red_pay_edu_type]").val() != 3){
				//alert("a")
					//$(".multiselect").css("display","none");
					$("#red_pay_subjects").css("display","none");
					$("#realed").css("display","none");
					$("#fakeed").css("display","none");
					
				}
				else{
					//alert("b")
					//$(".multiselect").css("display","block");
					$("#red_pay_subjects").css("display","block");
					$("#realed").css("display","block");
					$("#fakeed").css("display","block");
					getSubjects(data["class"]);
			}
			
			//alert(data['cost']);
		}

	})
}
function close_type_modal(){
	$("#edu_type_assign").css("display","none");
	$("#edu_type_assign").css("z-index",-9999);
}
function open_course_modal(id_user,id_course){
	$.ajax({
		url : '../tpl_php/ajax/courses.php',
		method : 'POST',
		dataType : 'json',
		data : {
			id_user   : id_user,
			id_course : id_course,
			flag : '9'
		},
		success: function(data){
			$("#course_well_update").fadeOut();
			$("#course_failed_update").fadeOut();
			$("#course_meta_info").empty()
			$("#course_apply_payment input[name = prolong_id_user]").val(id_user);
			$("#course_apply_payment input[name = prolong_id_course]").val(id_course);
			$("#course_apply_payment").css("display","block");
			$("#course_apply_payment").css("z-index",9999);
		}
		
	});
}
function close_course_modal(){
	$("#course_well_update").fadeOut();
	$("#course_failed_update").fadeOut();
	$("#course_meta_info").empty()
	$("#course_apply_payment").css("display","none");
	$("#course_apply_payment").css("z-index",-9999);
}
function add_course_payment(id_user,id_course,payment_times) {
	$.ajax({
		url : '../tpl_php/ajax/courses.php',
		method : 'POST',
		dataType : 'json',
		data : {
			id_user 	  : id_user,
			id_course 	  : id_course,
			payment_times : payment_times,
			flag : '10'
		},
		success: function(data){
			if(data['status'] == 'success') {
				$("#course_meta_info").empty().append(data['date_till']);
				$("#course_well_update").fadeIn(200).delay(1000).fadeOut(200);
			} else {
				$("#course_failed_update").fadeIn(200).delay(1000).fadeOut(200);
			}
		},
		error: function(){
			$("#course_failed_update").fadeIn(200).delay(1000).fadeOut(200);
		}
		
	});
}
function filter_courses(insert_field) {
	var course_class = $("select[name = course_class_pm]").val() || 0;
	var course_status = $("select[name = course_status_pm]").val() || 0;
	var course_course = $("select[name = course_select_pm]").val() || 0;
	$.ajax({
		url : '../tpl_php/ajax/courses.php',
		method : 'POST',
		dataType : 'json',
		data : {
			course_class  : course_class,
			course_status : course_status,
			course_course : course_course,
			flag : '11'
		},
		success: function(data){
			$("#" + insert_field + " tbody").empty().append(data);
		}, 
		error: function(){
			$("#" + insert_field + " tbody").empty();
		}
		
	});
}
function upd_type(id,edu_type,subjects){
	//alert(id+" "+edu_type+" "+days+" "+subjects);
	$.ajax({
		url : '../tpl_php/ajax/notifs.php',
		method : 'POST',
		dataType : 'json',
		data : {
			id : id,
			edu_type : edu_type,
			subjects : subjects,
			flag : '34'
		},
		success: function(data){
		}
		
	});
	close_type_modal();
	open_type_modal(id);
	get_people_pm($("input[name = search]").val());
}
function upd_date(id,days){
	//alert(id+" "+edu_type+" "+days+" "+subjects);
	$.ajax({
			url : '../tpl_php/ajax/notifs.php',
			method : 'POST',
			dataType : 'json',
			data : {
				id : id,
				days : days,
				flag : '58'
			},
			success: function(data){
			}
			
		});
	close_date_modal();
	open_date_modal(id);
	get_people_pm($("input[name = search]").val());
}
function select_cost(){
	//alert('a')
	var subjects = "";
	if ($("select[name = tid]").val() == 3) {
		var arr_s = $("#real").val();
		for(var id in arr_s){
			subjects += arr_s[id]+',';
		}
		subjects = subjects.slice(0,subjects.length-1);
	}
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			type_id  : $("select[name = tid]").val(),
			subjects : subjects,
			prolong_edu: 0,
			flag : '16'
		},
		success: function(data){
			$("input[name = sum_text]").empty();
			$("input[name = sum_text]").val(data['cost']);
			$("#signature_items").empty().append(data['inputs']);
			//$("#pay2").attr("href","http://online-shkola.com.ua/cabinet/makeform.php?price="+data['cost']+"&e_type="+$("select[name = tid]").val()+"&type=1&subjects="+subjects);
			$("#sum_text").empty();
			$("#sum_text").append(data['cost']+"грн");
			//alert(data['cost']);
		}

	})
}
function generate_cost(){
	//alert('a')
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			user_id : $("input[name = edu_id_pc]").val(),
			monthes : $("select[name = pay_time_student]").val(),
			type_id : $("input[name = edu_type_pc]").val(),
			prolong_edu: 1,
			flag : '16'
		},
		success: function(data){
			$("input[name = sum_text1]").empty();
			$("input[name = sum_text1]").val(data['cost']);
			$("#signature_items").empty().append(data['inputs']);
			//$("#pay3").attr("href","http://online-shkola.com.ua/cabinet/makeform.php?price="+data+"&e_type="+$("input[name = edu_type_pc]").val()+"&type=2");
			$("#sum_text1").empty();
			$("#sum_text1").append(data['cost']+"грн");
			
		}

	})
}
function getSubjects(class_id){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			lang : $("input[name = lang]").val(),
			flag : '18'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			//$("select[name = subjects]").empty();
			$("#real").empty();
			$("#realed").empty();
			for(var id in data){
				str += "<option value='"+id+"'>"+data[id]+"</option>";
			}
			//$("select[name = subjects]").append(str);
			$("#real").append(str);
			$("#realed").append(str);

		}

	})
}
function generate_cost_3(){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			user_id : $("input[name = user_id]").val(),
			subjects : $("#real").val(),
			class_id : $("input[name = class_id]").val(),
			flag : '19'
		},
		success: function(data){
			$("input[name = sum_text]").empty();
			$("input[name = sum_text]").val(data);
			//console.log("http://online-shkola.com.ua/cabinet/makeform.php?price="+data);
			$("#pay2").attr("href","http://online-shkola.com.ua/cabinet/makeform.php?price="+data+"&e_type="+$("select[name = tid]").val()+"&type=1");
			$("#sum_text").empty();
			$("#sum_text").append(data);
		}

	})
}
function getList( level , class_id, status, subject, type )
	{
		//alert(level + " " + class_id);
		$.ajax({
			url : '../tpl_php/users.php',
			method : 'POST',
			dataType : 'json',
			data : {
				level: level,
				id : class_id,
				status : status,
				subjects : subject,
				type : type,
				flag : '1'
			},
			success : function ( data ){
				if ( data )
				{
					var str = "";
					$(".users tbody").empty();

					for ( var id in data )
					{
						
						str += "<tr><td><a href='preview.php?id="+data[id]['sixth']+"'>" 
							+ data[id]['second'] + ' ' 
							+ data[id]['third'] + "</a></td><td>" +
							data[id]['forth'] + 
							"</td><td>" + data[id]['seventh'] + "</td><td>" + data[id]['fifth'] + "</td></tr>";

					}
					//alert(str);
					if(str == "")
					{
						//$list.empty();
						str = "<tr>\
						<td>пусто</td>\
						<td>пусто</td>\
						<td>пусто</td>\
						<td>пусто</td>\
						</tr>";
						$(".users tbody").append(str);
					}
					else{
						//$list.empty();
						//alert(str);
						$(".users tbody").append(str);
					}
				}
			}
		});
	};
function change_lock1(id){
		$.ajax({
			url : '../tpl_php/users.php',
			method : 'POST',
			dataType : 'json',
			data : {
				id : id,
				flag : '2'
			},
			success: function(){
			}
		});
		getList( $("select[name = level]").val() , $("select[name = class_id_us]").val(),
		$("select[name = status]").val(), $("select[name = subject_id_us]").val(), $("select[name = filter_edu_type]").val() );
	}

	
window.onload = function(){
	var $class_id_us = $("select[name = class_id_us]");
	var $uslist = $(".users tbody");
	var $level = $("select[name = level]");
	var $search = $("input[name = sText]");
	var $link = $("input[name = link]");
	var $new_link = $("input[name = new_link]");
	var $get_link = $("input[name = get_link]");
	var $status = $("select[name = status]");
	var $subject_id_us = $("select[name = subject_id_us]");

	var $class_id = $("select[name = class]");
	var $edu_type = $("select[name = edu_type]");
	var $cost = $("input[name = cost]");
	var $change = $("input[name = change]");
	var $userlevel = $("input[name = userlevel]");
filter_courses('course_pay_manager_users');
getList($level.val(),$class_id_us.val(),$status.val(),$subject_id_us.val());
	function update_cost(class_id, type, cost){
		//alert(class + ' ' + type + ' ' + cost);
		$.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				class_id : class_id,
				type : type,
				cost : cost,
				flag : '10'
			} ,
			success : function () {
				
			}
		});
	}
	function get_cost(class_id, type){
		//alert(class + ' ' + type );
		$.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				class_id : class_id,
				type : type,
				flag : '11'
			} ,
			success : function (data) {
				$cost.val(data);
			}
		});
	}

	function getSubjects_modern(class_id){
		$.ajax({
			url : "../../tpl_php/ajax/notifs.php",
			method : 'post',
			dataType : 'json',
			data : {
				class_id : class_id,
				lang : $("input[name = lang]").val(),
				flag : '18'
			},
			success: function(data){
				var str = "";
				var iter = 1;
				//$("select[name = subjects]").empty();
				$subject_id_us.empty();
				str += "<option value=\"1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33\"> \
				Все предметы </option>";
				for(var id in data){
					str += "<option value='"+id+"'>"+data[id]+"</option>";
				}
				//$("select[name = subjects]").append(str);
				$subject_id_us.append(str);

			}

		})
	}

	function create_link(link){
		$.ajax({
			url : '../tpl_php/users.php',
			method : 'POST',
			dataType : 'json',
			data : {
				link : link,
				flag : '3'
			},
			success: function(data){
				$new_link.empty();
				$new_link.val(data);
			}
		});
	}

	
	function search(name){
		$.ajax({
			url : '../tpl_php/search.php',
			method : 'POST',
			dataType : 'json',
			data : {
				name : name
			},
			success: function(data){
				$uslist.empty();
				var str = "";
				for ( var id in data )
				{
					str += "<tr><td><a href='preview.php?id="+id+"'>" 
					+ data[id]['first'] + ' ' 
					+ data[id]['second'] + "</a></td><td>" +
					data[id]['third'] + 
					"</td><td>" + "</td><td>" + data[id]['fourth'] + "</td></tr>";
					//alert(str);
				}
				$uslist.append(str);
			}
		});
	}

	function change_lock(id){
		$.ajax({
			url : '../tpl_php/users.php',
			method : 'POST',
			dataType : 'json',
			data : {
				id : id,
				status : current_s,
				flag : '2'
			},
			success: function(){
			}
		});
	}
	
	function change_class(class_id,id){
		//alert(class_id+" "+id)
		$.ajax({
			url : '../tpl_php/users.php',
			method : 'POST',
			dataType : 'json',
			data : {
				id : id,
				class_id : class_id,
				flag : '4'
			},
			success: function(){

			}
		});
	}
/*** менеджер оплат с админа ***/
if($userlevel.val() == 4){
$("select[name = red_pay_edu_type]").change(function(){
	if($(this).val() != 3){
		//$(".multiselect").css("display","none");
		$("#red_pay_subjects").css("display","none");
		$("#realed").css("display","none");
		$("#fakeed").css("display","none");
		
	}
	else{
		//$(".multiselect").css("display","block");
		$("#red_pay_subjects").css("display","block");
		$("#realed").css("display","block");
		$("#fakeed").css("display","block");
		getSubjects($("input[name = red_pay_class]").val());
	}
})
$("input[name = update_pay]").click(function(){
	upd_date($("input[name = red_pay_id]").val(),$("input[name = days]").val());
})
$("input[name = change_type]").click(function(){
	upd_type($("input[name = red_pay_id]").val(),$("select[name = red_pay_edu_type]").val(),$("#realed").val());
})
$("select[name = class_pm]").change(function(){
	get_people_pm_class_and_type($(this).val(),$("select[name = edu_type_pm]").val(),$("select[name = edu_status_pm]").val())
})
$("select[name = edu_type_pm]").change(function(){
	get_people_pm_class_and_type($("select[name = class_pm]").val(),$(this).val(),$("select[name = edu_status_pm]").val())
})
$("select[name = edu_status_pm]").change(function(){
	get_people_pm_class_and_type($("select[name = class_pm]").val(),$("select[name = edu_type_pm]").val(),$(this).val())
})
}
/*** менеджер оплат с админа ***/
if($userlevel.val() == 1){
	select_cost();
	if($("select[name = tid]").val() != 3){
		$(".multiselect").css("display","none");
		$(".pre_ms").css("display","none");
		$("#real").css("display","none");
		$("#fake").css("display","none");
		
	}
	else{
		$(".multiselect").css("display","block");
		$(".pre_ms").css("display","block");
		$("#real").css("display","block");
		$("#fake").css("display","block");
		getSubjects($("input[name = class_id]").val());
	}
$("select[name = tid]").change(function(){
	//select_cost();
	if($(this).val() != 3){
		$(".multiselect").css("display","none");
		$(".pre_ms").css("display","none");
		$("#real").css("display","none");
		$("#fake").css("display","none");
		$("#pay2").css("display","");
	}
	else{
		$(".multiselect").css("display","block");
		$(".pre_ms").css("display","block");
		$("#real").css("display","block");
		$("#fake").css("display","block");
		$("#pay2").css("display","none");
		getSubjects($("input[name = class_id]").val());
	}
})
/*$("select[name = status]").change(function(){
	generate_cost();
})*/
if($("input[name = edu_id_pc]").val().length)
	generate_cost();
/*$("#real").change(function(){
	generate_cost_3();
})*/
$("select[name = pay_time_student]").change(function(){
	//alert("a");
	generate_cost();
});
}


	//alert($class_id_us.val());
	$("select[name = class_profile]").change(function(){
		change_class($(this).val(),$("input[name = id_pr]").val())
	})
	$search.on("input",function(){
		//alert("alala");
		search($search.val());
	});
	$status.change(function(){
		getList($level.val(),$class_id_us.val(),$(this).val(),$subject_id_us.val(), $("select[name = filter_edu_type]").val());
	})
	$class_id_us.change(function(){
		//alert('a');
		getSubjects_modern($(this).val());
		getList($level.val(),$(this).val(),$status.val(),$subject_id_us.val(), $("select[name = filter_edu_type]").val());
	});
	$level.change(function(){
		getList($(this).val(),$class_id_us.val(),$status.val(),$subject_id_us.val(), $("select[name = filter_edu_type]").val());
	});
	$subject_id_us.change(function(){
		getList($level.val(),$class_id_us.val(),$status.val(),$(this).val(), $("select[name = filter_edu_type]").val());
	})
	$get_link.click(function(){
		create_link($link.val());
	})
	$class_id.change(function(){
		get_cost($(this).val(),$edu_type.val());
	})
	$edu_type.change(function(){
		get_cost($class_id.val(),$(this).val());
	})
	$change.click(function(){
		update_cost($class_id.val(), $edu_type.val(), $cost.val());
	})
	$("select[name = filter_edu_type]").change(function(){
		getList($level.val(),$class_id_us.val(),$status.val(),$subject_id_us.val(), $(this).val());
	})
	$("input[name = prolong_continue_course]").click(function(){
		add_course_payment($("input[name = prolong_id_user]").val(),$("input[name = prolong_id_course]").val(),$("select[name = course_times]").val())
	})
	$("select[data-type=course_payment_select]").change(function(){
		filter_courses('course_pay_manager_users');
	})
}