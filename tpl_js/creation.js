function open_course_redact(id_user, id_course) {
	$(".courses-settings").css("display","block");
	$("input[name = id_user]").val(id_user);
	$("input[name = id_course]").val(id_course);
	get_courseSubjects(id_user, id_course)
}
function close_course_redact() {
	$(".courses-settings").css("display","none");
	$("input[name = id_user]").val('');
	$("input[name = id_course]").val('');
	$("#course-subjects").empty()
}
function get_courseSubjects(id_user, id_course) {
	$.ajax({
		url : '../tpl_php/ajax/ct-interface.php' ,
		method : 'POST' , 
		dataType : 'json' ,
		data : { 
			course : id_course,
			id_user : id_user,
			flag : '2' },
		success: function(data) {
			if(data.length) {
				$("#course-subjects").empty().append(data);
			}
		}
	});
}
function save_subjectsOnTeacher() {
	$.ajax({
		url : '../tpl_php/ajax/ct-interface.php' ,
		method : 'POST' , 
		dataType : 'json' ,
		data : { 
			course : $("input[name = id_course]").val(),
			id_user : $("input[name = id_user]").val(),
			subjects: $("#course-subjects").val(),
			flag : '3' },
		success: function(data) {
		}
	});
}
window.onload=function(){
	//var $classes = $("select[name = classes]");
	var $classes = $("#classes");
	var $subjects = $("#subjects");
	var $id_user = $("input[name = id_pr]");
	getSubjects( $classes.val(),$id_user.val() );
function in_array(needle, haystack, strict) {   // Checks if a value exists in an array
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

function getSelectedIndexes ($oListbox)
{
  var arrIndexes = new Array;
  for (var i=0; i < $oListbox.options.length; i++)
  {
      if (oListbox.options[i].selected) arrIndexes.push(i);
  }
  return arrIndexes;
};


function getSubjects( class_id, t_id )
	{
		//alert(class_id);
		$.ajax({
			url : '../tpl_php/ajax/creation.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { 
				id : class_id,
				t_id : t_id,
				lang : $("input[name = lang]").val(),
				flag : 'subjects' } ,
			success : function ( data ) {
				if ( data )
				{
					var str = "";
					$subjects.empty();
					
					for ( var id in data )
					{
						//alert(n_data);
						if(in_array(id,data[id]["second"]))
							str += "<option selected value='" + id + "'>" + data[id]['first'] + "</option>";
						else
							str += "<option value='" + id + "'>" + data[id]['first'] + "</option>";
					}

					$subjects.append(str);
				}
			}
		});
	};
	function update_classes(class_id,t_id){
		$.ajax({
			url : '../tpl_php/ajax/creation.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { 
				id : class_id,
				t_id : t_id,
				flag : 'classes_upd' }
		});
		getSubjects( $classes.val(),$id_user.val() );
	}
	function update_subjects(subj_id,t_id){
		$.ajax({
			url : '../tpl_php/ajax/creation.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { 
				id : subj_id,
				t_id : t_id,
				flag : 'subjects_upd' }
		});
	}
	$classes.change(function(){
		getSubjects( $classes.val(),$id_user.val() );
		if($("input[name=prev_status]").val() == '2'){
			//alert('a');
			update_classes($classes.val(),$id_user.val() )
		}
	})
	$subjects.change(function(){
		if($("input[name=prev_status]").val() == '2'){
			//alert('a');
			update_subjects($subjects.val(),$id_user.val() )
		}
	})
}