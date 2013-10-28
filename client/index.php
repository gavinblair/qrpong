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
  var holding = {};
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
        v 40
        < 37
        > 39
        k 75
           */
          case 'up':
          case 'a':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 87);
              console.log("p1 up");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 38);
              console.log("p2 up");
            }
          break;
          case 'leggoup':
          case 'leggoa':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 87);
              console.log("p1 up leggo");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 38);
              console.log("p2 up leggo");
            }
          break;
          case 'down':
          case 'b':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 83);
              console.log("p1 down");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 40);
              console.log("p2 down");
            }
          break;
          case 'leggodown':
          case 'leggob':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 83);
              console.log("p1 down leggo");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 40);
              console.log("p2 down leggo");
            }
          break;
          case 'left':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 65);
              console.log("p1 left");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 37);
              console.log("p2 left");
            }
          break;

          case 'leggoleft':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 65);
              console.log("p1 left leggo");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 37);
              console.log("p2 left leggo");
            }
          break;
          case 'right':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keydown', 68);
              console.log("p1 right");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keydown', 39);
              console.log("p2 right");
            }
          break;

          case 'leggoright':
            if(controllers[0].id === id){
              //p1
              __triggerKeyboardEvent(document.body, 'keyup', 68);
              console.log("p1 right leggo");
            } else {
              //p2
              __triggerKeyboardEvent(document.body, 'keyup', 39);
              console.log("p2 right leggo");
            }
          break;
          case 'start':
            __triggerKeyboardEvent(document.body, 'keydown', 32);
            console.log("start");
            holding.start = true;
            if(holding.select){
            	__triggerKeyboardEvent(document.body, 'keydown', 75);
            	console.log("KILL");
            }
          break;
          case 'leggostart':
            __triggerKeyboardEvent(document.body, 'keydown', 32);
            console.log("start leggo");
            holding.start = false;
          break;
          case 'select':
            __triggerKeyboardEvent(document.body, 'keydown', 13);
            console.log("select");
            holding.select = true;
            if(holding.start){
            	__triggerKeyboardEvent(document.body, 'keydown', 75);
            	console.log("KILL");
            }
          break;
          case 'leggoselect':
            __triggerKeyboardEvent(document.body, 'keydown', 13);
            console.log("select leggo");
            holding.select = false;
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