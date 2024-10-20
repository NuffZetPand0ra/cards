<?php
namespace nuffy\cards\ShufflingStrategy;

interface ShufflingStrategyInterface
{
    public function shuffle(array &$cards): void;
}