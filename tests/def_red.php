<div class='tAnswer'>
    <textarea name="descAnswer" cols="20" rows="20" placeholder="Поле ввода" style="width:500px;"><?php
    	print($full_desc);
    ?></textarea>
</div>
<div class='createTest1'><br>
	<input type='submit' name='sbm' class='sbm' value='Редактировать'>
</div>
<div>
	<span><a href="testred.php?tid=<?=$_SESSION['test_red']['testId']?>">Вернуться в меню редактирования теста</a></span>
</div>