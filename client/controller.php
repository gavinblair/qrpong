<?php include 'config.php'; ?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<style>
	* {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	div {
		position: absolute;
		width: 100%;
		height: 50%;
		left: 0;
	}
	#up {
		top: 0;
		background: green;
	}
	#down{
		top: 50%;
		background: blue;
	}
</style>
<script>
	var conn;
	window.onload = function(){
		conn = new WebSocket('<?php echo $server; ?>');
		conn.onopen = function(e) {
		    console.log("Connection established!");
			conn.send('new controller?<?php echo $_GET['session']; ?>');
		};

		conn.onmessage = function(e) {
		    console.log(e.data);
		};

	};
	function up(){
		conn.send('up?<?php echo $_GET['session']; ?>');
	}
	function down(){
		conn.send('down?<?php echo $_GET['session']; ?>');
	}
	function leggo(){
		conn.send('leggo?<?php echo $_GET['session']; ?>');
	}
</script>

</head>
<body>
<?php echo $_GET['session']; ?>

<div id='up' ontouchstart='up()' ontouchend='leggo()'></div>
<div id='down' ontouchstart='down()' ontouchend='leggo()'></div>
</body>
</html>