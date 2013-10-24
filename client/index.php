<?php include 'config.php'; ?>
<?php
if(!isset($_GET['session'])){
	$_GET['session'] = time();
}
?>
<html>
<head>
<style>
#game, body {
	background: #000;
}
#game {
	width: 100%;
	height: 100%;
}
#qr {
	position: absolute;
	top: 50%; left: 50%;
	margin-left: -100px;
	margin-top: -100px;
}
</style>
<script>
	var conn;
	var controllers = [];
	var game;
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
				    		game = new Game();
				    		MainLoop();
				    	}
					}
		    	break;
		    	case 'up':
		    		//p1: 87 or p2: 38
		    		if(controllers[0].id === id){
		    			//p1
		    			game.keys.makePressed(87);
		    		} else {
		    			//p2
		    			game.keys.makePressed(38);
		    		}
		    	break;
		    	case 'down':
		    		//p1: 83 or p2: 40
		    		if(controllers[0].id === id){
		    			//p1
		    			game.keys.makePressed(83);
		    		} else {
		    			//p2
		    			game.keys.makePressed(40);
		    		}
		    	break;
		    	case 'leggo':
		    		if(controllers[0].id === id){
		    			//p1
		    			game.keys.unPressed(87);
		    			game.keys.unPressed(83);
		    		} else {
		    			//p2
		    			game.keys.unPressed(38);
		    			game.keys.unPressed(40);
		    		}
		    	break;
		    }
		};

	};

	// KEY LISTENER
	function KeyListener() {
	    this.pressedKeys = [];
	 
	    this.keydown = function(e) {
	        this.pressedKeys[e.keyCode] = true;
	    };
	 
	    this.keyup = function(e) {
	        this.pressedKeys[e.keyCode] = false;
	    };
	 
	    document.addEventListener("keydown", this.keydown.bind(this));
	    document.addEventListener("keyup", this.keyup.bind(this));
	}
	 
	KeyListener.prototype.isPressed = function(key)
	{
	    return this.pressedKeys[key] ? true : false;
	};
	KeyListener.prototype.makePressed = function(key)
	{
	    this.pressedKeys[key] = true;
	    return true;
	};
	KeyListener.prototype.unPressed = function(key)
	{
	    this.pressedKeys[key] = false;
	    return true;
	};
	KeyListener.prototype.addKeyPressListener = function(keyCode, callback)
	{
	    document.addEventListener("keypress", function(e) {
	        if (e.keyCode == keyCode)
	            callback(e);
	    });
	};

	function MainLoop() {
	    game.update();
	    game.draw();
	    // Call the main loop again at a frame rate of 30fps
	    setTimeout(MainLoop, 33.3333);
	}
	function Game() {
		document.getElementById('qr').style.display = 'none';
	    var canvas = document.getElementById("game");
	    this.width = canvas.width;
	    this.height = canvas.height;
	    this.context = canvas.getContext("2d");
	    this.context.fillStyle = "white";
	    this.keys = new KeyListener();
	    
	    this.p1 = new Paddle(5, 0);
	    this.p1.y = this.height/2 - this.p1.height/2;
	    this.display1 = new Display(this.width/4, 25);
	    this.p2 = new Paddle(this.width - 5 - 2, 0);
	    this.p2.y = this.height/2 - this.p2.height/2;
	    this.display2 = new Display(this.width*3/4, 25);
	    
	    this.ball = new Ball();
	    this.ball.x = this.width/2;
	    this.ball.y = this.height/2;
	    this.ball.vy = Math.floor(Math.random()*12 - 6);
	    this.ball.vx = 7 - Math.abs(this.ball.vy);
	}

	Game.prototype.draw = function()
	{
	    this.context.clearRect(0, 0, this.width, this.height);
	    this.context.fillRect(this.width/2, 0, 2, this.height);
	    
	    this.ball.draw(this.context);
	    
	    this.p1.draw(this.context);
	    this.p2.draw(this.context);
	    this.display1.draw(this.context);
	    this.display2.draw(this.context);
	};
	 
	Game.prototype.update = function() 
	{
	    if (this.paused)
	        return;
	    
	    this.ball.update();
	    this.display1.value = this.p1.score;
	    this.display2.value = this.p2.score;
	 
	    // To which Y direction the paddle is moving
	    if (this.keys.isPressed(83)) { // DOWN
	        this.p1.y = Math.min(this.height - this.p1.height, this.p1.y + 4);
	    } else if (this.keys.isPressed(87)) { // UP
	        this.p1.y = Math.max(0, this.p1.y - 4);
	    }
	 
	    if (this.keys.isPressed(40)) { // DOWN
	        this.p2.y = Math.min(this.height - this.p2.height, this.p2.y + 4);
	    } else if (this.keys.isPressed(38)) { // UP
	        this.p2.y = Math.max(0, this.p2.y - 4);
	    }
	 
	    if (this.ball.vx > 0) {
	        if (this.p2.x <= this.ball.x + this.ball.width &&
	                this.p2.x > this.ball.x - this.ball.vx + this.ball.width) {
	            var collisionDiff = this.ball.x + this.ball.width - this.p2.x;
	            var k = collisionDiff/this.ball.vx;
	            var y = this.ball.vy*k + (this.ball.y - this.ball.vy);
	            if (y >= this.p2.y && y + this.ball.height <= this.p2.y + this.p2.height) {
	                // collides with right paddle
	                this.ball.x = this.p2.x - this.ball.width;
	                this.ball.y = Math.floor(this.ball.y - this.ball.vy + this.ball.vy*k);
	                this.ball.vx = -this.ball.vx;
	            }
	        }
	    } else {
	        if (this.p1.x + this.p1.width >= this.ball.x) {
	            var collisionDiff = this.p1.x + this.p1.width - this.ball.x;
	            var k = collisionDiff/-this.ball.vx;
	            var y = this.ball.vy*k + (this.ball.y - this.ball.vy);
	            if (y >= this.p1.y && y + this.ball.height <= this.p1.y + this.p1.height) {
	                // collides with the left paddle
	                this.ball.x = this.p1.x + this.p1.width;
	                this.ball.y = Math.floor(this.ball.y - this.ball.vy + this.ball.vy*k);
	                this.ball.vx = -this.ball.vx;
	            }
	        }
	    }
	 
	    // Top and bottom collision
	    if ((this.ball.vy < 0 && this.ball.y < 0) ||
	            (this.ball.vy > 0 && this.ball.y + this.ball.height > this.height)) {
	        this.ball.vy = -this.ball.vy;
	    }
	    
	    if (this.ball.x >= this.width)
	        this.score(this.p1);
	    else if (this.ball.x + this.ball.width <= 0)
	        this.score(this.p2);
	};
	Game.prototype.score = function(p)
	{
	    // player scores
	    p.score++;
	    var player = p == this.p1 ? 0 : 1;
	 
	    // set ball position
	    this.ball.x = this.width/2;
	    this.ball.y = p.y + p.height/2;
	 
	    // set ball velocity
	    this.ball.vy = Math.floor(Math.random()*12 - 6);
	    this.ball.vx = 7 - Math.abs(this.ball.vy);
	    if (player == 1)
	        this.ball.vx *= -1;
	};
	// PADDLE
	function Paddle(x,y) {
	    this.x = x;
	    this.y = y;
	    this.width = 2;
	    this.height = 28;
	    this.score = 0;
	}

	Paddle.prototype.draw = function(p)
	{
	    p.fillRect(this.x, this.y, this.width, this.height);
	};
	//DISPLAY
	function Display(x, y) {
	    this.x = x;
	    this.y = y;
	    this.value = 0;
	}
	 
	Display.prototype.draw = function(p)
	{
	    p.fillText(this.value, this.x, this.y);
	};
	// BALL
	function Ball() {
	    this.x = 0;
	    this.y = 0;
	    this.vx = 0;
	    this.vy = 0;
	    this.width = 4;
	    this.height = 4;
	}
	 
	Ball.prototype.update = function()
	{
	    this.x += this.vx;
	    this.y += this.vy;
	};
	 
	Ball.prototype.draw = function(p)
	{
	    p.fillRect(this.x, this.y, this.width, this.height);
	};


</script>

</head>
<body>
<img id='qr' src='http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=http%3A//<?php echo $clienturl; ?>/controller.php%3Fsession%3D<?php echo $_GET['session']; ?>&chld=H|0' />
<canvas id="game"></canvas>
<a href="https://github.com/gavinblair/qrpong"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png" alt="Fork me on GitHub"></a>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-29149745-2', '65.181.120.53');
  ga('send', 'pageview');

</script>
</body>
</html>
