<?php
namespace nuffy\cards\ShufflingStrategy;

class DefaultShufflingStrategy implements ShufflingStrategyInterface
{
    public function shuffle(array &$cards): void
    {
        \shuffle($cards);
    }
}