<?php

namespace EmailBundle;

class RabbitMQ
{
    public static function sendMessage($message)
    {
        // todo: zoli: implement me
        //$connection = new AMQPStreamConnection('localhost', '5672', 'rabbitmq', 'rabbitmq');
        //$channel = $connection->channel();
        //$channel->queue_declare('hello', false, false, false, false);

        //$msg = new AMQPMessage($message);
        //$channel->basic_publish($msg, '', 'hello');

        //$channel->close();
        //$connection->close();
        echo "Message $message sent to rabbitmq.\n";
    }

    public static function processMessages()
    {
        echo 'Processing...';
        // todo: zoli: implement me
        echo 'Finished.';
    }
}
