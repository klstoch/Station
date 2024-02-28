<?php

namespace Station;

class ClientQueue
{
    private array $clientQueue = [];

    public function add(Client $client): void
    {
        $this->clientQueue[] = $client;
    }

    public function get(): Client|null
    {
        return array_shift($this->clientQueue);
    }

    public function delete(Client $client): void
    {
        foreach ($this->clientQueue as $i => $clientFromQueue) {
            if ($clientFromQueue === $client) {
                unset($this->clientQueue[$i]);
                return;
            }
        }
    }

    public function isEmptyClientQueue(): bool
    {

        return empty($this->clientQueue);

    }
}