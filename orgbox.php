<!DOCTYPE html>
<?php
	session_start(); 

	require_once("../config.php");

	include("./funcs/load_obi.php");
	require_once("ob_func.php");
   require_once("../util/utils.php");
   $userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 2;
   $hitid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : 2;
   $taskid = isset($_SESSION['task_id']) ? $_SESSION['task_id'] : 2;
   $megahitid = isset($_SESSION['hit_id']) ? $_SESSION['hit_id'] : 2;
   $systemid = isset($_SESSION['system_id']) ? $_SESSION['system_id'] : 1;

   
?>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="./jquery.mjs.nestedSortable.js"></script>
      <script src="https://unpkg.com/packery@2/dist/packery.pkgd.js"></script>
  

<script type="text/javascript">
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
      if(~classtype.indexOf(' '))
      {
         return classtype.substr(0,classtype.indexOf(' '))
      }
      else
      {
         return classtype;
      }
   }
   var grid;
   $(document).ready(function()
   {  
      $('.orgboxdivs').packery({
        itemSelector: '.orgboxdiv', 
        columnWidth: 10 
      });

      function orderBoxes() {
         var itemElems =  $('.orgboxdivs').packery('getItemElements');
         var obAndOrder = [];
         $( itemElems ).each( function( i, itemElem ) {
            var boxId = $( itemElem ).attr("box");
            var newOrder = i + 1;
            obAndOrder.push(boxId + '_' + newOrder)
            $( itemElem ).attr("oborder", i + 1);
         });
         var post_info = {};
         post_info['obs'] = obAndOrder;
         $.post("./funcs/order_ob_packery.php", post_info, function(data) {}); 
      }  

      $('.orgboxdivs').on( 'layoutComplete', orderBoxes );
      $('.orgboxdivs').on( 'dragItemPositioned', orderBoxes );   

        $( ".orgboxdiv" ).resizable({
         minWidth: "400px",
         minHeight: 60,
         start: function(event,ui) {
            var content = $(this).children(".orgboxdivcontent");
            $(this).css({
                  minHeight: content.outerHeight()+ 30,
                  minWidth: "400px"
            });
         },  
         stop: function(event,ui) {            
            var content = $(this).children(".orgboxdivcontent");
            $(this).css({
                  minHeight: content.outerHeight() + 40,
                  minWidth: content.outerWidth()
            });         
            var post_info = {};
            post_info['obid'] = $( this ).attr("box");
            post_info['obwidth'] = $( this ).css("minWidth");
            post_info['obheight'] = $( this ).css("minHeight");
            post_info['save_reason'] = "Resize Box";
            post_info['save_width'] =  $(window).width();
            post_info['save_height'] =  $(window).height();
            post_info['save_state'] = $("#orgboxdivs").html();
            $.post("./funcs/resize_ob.php", post_info, function(data) {   
               recordobaction(post_info['obid'], "box", "record",  "Resize",  "", "", data); 
            }).done(function(){
               $('.orgboxdivs').packery('layout');
            }); 
            
         } 
      });

      
      var boxes =  $('.orgboxdivs').find( ".orgboxdiv" ).draggable({
         stop: function(e, ui){
            var post_info = {};
            post_info['obid'] = $( this ).attr("box");
            post_info['save_reason'] = "Drag Box";
            post_info['save_width'] =  $(window).width();
            post_info['save_height'] =  $(window).height();
            post_info['save_state'] = $("#orgboxdivs").html();
            $.post("./funcs/save_state.php", post_info, function(data) {
                  recordobaction(post_info['obid'], "box", "record",  "Drag",  "", "", data);
            });
         }
      });
      $('.orgboxdivs').packery( 'bindUIDraggableEvents', boxes );

      var old_s_index, old_r_index;
      $( ".orgboxitems" ).nestedSortable(
      {
         connectWith: ".orgboxitems",
         handle: 'div',
         items: '.orgboxitem',
         disabledClass: '.orgboxitemnew, .orgboxitemblank', 
         forcePlaceholderSize: true, 
         tolerance: 'pointer',       
         toleranceElement : '> div',
		   maxLevels: 3,
         scroll: true,
         revert: true,
         placeholder: "ui-state-highlight",
         opacity: 0.8,
         start: function(event, ui){
            ui.placeholder.height(ui.item.height());
            old_s_index = $(this).nestedSortable('serialize');
         },
         over: function(event,ui){
            old_r_index = $(this).nestedSortable('serialize');
         },
         update: function(event,ui){
            var obid = $(this).closest(".orgboxdiv").attr('box');
            
            resizeob(obid, true);
            var new_index = $(this).nestedSortable('serialize');
            var post_info = {};
				post_info['ob_structure'] = JSON.stringify(new_index);
				post_info['ob_id'] = obid;
            post_info['save_reason'] = "Ordering Items";
            post_info['save_width'] =  $(window).width();
            post_info['save_height'] =  $(window).height();
            post_info['save_state'] = $("#orgboxdivs").html();
            $.post("./funcs/order_sb.php", post_info, function(data) {
               if(ui.sender == null){
                  recordobaction(obid, "box", "record",  "Item Order",  JSON.stringify(old_s_index), JSON.stringify(new_index), data);
               }
               else{
                  recordobaction(obid, "box", "record",  "Item Order",  JSON.stringify(old_r_index), JSON.stringify(new_index), data);
               }
            });
            $(".orgboxdivs").packery('layout');
            
         }
      }).disableSelection();

      
   
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
         } 
      });
      //Apply the listeners to all the orgboxes
      $(document.body).on("dragover",".orgboxdiv",  function(e) {
         e.preventDefault();
         return false;
      });

      $(document.body).on("drop", ".orgboxdiv", function(e) {
         e.dataTransfer = e.originalEvent.dataTransfer;
         e.preventDefault();
         var drop_text = e.dataTransfer.getData("Text");
         var drop_url = e.dataTransfer.getData("dragplus_url");
         var drop_title = e.dataTransfer.getData("dragplus_title");
         var obid = $(this).attr("box");
         var post_info = {};
         post_info['obi_text'] = drop_text;
         post_info['obi_url'] = drop_url;
         post_info['obi_title'] = drop_title;
         post_info['ob_box_id'] = obid;
         post_info['type'] = 2;

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

         $.post("./funcs/create_obi2.php", post_info, function(data) {
            var returnarray = JSON.parse(data);
            $("#orgboxitems" + obid).append(returnarray[0]);
            obiid = returnarray[1];

            $("#orgboxitems" + obid).nestedSortable();
            var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
            var post_info = {};
            post_info['ob_structure'] = JSON.stringify(new_index);
            post_info['ob_id'] = obid;
            post_info['save_reason'] = "Create Item";
            post_info['save_width'] =  $(window).width();
            post_info['save_height'] =  $(window).height();
            post_info['save_state'] = $("#orgboxdivs").html();
            $.post("./funcs/order_sb.php", post_info, function(data) {
               recordobaction(obiid, "item", "record",  "Drop Create",  "", "", data);
               recordobaction(obiid, "item", "record",  "Active",  0, 1, data);
               recordobaction(obiid, "item", "record",  "Title",  "", drop_title, data);
               recordobaction(obiid, "item", "record",  "URL",  "", drop_url, data);
               recordobaction(obiid, "item", "record",  "Text",  "", drop_text, data);
            });
         }).done(function() {
            resizeob(obid, true);
         }).done(function() {
            $("#orgboxdivs").packery('layout');
         });
         return false;
      });

      $(document.body).on("drop", function(e) {
         e.dataTransfer = e.originalEvent.dataTransfer;
         e.preventDefault();
         return false;
      });


      $(document.body).on("dragover", ".orgboxdivs", function(e) {
         e.preventDefault();
         return false;
      });

      $(document.body).on("drop", ".orgboxdivs", function(e) {
         e.dataTransfer = e.originalEvent.dataTransfer;
         e.preventDefault();
         var drop_text = e.dataTransfer.getData("Text");
         var drop_url = e.dataTransfer.getData("dragplus_url");
         var drop_title = e.dataTransfer.getData("dragplus_title");
         var post_info = {};
         post_info['obi_text'] = drop_text;
         post_info['obi_url'] = drop_url;
         post_info['obi_title'] = drop_title;
         post_info['ob_title'] = "NEW BOX";
         post_info['type'] = 2;

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

         var obid, returnarray;

         $.post("./funcs/create_ob_packery.php", post_info, function(data) {
            var $item2 = $("<div class='orgboxdiv' id='brandnewbox'><br/></div>");
            $("#orgboxdivs").packery().append($item2).packery( 'appended', $item2 ).packery('layout');
            
            returnarray = JSON.parse(data);
            obid = returnarray[1];
         }).done(function() {
            $("#brandnewbox").prop('id', 'ob_'+obid);
            
            $("#ob_" + obid).attr('box', obid);
            $("#ob_" + obid).attr('sid', obid);
            $("#ob_" + obid).html(returnarray[0]);
            
            $("#orgboxdivs").packery().packery('reloadItems').packery('layout');
            
            $( "#ob_" + obid ).resizable({
               minWidth: "400px",
               minHeight: 60,
               start: function(event,ui) {
                  var content = $(this).children(".orgboxdivcontent");
                  $(this).css({
                     minHeight: content.outerHeight()+ 40,
                     minWidth: "400px"
                  });
               },  
               stop: function(event,ui) {            
                  var content = $(this).children(".orgboxdivcontent");
                  $(this).css({
                        minHeight: content.outerHeight() + 40,
                        minWidth: content.outerWidth()
                  });         
                  var post_info = {};
                  post_info['obid'] = $( this ).attr("box");
                  post_info['obwidth'] = $( this ).css("minWidth");
                  post_info['obheight'] = $( this ).css("minHeight");
                  post_info['save_reason'] = "Resize Box";
                  post_info['save_width'] =  $(window).width();
                  post_info['save_height'] =  $(window).height();
                  post_info['save_state'] = $("#orgboxdivs").html();
                  post_info['save'] = true;
                  $.post("./funcs/resize_ob.php", post_info, function(data) {  
                     recordobaction(post_info['obid'], "box", "record",  "Resize",  "", "", data);  
                  }).done(function(){
                     $('.orgboxdivs').packery('layout');
                  }); 
                  
               } 
            });

            var old_s_index, old_r_index;
            $( ".orgboxitems" ).nestedSortable(
            {
               connectWith: ".orgboxitems",
               handle: 'div',
               items: '.orgboxitem',
               disabledClass: '.orgboxitemnew, .orgboxitemblank', 
               forcePlaceholderSize: true, 
               tolerance: 'pointer',       
               toleranceElement : '> div',
               maxLevels: 3,
               scroll: true,
               revert: true,
               placeholder: "ui-state-highlight",
               opacity: 0.8,
               start: function(event, ui){
                  ui.placeholder.height(ui.item.height());
                  old_s_index = $(this).nestedSortable('serialize');
               },
               over: function(event,ui){
                  old_r_index = $(this).nestedSortable('serialize');
               },
               update: function(event,ui){

                  var obid = $(this).closest(".orgboxdiv").attr('box');
                  resizeob(obid, true);
                  var new_index = $(this).nestedSortable('serialize');
                  post_info['ob_structure'] = JSON.stringify(new_index);
                  post_info['ob_id'] = obid;
                  post_info['save_reason'] = "Ordering Items";
                  post_info['save_width'] =  $(window).width();
                  post_info['save_height'] =  $(window).height();
                  post_info['save_state'] = $("#orgboxdivs").html();
                  $.post("./funcs/order_sb.php", post_info, function(data) {
                     if(ui.sender == null){
                        recordobaction(obid, "box", "record",  "Item Order",  JSON.stringify(old_s_index), JSON.stringify(new_index), data);
                     }
                     else{
                        recordobaction(obid, "box", "record",  "Item Order",  JSON.stringify(old_r_index), JSON.stringify(new_index), data);
                     }
                  });
                  $("#orgboxdivs").packery().packery('layout');
                  
               }
            }).disableSelection();


            var dragItem = $( "#ob_" + obid ).draggable({
               stop: function(e, ui){
                  var post_info = {};
                  post_info['obid'] = $( this ).attr("box");
                  post_info['save_reason'] = "Drag Box";
                  post_info['save_width'] =  $(window).width();
                  post_info['save_height'] =  $(window).height();
                  post_info['save_state'] = $("#orgboxdivs").html();
                  $.post("./funcs/save_state.php", post_info, function(data) {
                        recordobaction(post_info['obid'], "box", "record",  "Drag",  "", "", data);
                  });
               }
            });
            $("#orgboxdivs").packery( 'bindUIDraggableEvents', dragItem );

            
         }).done(function() {
            post_info['ob_box_id'] = obid;
             $.post("./funcs/create_obi2.php", post_info, function(data) {
               var returnarray = JSON.parse(data);
               $("#orgboxitemnew_" + obid).remove();
               $("#orgboxitems" + obid).append(returnarray[0]);
               obiid = returnarray[1];
               
               $("#orgboxdivs").packery('layout');
            }).done(function(){
               $("#orgboxitems" + obid).nestedSortable();
               var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
               var post_info = {};
               post_info['ob_structure'] = JSON.stringify(new_index);
               post_info['ob_id'] = obid;
               $.post("./funcs/order_sb.php", post_info, function(data) {
                  
               });
            }).done(function(){
                  post_info['save_reason'] = "Create Box and Item";
                  post_info['save_width'] =  $(window).width();
                  post_info['save_height'] =  $(window).height();
                  post_info['save_state'] = $("#orgboxdivs").html();
                   $.post("./funcs/save_state.php", post_info, function(data) {
                     recordobaction(obid, "box", "record",  "Drop Create",  "", "", data);
                     recordobaction(obid, "box", "record",  "Active",  0, 1, data);
                     recordobaction(obid, "box", "record",  "Title",  "", "NEW BOX", data);
                     recordobaction(obiid, "item", "record",  "Drop Create",  "", "", data);
                     recordobaction(obiid, "item", "record",  "Active",  0, 1, data);
                     recordobaction(obiid, "item", "record",  "Title",  "", drop_title, data);
                     recordobaction(obiid, "item", "record",  "URL",  "", drop_url, data);
                     recordobaction(obiid, "item", "record",  "Text",  "", drop_text, data);
                  });                 
               }); 
         });
         return false;
      });
     


      $(window).load(function() {
         var res_info = {};
         res_info['window_width'] =  $(window).width();
         res_info['window_height'] = $(window).height();
         res_info['monitor_width'] = window.screen.availWidth;
         res_info['monitor_height'] = window.screen.availHeight;
         $.post("./funcs/set_res.php", res_info, function(data) {
         });


         var subitemids = $(".orgboxdiv").map(function(){
            return $(this).attr('box');
         }).get();
         $.each( subitemids, function( index, value ){
            var post_info = {};
            post_info['ob_key'] = value;
            $.post("./funcs/load_ob_size.php", post_info, function(data) {         
               var returnarray = JSON.parse(data);
               var widget = $('.orgboxdiv[box=' + value + ']').resizable( "widget" );
               var content = widget.children(".orgboxdivcontent");
               widget.css({
                     minHeight: returnarray[1],
                     minWidth: returnarray[0]
               });
               $("#orgboxdivs").packery().packery('layout');
            });
         })
         
      });

   });

   function resizeob(obid, shrink){
      var ob = $('.orgboxdiv[box=' + obid + ']');
      var widget =ob.resizable( "widget" );
      var content = widget.children(".orgboxdivcontent");
      
      widget.css({
            height: content.outerHeight() + 40,
            minWidth: content.outerWidth()
      });
      var post_info = {};
      post_info['obid'] = ob.attr("box");
      post_info['obwidth'] = ob.css("minWidth");
      post_info['obheight'] = ob.css("minHeight");
      $.post("./funcs/resize_ob.php", post_info, function(data) {  
      }); 
   }

   function createobi(obid)
   {

      var post_info = {};
      post_info['ob_box_id'] = obid;
      $.post("./forms/create_obi_packery.php", post_info, function(data) {
         $("#orgboxitems" + obid).append(data);         
      }).done(function() {
         resizeob(obid, false);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
      });
   }

   function createobicancel(obid)
   {
      $("#orgboxitemnew_" + obid).remove();
      resizeob(obid, false);
      $("#orgboxdivs").packery('layout');
   }

   function createobicheck(obid)
   {
            createobiconfirm(obid, 0, '');
   }

   function createobiconfirm(obid, pid, pr)
   {
         var obi_title = $("#d_obi_title").val();
         var obi_url = $("#d_obi_url").val();
         var obi_text = $("#d_obi_text").val();
         var obi_note = $("#d_obi_note").val();
         var obiid = 0;
         var createType = $("#htype").val();
         var post_info = {};
         post_info['obi_title'] = obi_title;
         post_info['obi_url'] = obi_url;
         post_info['obi_text'] = obi_text;
         post_info['obi_note'] = obi_note;
         post_info['obi_box_id'] = obid;
         
         $.post("./funcs/create_obi2.php", post_info, function(data) {
            var returnarray = JSON.parse(data);
            $("#orgboxitemnew_" + obid).remove();
            $("#orgboxitems" + obid).append(returnarray[0]);
            obiid = returnarray[1];
            
            $("#orgboxdivs").packery('layout');
         }).done(function(){
            $("#orgboxitems" + obid).nestedSortable();
            var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
            var post_info = {};
            post_info['ob_structure'] = JSON.stringify(new_index);
            post_info['ob_id'] = obid;
            $.post("./funcs/order_sb.php", post_info, function(data) {               
            }).done(function(){
               post_info['save_reason'] = "Create Item";
               post_info['save_width'] =  $(window).width();
               post_info['save_height'] =  $(window).height();
               post_info['save_state'] = $("#orgboxdivs").html();
               $.post("./funcs/save_state.php", post_info, function(data) {
                     recordobaction(obiid, "item", "record",  "Click Create",  "", "", data);
                     recordobaction(obiid, "item", "record",  "Active",  0, 1, data);
                     recordobaction(obiid, "item", "record",  "Title",  "", obi_title, data);
                     recordobaction(obiid, "item", "record",  "URL",  "", obi_url, data);
                     recordobaction(obiid, "item", "record",  "Text",  "", obi_text, data);
                     recordobaction(obiid, "item", "record",  "Note",  "", obi_note, data);
               });                
            });

         }).done(function() {
            resizeob(obid, false);
         }).done(function() {
            $("#orgboxdivs").packery('layout');
         });
   }

   function editobi(obiid)
   {
      var obid = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');

      var post_info = {};
      post_info['obi_id'] = obiid;
      post_info['obi_title'] = $("#obi_url_" + obiid).text();
      post_info['obi_url'] = $("#obi_url_" + obiid).attr('href');
      post_info['obi_text'] = $("#obi_text_" + obiid).text();
      post_info['obi_note'] = $("#obi_note_" + obiid).text();
      
      $.post("./forms/edit_obi_packery.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      }).done(function() {
         resizeob(obid, false);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
      });
   }

   function editobicancel(obiid)
   {
      var obid = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');

      var post_info = {};
      post_info['obi_id'] = obiid;
      $.get("./funcs/cancel_obi.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      }).done(function() {
         resizeob(obid, false);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
      });
      
   }

   function editobiconfirm(obiid)
   {
      var obid = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');
      var obi_title_old = $("#hobititle").val();
      var obi_url_old = $("#hobiurl").val();
      var obi_text_old = $("#hobitext").val();
      var obi_note_old = $("#hobinote").val();
      var createType = $("#htype").val();
      var post_info = {};
      post_info['obi_id'] = obiid;
      obi_title = $("#d_obi_title_" + obiid).val();
      obi_url= $("#d_obi_url_" + obiid).val();
      obi_text = $("#d_obi_text_" + obiid).val();
      obi_note = $("#d_obi_note_" + obiid).val();
      post_info['obi_title'] = $("#d_obi_title_" + obiid).val();
      post_info['obi_url'] = $("#d_obi_url_" + obiid).val();
      post_info['obi_text'] = $("#d_obi_text_" + obiid).val();
      post_info['obi_note'] = $("#d_obi_note_" + obiid).val();
      $.post("./funcs/edit_obi2.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      }).done(function() {
         resizeob(obid, false);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
         
         //Save orgbox
         post_info['save_reason'] = "Edit Item";
         post_info['save_width'] =  $(window).width();
         post_info['save_height'] =  $(window).height();
         post_info['save_state'] = $("#orgboxdivs").html();
         $.post("./funcs/save_state.php", post_info, function(data) {
            recordobaction(obiid, "item", "record",  "Edit",  "", "", data);
            if(obi_title != obi_title_old){
               recordobaction(obiid, "item", "record",  "Title",  obi_title_old, obi_title, data);
            }
            if(obi_url != obi_url_old){
               recordobaction(obiid, "item", "record",  "URL",  obi_url_old, obi_url, data);
            }
            if(obi_text != obi_text_old){
               recordobaction(obiid, "item", "record",  "Text",  obi_text_old, obi_text, data);
            }
            if(obi_note != obi_note_old){
               recordobaction(obiid, "item", "record",  "Note",  obi_note_old, obi_note, data);
            }
         })
      });
      
   }

   function removeobi(obiid)
   {
      var obid = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');
      var post_info = {};
      post_info['obi_id'] = obiid;
      post_info['ob_id'] = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');
      var subitemids = $("#obi_"+ obiid + " .orgboxitem").map(function(){
         return $(this).attr('id');
      }).get();
      post_info['obi_subitems'] = subitemids.toString();

      $.post("./forms/remove_obi_packery.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      }).done(function() {
         resizeob(obid, true);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
      }); 
   }

   function removeobicancel(obiid)
   {
      var obid = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');
      var post_info = {};
      post_info['obi_id'] = obiid;

      $.get("./funcs/cancel_obi.php", post_info, function(data) {
         $("#obi_div_" + obiid).html(data);
      }).done(function() {
         resizeob(obid, true);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
      });
   }

   function removeobiconfirm(obiid, obid, obisubitems)
   {
      var obid = $("#obi_div_"+ obiid).closest(".orgboxdiv").attr('box');

      var createType = $("#htype").val();
      var post_info = {};
      post_info['obi_id'] = obiid;
      post_info['obi_subitems'] = obisubitems;
      $.post("./funcs/remove_obi2.php", post_info, function(data) {
         $("#obi_" + obiid).remove();
      }).done(function() {
         resizeob(obid, true);
      }).done(function() {
         $("#orgboxdivs").packery('layout');
         
         
         $("#orgboxitems" + obid).nestedSortable();
         var new_index = $("#orgboxitems" + obid).nestedSortable('serialize');
         var post_info = {};
         post_info['ob_structure'] = JSON.stringify(new_index);
         post_info['ob_id'] = obid;
         $.post("./funcs/order_sb.php", post_info, function(data) {
         }).done(function(){
            post_info['save_reason'] = "Remove Item";            
            post_info['save_width'] =  $(window).width();
            post_info['save_height'] =  $(window).height();
            post_info['save_state'] = $("#orgboxdivs").html();
            $.post("./funcs/save_state.php", post_info, function(data) {
               recordobaction(obiid, "item", "record",  "Remove",  "", "", data);
               recordobaction(obiid, "item", "record",  "Active",  1, 0, data);
            })
         })
      });
      
   }



   function createob()
   {
      var createform = $("<div class='orgboxdiv' id='brandnewbox' style='background-color:lightblue'>" +
      "<label class='textlabel'>Title:</label><input class='input textentry' id='d_ob_title' name='title' type='text'><br/>" +
      "<div class='orgboxclear'></div>" +
      "<button id='cancel_obi_button' onclick='createobcancel()' class='cancelbutton'>Cancel</button>" +
      "<button id='save_obi_button' onclick='createobconfirm()' class='savebutton'>Save</button>" +
      "<div style='clear: both;'></div><input type='hidden' id='htype' name='htype' value=''></div>");
      $("#orgboxdivs").packery().append(createform).packery( 'appended', createform ).packery('layout');
      $("#d_ob_title").focus();
   }

   function createobcancel()
   {
      $("#brandnewbox").remove();
   }

   function createobconfirm()
   {
      var ob_title = $("#d_ob_title").val();
      var obid = 0;
      var post_info = {};
      post_info['ob_title'] = ob_title;
      var createType = $("#htype").val();
      $.post("./funcs/create_ob_packery.php", post_info, function(data) {
         var returnarray = JSON.parse(data);
         obid = returnarray[1];
         $("#brandnewbox").prop('id', 'ob_'+obid);
         $("#ob_" + obid).attr('box', obid);
         $("#ob_" + obid).attr('sid', obid);
         $("#ob_" + obid).html(returnarray[0]);
         $("#ob_" + obid).css('background-color', 'rgb(253, 253, 253)');
         
      }).done(function(){
         $("#orgboxdivs").packery('reloadItems').packery('layout');
         
         $( "#ob_" + obid ).resizable({
            minWidth: "400px",
            minHeight: 60,
            start: function(event,ui) {
               var content = $(this).children(".orgboxdivcontent");
               $(this).css({
                  minHeight: content.outerHeight()+ 30,
                  minWidth: "400px"
               });
            },  
            stop: function(event,ui) {            
               var content = $(this).children(".orgboxdivcontent");
               $(this).css({
                     minHeight: content.outerHeight() + 40,
                     minWidth: content.outerWidth()
               });         
               var post_info = {};
               post_info['obid'] = $( this ).attr("box");
               post_info['obwidth'] = $( this ).css("minWidth");
               post_info['obheight'] = $( this ).css("minHeight");
               post_info['save_reason'] = "Resize Box";
               post_info['save_width'] =  $(window).width();
               post_info['save_height'] =  $(window).height();
               post_info['save_state'] = $("#orgboxdivs").html();
               $.post("./funcs/resize_ob.php", post_info, function(data) {   
                  recordobaction(post_info['obid'], "box", "record",  "Resize",  "", "", data);       
               }).done(function(){
                  $('.orgboxdivs').packery('layout');
               }); 
               
            } 
         });

         var old_s_index, old_r_index;
         $( ".orgboxitems" ).nestedSortable(
         {
            connectWith: ".orgboxitems",
            handle: 'div',
            items: '.orgboxitem',
            disabledClass: '.orgboxitemnew, .orgboxitemblank', 
            forcePlaceholderSize: true, 
            tolerance: 'pointer',       
            toleranceElement : '> div',
            maxLevels: 3,
            scroll: true,
            revert: true,
            placeholder: "ui-state-highlight",
            opacity: 0.8,
            start: function(event, ui){
               ui.placeholder.height(ui.item.height());
               old_s_index = $(this).nestedSortable('serialize');
            },
            over: function(event,ui){
               old_r_index = $(this).nestedSortable('serialize');
            },
            update: function(event,ui){
               var obid = $(this).closest(".orgboxdiv").attr('box');
               resizeob(obid, true);
               var new_index = $(this).nestedSortable('serialize');
               var post_info = {};
               post_info['ob_structure'] = JSON.stringify(new_index);
               post_info['ob_id'] = obid;
               post_info['save_reason'] = "Ordering Items";
               post_info['save_width'] =  $(window).width();
               post_info['save_height'] =  $(window).height();
               post_info['save_state'] = $("#orgboxdivs").html();
               $.post("./funcs/order_sb.php", post_info, function(data) {
                  if(ui.sender == null){
                     recordobaction(obid, "box", "record",  "Item Order",  JSON.stringify(old_s_index), JSON.stringify(new_index), data);
                  }
                  else{
                     recordobaction(obid, "box", "record",  "Item Order",  JSON.stringify(old_r_index), JSON.stringify(new_index), data);
                  }
               });
               $("#orgboxdivs").packery('layout');
               
            }
         }).disableSelection();

         var dragItem = $( "#ob_" + obid ).draggable({
            stop: function(e, ui){
               var post_info = {};
               post_info['obid'] = $( this ).attr("box");
               post_info['save_reason'] = "Drag Box";
               post_info['save_width'] =  $(window).width();
               post_info['save_height'] =  $(window).height();
               post_info['save_state'] = $("#orgboxdivs").html();
               $.post("./funcs/save_state.php", post_info, function(data) {
                     recordobaction(post_info['obid'], "box", "record",  "Drag",  "", "", data);
               });
            }
         });
         $("#orgboxdivs").packery( 'bindUIDraggableEvents', dragItem );

         post_info['save_reason'] = "Create Box";
         post_info['save_width'] =  $(window).width();
         post_info['save_height'] =  $(window).height();
         post_info['save_state'] = $("#orgboxdivs").html();
         $.post("./funcs/save_state.php", post_info, function(data) {
            recordobaction(obid, "box", "record",  "Click Create",  "", "", data);
            recordobaction(obid, "box", "record",  "Active",  0, 1, data);
            recordobaction(obid, "box", "record",  "Title",  "", ob_title, data);
         });
      });
   
   }


   function editob(obid)
   {
      var post_info = {};
      post_info['ob_id'] = obid;
      post_info['ob_title'] = $("#ob_title_" + obid).text();
      
      $.post("./forms/edit_ob_form.php", post_info, function(data) {
         $("#ob_div_" + obid).html(data);
      });
   }

   function removeob(obid)
   {
      var post_info = {};
      post_info['ob_id'] = obid;
      post_info['ob_title'] = $("#ob_title_" + obid).text();
      post_info['ob_box_id'] = obid;
      post_info['ob_items'] = $("#orgboxitems" + obid).nestedSortable('serialize');
            
      var confirmText = "<div class='warning'>Do you want to delete this box and all its contents?<br/>"
      + "<button id='cancel_ob_button' onclick='removeobcancel(\"" + obid + "\", \"" + post_info['ob_title'] + "\")' class='cancelbutton'>Cancel</button>"
      + "<button id='save_ob_button' onclick='removeobconfirm(\"" + obid + "\")' class='savebutton'>Delete</button>" 
      + "<div style='clear: both;'></div>";
      + "</div>";
      $("#ob_div_" + obid).html(confirmText);     
   }

   function removeobcancel(obid, obtitle)
   {
        var post_info = {};
        post_info['ob_id'] = obid;
        post_info['ob_title'] = obtitle;
        $.get("./funcs/cancel_ob.php", post_info, function(data) {  
            $("#ob_div_" + obid).html(data);
        });
   }

   function removeobconfirm(obid)
   {
      var post_info = {};
      post_info['ob_id'] = obid;
      post_info['ob_title'] = $("#ob_title_" + obid).text();
      post_info['ob_box_id'] = obid;
      post_info['ob_items'] = $("#orgboxitems" + obid).nestedSortable('serialize');
      $.post("./funcs/remove_ob2.php", post_info, function(data) {
         $("#ob_" + obid).remove();
         $(".orgboxdivs").packery('layout');
      }).done(function(){
         post_info['save_reason'] = "Remove Box";         
         post_info['save_width'] =  $(window).width();
         post_info['save_height'] =  $(window).height();
         post_info['save_state'] = $("#orgboxdivs").html();
         $.post("./funcs/save_state.php", post_info, function(data) {
            recordobaction(obid, "box", "record",  "Remove",  "", "", data);
            recordobaction(obid, "box", "record",  "Active",  1, 0, data);
         })
      });
   }
   

   
