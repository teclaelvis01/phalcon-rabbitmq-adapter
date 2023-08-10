<?php

namespace Pmqelvis;


class ConsumeQueue  implements ConsumeQueueInterface
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


    public function __construct(QueueAdapterInterface $adapter, string $queueName, $exchangeName = '', $typeExchange = RabbitMQAdapter::ExchangeTypeDirect)
    {
        $this->adapter = $adapter;
        $this->queueName = $queueName;
        $this->exchangeName = $exchangeName;
        $this->typeExchange = $typeExchange;



        if ($this->exchangeName != '') {
            $this->adapter->channel()->exchange_declare($this->exchangeName, $this->typeExchange, false, true, false);
        }
        $this->adapter->channel()->queue_declare($this->queueName, false, true, false, false);
        if ($this->exchangeName != '') {
            $this->adapter->channel()->queue_bind($this->queueName, $this->exchangeName, $this->queueName . '-routing-key');
        }
    }

    public function consume($callback):void
    {
        $this->adapter->channel()->basic_qos(null, 1, null);
        $this->adapter->channel()->basic_consume($this->queueName, '', false, false, false, false, $callback);

        while (count($this->adapter->channel()->callbacks)) {
            $this->adapter->channel()->wait();
        }
    }

    public function __destruct()
    {
        $this->adapter->channel()->close();
        $this->adapter->connection()->close();
    }
}
