<?php
namespace nuffy\cards;

use nuffy\cards\Card\{Card, CardInterface};

/** @package nuffy\cards */
abstract class CardCollection implements \Iterator
{
    /**
     * Drawn cards
     * 
     * @var CardInterface[]
     */
    protected $drawn_cards = [];
    /**
     * Cards remaining in collection
     * 
     * @var CardInterface[]
     */
    protected $remaining_cards = [];

    /**
     * Adds a card to the remaining cards in the collection
     * 
     * @param CardInterface $card 
     * @return static 
     */
    public function addCard(CardInterface $card) : static
    {
        $this->remaining_cards[] = $card;

        return $this;
    }

    /**
     * Add multiple cards to collection.
     * 
     * @param CardInterface[] $cards 
     * @return static 
     */
    public function addCards(array $cards) : static
    {
        foreach($cards as $card){
            if(!$card instanceof CardInterface) throw new DeckException("Card does not implement CardInterface");
            $this->addCard($card);
        }

        return $this;
    }

    /**
     * Shuffles deck (using array_shuffle)
     * 
     * @param bool $only_remaining_cards Set this to false to rewind the collection first
     * @return static 
     */
    public function shuffle(bool $only_remaining_cards = true) : static
    {
        if(!$only_remaining_cards) $this->rewind();

        \shuffle($this->remaining_cards);
        $this->rewind();

        return $this;
    }

    /**
     * Draws a card from collection
     * 
     * @param int $position What position you want to draw
     * @return CardInterface 
     * @throws DeckException 
     */
    public function draw(int $position = 0) : CardInterface
    {
        if(count($this->remaining_cards) === 0) throw new DeckException('There are no cards to draw.');

        if($position == 0) return $this->drawn_cards[] = array_shift($this->remaining_cards);

        $return = $this->remaining_cards[$position];
        $this->drawn_cards[] = $this->remaining_cards[$position];
        unset($this->remaining_cards[$position]);
        $this->remaining_cards = array_values($this->remaining_cards);
        return $return;
    }

    /**
     * Draws specific card if found
     * 
     * @param CardInterface $card Card to draw
     * @return CardInterface 
     * @throws DeckException 
     */
    public function drawSpecific(CardInterface $card) : CardInterface
    {
        $card_to_find = $this->search($card);
        if($card_to_find){
            return $this->draw($card_to_find);
        }
        throw new DeckException("Couldn't find card $card");
    }

    /**
     * Draws multiple cards
     * 
     * @param int $amount How many cards to draw
     * @param int $position Position to draw from
     * @return CardInterface[] 
     * @throws DeckException 
     */
    public function drawMultiple(int $amount, int $position = 0) : array
    {
        if(count($this->remaining_cards) < $amount + $position) throw new DeckException('Not enough cards in collection.');
        $return = [];
        for($i = 0; $i < $amount; $i++){
            $return[] = $this->draw($position);
        }
        return $return;
    }

    /**
     * Returns remaining cards as indexed array
     * 
     * @return CardInterface[]
     */
    public function getRemainingCards() : array
    {
        return $this->remaining_cards;
    }

    /**
     * Returns drawn cards as indexed array
     * 
     * @return CardInterface[]
     */
    public function getDrawnCards() : array
    {
        return $this->drawn_cards;
    }

    /**
     * Draws and returns remaining cards
     * 
     * @return CardInterface[]
     */
    public function drawRemaining() : array
    {
        $remaining_cards = $this->remaining_cards;
        $this->drawn_cards = array_merge($this->drawn_cards, $this->remaining_cards);
        $this->remaining_cards = [];
        return $remaining_cards;
    }

    /**
     * "Undraws" the drawn cards in this collection
     * 
     * @return static 
     */
    public function flushDraws() : static
    {
        $this->drawn_cards = [];
        
        return $this;
    }

    /**
     * Search for a specific card
     * 
     * @param CardInterface $card_to_find 
     * @return null|int Card position if found
     */
    public function search(CardInterface $card_to_find) : ?int
    {
        $cards_to_search = array_values($this->remaining_cards);
        foreach($cards_to_search as $i=>$card_in_deck){
            if($card_in_deck == $card_to_find) return $i;
        }

        return null;
    }

    /**
     * Sorts the collection
     * 
     * @param null|callable $sort Function to sort collection, using usort()
     * @return static 
     */
    public function sort(?callable $sort = null) : static
    {
        if($sort){
            $sort($this->remaining_cards);
        }else{
            usort($this->remaining_cards, function(CardInterface $a, CardInterface $b){
                if($a->getSuit()->getValue() == $b->getSuit()->getValue()){
                    return $a->getRank()->getValue() <=> $b->getRank()->getValue();
                }
                return $a->getSuit()->getValue() <=> $b->getSuit()->getValue();
            });
        }
        return $this;
    }

    /**
     * Counts remaining cards
     * 
     * @return int 
     */
    public function count() : int
    {
        return count($this->remaining_cards);
    }



    /**
     * Implementation of Traversible
     */

    public function current() : CardInterface
    {
        return $this->remaining_cards[0];
    }

    public function key()
    {
        return count($this->drawn_cards);
    }

    public function next()
    {
        $this->draw();
    }

    public function rewind()
    {
        $this->remaining_cards = array_merge($this->drawn_cards, $this->remaining_cards);
        $this->flushDraws();
    }

    public function valid() : bool
    {
        return isset($this->remaining_cards[0]);
    }
}