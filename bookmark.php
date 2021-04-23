<!DOCTYPE html>
<?php
	session_start();

	require_once("../config.php");

	include("./funcs/load_obi.php");
	require_once("ob_func.php");
   require_once("../util/utils.php");

	require_once("./funcs/create_ob_func.php");
   $userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 5;
   $hitid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : 2;
   $megahitid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : 2;
   $bookmarkid = isset($_SESSION['bookmark_id']) ? $_SESSION['bookmark_id'] : 1;
   $_SESSION['bookmark_id'] = $bookmarkid;

   $sqlOB = "SELECT * FROM orgboxes WHERE ob_active=TRUE AND ob_user_id=$userid AND ob_task_id=$hitid ORDER BY ob_order";
   $resultOB = mysqli_query($db, $sqlOB);
   if($resultOB->num_rows === 0)
   {
      $data = array();
      $data['ob_title'] = "Bookmarks"; 
      $obid = create_ob($data);
      $_SESSION['bookmark_id'] = $obid;
   }

   require_once("./funcs/create_usertask.php");   
   $sqlOB = "SELECT * FROM usertasks WHERE user_id=$userid AND task_id=$hitid";
   $resultOB = mysqli_query($db, $sqlOB);
   if($resultOB->num_rows === 0)
   {
      $data = array();
      $data['user_id'] = $userid; 
      $data['task_id'] = $hitid;  
      $data['system_id'] = $systemid;
      create_ut($data);
   }
    
?>
<head>
<title>Bookmarks</title>
<style>

   .imgex {
      text-align: center;
         float: left;
         margin: 0.5em 1em;
   }


#bookmarklet-button-span {
   background-color: #dddddd;
   border: 2px solid #a1a1a1;
   border-radius: 10px;
   padding: 5px 5px;
   width: auto;
   display: inline-block;
}

#bookmarklet-button-span, a:link {
   color: #606060;
}

 a:link {
     color: rgb(26,13,171);
 }

 a:visited {
     color: #551A8B;
 }

</style>

</head>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="./jquery.mjs.nestedSortable.js"></script>
  

