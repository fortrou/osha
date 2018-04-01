
	/*var $from = $("input[name = from]");
	var $to = $("input[name = to]");
	var $btn = $("input[name = mail_to_admin]");
	var $text = $("textarea[name = text_chat]");
	var $chat_field = $('.tech_chat');*/

	
	/*function getMessages(from, to){
		$.ajax({
			url : "http://online-shkola.com.ua/tpl_php/ajax/chat.php",
			method : 'post',
			dataType : 'json',
			data : {
				to : to,
				from : from,
				flag: flag
			},
			success : function(data){
				var str = "";
				for(var id in data){
					if(data[id]['id'] == from){
						str += "<div><span class='name'>Вы:</span> | <span class='date'>" + data[id]['date'] 
						+ "</span><br><span class='text'>"+
						data[id]['message']+"</span></div>";
					}
				}
				$chat_field.empty();
				$chat_field.append(str);
				$chat_field.scrollTop(10000000);
			}
		});
	}*/
	
	
	
//window.onload = function(){
	
	
	//alert($("#chat_id"));
	//
//}
/*$(document).ready(function(){
	var nd = new Date();
	var d = "";
	d += nd.getFullYear()+'-'+nd.getMonth()+'-'+nd.getDate()+' '+nd.getHours()+':'+nd.getMinutes()+":"+nd.getSeconds();
	alert(d);
	
	function send(from_p,to_p,chat_id, text){
		alert(from+' '+to+' '+ chat_id +' '+text);
		$.ajax({
			url : '../tpl_php/ajax/chat.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				message : text,
				from : from_p,
				to : to_p,
				chat_id : chat_id,
				flag : '1'
			},
			success: function(){

			}
			
		});
	}
	function request(time_a,chat_id){
		//alert(time+' '+chat_id);
		$.ajax({
			url : '../tpl_php/ajax/chat.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : {
				time_a : time_a,
				chat_id : chat_id,
				flag : '3'
			},
			success : function(data){
				console.log('success, reload it');
				request(data['time'],data['cid']);
			},
			error : function(){
				console.log("no new messages");
				request(time,chat_id);
			}
		});
	}*/
	/*var level_chat = document.getElementById('level_chat');
	if(level_chat.value != 4){
		var el = document.getElementById('chat_id');
		request(d,el.value);
	}*/
	//alert(el.value);
	
	//test();
//})

