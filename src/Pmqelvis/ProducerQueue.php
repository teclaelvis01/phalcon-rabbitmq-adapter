<?php

namespace Pmqelvis;

use PhpAmqpLib\Message\AMQPMessage;

class ProducerQueue implements ProducerQueueInterface
{

    /**
     * @var QueueAdapterInterface
     */
    protected $adapter;
    /**
     * @var string
     */
    protected $queueName;
    /**
     * @var string
     */
    protected $exchangeName;
    /**
     * @var string
     */
    protected $typeExchange;

    /**
     * @var string
     */
    protected $routeKey = '';


    public function __construct(QueueAdapterInterface $adapter, string $queueName, $exchangeName = '', $typeExchange = RabbitMQAdapter::ExchangeTypeDirect)
    {
        $this->adapter = $adapter;
        $this->exchangeName = $exchangeName;
        $this->queueName = $queueName;

        $this->exchangeName = $exchangeName;



        if ($this->exchangeName != '') {
            $this->adapter->channel()->exchange_declare($this->exchangeName, $typeExchange, false, true, false);
            $this->routeKey = $this->queueName . '-routing-key';
            return;
        }
        $this->routeKey = $this->queueName;
        $this->adapter->channel()->queue_declare($this->queueName, false, true, false, false);
    }

    public function publish($message): void
    {
        // if $message is array or json, convert to string
        if (is_array($message) || is_object($message)) {
            $message = json_encode($message);
        }
        $msg = new AMQPMessage($message, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->adapter->channel()->basic_publish($msg, $this->exchangeName, $this->routeKey);
        $this->__destruct();
    }

    public function __destruct()
    {
        $this->adapter->channel()->close();
        $this->adapter->connection()->close();
    }
}
