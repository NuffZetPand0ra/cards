<?php
namespace nuffy\cards;

class DefaultShufflingStrategy implements ShufflingStrategyInterface
{
    public function shuffle(array &$cards): void
    {
        \shuffle($cards);
    }
}