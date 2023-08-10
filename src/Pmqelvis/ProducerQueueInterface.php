<?php

namespace Pmqelvis;

interface ProducerQueueInterface
{
    public function publish($data): void;
}
