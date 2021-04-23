<!--- Copyright (C) 2020 UNC Interactive Information Systems Lab, School of Information and Library Science --->
<!DOCTYPE html>
<?php
	//session_cache_limiter('public');
	session_start();    
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script type="text/javascript">
  document.addEventListener("dragstart", function(event) {
      event.dataTransfer.setData("dragplus_url", window.location.href);
      event.dataTransfer.setData("dragplus_title", document.title);
      console.log("dragstart event = ", event);
  }).addEventListener("copy", function(event) {
      event.clipboardData.setData("text/plain", window.getSelection().toString());
      event.clipboardData.setData("dragplus_url", window.location.href);
      event.clipboardData.setData("dragplus_title", document.title);
      console.log("copy event = ", event);
      event.preventDefault();
  });
</script>


<link rel="stylesheet" type="text/css" href="orgbox.css">


<head>
	<title>OrgBox Drag and Drop Text</title>
</head>
<h2>Drag and drop text for OrgBox</h2>

<div class="orgboxclear"></div>
<div class="orgboxdiv" item-id="90000" id="orgbox">

<p><b>Text on this page will be copied with the page title and the page url. When drag-and-dropped into the OrgBox tool, 
the title, url, and text will all populate their corresponding blanks. This is made possible through the event listeners that work
on both the copy command and the dragstart command.</b></p>

<br/>
(NOT) Sortable List
<ul id="sortable">
  <li class="orgitem"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
  <li class="orgitem"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
  <li class="orgitem"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
  <li class="orgitem"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
  <li class="orgitem"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
  <li class="orgitem"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li>
</ul>



<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
 dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
 ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
 dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
 ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

</div>
<div class="orgboxclear"></div>