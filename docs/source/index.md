Configuration
============

For install `phalcon-rabbitmq-adapter` library you can use composer:


## Installation

```bash
composer require teclaelvis/rabbitmq-phalcon-adapter:1.0.0
```

## Phalcon usage

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
