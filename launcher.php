<!--- Copyright (C) 2020 UNC Interactive Information Systems Lab, School of Information and Library Science --->
<?php
	session_start();
?>
<html>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
<script LANGUAGE="JavaScript">
	function launchorgbox() {
      var userid = $("#d_user").val();
      var hitid = $("#d_hit").val();
      var systemid = $("#d_system option:selected").val();;
      
		f = document.createElement('form');
		f.target = 'zzz';
		f.method = 'POST';
		f.style.visibility = 'hidden';
      f.action = './systemdirect.php';
      
		mi = document.createElement('input');
		mi.type = 'text';
		mi.name = 'system_id';
		mi.value = systemid;
      f.appendChild(mi);
      
		mi = document.createElement('input');
		mi.type = 'text';
		mi.name = 'user_id';
		mi.value = userid;
      f.appendChild(mi);
      
		mi = document.createElement('input');
		mi.type = 'text';
		mi.name = 'hit_id';
		mi.value = hitid;
		f.appendChild(mi);
      document.body.appendChild(f);
		window
				.open(
						'',
						'zzz',
                  'scrollbars=yes,menubar=no,height=1000,width=900,top=0,left=0,resizable=yes,toolbar=no,status=no');
		f.submit();
	}
</script>

<head>
	<title>OrgBox Launcher</title>
</head>

<body>
<h1>OrgBox Launcher</h1>

<label class='textlabel'>User: </label>
<input class='input textentry' id='d_user' name='user' type='text'><br/>
<label class='textlabel'>Task: </label>
<input class='input textentry' id='d_hit' name='hit' type='text'><br/>
<label class='textlabel'>System: </label>
<select id='d_system'>
	<option value="0">OrgBox Base</option>  
</select>
<br/>
<button id='update_button' onclick='launchorgbox()' class='savebutton'>Launch OrgBox in New Window</button>
<div style='clear: both;'></div>

<br/>
<a href='./obdragtext.php'  target='_blank'>Open OrgBox Drag and Drop Text Page</a>

</body>
</html>