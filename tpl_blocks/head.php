<!--<?php
	var_dump($_SERVER);
?>-->
	<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">
<!--The 2010 IANA standard but not supported in IE-->
<link rel="stylesheet" type="text/css" media="all" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_css/style.css" />
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_css/lightbox.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> 
<script type="text/javascript" src="../editors/include/spoiler.js"></script>
	<script type="text/javascript">
		function close_alertation(type) {
			if(!$("#" + type).hasClass("no-display"))
				$("#" + type).toggleClass("no-display")
		}
		function alertion_window(type, text, time, flag) {
			if(flag == 'close') {
				if(!$("#" + type).hasClass("no-display"))
					$("#" + type).toggleClass("no-display")
			}
			if(flag == 'open') {
				if($("#" + type).hasClass("no-display")) {
					$("#" + type).toggleClass("no-display")
					$("#" + type + " .alert-text").empty().append(text)
				}
				if(time != 'infinite') {
					setTimeout(close_alertation, (time * 1000), type);
				}
			}
		} 
		
	</script>
	<script type="text/javascript">
		var chat_1;
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
				if(chat_1 && chat_1.readyState != 4) chat_1.abort();
				chat_1 = $.ajax({
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

		function captcha_trigger(form_id) {
			$("#captcha-form input[name = form-id]").val(form_id);
			$("#captcha-form").toggleClass("no-display");
		}
 	</script> 
 	<script type="text/javascript">
		function get_urlParam(name) {
			if(name == "" || name == null || name == undefined) return false;
			var params = window
			.location
			.search
			.replace('?','')
			.split('&')
			.reduce(
				function(p,e){
					var a = e.split('=');
					p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
					return p;
				},
				{}
			);
			return params[name];
		}
 		function set_cookie ( name, value,  exp_s, path, domain, secure ) {
			var cookie_string = name + "=" + escape ( value );
			if ( exp_s ) {
				var date = new Date;
				console.log(date)
				date.setUTCSeconds(date.getUTCSeconds() + exp_s);
				console.log(date)
		    	cookie_string += "; expires=" + date.toUTCString();
			}
			if ( path )
				cookie_string += "; path=" + escape ( path );
			if ( domain )
				cookie_string += "; domain=" + escape ( domain );
			if ( secure )
				cookie_string += "; secure";
			document.cookie = cookie_string;
		}
		function delete_cookie ( cookie_name ) {
			var cookie_date = new Date ( );  // Текущая дата и время
			cookie_date.setTime ( cookie_date.getTime() - 1 );
			document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
		}
		function get_cookie ( cookie_name ) {
			var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
			if ( results )
				return ( unescape ( results[2] ) );
			else
				return null;
		}
		function check_test() {
			var test_id = get_urlParam('id');
			console.log('completed')
			var current_url = window.location.href;
			var test_cookie = get_cookie('test_completing');
			if(test_cookie == test_id || (test_cookie == null && test_id != null)) {
				if(current_url.indexOf('tests/completing.php') + 1) {
					delete_cookie('test_completing');
					set_cookie("test_completing", test_id, 15)
				}
			}
		}
		check_test();
		setInterval(check_test, 14000);
 	</script>
 	<script type="text/javascript">
 		function close_once(type, id_user){
 			
 			$(".frame").css("display","none");
 			if($("input[name = no_more]").is(":checked")){
 				$.ajax({
					url : "../tpl_php/ajax/notifs.php",
					method : 'post',
					dataType : 'json',
				data : {
					type : type,
					id_user : id_user,
					flag : '67'
				}
				
			});
 			}
 		}
 		function close_forever(type, id_user){
 			$.ajax({
					url : "../tpl_php/ajax/notifs.php",
					method : 'post',
					dataType : 'json',
				data : {
					type : type,
					id_user : id_user,
					flag : '67'
				}
				
			});
			close_once();
 		}
 	</script>
	<script type="text/javascript">
    $(document).ready(function(){
        
        var $menu = $("#menu");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });//scroll
        
        $menu.hover(
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).removeClass('transbg');
                }
            },
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).addClass('transbg');
                }
            });//hover
    });//jQuery
</script> 
<script language="javascript">
	var D = document;
	function expMenu(id) {
	  var itm = null;
	  if (D.getElementById) {
		itm = D.getElementById(id);
	  } else if (D.all){
		itm = D.all[id];
	  } else if (D.layers){
		itm = D.layers[id];
	  }
	  if (!itm) {
		// do nothing
	  }
	  else if (itm.style) {
		if (itm.style.display == "none") { itm.style.display = ""; }
		else { itm.style.display = "none"; }
	  }
	  else { itm.visibility = "show"; }
	}
	function ShowHide(id1, id2) {
	  if (id1 != "") expMenu(id1);
	  if (id2 != "") expMenu(id2);
	} 
</script>
<script type="text/javascript">
	function checkAll(oForm,checked)
	{
		oForm['checkbox[1]'].checked = checked;
		oForm['checkbox[2]'].checked = checked;
		oForm['checkbox[3]'].checked = checked;
		oForm['checkbox[4]'].checked = checked;
		oForm['checkbox[5]'].checked = checked;
	}
	/*function add_del(id,user){
		$.ajax({
			url : '../tpl_php/ajax/notifs.php' ,
			method : 'POST' , 
			dataType : 'json' ,
			data : { id : id,
				user : user,
			flag : '8' } ,
			success : function ( data ) {
				
			}
		})
	}*/
</script> 
<script type="text/javascript">
			function onoff(t){
	p=document.getElementById(t);
	if(p.style.display=="table"){
		p.style.display="none";}
	else{
		p.style.display="table";}
}
</script>
<script type="text/javascript">
	function onoff2(t){
	p=document.getElementById(t);
	if(p.style.display=="block"){
		p.style.display="none";}
	else{
		p.style.display="block";}
}
</script>
 
