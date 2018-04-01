<div class='createTest'>
    <div id="quest2" class="collapsed" style="width:800px">
        <form method='post' action="<?=$_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data" >
			<?php
				require_once('choose_mark.php');
			?>
			<p> <span class='testText'>Введите вопрос</span></p>
			<textarea type='text' name='quest' class='quest' style='width:960px; min-height: 200px;'><? print( $_SESSION['correct']['quest']); ?></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('quest');
			</script><br>
 
			<p> <span class='testText'>Введите правильный ответ</span></p>
			<input style="width: 900px;" type="text" name="answer" placeholder="Введите ответ"></input>


			<?php
				require_once('solution_definition.php');
			?>
            
        </form>
    </div>
</div>