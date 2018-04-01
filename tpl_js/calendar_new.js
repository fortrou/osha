window.onload = function(){
	var current = new Date();
	var startDate;
	var reqDate = new Date(current.getFullYear(), current.getMonth(),1);
	
	var current_day = current.getDate();
	var current_month = current.getMonth();

	var $month_sel = $("select[name = pokaz]");
	var $calendar = $("#calendar");

	var language = $("input[name=language]");
	var level = $("input[name=level]");
	var $user = $("input[name = user]");
	/*if(level.val() == 2){
		getTclasses($user.val());
	}*/
	if ( level.val() == 4 || level.val() == 3 ){
		var $cl = $("select[name=class]");
		getSubjects($cl.val());
	}
	if(level.val() == 2)
		var $cl = $("select[name = class]");
	if(level.val() == 1)
		var $cl = $("input[name=class]");
//alert($("select[name = class]").val());
	var $class_t = $cl.val();

	if(level.val() == 2){
		getTsubjects($cl.val(),$user.val());
	}
	$("input[name = req_year]").val(current.getFullYear());
	if(language.val() == "ru"){
		var month_name = ["Январь" , "Февраль" , "Март" , "Апрель" , 
					  	 "Май" , "Июнь" , "Июль" , "Август" , 
					  	 "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];

		var calendar_month = ["января" , "февраля" , "марта", "апреля", 
						  	 "мая" , "июня", "июля", "августа" , 
						  	 "сентября" , "октября", "ноября", "декабря"];

		var calendar_weeks = [ "вс" , "пн" , "вт" , "ср", "чт", "пт", "сб" ];
	} else {
		var month_name = ["Січень" , "Лютий" , "Березень" , "Квітень" , 
					  	 "Травень" , "Червень" , "Липень" , "Серпень" , 
					  	 "Вересень", "Жовтень", "Листопад", "Грудень"];

		var calendar_month = ["січня" , "лютого" , "березня", "квітня", 
						  	 "травня" , "червня", "липня", "серпня" , 
						  	 "вересня" , "жовтня", "листопада", "грудня"];

		var calendar_weeks = [ "нд" , "пн" , "вт" , "сер", "чт", "пт", "сб" ];
	}
	

	
	
	// FORM MONTH SELECT
	var str = "";
	for (var i = 0; i < 12; i++ ) 
	{
		str += "<option value='" + (i+1) + '\'';
		if ( i == current.getMonth() ) str +=  " selected";
		str += ' >';
		str += month_name[i]; 
		str += '</option>';
	};
	$month_sel.append(str);
	// END OF FORM
	//alert($cl.val());
	var $subj_t = $("select[name = subject]").val();
	catchAndDraw(reqDate.getMonth()+1, reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()),language.val(),$subj_t);
	$(".cyear").empty();
	$(".cyear").append($("input[name = req_year]").val());
	function draw(arg,year,month){

		var days = daysInMonth(year,month-1);
		var str = "";
		var tr = "";
		var cnt = 1;
		var ncnt = 1;
		//alert(days);
//alert(current.getDate()+ ' ' +current.getMonth());
		//if(arg){
			for (var i = 1; i <= days; i++) {
			
			var date = new Date(year,month-1,i);
			//alert(date.getDate()+ ' ' +date.getMonth());
			var days_cur = daysInMonth(year, month-1);
			if((month-1) != 0){
				var pre_date = new Date(year,month-2,i);
				var pre_days = daysInMonth(year, month-2);
				var new_month = month-2;
			}
			else{
				var pre_date = new Date(1*$("input[name = req_year]").val()-1,11,i);
				var pre_days = daysInMonth(1*$("input[name = req_year]").val()-1, 11);
				var new_month = 11;
			}
			var day = date.getDay();
			var new_str = "";
			if(cnt==1){
				tr += "<tr>";
			}
			if(i==1){
				if(day == 2){
					tr += "<td class='ntm'><span class='date'>"+ pre_days + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[1] +"</span></td>";
					cnt = 2;
				}
				if(day == 3){
					tr += "<td class='ntm'><span class='date'>"+ (pre_days-1) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[1] +"</span></td>\
					<td class='ntm'><span class='date'>"+ pre_days + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[2] +"</span></td>";
					cnt = 3;
				}
				if(day == 4){
					tr += "<td class='ntm'><span class='date'>"+ (pre_days-2) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[1] +"</span></td>\
					<td class='ntm'><span class='date'>"+ (pre_days-1) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[2] +"</span></td><td class='ntm'><span class='date'>"+ pre_days + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[3] +"</span></td>";
					cnt = 4;
				}
				if(day == 5){
					tr += "<td class='ntm'><span class='date'>"+ (pre_days-3) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[1] +"</span></td>\
					<td class='ntm'><span class='date'>"+ (pre_days-2) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[2] +"</span></td><td ><span class='date'>"+ (pre_days-1) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[3] +"</span></td>\
					<td class='ntm'><span class='date'>"+ pre_days + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[4] +"</span></td>";
					cnt = 5;
				}
				if(day == 6){
					tr += "<td class='ntm'><span class='date'>"+ (pre_days-4) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[1] +"</span></td>\
					<td class='ntm'><span class='date'>"+ (pre_days-3) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[2] +"</span></td><td class='ntm'><span class='date'>"+ (pre_days-2) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[3] +"</span></td>\
					<td class='ntm'><span class='date'>"+ (pre_days-1) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[4] +"</span></td><td class='ntm'><span class='date'>"+ pre_days + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[5] +"</span></td>";
					cnt = 6;
				}
				if(day == 0){
					tr += "<td class='ntm'><span class='date'>"+ (pre_days-5) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[1] +"</span></td>\
					<td class='ntm'><span class='date'>"+ (pre_days-4) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[2] +"</span></td><td ><span class='date'>"+ (pre_days-3) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[3] +"</span></td>\
					<td class='ntm'><span class='date'>"+ (pre_days-2) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[4] +"</span></td><td ><span class='date'>"+ (pre_days-1) + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[5] +"</span></td>\
					<td class='ntm'><span class='date'>"+ pre_days + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[6] +"</span></td>";
					cnt = 7;
				}
			}
			
			

			//alert(date);

			for(var id in arg){

				//alert(arg[id]['title']);
				var c_date = new Date(arg[id]['days']);
				//alert(c_date.getDate()+ ' ' +c_date.getMonth());
				//alert(c_date);
				//alert(c_date.getDate()+ ' ' +current.getDate() + ' ' + c_date.getMonth() + ' ' + current.getMonth());
				if(c_date.getDate() == date.getDate() && c_date.getMonth() == date.getMonth()){
					
					if(c_date.getDate() != current.getDate() &&  level.val() != 1)
						new_str += "<table><tr>" + "<td><a class='nottoday' href='../lessons/watch.php?id="+ arg[id]['id']+"'>" 
						+ arg[id]['name'] + "</a></td>" + "<td>" + arg[id]['hours'] + "</td>" + "</tr>";
					else if((c_date.getMonth() < current.getMonth()) || (c_date.getMonth() == current.getMonth() &&
					 (c_date.getDate() < current.getDate())) || 
						((c_date.getMonth() >= current.getMonth() || c_date.getMonth() < current.getMonth()) 
							&& c_date.getFullYear() < current.getFullYear() ) &&  level.val() == 1){
						//alert(c_date.getDate()+ ' ' +current.getDate() + ' ' + c_date.getMonth() + ' ' + current.getMonth());
						new_str += "<table><tr>" + "<td><a class='nottoday' href='../lessons/watch.php?id="+ arg[id]['id'] + "'>" 
						+ arg[id]['name'] + "</a></td>" + "<td>" + arg[id]['hours'] + "</td>" + "</tr>";
					}
					else if((((c_date.getDate() > current.getDate()) &&
					 (c_date.getMonth() >= current.getMonth())) || (c_date.getMonth() > current.getMonth())) && level.val() == 1){
						new_str += "<table><tr>" + "<td><span class='nottoday'>" + arg[id]['name'] +
						 "</td>" + "<td>" + arg[id]['hours'] + "</span></td>" + "</tr>";
						}
					else if(c_date.getDate() == current.getDate())
						new_str += "<table><tr>" + "<td><a href='../lessons/watch.php?id="+ arg[id]['id'] + "'>" + arg[id]['name'] +
						 "</a></td>" + "<td>" + arg[id]['hours'] + "</td>" + "</tr>";
					
					if ( level.val() == 4 )
						new_str += "<tr><td><a class='change_date' href='../lessons/red.php?id=" + arg[id]['id'] + "'>" + "перенос" + '</a></td></tr>';
					new_str += "</table>";
					//alert(new_str);
				}
			}
			if(current.getDate() == date.getDate() && current.getMonth()-1 == date.getMonth()-1){
				tr += "<td class='today'><div>" + "<span class='date'>"+ i + ' '+ calendar_month[month-1] 
				+ ', ' + calendar_weeks[day] + "</span>" + new_str + "</div></td>";
			}
			else{
				tr += "<td>" + "<span class='date'>"+ i + ' '+ calendar_month[month-1] 
				+ ', ' + calendar_weeks[day] + "</span>" + new_str + "</td>";
			}
			/*if(i == 1 && ncnt % 6 != 0){
				while(ncnt % 6 != 0){
					//tr += "<td class='empty'></td>";
				}
			}*/

			if(i == days_cur){
				if((month-1) != 11){
				var pre_date = new Date(year,month,i);
				var pre_days = daysInMonth(year, month);
				var new_month = month;
			}
			else{
				var pre_date = new Date(1*$("input[name = req_year]").val()-1,0,i);
				var pre_days = daysInMonth(1*$("input[name = req_year]").val()-1, 0);
				var new_month = 0;
			}
				if (day == 1) {
					tr += "<td class='ntm'><span class='date ntm'>"+ 1 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[2] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 2 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[3] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 3 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[4] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 4 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[5] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 5 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[6] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 6 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[0] +"</span></td>";
				cnt = 7;
				};
				if (day == 2) {
					tr += 
				"<td class='ntm'><span class='date'>"+ 1 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[3] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 2 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[4] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 3 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[5] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 4 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[6] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 5 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[0] +"</span></td>";
				cnt = 7;
				};
				if (day == 3) {
					tr += 
				"<td class='ntm'><span class='date'>"+ 1 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[4] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 2 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[5] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 3 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[6] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 4 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[0] +"</span></td>";
				cnt = 7;
				};
				if (day == 4) {
					tr += 
				"<td class='ntm'><span class='date'>"+ 1 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[5] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 2 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[6] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 3 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[0] +"</span></td>";
				cnt = 7;
				};
				if (day == 5) {
					tr += 
				"<td class='ntm'><span class='date'>"+ 1 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[6] +"</span></td>"+
				"<td class='ntm'><span class='date'>"+ 2 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[0] +"</span></td>";
				cnt = 7;
				};
				if (day == 6) {
					tr += 
				"<td class='ntm'><span class='date'>"+ 1 + ' '+ calendar_month[new_month] 
				+ ', ' + calendar_weeks[0] +"</span></td>";
				cnt = 7;
				};
			}
			if(cnt == 7){
				tr += "</tr>";
				str += tr;
				//alert(tr);
				tr = "";
				cnt = 1;
			}
			else{
				cnt++;
			}
			ncnt++;
		};
	//}
		//alert(str);
		//alert(str);
		$calendar.empty();
		$calendar.append(str);
	}

	function daysInMonth(year, month) {
		return 32 - new Date(year, month, 32).getDate();
	};

	function getSubjects(class_id){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			lang : language.val(),
			class_id : class_id,
			flag : '18'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			if(language.val() == "ru"){
				str += "<option value=\"0\"> \
				Все предметы </option>";
			} else {
				str += "<option value=\"0\"> \
				Усі предмети </option>";
			}
			//$("select[name = subjects]").empty();
			$("select[name = subject]").empty();
			for(var id in data){
				str += "<option value='"+id+"'>"+data[id]+"</option>";
			}
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
			lang : language.val(),
			class_id : class_id,
			teacher_id : teacher_id,
			flag : '30'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			//$("select[name = subjects]").empty();
			$("select[name = subject]").empty();
			for(var id in data){
				if(id == 0){
					if(language.val() == "ru"){
						str +="<option value='0' selected>Все предметы</option>";
					} else {
						str +="<option value='0' selected>Усі предмети</option>";
					}
				} else
					str += "<option value='"+id+"'>"+data[id]+"</option>";
			}
			//$("select[name = subjects]").append(str);
			$("select[name = subject]").append(str);
			catchAndDraw(reqDate.getMonth()+1, reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()),language.val(),data[0]);
		}

	})

}

	function catchAndDraw(month,year, class_n,days,lang,subjects) 
	{
		//alert(month+' '+year+' '+class_n+' '+days+' '+lang+' '+subjects);
		$.ajax({
		url : '../tpl_php/ajax/calendar_new_jx.php' ,
		method : 'POST' ,
		dataType : 'json' , 
		data : {
				 month : month ,
				 year : year ,
				 class_n : class_n ,
				 end_day : days ,
				 language : lang ,
				 subjects : subjects,
				 isDiary : '0'
				} ,
		
		success : function (ea) {
				
				draw(ea,year,month);
			},
		error : function (){
				draw('',year,month);
			}
		});
	};

	function getTclasses(id){
		
		$.ajax({
			url : '../tpl_php/ajax/get_classes.php' ,
			method : 'POST' ,
			dataType : 'json' , 
			data : {
					 id : id
					} ,
			success : function(data){

				var str = "";
				$cl.empty();
				for(var id in data){

					if(id == 0)
						str +="<option value='"+data[id]['first']+"' selected>Все классы</option>";
					else
						str += "<option value='"+id+"'>"+data[id]['first']+"</option>";
				}
				$cl.append(str);
			}
		})
	};

