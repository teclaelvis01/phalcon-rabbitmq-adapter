<?php

namespace Pmqelvis;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class QueueManagerFactory
{
    /**
     * @var QueueAdapterInterface
     */
    private $adapter;

    public function __construct(QueueAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $queueName
     * @param string $type 'consumer' | 'producer'
     * @param string $exchangeName
     * @param string $typeExchange 'direct' | 'fanout' | 'topic' | 'headers'
     */
    public function build($queueName, $type = 'consumer', $exchangeName = '', $typeExchange = RabbitMQAdapter::ExchangeTypeDirect)
    {
        switch ($type) {
            case 'consumer':
                return new ConsumeQueue($this->adapter, $queueName, $exchangeName, $typeExchange);
            case 'producer':
                return new ProducerQueue($this->adapter, $queueName, $exchangeName, $typeExchange);
            default:
                throw new \Exception('Invalid queue manager type');
        }
    }

    /**
     * @param string $queueName
     * @param string $exchangeName
     * @param string $typeExchange 'direct' | 'fanout' | 'topic' | 'headers'
     * @return ConsumeQueue
     */
    public function buildConsumer($queueName, $exchangeName = '', $typeExchange = RabbitMQAdapter::ExchangeTypeDirect)
    {
        return new ConsumeQueue($this->adapter, $queueName, $exchangeName, $typeExchange);
    }

    /**
     * @param string $queueName
     * @param string $exchangeName
     * @param string $typeExchange 'direct' | 'fanout' | 'topic' | 'headers'
     * @return ProducerQueue
     */
    public function buildProducer($queueName, $exchangeName = '', $typeExchange = RabbitMQAdapter::ExchangeTypeDirect)
    {
        return new ProducerQueue($this->adapter, $queueName, $exchangeName, $typeExchange);
    }

    // TODO: return connection object
    /**
     * get connection
     * @return  AMQPStreamConnection | AMQPSSLConnection;
     */
    public function getConnection()
    {
        return $this->adapter->connection();
    }

    /**
     * get channel
     * @return AMQPChannel
     */
    public function getChannel()
    {
        return $this->adapter->channel();
    }
}
