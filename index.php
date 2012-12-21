<!DOCTYPE html>
<html>
<head>
	<title>EDIT</title>
</head>
<style type='text/css'>
	.container{
		width: 960px;
		margin-right: auto;
		margin-left: auto;
	}
</style>
<body>
	<div class='container'>
		<?php
			if (!empty($_POST)){
				$filename = 'test.php';
				$source = $_POST['source'];
				if (is_writable($filename)) {

					if (!$handle = fopen($filename, 'w')) {
						 echo "Cannot open file ($filename)";
						 exit;
					}

					if (fwrite($handle, $source) === FALSE) {
						echo "Cannot write to file ($filename)";
						exit;
					}

					echo "Success, wrote to file ($filename)";

					fclose($handle);

				} else {
					echo "The file $filename is not writable";
				}
			}
		?>
		<h4 style='color:#666'>Test.php</h4>
		<?php
		include('test.php');
		$source = file_get_contents('test.php');
		?>
		<br/><br/><br/>
		<form method = 'post'>
			<textarea id='editor' name='source' style='width:800px;height:300px'><?php echo $source?></textarea><br/>
			<input type='submit' >
		</form>
	</div>
	<script src='ace.js'></script>
	<script src="ace/lib/ace/theme/twilight.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
				    var editor = ace.edit("editor");
	    editor.setTheme("ace/lib/ace/theme/twilight");
	    editor.getSession().setMode("ace/lib/ace/mode/javascript");


	</script>
</body>
</html>