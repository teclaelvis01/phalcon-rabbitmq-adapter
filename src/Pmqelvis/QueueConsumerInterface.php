<?php
namespace Pmqelvis;
interface QueueConsumerInterface {
    public function consume($callback);
}
