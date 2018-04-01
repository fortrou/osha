<?php
	session_start();
    require_once("../tpl_php/autoload.php");
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) {
		header("Location: ../index.php");
	}
	$options = new Options();
	if(isset($_POST['save_changes'])) {
		$options->redact_option($_POST['option_name'], $_POST['option_value']);
		header("Location: options.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>options</title>
<?php require_once("../tpl_blocks/head.php"); ?>
</head>
<body>
	<?php require_once("../tpl_blocks/header.php"); ?>
	<div class="content">
		<div class="row">
			<h1>Опции сайта</h1>
			<div class="option-list">
				<ul>
					<?php
						$option_list = $options->get_optionList(1);
						print($option_list);
					?>
				</ul>
			</div>
		</div>
	</div>
	<?php require_once('../tpl_blocks/footer.php'); ?>
</body>
</html>