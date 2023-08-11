# phalcon-rabbitmq-adapter
Adapter for phalcon queue manager


## Installation

```bash
composer require teclaelvis/rabbitmq-phalcon-adapter:1.0.1
```


## Initial configuration

We need to create a new instance of the `QueueManagerFactory` class and pass the adapter as a parameter.


```php
use Pmqelvis\QueueManagerFactory;
use Pmqelvis\RabbitMQAdapter;

require __DIR__ . '/vendor/autoload.php';


// // create a new instance of the rabbitmq adapter

$adapter = new RabbitMQAdapter('localhost', 5672, 'guest', 'guest');
$queueFactory = new QueueManagerFactory($adapter);

``````

If you are working with a `ssl connection`, you can use the follow code:

```php
$adapter = new RabbitMQAdapter('localhost', 5672, 'guest', 'guest', true);
 
// continue with the configuration
...
```

in Phalcon we can use the `QueueManagerFactory` class as a service, for example:

```php
$di->setShared('queue', function () use ($config) {
    $ssl = getenv('APPLICATION_ENV') != 'development';
    $adapter = new RabbitMQAdapter(
        $config->rabbitmq->host,
        $config->rabbitmq->port,
        $config->rabbitmq->user,
        $config->rabbitmq->password,
        $ssl
    );
    return new QueueManagerFactory($adapter);
});
```

and then we can use it in our logic to get the queue manager:

```php

$queueFactory = $this->di->getShared('queue');

...

```


## Producer configuration

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

## Consumer configuration

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
