function select_cost(type){
	//alert('a')
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			type_id : $("select[name = tid]").val(),
			flag : '16'
		},
		success: function(data){
			$("input[name = sum_text]").empty();
			$("input[name = sum_text]").val(data['cost']);
			$("#pay2").attr("href","http://online-shkola.com.ua/cabinet/makeform.php?price="+data['cost']);
			$("#sum_text").empty();
			$("#sum_text").append(data['cost']);
			//alert(data['cost']);
		}

	})
}
function generate_cost(){
	//alert('a')
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			user_id : $("input[name = user_id]").val(),
			days : $("select[name = status]").val(),
			flag : '17'
		},
		success: function(data){
			$("input[name = sum_text1]").empty();
			$("input[name = sum_text1]").val(data);
			$("#pay3").attr("href","http://online-shkola.com.ua/cabinet/makeform.php?price="+data);
			$("#sum_text1").empty();
			$("#sum_text1").append(data);
			//alert(data['cost']);
		}

	})
}
function getSubjects(class_id){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			class_id : $("input[name = class_id]").val(),
			flag : '18'
		},
		success: function(data){
			var str = "";
			var iter = 1;
			$("select[name = subjects]").empty();
			for(var id in data){
				str += "<option value='"+id+"'>"+data[id]+"</option>";
			}
			$("select[name = subjects]").append(str);

		}

	})
}
function generate_cost_3(){
	$.ajax({
		url : "../tpl_php/ajax/notifs.php",
		method : 'post',
		dataType : 'json',
		data : {
			user_id : $("input[name = user_id]").val(),
			subjects : $("select[name = subjects]").val(),
			class_id : $("input[name = class_id]").val(),
			flag : '19'
		},
		success: function(data){
			$("input[name = sum_text]").empty();
			$("input[name = sum_text]").append(data);
			//console.log("http://online-shkola.com.ua/cabinet/makeform.php?price="+data);
			$("#pay2").attr("href","http://online-shkola.com.ua/cabinet/makeform.php?price="+data);
			$("#sum_text").empty();
			$("#sum_text").append(data);
		}

	})
}
window.onload=function(){
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
	select_cost($(this).val());
	if($(this).val() != 3){
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
})
$("select[name = status]").change(function(){
	generate_cost();
})
$("select[name = subjects]").change(function(){
	generate_cost_3();
})

}