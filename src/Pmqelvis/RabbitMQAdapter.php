<?php

namespace Pmqelvis;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQAdapter implements QueueAdapterInterface
{

    const ExchangeTypeTopic = 'topic';
    const ExchangeTypeDirect = 'direct';
    const ExchangeTypeFanout = 'fanout';
    const ExchangeTypeHeaders = 'headers';

    /**
     * @var AMQPStreamConnection | AMQPSSLConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;
    /**
     * @var string
     */
    protected $queueName;

    /**
     * exchange name
     */
    protected $exchangeName;

    /**
     * Type exchange
     * @var string
     */
    protected $typeExchange;

    protected $ssl;

    public function __construct(string $host, int $port, string $user, string $password, bool $ssl = false, array $options = [])
    {
        $this->ssl = $ssl;
        if ($this->ssl) {
            $sslOptions = [
                'verify_peer' => false
            ];
            $this->connection = new AMQPSSLConnection($host, $port, $user, $password, '/', $sslOptions, $options);
            $this->channel = $this->connection->channel();
            return;
        }
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
    }


    public function channel(): AMQPChannel
    {
        return  $this->channel;
    }
    /**
     * @return AMQPStreamConnection | AMQPSSLConnection
     */
    public function connection()
    {
        return $this->connection;
    }
}
