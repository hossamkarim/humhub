<script src="https://cdn.socket.io/socket.io-1.3.6.js"></script>
<div class="container">

    <div class="row">
        <div class="panel panel-default">

            <div class="panel-heading">Invite People</div>


            <div class="panel-body">
               <?php
               // Some member stats
               //Yii::import('application.modules.mail.models.*');
               $criteria = new CDbCriteria;
               $criteria->group = 'user_id';
               $criteria->condition = 'user_id IS NOT null';
               $onlineUsers = UserHttpSession::model()->findAll($criteria);
               ?>

               <input id='recipient'>
               <button onclick="javascript:invite();">Send Invitation</button>
               <?php
                 $this->widget('application.modules_core.user.widgets.UserPickerWidget',array(
                      // additional javascript options for the date picker plugin
                      'inputId' => 'recipient',
                      //'model' => $model,
                      'attribute' => 'recipient',
                      'userGuid' => Yii::app()->user->guid,
                      'focus' => true,
                 ));
               ?>
            </div>


        </div>

    </div>


    <div class="row">
        <div class="panel panel-default">

            <div class="panel-heading">Chat</div>

            <div class="panel-body">
              <ul id="messages"></ul>
              <form action="">
                <input id="m" autocomplete="off" /><button>Send</button>
              </form>

              <script>
                var notificationRoom = 'notification-room'
                var username = '<?php echo Yii::app()->user->name ?>'
                var userid = '<?php echo Yii::app()->user->guid ?>'
                var socket = io('http://localhost:8080', {path: '/n-chat/socket.io/'});
                socket.emit('subscribe', {room: 'room-' + userid, username: username });
                var room = makeid();
                socket.emit('subscribe', {room: room, username: username });
                $('form').submit(function(){
                  var message = $('#m').val();
                  if (room) {
                    socket.emit('send', { room: room, username: username, message: $('#m').val() });
                  }
                  $('#m').val('');
                  return false;
                });
                socket.on('message', function(data){
                  var remoteUsername = data.username;
                  var message = data.message;
                  var show = remoteUsername + ': ' + message
                  $('#messages').append($('<li>').text(show));
                });

                socket.on('chat-request', function(data) {
                  $('#messages').append($('<li>').text(JSON.stringify(data)));
                  socket.emit('subscribe', {room: data.address, username: username});
                  // change the current room
                  room = data.address;
                  socket.emit('send', {
                    room: data.address,
                    username: username,
                    message: "Happy to join, thanks for inviting me"
                  });

                });


                function invite() {
                  var people = $.fn.userpicker.parseUserInput('recipient');
                  var pa = people.split(',');
                  pa.splice(-1,1);
                  socket.emit('chat-request', {
                    room: notificationRoom,
                    username: username,
                    people: pa,
                    address: room,
                    message: '<?php echo Yii::app()->user->name ?> invited you to chat'
                  });
                }

                function makeid() {
                  var text = "";
                  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  for( var i=0; i < 5; i++ )
                      text += possible.charAt(Math.floor(Math.random() * possible.length));
                  return text;
               }
              </script>
            </div>



        </div>

    </div>

</div>
