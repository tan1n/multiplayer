const app = require('express')();
const http = require('http').createServer(app);
const io = require('socket.io')(http);

const questions = [
	{
		"question" : "Capital of bangladesh",
		"option_1" : "London",
		"option_2" : "Mumbai",
		"option_3" : "Paris",
		"correct"  : "Dhaka"
	},
	{
		"question" : "Capital of India",
		"option_1" : "London",
		"option_2" : "Mumbai",
		"option_3" : "Paris",
		"correct"  : "Dehli"
	}
];

const maxNumOfPlayer=2;

io.on('connection', function(socket){

	socket.on('join_room',function(room_name){
		io.in(room_name).clients(function(error, clients){
			clients.length++;
			if(clients.length>maxNumOfPlayer){
				console.log("Already 2 player connected");
				//send error to the user id(not room)
			}
			if(clients.length<=maxNumOfPlayer){
				socket.join(room_name);
				console.log("User joined at room: "+room_name);
				console.log("Total user :"+clients.length);
			}
		  	if(clients.length===maxNumOfPlayer){
		  		io.in(room_name).emit('start');
		  		console.log("Game will start in room:"+room_name);
			  	questions.forEach(function(item,index){
					setTimeout(function(){
						io.in(room_name).emit("question",item);
					},10000*index+1)
			  	});
			  	//send finish game to room
			  	io.in(room_name).emit('finish')
		  	}
		});
	});

	socket.on('answer',function({room,mark}){
		
	});

});



http.listen(3000, function(){
  console.log('listening on *:3000');
});