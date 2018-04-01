<div class="profile">
	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
	<table>
		<tr>
			<td><div class='head_profile'>
				<?php if($user->uniGet('level') == 3): ?>
				<h1>Данные Менеджера</h1>
				<?php endif; ?>
				<?php if($user->uniGet('level') == 4): ?>
				<h1>Данные Супер-Админа</h1>
				<?php endif; ?>
				<?php if($_SESSION['data']['level'] != 4): ?>
				<ul>
					<li>Login: <b><?php print($user->uniGet('login')) ?></b></li>
					<li>Фамилия: <b><?php print($user->uniGet('surname')) ?></b></li>
					<li>Имя: <b><?php print($user->uniGet('name')) ?></b></li>
					<li>Отчество: <b><?php print($user->uniGet('patronymic')) ?></b></li>
				</ul><br>
				<ul>
					<li>E-mail: <b><?php print($user->uniGet('email')) ?></b></li>
					<!--<li>Телефон: <b><?php print($user->uniGet('phone')) ?></b></li>-->
				</ul><br>
			</div>
			
			<?php else: ?>
			<ul>
				<input type="hidden" name="id_pr" value="<?=$user->uniGet('id') ?>">
				<li>login: <input type="text" name="login" disabled value="<?=$user->uniGet('login')?>"></li>
				<li>Фамилия: <input type="text" name="surname" value="<?=$user->uniGet('surname')?>"></li>
				<li>Имя: <input type="text" name="name" value="<?=$user->uniGet('name')?>"></li>
				<li>Отчество: <input type="text" name="patronymic" value="<?=$user->uniGet('patronymic')?>"></li>
			</ul><br>
			<ul>
				<li>E-mail: <input type="text" name="email" value="<?=$user->uniGet('email')?>"></li>
			</ul>
			<?php endif; ?>
			<?php
				if($user->uniGet('avatar') != "" && $user->uniGet('avatar') != "Array"){
					print("<img width='300px' height='300px' src='../upload/avatars/".$user->uniGet('avatar')."'>");
				}
				else{
					print("<img width='300px' height='300px' src='../upload/avatars/default.jpg'>");
				}
			?>
		</td>
		</tr>
	</table>
	<?php if($_SESSION['data']['level'] == 4): ?>
	<div class="footer_profile">
			<?php if($_SESSION['data']['level'] == 3 || $_SESSION['data']['level'] == 4 ): ?>
				<input type="submit" name="redact_target" value="Редактировать данные">
			<?php endif; ?>
			<input type="hidden" name="id_target" value="<?=$user->uniGet('id')?>">
			<input type="hidden" name="lock_status_target" value="<?=$user->uniGet('lock_status')?>">
			<?php if($user->uniGet('lock_status') == 0 && $_SESSION['data']['level'] == 4): ?>
				<input type="submit" name="lock_target" value="Заблокировать">
			<?php endif; ?>
			<?php if($user->uniGet('lock_status') == 1 && $_SESSION['data']['level'] == 4): ?>
				<input type="submit" name="unlock_target" value="Разблокировать">
			<?php endif; ?>
		</form>
		<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" onsubmit="return confirm('Вы действительно хотите удалить данного пользователя?');">
			<input type="hidden" name="id_target" value="<?=$user->uniGet('id')?>">
			<input type="submit" name="delete_target" value="Удалить">
		</form>
	</div>
	<?php endif; ?>
</div>