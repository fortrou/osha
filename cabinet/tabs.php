<?php
session_start();
if(!isset($_SESSION['data'])){
header("Location:../index.php");
}
require '../tpl_php/autoload.php';
/*if(!isset($_GET['tab'])){
    header("Location:index.php#tab1");
}*/
//var_dump($_POST);
//var_dump($_FILES['avatar']);
if(isset($_POST['send'])){
	User::update_avatar($_FILES['avatar'],$_SESSION['data']['login']);
	User::redactPD($_POST, $_SESSION['data']['id'], $_SESSION['data']['login']);
	if($_SESSION['data']['level'] == 1)
		header("Location:index.php#tab1");
	else
		header("Location:index.php#tab3");
}

?>
<!DOCTYPE html> 
<head>  		
	<title>Личная информация - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/users.js"></script>
	<style>   
     
        
       
</style>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0">
			<div class="cabinet"> 
			
			
			
			
          




			
						
	<div class="tabbed-area adjacent">
                                
                <div id="tab_1">
                   <?php
								//var_dump($_SESSION);
								require_once("req_mods/pdata.php");
							?>
                </div>
                
                <div id="tab_2">
                    <h1>Управление рассылками</h1>
                </div>
                
                <div id="tab_3">
                  <h1> Управление оплатами</h1>
						<?php
							require_once("req_mods/pay_manager.php");
						?>
                </div>
                
                <div id="tab_4">
                   <h1>Списки пользователей</h1>
						<?php
							require_once("req_mods/people_list.php");
						?>
                </div>
                
                <ul class="tabs_lc group">
                    <li><a href="#tab_1">Личная информация</a></li>
                    <li><a href="#tab_2">Управление рассылками</a></li>
                    <li><a href="#tab_3">Управление оплатами</a></li>
                    <li><a href="#tab_4">Списки пользователей</a></li>
                </ul>
            
            </div>
						 				
						
						
			  <!--			
				<div class="menu1">
						<br id="tab2"/><br id="tab3"/>
						<a href="#tab3">Личная информация</a>
						<a href="#tab2">Управление рассылками</a>
						<a href="#tab4">Управление оплатами</a>
						<div><h1>Управление оплатами</h1>
					  <h6><a href="#">Информация об оплате</a></h6></div>
					  <div><h1>Управление рассылками</h1>
					  	

						<form name="form1" method="post" action="">
						<label><input type="checkbox" name="total" value="checkbox" onClick="checkAll(this.form,this.checked)"> Выделить все</label><br>
						<label><input type="checkbox" name="checkbox[1]" > Перенос онлайн-урока</label><br>
						<label><input type="checkbox" name="checkbox[2]" > Новое сообщение в чате</label><br>
						<label><input type="checkbox" name="checkbox[3]" > Новости</label><br>
						<label><input type="checkbox" name="checkbox[4]" > Оценка за творческое ДЗ</label><br>
						<label><input type="checkbox" name="checkbox[5]" > Оценка в табеле(семестр, год, ГИА, итог)</label><br>
						</form>
</div>
						<div>
							<?php
								//var_dump($_SESSION);
								require_once("req_mods/pdata.php");
							?>
							
							
						</div>
					  
				</div>	-->		
                        
			  
			</div>			
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 