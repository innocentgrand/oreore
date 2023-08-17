<?php
namespace Ore\Controller;

use Ore\Controller\Ctrl;

class DebuggerCtrl extends Ctrl
{
	protected $useView = false;
	
	public function Index()
	{
		echo <<<HTML
<html>
    <head>
        <meta charset="utf-8">
        <title>Debugger</title>
		<script>
document.addEventListener('DOMContentLoaded', function() {
	var storageData = localStorage.getItem('script');
	if (document.getElementById("text").value == "" && storageData != "" ) {
		document.getElementById("text").value = storageData;
	}
	document.getElementById("text").addEventListener('keydown', function(e) {
		var elem, end, start, value;
		if (e.keyCode === 9) {
			if (e.preventDefault) {
				e.preventDefault();
			}
			elem = e.target;
			start = elem.selectionStart;
			end = elem.selectionEnd;
			value = elem.value;
			elem.value = "" + (value.substring(0, start)) + "\t" + (value.substring(end));
			elem.selectionStart = elem.selectionEnd = start + 1;
			return false;
		}
		if (e.shiftKey && e.key == 'Enter') {
			if (e.preventDefault) {
				e.preventDefault();
			}
			localStorage.setItem("script", e.target.value);
			document.dform.submit();
		}
	});

});
		</script>
		<style>
			textarea {
				width: 100%	
			}	
		</style>
	</head>
	<body>
		<form name="dform" method="post" action="/_____debugger/exec/" target="exec" >
			<textarea rows="36" id="text" name="debug" spellcheck=”false” ></textarea>
			<br>
			<input type="submit" name="go" value=" GO ">
		</form>
		<iframe src="/_____debugger/exec/" name="exec" width="100%" height="800px"></iframe>
	</body>
</html>
HTML;
	}

	public function Exec()
	{
		if (!empty($_POST["debug"]))
		{
			eval($_POST["debug"]);
		}	
	}
}
