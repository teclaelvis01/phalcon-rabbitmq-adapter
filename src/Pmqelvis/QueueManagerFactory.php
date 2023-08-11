<?php

namespace Pmqelvis;


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
        $adapter = clone $this->adapter;
        switch ($type) {
            case 'consumer':
                return new ConsumeQueue($adapter, $queueName, $exchangeName, $typeExchange);
            case 'producer':
                return new ProducerQueue($adapter, $queueName, $exchangeName, $typeExchange);
            default:
                throw new \Exception('Invalid queue manager type');
        }
    }
}
