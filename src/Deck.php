<?php
namespace nuffy\cards;

use nuffy\cards\Card\{Card, Suit, Rank};
use nuffy\cards\ShufflingStrategy\ShufflingStrategyInterface;

/** @package nuffy\cards */
class Deck extends CardCollection
{
    /**
     * Constructor
     * 
     * @param ShufflingStrategyInterface|null $shufflingStrategy
     */

    public function __construct(ShufflingStrategyInterface $shufflingStrategy = null)
    {
        parent::__construct($shufflingStrategy);
    }
}