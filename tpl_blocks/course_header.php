<?php ?>
	<div class="header_course-container">
		<ul>
			<li class="header_course-logo"><img src="/tpl_img/course_logo.png"></li>
			<li class="header_course-menu_ico">
				<a>
					<img style="margin-right:5px;" src="/tpl_img/course_calendar.png" onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']) ?>/schedule/courseDiary.php'">
				</a>
			</li>
			<li class="header_course-menu_ico">
				<a>
					<img src="/tpl_img/course_dz.png" onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']) ?>/homework'"><!--<span class="header_course-notification">7</span>-->
				</a>
			</li>
			<li class="header_course-menu_ico">
					<img src="/tpl_img/course_events.png" onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']) ?>/events'"><!--<span class="header_course-notification">7</span>-->
			</li>
			<li class="header_course-menu_ico">
					<img src="/tpl_img/course_messages.png"  onclick="location.href = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/chats/index.php?id=<? echo $_SESSION['data']['chat_id'];?>'"><!--<span class="header_course-notification">7</span>-->
			</li>
			<li id="header_course-selector"><span>Курс</span><img src="/tpl_img/exclamation-sign.png">
			<div id="header_toggle_menu-show" class="header_toggle_menu">
		<table>
			<tr class="course_menu_active">
				<td>Активный курс</td>
				<td><p class="header_toggle_menu-index">0</p></td>
			</tr>
			<tr class="course_menu_passive">
				<td>Название курс</td>
				<td><p class="header_toggle_menu-index">0</p></td>
			</tr>
			<tr class="course_menu_passive">
				<td>Название курс</td>
				<td><p class="header_toggle_menu-index">0</p></td>
			</tr>
			<tr class="course_menu_passive">
				<td>Название курс</td>
				<td><p class="header_toggle_menu-index">0</p></td>
			</tr>
		</table>
	</div>
			</li>
			<li id="header_course-profile"><span>Профиль</span><img id="header_course-profile-first_pic" src="http://www.freeiconspng.com/uploads/user-icon-png-person-user-profile-icon-20.png"><img id="header_course-profile-second_pic" src="/tpl_img/sort-down.png"></li>
			<li id="language-selector">
			<form method="post" action="/schedule/diary.php">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</li>
		</ul>
	</div>
	<script>
		document.getElementById("header_course-selector").onclick = function(){
console.log(document.getElementsByClassName("header_course-selector")[0]);
}
	</script>
	
<?php ?>
