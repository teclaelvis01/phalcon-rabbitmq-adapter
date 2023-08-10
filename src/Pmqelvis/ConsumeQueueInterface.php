<?php

namespace Pmqelvis;

interface ConsumeQueueInterface
{
    public function consume(callable $callback): void;
}
