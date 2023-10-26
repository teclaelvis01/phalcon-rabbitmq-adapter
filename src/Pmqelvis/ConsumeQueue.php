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

    /**
     * @var bool
     */
    protected $destroy;


    public function __construct(QueueAdapterInterface $adapter, string $queueName, $exchangeName = '', $typeExchange = RabbitMQAdapter::ExchangeTypeDirect, $destroy = false)
    {
        $this->adapter = $adapter;
        $this->queueName = $queueName;
        $this->exchangeName = $exchangeName;
        $this->typeExchange = $typeExchange;
        $this->destroy = $destroy;



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

        while ($this->adapter->channel()->is_open()) {
            $this->adapter->channel()->wait();
        }
        $this->__destruct();
    }

    public function __destruct()
    {
        if(!$this->destroy){
            return;
        }
        $this->adapter->channel()->close();
        $this->adapter->connection()->close();
    }
}
