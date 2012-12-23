<?php
header('X-XSS-Protection: 0');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
  	<script src="jquery-1.8.3.min.js"></script>
	<title>EDIT</title>
</head>
<style type='text/css'>

	#editor{
		width:800px;height:300px
	}
	.textarea-hidden {
		display: none;
	}
	.container{
		width: 800px;
		margin-right: auto;
		margin-left: auto;
	}

	.ace_editor {
		position: relative !important;
		border: 1px solid lightgray;
		margin: auto;
		height: 200px;
		width: 80%;
	}


	input[type=submit] {
		padding: 5px 10px;
	}

	.ace_editor.fullScreen {
		width: auto!important;
		height: auto !important;
		border: 0;
        margin: 0;
        position: fixed !important;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 10;
        background: white;
    }

	.fullScreen {
		overflow: hidden
	}

	.scrollmargin {
		height: 500px;
        text-align: center;
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
		<form method='post'>
			<pre id='editor' ><?php echo htmlspecialchars($source)?></pre><br/>
			<textarea name='source' class='textarea-hidden' ></textarea>
			<input type='submit'  id='submit-form'>
		</form>
	</div>

	<script src='ace/build/src/ace.js'></script>
	<script type="text/javascript">
		var $ = document.getElementById.bind(document);
		var dom = require("ace/lib/dom");

		var commands = require("ace/commands/default_commands").commands;
		commands.push({
			name: "Toggle Fullscreen",
			bindKey: "F11",
			exec: function(editor) {
				dom.toggleCssClass(document.body, "fullScreen")
				dom.toggleCssClass(editor.container, "fullScreen")
				editor.resize()
			}
		});

		commands.push({
			name: "Submit",
			bindKey: "F9",
			exec: function(editor) {
				jQuery('#submit-form').trigger('click');
			}
		})

		var editor = ace.edit("editor");
		editor.setTheme("ace/theme/twilight");
		editor.session.setMode("ace/mode/php");

		jQuery('#submit-form').click(function() {
			jQuery("textarea[name='source']").text(editor.getValue());
			jQuery(this).closest('form').submit();
		})
	</script>

</body>
</html>
