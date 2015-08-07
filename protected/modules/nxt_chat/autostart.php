<?php

Yii::app()->moduleManager->register(array(
    'id' => 'nxt_chat',
    'class' => 'application.modules.nxt_chat.ChatTopNavModule',
    'import' => array(
        'application.modules.nxt_chat.*',
    ),
    'events' => array(
        array('class' => 'TopMenuWidget', 'event' => 'onInit', 'callback' => array('ChatTopNavEvents', 'onTopMenuInit')),
    ),
));
?>
