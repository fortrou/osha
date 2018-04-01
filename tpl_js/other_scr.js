 $(window).load(function(){
      $(".index_body_2").sticky({ topSpacing: 0 });
    });
	
	
	jQuery(function($){			
			$('ul#items').easyPaginate({
				step:1
			});
		});  
		
$(document).ready(function() {  
    var overlay = $('#overlay23');  
    var open_modal = $('.open_modal23');  
    var close = $('.modal_close23, #overlay23'); 
    var modal = $('.modal_div23');  
     open_modal.mouseover( function(event){  
         event.preventDefault();  
         var div = $(this).attr('href');  
         overlay.fadeIn(400, 
             function(){  
                 $(div)  
                     .css('display', 'block') 
                     .animate({opacity: 1, top: '50%'}, 200);  
         });
     });
     close.click( function(){  
            modal  
             .animate({opacity: 0, top: '45%'}, 200,  
                 function(){ 
                     $(this).css('display', 'none');
                     overlay.fadeOut(400);  
                 }
             );
     });
});
 
$(document).ready(function(){
$('a[href*=#]').bind("click", function(e){
var anchor = $(this);
$('html, body').stop().animate({
scrollTop: $(anchor.attr('href')).offset().top
}, 1000);
e.preventDefault();
});
return false;
}); 
	 		function request(time_a){
			//alert(time_a+' '+$("input[name = ac_chat_id]").val());
				$.ajax({
					url : "../tpl_php/ajax/chat.php",
					method : 'post',
					dataType : 'json',
					data : {
						time_a : time_a,
						chat_id : $("input[name = ac_chat_id]").val(),
						flag : '3'
					},
					timeout : 10000,
					success : function(data){
						console.log('success, reload it');
						//request(data['time'],data['cid']);
					},
					error : function(){
						console.log("no new messages");
						//request(time_a);
					}
				});
			}
			function getMessages(id_chat){
				//alert(id_chat)
				$.ajax({
					url : "../tpl_php/ajax/chat.php",
					method : 'post',
					dataType : 'json',
					data : {
						id_chat : id_chat,
						flag: '2'
					},
					success : function(data){
						var str = data['data'];
						
						$("#ac_field_chat").empty();
						$("#ac_field_chat").append(str);
						$("#ac_field_chat").scrollTop(10000000);
					}
				});
			}
			function send(){
			/*alert($("input[name = ac_from]").val()+' '+$("input[name = ac_to]").val()+' '
				+$("input[name = ac_chat_id]").val()+' '+$("textarea[name = ac_text_chat]").val());*/
			$.ajax({
					url : "../tpl_php/ajax/chat.php",
					method : 'post',
					dataType : 'json',
				data : {
					message : $("textarea[name = ac_text_chat]").val(),
					from : $("input[name = ac_from]").val(),
					to : $("input[name = ac_to]").val(),
					chat_id : $("input[name = ac_chat_id]").val(),
					flag : '1'
				},
				success: function(){
					
				}
				
			});
			if(!$("input[name = com_id_chat").val()) {
				$("textarea[name = ac_text_chat]").val('');
				getMessages($("input[name = ac_chat_id]").val());
				$("textarea[name = ac_text_chat]").focus();
			}
		}
		
		var nd = new Date();
		var d = "";
		d += nd.getFullYear()+'-'+nd.getMonth()+'-'+nd.getDate()+' '+nd.getHours()+':'+nd.getMinutes()+":"+nd.getSeconds();
		var $level_chat = $('#level_chat');
			var $el = $('input[name = ac_chat_id]');
	
				
	