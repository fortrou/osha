function read_it(id){
		$.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { id : id,
			flag : '7' } ,
			success : function ( data ) {
				
			}
		});
		render_events($("input[name = id]").val(),$("input[name = lang]").val());
}
function render_events(id,lang){
	//alert(id+' '+lang);
	$.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { id : id,
			type : $("select[name = ev_type]").val(),
			lang : lang,
			top_lim: $("input[name = cur_top_lim]").val(),
			bot_lim: $("input[name = cur_bot_lim]").val(),
			flag : '6' } ,
			success : function ( data ) {
				if ( data )
				{
					var str = "";
					$(".events").empty();

					for ( var id in data )
					{
						if($("input[name = lang]").val() =='ru'){
							if(data[id]["fourth"] == 0)
								str += "<div class='unread'>" + data[id]['third'] + "<h5>" + data[id]["first"] 
							+ " <a href='"+data[id]['fifth']+"'>посмотреть</a></h5><div class='date_d'>" + data[id]["second"] + "</div>" + "</div>";
							if(data[id]["fourth"] != 0)
								str += "<div class='read'>" + "<h5>" + data[id]["first"] +
								 " <a href='"+data[id]['fifth']+"'>посмотреть</a></h5><div class='date_d'>" + data[id]["second"] + "</div>" 
							+ "</div>";
						} else {
							if(data[id]["fourth"] == 0)
								str += "<div class='unread'>" + data[id]['third'] + "<h5>" + data[id]["first"] 
							+ " <a href='"+data[id]['fifth']+"'>Подивитися</a></h5><div class='date_d'>" + data[id]["second"] + "</div>" + "</div>";
							if(data[id]["fourth"] != 0)
								str += "<div class='read'>" + "<h5>" + data[id]["first"] +
								 " <a href='"+data[id]['fifth']+"'>Подивитися</a></h5><div class='date_d'>" + data[id]["second"] + "</div>" 
							+ "</div>";
						}
					}

					$(".events").append(str);
				}
			}
		});
}
function paginate(id,num){
	$.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { id : id,
			type : $("select[name = ev_type]").val(),
			flag : '61' } ,
			success : function ( data ) {
				$("input[name = count_all]").val(data);
				render_pages($("input[name = cur_page]").val(),data);
			}
		});
	

}
function render_pages(page,count_all){
	var i = Math.ceil(count_all/50);
	//alert(i);
	var str = "";
	for(var j = 1; j <= i; j++){
		if(page == j)
		str += "<span onclick=\"change_page("+j+")\" class='selected'>"+j+"</span>";
		if(page != j)
		str += "<span onclick=\"change_page("+j+")\">"+j+"</span>";
	}
	$(".paginate").empty();
	$(".paginate").append(str);
}
function change_page(page){
	var top_lim = page*50;
	var bot_lim = top_lim-50;
	$("input[name = cur_bot_lim]").val(bot_lim);
	$("input[name = cur_top_lim]").val(top_lim);
	$("input[name = cur_page]").val(page);
	render_events($("input[name = id]").val(),$("input[name = lang]").val());
	render_pages(page,$("input[name = count_all]").val());
}
window.onload = function(){
	render_events($("input[name = id]").val(),$("input[name = lang]").val());
	paginate($("input[name = id]").val(),$("input[name = count]").val());
	$("select[name = ev_type]").change(function(){
		render_events($("input[name = id]").val(),$("input[name = lang]").val());
	})

}