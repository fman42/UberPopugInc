<?php

namespace App\Services\ProduceEvent;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQProducer extends Producer
{
    private $channel;
    private $connection;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('cow.rmq2.cloudamqp.com', 5672, 'qrwsbgvf', 'AxvwwkqMp52baIbZ_wcE8Tan2TOyN2Jy', 'qrwsbgvf');
        $this->channel = $this->connection->channel();
    }

    public function publish($topicName, $event)
    {
        $this->channel->queue_declare($topicName, false, false, false, false);
        $msg = new AMQPMessage(json_encode($event));
        $this->channel->basic_publish($msg, '', $topicName);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}