
<!DOCTYPE html>
<html>
<head>
	<title>Online game</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
	<div id='create'>
		<p>Room Create</p>
		<input type="text" id="room_name" value="">
		<button id='create_button'>Create Room</button>
	</div>
	<div id="wait">
		<p>Wait till other player connects</p>
	</div>
	<div id="start">
		<p>Player found match starting:</p>
	</div>
	<div id="game">
		<p id='question'></p>
		<button id='option_1' value="" class='opt'></button>
		<button id='option_2' value="" class='opt'></button>
		<button id='option_3' value="" class='opt'></button>
		<button id='correct' value="" class='opt'></button>
	</div>
</body>
<script type="text/javascript">

	$(document).ready(function(){

		var answer='';
		var point=0;
		var room;

		$('#wait').hide();
		$('#game').hide();
		$('#start').hide();

		const socket=io("localhost:3000");

		$('#create_button').click(function(){
			room=$('#room_name').val();
			socket.emit('join_room',room);
			$('#create').hide();
			$('#wait').show();
		});

		socket.on('start',function(start){
			$('#wait').hide();
			$('#start').show();
		});

		socket.on('question',function(question){
			$('#start').hide();
			answer=question.correct;
			renderQuestions(question);
		});

		$('.opt').click(function(){
	    	if(this.value===answer){
	    		point++;
	    		socket.to(room).emit('correct');
	    	}
	    });

	    socket.on('correct',function(){

	    });



	});

	function renderQuestions(question){
		$('#question').text(question.question);
		$('#option_1').val(question.option_1).text(question.option_1);
		$('#option_2').val(question.option_2).text(question.option_2);
		$('#option_3').val(question.option_3).text(question.option_3);
		$('#correct').val(question.correct).text(question.correct);
		$('#game').fadeIn(400);
	}



</script>
</html>