</script>


<link rel="stylesheet" type="text/css" href="nestedstyle.css">

<title>OrgBox</title>
<h2>OrgBox
<?php 

   echo "<span style='color:white'>(BLUE) User ID: ".$userid;
   echo " | ";
   echo "Task ID: ".$taskid;
   echo "HIT ID: ".$hitid;
   echo "<br></span>";
?> 
</h2>
<button id="new_ob_button" class="createbutton" onclick="createob()">Create New Box</button>

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
        <span id=<?php echo "ob_title_$obid"?> class="obtitlespan"><?php echo $obtitle ?> </span>
        <span class='removeit' onclick='removeob(<?php echo $obid?>)'>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span class='editit' onclick='editob(<?php echo $obid?>)'>&nbsp;&nbsp;&nbsp;&nbsp;</span>
      </div>
        <hr/>
        <div class="orgboxdivcontent">
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
            //the startol function closes the following li
            echo "<li class='orgboxitemnew'><div> Drag text here to add a new item or <button id='new_obi_button' box='".$obid."' class='new_obi_button' onclick='createobi(".$obid.")'>Manually Create Item</button> </div>";
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
               printitem($key, $value, $levelOneID, $levelTwoID, $currentLevel, $previousLevel);
               //after loop, set the level into the previous level
               $previousLevel = $currentLevel;
            }
            endol($key, 0, $previousLevel);
        ?>
        </div>
   </div>
<?php 
   endforeach;
?> 
</section>
       