<script type="text/javascript">
function recordobevent(elid, classtype, eventtype)
   {
      recordobaction(elid, classtype, eventtype, null, null, null, null);
   }

   function recordobaction(elid, classtype, eventtype, attribute, oldv, newv, ssid)
   {
      var d = new Date();
      var event_info = {};
      event_info['page_type'] = 'orgbox';
      event_info['on_url'] = location.href;
      event_info['client_time'] = d.getTime();
      event_info['event_type'] = eventtype;
      event_info['on_pagetype'] = 'orgbox';
      event_info['on_element_type'] = classtype;
      event_info['on_element_id'] = elid;
      if(attribute != null){
         event_info['attribute'] = attribute;
         event_info['old_value'] = oldv;
         event_info['new_value'] = newv;
         event_info['save_state_id'] = parseInt(ssid);
         event_info['system_id'] = 2; 
      }
      event_info['opt_target_url'] = $(this).find("a:first").attr('href');
      $.post("../util/log_event.php", event_info, function(data) {
      });  

         
   }
   function cleanclasstype(classtype)
   {
      if(~classtype.indexOf(' '))
      {
         return classtype.substr(0,classtype.indexOf(' '))
      }
      else
      {
         return classtype;
      }
   }
   
   $(document).ready(function()
   {   
        window.name = "bookmarkwin";
        //window.document.write("<p>This window's name is: " + window.name + "</p>"); 
        $(document.body).on("mouseenter", '.orgboxdiv, .orgboxitem', function(e) {
            $itemid = $(this).attr("sid");
            $itemclass = cleanclasstype($(this).attr("class"));
            recordobevent($itemid, $itemclass, "mouseenter");
        }).on("mouseleave", '.orgboxdiv, .orgboxitem', function(e) {
            $itemid = $(this).attr("sid");
            $itemclass = cleanclasstype($(this).attr("class"));
            recordobevent($itemid, $itemclass, "mouseleave");
        }).on("mousedown", '.orgboxdiv, .orgboxitem', function(e) {
            $itemid = $(this).attr("sid");
            $itemclass = cleanclasstype($(this).attr("class"));
            recordobevent($itemid, $itemclass, "mousedown");
        }).on("focus", '.orgboxdiv, .orgboxitem', function(e) {
            $itemid = $(this).attr("sid");
            $itemclass = cleanclasstype($(this).attr("class"));
            recordobevent($itemid, $itemclass, "focus");
        }).on("blur", '.orgboxdiv, .orgboxitem', function(e) {
            $itemid = $(this).attr("sid");
            $itemclass = cleanclasstype($(this).attr("class"));
            recordobevent($itemid, $itemclass, "blur");
        }).on("keydown", '.orgboxdiv, .orgboxitem', function(e) {
            if (e.which == 13) {
                $itemid = $(this).attr("sid");
                $itemclass = cleanclasstype($(this).attr("class"));
                recordobevent($itemid, $itemclass, "enterkey");
            }
        });

        //The following functions handle the extra data being dragged into the page
      document.addEventListener('DOMContentLoaded',function() {

            function drop_handler(ev) {
               ev.preventDefault();
               var drop_text = ev.dataTansfer.getData("Text");
               console.log("drop...");
               console.log("  text = ", drop_text);
            } 
      });
      //Apply the listeners to all the orgboxes
      $(document.body).on("dragover",".orgboxdiv",  function(e) {
         e.preventDefault();
         console.log("dragover");
         return false;
      });

      $(document.body).on("drop", ".orgboxdiv", function(e) {
         e.dataTransfer = e.originalEvent.dataTransfer;
         e.preventDefault();
         var drop_text = e.dataTransfer.getData("Text");
         var drop_url = e.dataTransfer.getData("dragplus_url");
         var drop_title = e.dataTransfer.getData("dragplus_title");
         var obid = $(this).attr("box");
         console.log("drop event");
         console.log("  text " + drop_text);
         console.log("  url " + drop_url);
         console.log("  title " + drop_title);
         console.log("  box " + obid);
         var post_info = {};
         post_info['obi_text'] = drop_text;
         post_info['obi_url'] = drop_url;
         post_info['obi_title'] = drop_title;
         post_info['ob_box_id'] = obid;
         var obiid = 0;
         //post_info['type'] = 2;
         $.post("./funcs/create_bookmark.php", post_info, function(data) {

            var returnarray = JSON.parse(data);
            $("#orgboxitemnew_" + obid).remove();
            $("#orgboxitems" + obid).append(returnarray[0]);
            obiid = returnarray[1];
         }).done(function(){
            $("#orgboxitems" + obid).nestedSortable();
            var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
            var post_info = {};
            post_info['ob_structure'] = JSON.stringify(new_index);
            post_info['ob_id'] = obid;
               $.post("./funcs/order_sb.php", post_info, function(data) {
                  console.log("update order got back " + data);
            }).done(function(){
               post_info['save_reason'] = "Create Bookmark";
               post_info['save_state'] = $("#orgboxdivs").html();
               $.post("./funcs/save_state.php", post_info, function(data) {
                  recordobaction(obiid, "item", "record",  "Title",  "", drop_title, data);
                  recordobaction(obiid, "item", "record",  "URL",  "", drop_url, data);
                  recordobaction(obiid, "item", "record",  "Text",  "", drop_text, data);
                  console.log("state saved!");
               });
            });

         });
         return false;
      });

      $(document.body).on("drop", function(e) {
         e.dataTransfer = e.originalEvent.dataTransfer;
         e.preventDefault();
         return false;
      });

        $( ".orgboxitems" ).nestedSortable(
        {
            connectWith: ".orgboxitems",
            handle: 'div',
            items: '.orgboxitem',
            disabledClass: '.orgboxitemnew, .orgboxitemblank', 
            forcePlaceholderSize: true,        
            toleranceElement : 'div',
            maxLevels: 1,
            disabled: true,
            scroll: true,
            revert: true,
            placeholder: "ui-state-highlight",
            opacity: 0.8,
            start: function(ev, ui){
                ui.placeholder.height(ui.item.height());
            },
            update: function(ev,ui){
                var bet = $(this).closest(".orgboxdiv").attr('box');
                var new_index = $(this).nestedSortable('serialize');
                var box_id = $(this).attr("box");
                var elid = $(this).parent().parent().attr("sid");
                var post_info = {};
                post_info['ob_structure'] = JSON.stringify(new_index);
                post_info['ob_id'] = box_id;
                post_info['save_reason'] = "Ordering Items";
                post_info['save_state'] = $("#orgboxdivs").html();
                $.post("./funcs/order_sb.php", post_info, function(data) {
                    //recordobaction(elid, "box", "record",  "item order",  oldindex.toString(), new_index.toString(), data);
                    console.log("item order saved " + bet);
                });
            }
        }).disableSelection();
   });

  

   function editobi(obiid)
   {
      console.log(obiid);

      var post_info = {};
      post_info['obi_id'] = obiid;
      post_info['obi_title'] = $("#obi_url_" + obiid).text();
      post_info['obi_url'] = $("#obi_url_" + obiid).attr('href');
      
      $.post("./forms/edit_bookmark_form.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
         console.log("main state!");
      })
      
      console.log("#orgboxitems" + obiid);
   }

   function removeobi(obiid)
   {
      var post_info = {};
      post_info['obi_id'] = obiid;
      post_info['ob_id'] = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');
      var subitemids = $("#obi_"+ obiid + " .orgboxitem").map(function(){
         return $(this).attr('id');
      }).get();
      post_info['obi_subitems'] = subitemids.toString();
      $.post("./forms/remove_bookmark_form.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      });
   }

   
   

   
</script>


<link rel="stylesheet" type="text/css" href="nestedstyle.css">


<h2>Bookmarks
<?php 

   echo "<span style='color:white'>(RED) User ID: ".$userid;
   echo " | ";
   echo "Task ID: ".$hitid;
   echo "<br></span>";
?>
</h2>

<div class="orgboxclear"></div>
<section class="orgboxdivs" id="orgboxdivs">
<?php 
   $sqlOB = "SELECT * FROM orgboxes WHERE ob_active=TRUE AND ob_user_id=$userid AND ob_task_id=$hitid ORDER BY ob_order";
   $resultOB = mysqli_query($db, $sqlOB);
   foreach($resultOB as $itemOB): 
      $obtitle = $itemOB['ob_title'];
      $obid = $itemOB['ob_id'];
      $obstruct = $itemOB['ob_structure']; 
?>
   <div class="orgboxdiv" box=<?php echo "'$obid'"?>  sid=<?php echo "'$obid'"?> id=<?php echo "ob_".$obid?>>
      <div id=<?php echo "ob_div_$obid"?>>
        <span id=<?php echo "ob_title_$obid"?>  class="obtitlespan"><?php echo $obtitle ?> </span>
      </div>
        <hr/>

        <?php         
            $pairs = explode("&",$obstruct);
            $rebuildArray = array();
            foreach ($pairs as $pair)
            {
               $exploded_pair = explode("=", $pair);
               preg_match('#\[(.*?)\]#', $exploded_pair[0], $match);
               $key = $match[1];
               $value = $exploded_pair[1];
               if(!is_null($key))
               {
                  $rebuildArray[$key] = $value;
               }
            }
            
            $levelOneID = 0;
            $levelTwoID = 0;
            $currentLevel = 1;
            $previousLevel = 1;
            echo "<ol class='orgboxitems' id='orgboxitems".$obid."' box='".$obid."'>";
            foreach($rebuildArray as $key => $value){
               //if there is no value, it's a level 1 item. reset vars
               if($value == "null"){
                  $levelOneID = $key;
                  $levelTwoID = 0;
                  $currentLevel = 1;
               }
               else if($value == $levelOneID){
                  $levelTwoID = $key;
                  $currentLevel = 2;                  
               }
               else if($value == $levelTwoID){
                  $currentLevel = 3;                  
               }
               else{
                  echo "miss";
               }
               
               startol($key, $currentLevel, $previousLevel);
               printbookmark($key);
               //after loop, set the level into the previous level
               $previousLevel = $currentLevel;
            }
            endol($key, 0, $previousLevel);
        ?>
   </div>
<?php 
   endforeach;
?> 
</section>