$(".left_cal").click(function() {
	
	if($month_sel.val() != 1){
		var reqDate = new Date( 1*$("input[name = req_year]").val(), $month_sel.val()-1, 1 );
		console.log(reqDate);
		var new_val = reqDate.getMonth();
		var str = "";
		for (var i = 0; i < 12; i++ ) 
		{
			str += "<option value='" + (i+1) + '\'';
			if ( i == new_val-1 ) str +=  " selected";
			str += ' >';
			str += month_name[i]; 
			str += '</option>';
		};
		$month_sel.empty();
		$month_sel.append(str);
		$month_sel.val(new_val);
		var reqDate = new Date( reqDate.getFullYear() , $month_sel.val()-1, 1 );
		console.log(reqDate);
	}
	else{
		//alert("a");
		var reqDate = new Date( 1*$("input[name = req_year]").val()-1 , 11, 1 );
		console.log(reqDate.getMonth()+' '+reqDate.getFullYear()+' '+reqDate.getDate());
		//console.log("b");
		var new_val = reqDate.getMonth()+1;
		$("input[name = req_year]").val(1*$("input[name = req_year]").val()-1)
		$(".cyear").empty();
		$(".cyear").append($("input[name = req_year]").val());
		var str = "";
		for (var i = 0; i < 12; i++ ) 
		{
			str += "<option value='" + (i+1) + '\'';
			if ( i == new_val-1 ) str +=  " selected";
			str += ' >';
			str += month_name[i]; 
			str += '</option>';
		};
		$month_sel.empty();
		$month_sel.append(str);
		$month_sel.val(new_val);
		var reqDate = new Date( reqDate.getFullYear() , $month_sel.val()-1, 1 );
	}

		
		
		//console.log(reqDate);
		//console.log($month_sel.val());
		catchAndDraw($month_sel.val(), reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()),language.val(),$("select[name = subject]").val());
	});

