<?php include 'config.php'; ?>
<!DOCTYPE html>
<!-- saved from url=(0037)http://raptjs.com/play/#/rapt/Dynamo/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Play</title>
    <link href="Play_files/application.css" media="screen" rel="stylesheet" type="text/css">
    <script>
  var conn;
  var controllers = [];
  var game;
  function __triggerKeyboardEvent(el, event, keyCode){
      var eventObj = document.createEventObject ?
          document.createEventObject() : document.createEvent("Events");
    
      if(eventObj.initEvent){
        eventObj.initEvent(event, true, true);
      }
    
      eventObj.keyCode = keyCode;
      eventObj.which = keyCode;
      
      el.dispatchEvent ? el.dispatchEvent(eventObj) : el.fireEvent("on"+event, eventObj); 
    
  } 

  window.onload = function(){
    conn = new WebSocket('<?php echo $server; ?>');
    conn.onopen = function(e) {
        console.log("Connection established!");
      conn.send('new screen?<?php echo $_GET['session']; ?>');
    };

    conn.onmessage = function(e) {
        console.log(e.data);
        var parts = e.data.split(':');
        var id = parts[1];
        switch(parts[0]){
          case 'new controller':
            //initialize a new controller
            if(controllers.length < 2){
              controllers.push({
                id: id,
              });
              //start the game?
              if(controllers.length === 1){
                document.getElementById('qr').style.border = '100px solid white';
                document.getElementById('qr').style.marginLeft = '-200px';
                document.getElementById('qr').style.marginTop = '-200px';
              }else if(controllers.length === 2){
                //start the game
                document.getElementById('qr').remove();

              }
          }
          break;
          /*
          w 87
        a 65
        s 83
        d 68
        ^ 38
        v 37
        < 40
        > 39
        k 75
           */
          case 'up':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 87);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 38);
            }
          break;
          case 'leggoup':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 87);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 38);
            }
          break;
          case 'down':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 68);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 37);
            }
          break;
          case 'leggodown':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 68);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 37);
            }
          break;
          case 'left':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 65);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 40);
            }
          break;

          case 'leggoleft':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 65);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 40);
            }
          break;
          case 'right':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 68);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 39);
            }
          break;

          case 'leggoright':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 68);
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 39);
            }
          break;
          case 'k':
            __triggerKeyboardEvent(document.body, 'keydown', 75);
          break;
          case 'leggok':
            __triggerKeyboardEvent(document.body, 'keydown', 75);
          break;
        }
    };

  };

</script>

    <script src="Play_files/jquery.min.js" type="text/javascript"></script>
    <script src="Play_files/rails.js" type="text/javascript"></script>
    
  <style>[touch-action="none"]{ -ms-touch-action: none; touch-action: none; }[touch-action="pan-x"]{ -ms-touch-action: pan-x; touch-action: pan-x; }[touch-action="pan-y"]{ -ms-touch-action: pan-y; touch-action: pan-y; }[touch-action="scroll"],[touch-action="pan-x pan-y"],[touch-action="pan-y pan-x"]{ -ms-touch-action: pan-x pan-y; touch-action: pan-x pan-y; }</style></head>
  <body>
    <div id="content">
      

<script type="text/javascript"><!--
var username = null;
// --></script>
<img id='qr' src='http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=http%3A//<?php echo $clienturl; ?>/controller.php%3Fsession%3D<?php echo $_GET['session']; ?>&chld=H|0' />
<div id="game">
  <canvas id="canvas" width="800" height="600" style="display: inline;">Your browser does not support the HTML5 canvas element.</canvas>
  <div id="loadingScreen" style="display: none;">Loading...</div>
</div>

<!--[if (!IE)|(gte IE 8)]><!-->
<link href="Play_files/game-datauri.css" media="screen" rel="stylesheet" type="text/css">
<!--<![endif]-->
<script src="Play_files/game.js" type="text/javascript"></script>

</div>

   

<canvas id="background-cache-a" style="display: none;" width="1600" height="1200"></canvas><canvas id="background-cache-b" style="display: none;" width="1600" height="1200"></canvas></body></html>