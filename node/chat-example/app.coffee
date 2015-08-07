app = require('express')()
http = require('http').Server(app)
io = require('socket.io')(http, {path: '/n-chat/socket.io/'})

app.get '/n-chat', (req, res) ->
  res.sendFile(__dirname + '/index.html')

io.sockets.on 'connection', (socket) ->

  socket.on 'subscribe', (data) ->
    room = data.room
    username = data.username
    console.log("user: #{username} joined room: #{room}")
    socket.join(room)

  socket.on 'unsubscribe', (room) ->
    console.log('leaving room', room)
    socket.leave(room)

  socket.on 'chat-request', (data) ->
    for p in data.people
      if p && p.length > 0
        proom = "room-#{p}"
        io.to(proom).emit('chat-request', data)

  socket.on 'send', (data) ->
    room = data.room
    username = data.username
    message = data.message
    console.log "user: #{username} sent #{message} to #{room}"
    io.to(room).emit('message', data)

http.listen 3000, ->
  console.log('listening on *:3000')