$(".right_cal").click(function() {
	console.log($month_sel.val());
	if($month_sel.val() != 12){
		var reqDate = new Date( current.getFullYear() , $month_sel.val(), 1 );
		//console.log(reqDate);
		var new_val = reqDate.getMonth()+1;
		$month_sel.val(new_val);

		var str = "";
		for (var i = 0; i < 12; i++ ) 
		{
			str += "<option value='" + (i+1) + '\'';
			if ( i == new_val-1 ) str +=  " selected";
			str += ' >';
			str += month_name[i]; 
			str += '</option>';
		};
		$month_sel.empty();
		$month_sel.append(str);

		var reqDate = new Date( $("input[name = req_year]").val() , $month_sel.val()-1, 1 );
	}
	else{
		//alert("a");
		var reqDate = new Date( 1*$("input[name = req_year]").val()+1, 0, 1 );
		//console.log(reqDate);
		//alert(reqDate.getMonth());
		var new_val = (reqDate.getMonth());
		$("input[name = req_year]").val((1*$("input[name = req_year]").val()+1))
		$month_sel.val(new_val);
		$(".cyear").empty();
		$(".cyear").append($("input[name = req_year]").val());
		var reqDate = new Date( $("input[name = req_year]").val() , $month_sel.val(), 1 );
		var str = "";
		for (var i = 0; i < 12; i++ ) 
		{
			str += "<option value='" + (i+1) + '\'';
			if ( i == new_val-1 ) str +=  " selected";
			str += ' >';
			str += month_name[i]; 
			str += '</option>';
		};
		$month_sel.empty();
		$month_sel.append(str);
	}
		
		console.log($("input[name = req_year]").val());
		//console.log($month_sel.val());
		
		console.log(reqDate);
		//console.log($month_sel.val());
		catchAndDraw($month_sel.val(), reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$("select[name = subject]").val());
	});

