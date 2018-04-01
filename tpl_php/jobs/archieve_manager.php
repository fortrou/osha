<?php
	set_time_limit(7200);
	session_start();
	if($_SESSION['data']['level'] != 4) header("Location: http://online-shkola.com.ua");
	$base_url = "http://online-shkola.com.ua/";
	$old_file_base_path = "../../upload/hworks/";
	$new_file_base_path = "../../temp_catalog/";
	require_once("../autoload_light.php");
	require_once("../functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Zip all HW for admin</title>
	<?php require_once("../../tpl_blocks/head.php"); ?>
</head>
<body>
<?php require_once("../../tpl_blocks/header.php"); ?>
	(div.user-filters>(select[name='class']+select[name=]))+(div.user-list)
<?php require_once("../../tpl_blocks/footer.php"); ?>
</body>
</html>
