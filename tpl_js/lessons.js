function lesson_create_load() {
	var course_id  = $("#course_list").val() || 0;
	var class_id   = $("#class").val() 		 || 0;
	var subject_id = $("#subject").val()  	 || 0;
	$.ajax({
			url : '../tpl_php/subjects.php' ,
			method : 'POST' ,
			dataType : 'JSON',
			data : {
				class_id   : class_id,
				subject_id : subject_id,
				course_id  : course_id,
				flag : '3'
			},
			success : function (data){
				//alert(teachers);
				$("#teacher_ua").empty().append(data['teacher']);
				$("#teacher_ru").empty().append(data['teacher']);
				$("#theme_list").empty().append(data['theme']);
				$("#subject").empty().append(data['subject']);

			}
		})
}
$(document).ready(function() {

	var $class = $("#class");
	var $subj = $("#subject");
	var $course = $("#course_list");

	var $teach_ua = $("#teacher_ua");
	var $teach_ru = $("#teacher_ru");
	/*$course.change(function(){
		var selected_class = $class.val() || 0;
		$.ajax({
			url : '../tpl_php/ajax/ct-interface.php' ,
			method : 'POST' ,
			dataType : 'JSON',
			data : {
			 		 course : $course.val(),
					 selected_class : selected_class,
					 flag : '1'
				   },
			success : function (subjects){

			}
		})
	})
	$class.change(function(){
		var selected_course = $course.val();
		if ( $(this).val() == 0 )
		{
			clearAndLeft($subj);
			clearAndLeft($teach_ru);
			clearAndLeft($teacher_ua);
		}
		else
		{
			$subj.removeAttr("disabled");
			$subj.empty();

			$teach_ru.removeAttr("disabled");
			$teach_ru.empty();

			$teach_ua.removeAttr("disabled");
			$teach_ua.empty();


			$.ajax({
				url : '../tpl_php/subjects.php' ,
				method : 'POST' ,
				dataType : 'JSON',
				data : {
						 id : $(this).val(),
						 lang : $("input[name=lang]").val(),
						 course : $course.val(),
						 flag : '1'
					   },
				success : function (subjects){
					$("#theme_list").empty().append(subjects['themes']);
					if ( subjects['subjects'] )
					{
						var str = "";
						for ( var id in subjects['subjects'])
						{
							str += "<option value='" + id + "'>";
							str += subjects['subjects'][id] + "</option>";
						}

						$subj.append(str);

						if ( !str ) 
						{
							clearAndLeft($subj);
							clearAndLeft($teach_ru);
							clearAndLeft($teach_ua);
						}
						else
						{
							str = "";

							for ( var id in subjects['teacher'] )
							{
								str += "<option value='" + id + "'>";
								str += subjects['teacher'][id] + "</option>";
							
							}

							$teach_ru.append(str);
							$teach_ua.append(str);
						}
					}
					else
					{
						clearAndLeft($subj);
						clearAndLeft($teach_ru);
						clearAndLeft($teach_ua);	
					}
				},
				error : function(){
					console.log('Missed!');
				}
			});
		}
	});*/
	/*$("select[name = subject]").change(function(){
		$.ajax({
			url : '../tpl_php/subjects.php' ,
			method : 'POST' ,
			dataType : 'JSON',
			data : {
				class_id : $class.val(),
				subject_id : $("select[name = subject]").val(),
				course : $course.val(),
				flag : '2'
			},
			success : function (teachers){
				//alert(teachers);
				$teach_ru.empty();
				$teach_ua.empty();
				$teach_ru.append(teachers);
				$teach_ua.append(teachers);
			}
		})
	})*/
$("select[data-filter=lesson-create]").change(function(){
	lesson_create_load();
});
function clearAndLeft(obj) {
	obj.attr("disabled" , "");
	obj.empty();
	obj.append("<option>--</option>")
}

});