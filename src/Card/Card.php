<?php
namespace nuffy\cards\Card;

/** @package nuffy\cards\Card */
class Card
{
    /**
     * Creates a new card
     * 
     * @param Rank $rank 
     * @param Suit $suit 
     * @return void 
     */
    public function __construct(
        public Rank $rank,
        public Suit $suit
    ){}

    public function __toString() : string
    {
        return $this->rank.$this->suit->getSymbol();
    }

    /**
     * Get the suit of the card
     * 
     * @return Suit 
     */
    public function getSuit() : Suit
    {
        return $this->suit;
    }

    /**
     * Get the rank of the card
     * 
     * @return Rank 
     */
    public function getRank() : Rank
    {
        return $this->rank;
    }
}