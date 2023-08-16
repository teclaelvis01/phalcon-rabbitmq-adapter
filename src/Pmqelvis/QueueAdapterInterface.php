<?php
namespace Pmqelvis;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

interface QueueAdapterInterface
{
    
    public function channel(): AMQPChannel;

    public function connection(): AMQPStreamConnection | AMQPSSLConnection;
    


    // public function exchangeDeclare(string $exchangeName, string $typeExchange): void;

    // public function queueDeclare(string $queueName): void;

    // public function queueBind(string $queueName, string $exchangeName, string $routingKey = ''): void;

    // public function basicPublish(string $message, string $exchangeName, string $routingKey = ''): void;

    // public function basicConsume(string $queueName, callable $callback): void;
}