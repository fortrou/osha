window.onload = function() {
	
	var current = new Date();
	current = new Date(current.getFullYear(),current.getMonth(),1);
	var startDate = new Date(current.getFullYear(),current.getMonth(),1);

	var current_day = current.getDate();
	var current_month = current.getMonth();

	var month_sel = $("select[name = pokaz]");
	var calendar = document.getElementById("calendar");

	var language = $("input[name=language]");
	var level = $("input[name=level]");

	if ( level.val() == 4 )
		var cl = $("select[name=class]");
	else
		var cl = $("input[name=class");
	
	var month_name = ["Январь" , "Февраль" , "Март" , "Апрель" , 
					  "Май" , "Июнь" , "Июль" , "Август" , 
					  "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];

	var calendar_month = ["января" , "февраля" , "марта", "апреля", 
						  "мая" , "июня", "июля", "августа" , 
						  "сентября" , "октября", "ноября", "декабря"];

	var calendar_weeks = [ "вс" , "пн" , "вт" , "ср", "чт", "пт", "сб" ];


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
	month_sel.append(str);
	// END OF FORM
	//var new_d = new Date(current.getFullYear(),current.getMonth(),1)
	scrollToMonday(current);
	catchAndDraw();
var month = startDate.getMonth()+1;
			
// FUNCTIONS

function daysInMonth(year, month) {
	return 32 - new Date(year, month, 32).getDate();
};

function draw ( ea )
{			
	var table_str = "";

	if ( calendar.innerHTML ) calendar.innerHTML = "";

	for ( var i = 0 ; i < 5 ; i ++ )
	{
		table_str += "<tr>";
		
		var temp_str = "";

			for ( var j = 0 ; j < 7 ; j ++ )
			{
				temp_str += "<td ";
				
				if ( current.getDate() == current_day && current_month == current.getMonth() ) 
					temp_str += " class='active_calendar_table'";

				temp_str += " >" + '<h4>';
				temp_str += current.getDate() + ',' + calendar_weeks[current.getDay()] + '</h4';
				temp_str += "</td>";
				
				if ( ea )
				{
					var dating = current.getFullYear() + '-';

					if ( current.getMonth() + 1 < 10)
						var month =  '0' + (current.getMonth()+1);
					else
						var month = current.getMonth() +1;

					dating += month + '-';
					if ( current.getDate() < 10)
						var day = '0' + current.getDate();
					else
						var day = current.getDate();

					dating += day;
					
					if ( ea[dating] )
					{
						temp_str += '<table>';
						for ( var arr in ea[dating] )
						{
							temp_str += '<tr><td>' + ea[dating][arr]['name'] + '</td>';

							if ( current.getDate() <= current_day && current.getMonth() <= current_month )
								temp_str += "<td><a href='../lessons/watch.php?id=" + ea[dating][arr]['id'] + "'>" + ea[dating][arr]['theme'] + '</a></td></tr>'; 
							else if ( level.val() > 1 )
								temp_str += "<td><a href='../lessons/watch.php?id=" + ea[dating][arr]['id'] + "'>" + ea[dating][arr]['theme'] + '</a></td></tr>';
							else
								temp_str += "<td>" + ea[dating][arr]['theme'] + "</td></tr>";

							temp_str += "<tr>";

							if ( level.val() == 4 )
								temp_str += "<td><a href='../lessons/red.php?id=" + ea[dating][arr]['id'] + "'>" + "перенос" + '</a></td>';

							temp_str += '<td>' + arr + '</td></tr>';
						}
						temp_str += '</table>';
					}
				}

				table_str += temp_str;
				temp_str = "";
				
				current.setDate( current.getDate() + 1 );
			}
		table_str += "</tr>";
		//alert(table_str);
	}

	calendar.innerHTML += table_str;
};
alert("a");
function catchAndDraw() 
{
	
	$.ajax({
	url : '../tpl_php/calendar_jx.php' ,
	method : 'POST' ,
	data : { //start_day : startDate.getDate() , 
			 start_day : 1, 
			 start_month : (startDate.getMonth()+1) ,
			 start_year : startDate.getFullYear() ,
			 end_month : (startDate.getMonth()+1),
			 end_day : daysInMonth(startDate.getFullYear(),startDate.getMonth()),
			 class : cl.val(),
			 language : language.val(),
			 isDiary : '0'
			} ,
	dataType : 'json' , 
	success : function (ea) {
			draw(ea);
		},
	error : function (){
			draw(false);
		}
	});
};

function scrollToMonday ( date )
{
	//alert(date);
	// SCROLL TO MONDAY
	var pr = 1;
	if ( !date.getDay() ) pr = -6;
	//d = date.getDate() - date.getDay() + pr;
	//alert(d);
	date.setDate( date.getDate() - date.getDay() + pr);
	
	// SCROLL TO MONDAY	
	startDate = date;
};
// FUNCTIONS

// EVENTS
	$(".left_cal").click(function() {

		current.setDate( current.getDate() - 35 * 2 );
		startDate = new Date(current.getFullYear(),current.getMonth(),1);
		//alert(startDate);
		scrollToMonday ( startDate );
		catchAndDraw();
	});

	$(".right_cal").click(function() {
		startDate = new Date(current.getFullYear(),current.getMonth(),1);
		//alert(startDate);
		scrollToMonday ( startDate );
		catchAndDraw();
	});

	month_sel.change(function() {
		if ( month_sel.val() == current.getMonth() ) 
			return;
		else
		{
			var newDate = new Date( current.getFullYear() , month_sel.val() - 1 , 1 );
			scrollToMonday(newDate);

			current = newDate;	
			catchAndDraw();
		}
	});

	cl.change(function() {
		current.setDate( current.getDate() - 35 );
		startDate = new Date(current.getFullYear(),current.getMonth(),1);
		scrollToMonday ( startDate );
		catchAndDraw();

	});
// EVENTS

}