$month_sel.change(function() {
	
		var reqDate = new Date( current.getFullYear() , $month_sel.val(), 1 );
		catchAndDraw(reqDate.getMonth(), reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$("select[name = subject]").val());
	
});
/*$cl.change(function() {
		var reqDate = new Date( current.getFullYear() , $month_sel.val(), 1 );
		//alert(daysInMonth(reqDate.getFullYear(),reqDate.getMonth()));
		//alert(reqDate.getMonth());
		
		catchAndDraw(reqDate.getMonth(), reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()),language.val(),$("select[name = subject]").val());

	});*/
$cl.change(function() {

		
		//alert(daysInMonth(reqDate.getFullYear(),reqDate.getMonth()));
		//alert(reqDate.getMonth());
		if(level.val() == 2)
			getTsubjects($(this).val(),$user.val());
		else
			getSubjects($cl.val());
		$class_t = $(this).val();
		if($month_sel.val()!=12/* && $month_sel.val()!=1*/){
			var reqDate = new Date( $("input[name = req_year]").val() , $month_sel.val(), 1 );
			catchAndDraw(reqDate.getMonth(), reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$("select[name = subject]").val());
		}
		if ($month_sel.val()==12) {
			var reqDate = new Date($("input[name = req_year]").val(), $month_sel.val()-1, 1 );
			catchAndDraw(reqDate.getMonth()+1, reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$("select[name = subject]").val());
		}
	});
