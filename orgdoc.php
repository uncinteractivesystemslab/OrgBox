<!DOCTYPE html>
<?php
    session_start();

    require_once("../config.php");
    include("./funcs/load_obi.php");
    require_once("../util/utils.php");

    $userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 2;
    $hitid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : 2;
    $taskid = isset($_SESSION['task_id']) ? $_SESSION['task_id'] : 2;
    $data['task_id'] = $taskid;  
    $megahitid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : 2;
    $systemid = isset($_SESSION['system_id']) ? $_SESSION['system_id'] : 3;
?>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Quill -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="nestedstyle.css">

<title>OrgDoc</title>
<h2>OrgDoc
<?php 

   echo "<span style='color:white'>(BLUE) User ID: ".$userid;
   echo " | ";
   echo "Task ID: ".$hitid;
   echo "<br></span>";
?> 
</h2>

<!-- Create the editor container -->
<div style="position:relative;">
  <div id="editorcontainer" style="height:60em; min-height:100%; 
          margin-top:3em">
   <div id="editor" ></div>
</div>
</div>
<style>
.ql-container.ql-snow {
    height: auto;
}
.ql-editor {
    min-height: 700px;
    height: 60%;
     max-height: 700px; 
    overflow-y: scroll;
}
</style>
<script type="text/javascript">

function isEmpty(str) {
    return (!str || 0 === str.length);
}

var Delta = Quill.import('delta');


var toolbarOptions = [
  ['bold', 'italic', 'underline'],        // toggled buttons
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'align': [] }],
  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  ['clean']                                         // remove formatting button
];

  var quill = new Quill('#editor', {
      theme: 'snow',
      modules: {
         toolbar: toolbarOptions,
         clipboard: {
            matchVisual: false
        }
      }
  });


/* Add Tooltip to Quill functions */
$('button.ql-bold').attr('title', 'Bold');
$('button.ql-italic').attr('title', 'Italic');
$('button.ql-underline').attr('title', 'Underline');
$('button.ql-list').attr('title', 'List');
$('button.ql-indent').attr('title', 'Indent');
$('button.ql-align').attr('title', 'Align');
$('button.ql-clean').attr('title', 'Clear Formatting'); 


var change = new Delta();
quill.on('text-change', function(delta) {
  change = change.compose(delta);
});

