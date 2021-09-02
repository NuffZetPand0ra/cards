<?php
namespace nuffy\cards\Card;

/** @package nuffy\cards\Card */
class Suit implements SuitInterface
{
    public const SUITS = [
        "C",
        "D",
        "H",
        "S"
    ];

    protected const SYMBOLS = [
        "C"=>"♧",
        "D"=>"♢",
        "H"=>"♡",
        "S"=>"♤"
    ];

    /**
     * Creates new suit
     * 
     * @param string $suit Must be string from Suit::SUITS
     * @return void 
     * @throws CardException 
     */
    public function __construct(
        protected string $suit
    ){
        $suit = strtoupper($suit);
        if(!in_array($suit, self::SUITS)) throw new CardException("$suit is not a valid suit");
        $this->suit = $suit;
    }

    public function __toString() : string
    {
        return $this->getString();
    }

    public static function create(string $suit) : self
    {
        return new self($suit);
    }

    public function getString() : string
    {
        return $this->suit;
    }

    public function getValue() : int
    {
        return array_search($this->suit, self::SUITS);
    }

    public function getSymbol() : string
    {
        return self::SYMBOLS[$this->suit];
    }
}