$("select[name = subject]").change(function(){
	console.log($month_sel.val());
		if($month_sel.val()!=12/* && $month_sel.val()!=1*/){
			var reqDate = new Date( $("input[name = req_year]").val() , $month_sel.val(), 1 );
			//alert(daysInMonth(reqDate.getFullYear(),reqDate.getMonth()));
			console.log(reqDate);
			catchAndDraw(reqDate.getMonth(), reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$(this).val());
		}
		if ($month_sel.val()==12) {
			var reqDate = new Date( $("input[name = req_year]").val() , $month_sel.val()-1, 1 );
			//alert(daysInMonth(reqDate.getFullYear(),reqDate.getMonth()));
			console.log(reqDate);
			catchAndDraw(reqDate.getMonth()+1, reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$(this).val());
		};
		/*if ($month_sel.val()==1) {
			var reqDate = new Date( $("input[name = req_year]").val() , $month_sel.val()-1, 1 );
			//alert(daysInMonth(reqDate.getFullYear(),reqDate.getMonth()));
			console.log(reqDate);
			catchAndDraw(reqDate.getMonth()+1, reqDate.getFullYear(),$class_t,daysInMonth(reqDate.getFullYear(),reqDate.getMonth()+1),language.val(),$(this).val());
		};*/
	});
}