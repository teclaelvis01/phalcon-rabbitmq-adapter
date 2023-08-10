<?php
namespace Pmqelvis;
interface QueueProducerInterface {
    public function publish($message);
}
