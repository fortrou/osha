$(document).ready(function() {

	var current = new Date();
	
	var current_day = current.getDate();
	var current_month = current.getMonth();

	var nWeeks = 1;
	var startDate;
	var endDate;

	var language = $("input[name=language]");
	var level = $("input[name=level]");
	var $user = $("input[name = user]");

	if ( level.val() >= 2 )
		var $cl = $("select[name=class]");
	else
		var $cl = $("input[name=class");

	var date_s = $('input[name=date_s]');
	var date_do = $('input[name=date_do]');
	if(language.val() == 'ru'){
		var week_days = [ "Воскресенье", "Понедельник", "Вторник" , "Среда" , 
						"Четверг", "Пятница" , "Суббота" ];
	} else {
		var week_days = [ "Неділя", "Понеділок", "Вівторок" , "Середа" , 
						"Четвер", "П'ятниця" , "Субота" ];
	}

	var table = $('.diary_table');



	if(level.val() == 2){
		getTsubjects($cl.val(),$user.val());
		//catchAndDraw($cl.val(),$("select[name = subject]").val());
		scrollToMonday(current);
	catchAndDraw($cl.val(),$("select[name = subject]").val());
	}
	else{
		scrollToMonday(current);
		catchAndDraw($cl.val(),$("select[name = subject]").val());
	}
	//alert($("select[name = subject]").val());
	
	
//alert($("select[name = subject]").val());
	//alert($cl.val());
	
// FUNCTIONS
function draw(st,en, list)
{
	table.empty();
		while ( en - st )
		{
			var str = "";
			/**
			 * class = 'now_day' - текущий день
			 * class = 'diary_perenos' - ссылка на перенос
			 *
			 *
			 *
			 *
			 **/
			str += "<table "
			//alert(st.getDate()+"   |   "+st.getMonth());
			if ( st.getDate() == current_day && st.getMonth() == current_month ) str += "class='now_day'";
			str += " >";
			var stdays = st.getDate();
			var day_of_week = st.getDay();
			var re_text = week_days[day_of_week]?week_days[day_of_week]:"";
			str += "<tr><th rowspan='6'><span>" + re_text  + ',' + stdays + "</span></th>";
			if(language.val() == 'ru') {
				str += '<th>Предмет</th><th>Тема</th><th>Время</th></tr>';
			} else {
				str += '<th>Предмет</th><th>Тема</th><th>Час</th></tr>';
			}

			if ( list )
			{
				var dating = st.getFullYear() + '-';

					if ( st.getMonth() + 1 < 10)
						var month =  '0' + (st.getMonth()+1);
					else
						var month = st.getMonth() +1;

					dating += month + '-';
					if ( st.getDate() < 10)
						var day = '0' + st.getDate();
					else
						var day = st.getDate();

				dating += day;

				if ( list[dating] )
				{
					for ( var arr in list[dating] )
					{
						str += '<tr>';
						str += '<td>' + list[dating][arr]['name'] + '</td>';
						//alert(current_month+'  ||  '+st.getMonth());
						if ( (current_day >= st.getDate() && current_month == st.getMonth()) || ( current_month > st.getMonth()) )
							str += "<td><a href='../lessons/watch.php?id=" + list[dating][arr]['id'] + "'>" + list[dating][arr]['theme'] + '</a>';
						else if ( level.val() > 1 )
							str += "<td><a href='../lessons/watch.php?id=" + list[dating][arr]['id'] + "'>" + list[dating][arr]['theme'] + '</a>';
						else
							str += "<td>" + list[dating][arr]['theme'];

						if ( level.val() == 4 ) str += "  <span style='margin-left:20px;'></span>  <a class='diary_perenos' href='../lessons/red.php?id="
						+ list[dating][arr]['id'] +"'>перенести</a>"; 

						str += '</td>';

						str += '<td>' + list[dating][arr]['hours'] + '</td>';
						str += '</tr>';
					}
				}
			}

			str += "</table>";

			st.setDate( st.getDate() + 1 );
			table.append(str);
		}
		
}

function scrollToMonday ( date )
{
	// SCROLL TO MONDAY
	var pr = 1;
	if ( !date.getDay() ) pr = -6;

	date.setDate( date.getDate() - date.getDay() + pr);
	// SCROLL TO MONDAY	

	startDate = new Date(date.getFullYear() , date.getMonth() , date.getDate());
	
	endDate = new Date(startDate.getFullYear() , startDate.getMonth(), startDate.getDate() );
	endDate.setDate( endDate.getDate() + (nWeeks * 7));
};

function catchAndDraw(class_n,subject_v) 
{
	//alert($("select[name = subject]").val());
	//alert($("select[name = subject]").val());
	//alert(class_n);
	if(date_s.val()) {
	//alert("date_start1: " + date_s.val());
		var date_ss = new Date(date_s.val());
		var begin_date = date_ss.getDate();
		var begin_month = date_ss.getMonth()+1;
		var begin_year =  date_ss.getFullYear();
	} else {
		//alert("date_start2: " + startDate);
		var begin_date = startDate.getDate();
		var begin_month = (startDate.getMonth()+1);
		var begin_year =  startDate.getFullYear();
	}
	if(date_do.val()) {
		//alert("date_end1: " + date_do);
		var date_doo = new Date(date_do.val());
		var end_date = date_doo.getDate();
		var end_month = date_doo.getMonth()+1;
		var end_year =  date_doo.getFullYear();
	} else {
		//alert("date_end2: " + end_date);
		var end_date = endDate.getDate();
		var end_month = (endDate.getMonth()+1);
		var end_year =  endDate.getFullYear();
	}
	$.ajax({
	url : '../tpl_php/calendar_jx.php' ,
	method : 'POST' ,
	data : { start_day : begin_date, 
			 start_month : begin_month,
			 start_year : begin_year,
			 end_day : end_date,
			 end_month : end_month,
			 end_year : end_year,
 			 class_n : class_n,
 			 subject_v : subject_v,
			 language : language.val(),
			 isDiary : '1'
			} ,
	dataType : 'json' , 
	success : function (ea) {
			if(date_s.val()) {
				var date_from = date_ss;
			} else {
				var date_from = startDate;
			} 
			if(date_do.val()) {
				var date_till = date_doo;
			} else {
				var date_till = endDate;
			}
			draw(startDate,endDate,ea);
		},
	error : function (){
			draw(startDate,endDate,false);
		}
	});
};
function getSubjects(class_id){
	$.ajax({
		url : "../../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : class_id,
			lang : language.val(),
			flag : '18'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			str += "<option value=\"0\" selected> \
				Все предметы </option>";
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
			class_id : class_id,
			lang : language.val(),
			teacher_id : teacher_id,
			flag : '30'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			//$("select[name = subjects]").empty();
			$("select[name = subject]").empty();
			for(var id in data){
				if(id == 0)
					str +="<option value='0' selected>Все предметы</option>";
				else
					str += "<option value='"+id+"'>"+data[id]+"</option>";
			}

			//$("select[name = subjects]").append(str);
			$("select[name = subject]").append(str);
			//alert(data[0]);
			$("select[name = subject]").val(data[0]);
			//alert($("select[name = subject]").val());
			
		}

	})

}
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
// FUNCTIONS

// EVENTS
$('select[name = pokaz]').change(function(){

	nWeeks = $('select[name = pokaz]').val() / 7;

	current = new Date();
	scrollToMonday( current );

	catchAndDraw($cl.val(),$("select[name = subject]").val());
});

date_s.change(function(){
	if ( date_do.val() )
	{
		if ( date_s.val() > date_do.val() ) 
		{
			alert('Некорректный запрос!');
			date_s.val("");
		}
		else
		{
			startDate = new Date(date_s.val());
			scrollToMonday(startDate);

			endDate = new Date(date_do.val());
			endDate.setHours(0);
			
			catchAndDraw($cl.val(),$("select[name = subject]").val());
		}
	}
	else
	{
		startDate = new Date(date_s.val());
		
		scrollToMonday(startDate);

		catchAndDraw($cl.val(),$("select[name = subject]").val());
	}
		
});

date_do.change(function () {
	if ( date_s.val() )
	{
		if ( date_s.val() > date_do.val() )
		{
			alert("Данная дата меньше начальной!");
			date_do.val("");
		}
		else
		{
			startDate = new Date(date_s.val());
			scrollToMonday(startDate);

			endDate = new Date(date_do.val());
			endDate.setHours(0);
			
			catchAndDraw($cl.val(),$("select[name = subject]").val());
		}
	}
	else
		alert("Укажите начальную дату!");
});
$cl.change(function() {
	current.setDate( current.getDate());
	startDate = new Date(current.getFullYear(),current.getMonth(),current.getDate());
	//alert(startDate);
	catchAndDraw($(this).val(),$("select[name = subject]").val());
	if(level.val() == 2)
		getTsubjects($(this).val(),$user.val());
	else
		getSubjects($(this).val());

});
$("select[name = subject]").change(function(){
	current.setDate( current.getDate());
	startDate = new Date(current.getFullYear(),current.getMonth(),current.getDate());
		//alert(daysInMonth(reqDate.getFullYear(),reqDate.getMonth()));
		//alert(reqDate.getMonth());
		
		catchAndDraw($cl.val(),$(this).val());
	});
// EVENTS

});