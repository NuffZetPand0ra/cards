<?php
namespace nuffy\cards;

use nuffy\cards\Card\{Card, CardInterface};
use nuffy\cards\ShufflingStrategy\{ShufflingStrategyInterface, DefaultShufflingStrategy};

/** @package nuffy\cards */
abstract class CardCollection implements \Iterator, \Countable
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
     * Shuffling strategy
     * 
     * @var ShufflingStrategyInterface
     */
    protected $shufflingStrategy;

    /**
     * Constructor
     * 
     * @param ShufflingStrategyInterface|null $shufflingStrategy
     */
    public function __construct(ShufflingStrategyInterface $shufflingStrategy = null)
    {
        $this->shufflingStrategy = $shufflingStrategy ?: new DefaultShufflingStrategy();
    }

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
            if(!$card instanceof CardInterface) throw new CardCollectionException("Card does not implement CardInterface");
            $this->addCard($card);
        }

        return $this;
    }

    /**
     * Shuffles deck
     * 
     * @param bool $only_remaining_cards Set this to false to rewind the collection first
     * @return static 
     */
    public function shuffle(bool $only_remaining_cards = true) : static
    {
        if(!$only_remaining_cards) $this->rewind();

        $this->shufflingStrategy->shuffle($this->remaining_cards);
        $this->rewind();

        return $this;
    }

    /**
     * Draws a card from collection
     * 
     * @param int $position What position you want to draw
     * @return CardInterface 
     * @throws CardCollectionException 
     */
    public function draw(int $position = 0) : CardInterface
    {
        if(count($this->remaining_cards) === 0) throw new CardCollectionException('There are no cards to draw.');

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
     * @throws CardCollectionException 
     */
    public function drawSpecific(CardInterface $card) : CardInterface
    {
        $card_to_find = $this->search($card);
        if($card_to_find !== null){
            return $this->draw($card_to_find);
        }
        throw new CardCollectionException("Couldn't find card $card");
    }

    /**
     * Draws multiple cards
     * 
     * @param int $amount How many cards to draw
     * @param int $position Position to draw from
     * @return CardInterface[] 
     * @throws CardCollectionException 
     */
    public function drawMultiple(int $amount, int $position = 0) : array
    {
        if(count($this->remaining_cards) < $amount + $position) throw new CardCollectionException('Not enough cards in collection.');
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
        $this->drawn_cards = [...$this->drawn_cards, ...$this->remaining_cards];
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
        usort($this->remaining_cards, $sort ?: [$this, 'defaultSort']);
        return $this;
    }

    private function defaultSort(CardInterface $a, CardInterface $b) : int
    {
        if($a->getSuit()->getValue() == $b->getSuit()->getValue()){
            return $b->getRank()->getValue() <=> $a->getRank()->getValue();
        }
        return $b->getSuit()->getValue() <=> $a->getSuit()->getValue();
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

    public function key() : int
    {
        return count($this->drawn_cards);
    }

    public function next() : void
    {
        $this->draw();
    }

    public function rewind() : void
    {
        $this->remaining_cards = [...$this->drawn_cards, ...$this->remaining_cards];
        $this->flushDraws();
    }

    public function valid() : bool
    {
        return isset($this->remaining_cards[0]);
    }
}