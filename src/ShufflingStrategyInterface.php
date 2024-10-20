<?php
namespace nuffy\cards;

interface ShufflingStrategyInterface
{
    public function shuffle(array &$cards): void;
}