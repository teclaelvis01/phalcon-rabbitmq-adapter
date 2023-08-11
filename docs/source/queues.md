Queues using RabbitMQ
============

## Producer use case

The example below shows how to configure a producer

```php

use Pmqelvis\QueueManagerFactory;
use Pmqelvis\RabbitMQAdapter;

require __DIR__ . '/vendor/autoload.php';


/**
 *  before we need get the QueueManagerFactory instance
 * $queueFactory = new QueueManagerFactory($adapter);
 */
...
...

$queue = $queueFactory->build('test', 'producer', 'test-exchange');
$queue->publish('Hello World from my library');
    
```

## Consumer use case

The example below shows how to configure a consumer


```php
use Pmqelvis\QueueManagerFactory;
use Pmqelvis\RabbitMQAdapter;

require __DIR__ . '/vendor/autoload.php';


/**
 *  before we need get the QueueManagerFactory instance
 * $queueFactory = new QueueManagerFactory($adapter);
 */
...
...


$queue = $queueFactory->build('test','consumer' ,'test-exchange');

$queue->consume(function ($message) {
    echo $message->body;
    $message->ack();
});
    
```

The code above will consume the messages from the queue and print the message body and `$message->ack()` will acknowledge the message.




## RabbitMQAdapter options


| Option | Type | Default | Description |
| --- | --- | --- | --- |
| host | string | '' | RabbitMQ host |
| port | int | 5672 | RabbitMQ port |
| user | string | '' | RabbitMQ user |
| password | string | '' | RabbitMQ password |
| ssl | bool | false | Enable ssl mode |

## QueueManagerFactory options

The `QueueManagerFactory->build()` method accepts the following options:

| Option | Type | Default | Description |
| --- | --- | --- | --- |
| queueName | string | '' | Queue name |
| type | string | '' | Queue type (producer or consumer) |
| exchangeName | string | '' | Exchange name |
| exchangeType | string | '' | Exchange type |

if type is `consumer` the method returns a `ConsumeQueue` instance, if type is `producer` the method returns a `ProducerQueue` instance.
