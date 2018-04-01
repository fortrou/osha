$(document).ready(function() {

	var current = new Date();
	
	var current_day = current.getDate();
	var current_month = current.getMonth();

	var nWeeks = 1;
	var startDate;
	var endDate;

	var language = $("input[name=language]");
	
		var $cl = $("select[name=class]");
	

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

	scrollToMonday(current);
	catchAndDraw();

// FUNCTIONS
function draw(st,en, list)
{
	var str = "";
		while ( en - st )
		{
			//alert(en+' '+st);
			str += "<table "
			if ( st.getDate() == current_day && st.getMonth() == current_month ) str += "class='now_day'";
			str += " >";

			str += "<tr><th rowspan='6'><span>" + week_days[st.getDay()]  + ',' + st.getDate() + "</span></th>";
			if(language.val() == 'ru')
				str += "<th>Предмет</th>" + "<th>Тема</th><th>Время</th>" + "</tr>";
			else
				str += "<th>Предмет</th>" + "<th>Тема</th><th>Час</th>" + "</tr>";

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
				if ( list[dating] || new Date(list[dating]).getDate()== new Date(dating).getDate())
				{
					for ( var arr in list[dating] )
					{
						str += '<tr>';
						str += '<td>' + list[dating][arr]['name'] + '</td>';

						if ( current_day >= st.getDate())
							str += "<td><a href='../lessons/watch.php?id=" + list[dating][arr]['id'] + "'>" + list[dating][arr]['theme'] + '</a>';
						/*else if ( level.val() > 1 )
							str += "<td><a href='../lessons/watch.php?id=" + list[dating][arr]['id'] + "'>" + list[dating][arr]['theme'] + '</a>';*/
						else
							str += "<td>" + list[dating][arr]['theme'];

						str += '</td>';

						str += '<td>' + arr + '</td>';
						str += '</tr>';
						//alert(str);
					}
				}
			}

			str += "</table>";

			st.setDate( st.getDate() + 1 );
		}
	table.empty().append(str);	
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

function catchAndDraw() 
{
	//alert(class_n);
	$.ajax({
	url : '../tpl_php/diary_test_jx.php' ,
	method : 'POST' ,
	data : { start_day : startDate.getDate() , 
			 start_month : (startDate.getMonth()+1) ,
			 start_year : startDate.getFullYear() ,
			 end_day : endDate.getDate(),
			 end_month : (endDate.getMonth() +1),
			 end_year : endDate.getFullYear() ,
			 language : language.val(),
			 isDiary : '1'
			} ,
	dataType : 'json' , 
	success : function (ea) {
		//alert(ea);
			draw(startDate,endDate,ea);
		},
	error : function (){
			draw(startDate,endDate,false);
		}
	});
};

// FUNCTIONS

// EVENTS
$('select[name = pokaz]').change(function(){

	nWeeks = $('select[name = pokaz]').val() / 7;

	current = new Date();
	scrollToMonday( current );

	catchAndDraw();
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
			
			catchAndDraw();
		}
	}
	else
	{
		startDate = new Date(date_s.val());
		
		scrollToMonday(startDate);

		catchAndDraw();
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
			
			catchAndDraw();
		}
	}
	else
		alert("Укажите начальную дату!");
});
$cl.change(function() {
	current.setDate( current.getDate());
	startDate = new Date(current.getFullYear(),current.getMonth(),current.getDate());
	//alert(startDate);
	catchAndDraw();	

});

// EVENTS

});