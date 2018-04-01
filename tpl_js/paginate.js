function show_positions(page,class_tr){
	for(var j = 0; j <= $("."+class_tr).length; j++){
		$("."+class_tr+'_'+j).css("display","none");
	}
	var top_lim = page*25;
	var bot_lim = page*25-25;
	//alert(top_lim);
	//alert(bot_lim);
	for (var i = bot_lim; i < top_lim; i++) {
		$("."+class_tr+'_'+i).css("display","");
	};
}
function change_page(page,class_pages,class_tr){
	//alert(class_pages);
	$("input[name = "+class_pages+"]").val(page);
	create_pages(page,class_pages,class_tr);
	show_positions(page,class_tr)
}
function create_pages(page,class_pages,class_tr){

	var pages = Math.ceil((1*$("."+class_tr).length)/25);
	//alert(pages);
	var str = "";
	for(var i = 1; i <= pages; i++){
		//alert("i");
		if(i == page){
			str += "<li class='active' onclick=\"change_page("+i+",'"+class_pages+"','"+class_tr+"')\">"+i+"</li>"
		}
		else{
			str += "<li onclick=\"change_page("+i+",'"+class_pages+"','"+class_tr+"')\">"+i+"</li>"
		}
	}
	$("."+class_pages).empty();
	$("."+class_pages).append(str);
	//alert("."+class_pages).text;
}