// Save periodically
setInterval(function() {
  if (change.length() > 0) {
      console.log('Saving changes', change);
      var d = new Date();
      var event_info = {};
      event_info['save_reason'] = "Save Doc";
      event_info['save_state'] = JSON.stringify(quill.getContents());
      event_info['save_html'] = $(".ql-editor").html();
      $.post('./funcs/save_doc.php', event_info, function(data) {
         recordobaction(4, "orgdoc", "doc saved",  null,  null, null, null);
      });
      
      change = new Delta();
  }
}, 5*1000);


   function recordobevent(elid, classtype, eventtype)
   {
      recordobaction(elid, classtype, eventtype, null, null, null, null);
   }

   function recordobaction(elid, classtype, eventtype, attribute, oldv, newv, ssid)
   {
      var d = new Date();
      var event_info = {};
      event_info['page_type'] = 'obscaffolding';
      event_info['on_url'] = location.href;
      event_info['client_time'] = d.getTime();
      event_info['event_type'] = eventtype;
      event_info['on_pagetype'] = 'obscaffolding';
      event_info['on_element_type'] = classtype;
      event_info['on_element_id'] = elid;
      if(attribute != null){
         event_info['attribute'] = attribute;
         event_info['old_value'] = oldv;
         event_info['new_value'] = newv;
         event_info['save_state_id'] = parseInt(ssid);
         event_info['system_id'] = <?php echo $_SESSION['system_id']; ?>; 
      }
      event_info['opt_target_url'] = $(this).find("a:first").attr('href');
      $.post("../util/log_event.php", event_info, function(data) {
      });
   }

   function cleanclasstype(classtype)
   {
      if(~classtype.includes("ql-"))
      {
         classtype = classtype.replace("ql-", "");
      }

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
         
      $(document.body).on("mouseenter", '.ql-editor', function(e) {
         $itemid = $(this).attr("sid");
         $itemclass = cleanclasstype($(this).attr("class"));
         recordobevent($itemid, $itemclass, "mouseenter");
      }).on("mouseleave", '.ql-editor', function(e) {
         $itemid = $(this).attr("sid");
         $itemclass = cleanclasstype($(this).attr("class"));
         recordobevent($itemid, $itemclass, "mouseleave");
      }).on("mousedown", '.ql-editor, .ql-list, .ql-clean, .ql-align, .ql-indent, .ql-bold, .ql-italic, .ql-underline', function(e) {
         $itemid = $(this).attr("sid");
         $itemclass = cleanclasstype($(this).attr("class"));
         recordobevent($itemid, $itemclass, "mousedown");
      });
    

      $(document.body).on("drop", "#editor", function(e) {
        e.preventDefault();
        let native;
        if (document.caretRangeFromPoint) {
            native = document.caretRangeFromPoint(e.clientX, e.clientY);
        } else if (document.caretPositionFromPoint) {
            const position = document.caretPositionFromPoint(e.clientX, e.clientY);
            native = document.createRange();
            native.setStart(position.offsetNode, position.offset);
            native.setEnd(position.offsetNode, position.offset);
        } else {
            return;
        }
        const normalized = quill.selection.normalizeNative(native);
        const range = quill.selection.normalizedToRange(normalized);
        console.log("drop");
         e.dataTransfer = e.originalEvent.dataTransfer;
         var drop_text = e.dataTransfer.getData("Text");
         var drop_url = e.dataTransfer.getData("dragplus_url");
         var drop_title = e.dataTransfer.getData("dragplus_title");
         var obiid = 0;
         console.log("drop event");
         console.log(drop_text);
         var post_info = {};
         post_info['obi_text'] = drop_text;
         post_info['obi_url'] = drop_url;
         post_info['obi_title'] = drop_title;

         post_info['get_uid'] = e.dataTransfer.getData("get_uid");
         post_info['get_hid'] = e.dataTransfer.getData("get_hid");
         post_info['page_type'] = e.dataTransfer.getData("page_type");
         post_info['page_user_id'] = e.dataTransfer.getData("page_user_id");
         post_info['page_hit_id'] = e.dataTransfer.getData("page_hit_id");
         post_info['page_task_id'] = e.dataTransfer.getData("page_task_id");
         post_info['page_qid'] = e.dataTransfer.getData("page_qid");
         post_info['page_pg'] = e.dataTransfer.getData("page_pg");
         post_info['page_seq_num'] = e.dataTransfer.getData("page_seq_num");
         post_info['page_creation_time'] = e.dataTransfer.getData("page_creation_time");
         $.post("./funcs/quick_set_vars.php", post_info, function(data) {});            
            

        s = window.getSelection();
        length = s.toString().length;
        var droppos = 0;
        if(quill.getSelection(true)){
            droppos = quill.getSelection(true).index;
        }
        if (window.getSelection) {
            if (window.getSelection().empty) {  // Chrome
                quill.deleteText(droppos, length);
            } else if (window.getSelection().removeAllRanges) {  // Firefox
            }
        } else if (document.selection) { }

         if(isEmpty(drop_title)){ 
            var wheretodrop = range.index - length;
            if(range.index < droppos){
                wheretodrop = range.index;
            }
            quill.insertText(wheretodrop, drop_text);
            quill.setSelection(range);
            recordobaction(4, "docitem", "record",  "Drop Move",  "", drop_text, 0);
         }
         else{
            $.post("./funcs/create_obi2.php", post_info, function(data) {
            quill.insertText(range.index, drop_text.replace(/\n\s*\n/g, '\n'));
            quill.setSelection(range);
                var returnarray = JSON.parse(data);
                obiid = returnarray[1];
            }).done(function(){
                recordobaction(obiid, "docitem", "record",  "Drop Create",  "", drop_text, 0);
            });  
         }
       

      }).on("dragstart", "#editor", function(e) {
      }).on("paste", "#editor", function(e) {
         var drop_text = e.originalEvent.clipboardData.getData("text/plain");
         var drop_url = e.originalEvent.clipboardData.getData("dragplus_url");
         var drop_title = e.originalEvent.clipboardData.getData("dragplus_title");
         var obiid = 0;
         console.log("paste event");
         
         var post_info = {};
         post_info['obi_text'] = drop_text;
         post_info['obi_url'] = drop_url;
         post_info['obi_title'] = drop_title;

         post_info['get_uid'] = e.originalEvent.clipboardData.getData("get_uid");
         post_info['get_hid'] = e.originalEvent.clipboardData.getData("get_hid");
         post_info['page_type'] = e.originalEvent.clipboardData.getData("page_type");
         post_info['page_user_id'] = e.originalEvent.clipboardData.getData("page_user_id");
         post_info['page_hit_id'] = e.originalEvent.clipboardData.getData("page_hit_id");
         post_info['page_task_id'] = e.originalEvent.clipboardData.getData("page_task_id");
         post_info['page_qid'] = e.originalEvent.clipboardData.getData("page_qid");
         post_info['page_pg'] = e.originalEvent.clipboardData.getData("page_pg");
         post_info['page_seq_num'] = e.originalEvent.clipboardData.getData("page_seq_num");
         post_info['page_creation_time'] = e.originalEvent.clipboardData.getData("page_creation_time");
         $.post("./funcs/quick_set_vars.php", post_info, function(data) {});

         if(isEmpty(drop_title)){
            recordobaction(4, "docitem", "record",  "Paste Move",  "", drop_text, 0);         
         }
         else{
            $.post("./funcs/create_obi2.php", post_info, function(data) {
                var returnarray = JSON.parse(data);
                obiid = returnarray[1];
            }).done(function(){
                recordobaction(obiid, "docitem", "record",  "Paste Create",  "", drop_text, 0);
            });   
         }
      });


   });

   
   $(window).load(function() {
            console.log('window load ');
      
      $.post("./funcs/load_doc.php", function(data) {
         var editor = document.getElementsByClassName('ql-editor');
         quill.setContents(JSON.parse(data));
            console.log('load success ', data);
      });
      
   });

   
</script>


