var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http, {path: '/n-chat/socket.io/'});

app.get('/n-chat', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket){
  socket.on('chat message', function(msg){
    io.emit('chat message', msg);
  });
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});
