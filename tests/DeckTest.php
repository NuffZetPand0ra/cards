<?php
namespace nuffy\cards\tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Depends;
use nuffy\cards\Deck;
use nuffy\cards\CardCollection;
use nuffy\cards\Card\CardFactory;

class DeckTest extends TestCase
{
    public function testDeckCreation()
    {
        $deck = new Deck();
        $this->assertInstanceOf(Deck::class, $deck);
        $this->assertInstanceOf(CardCollection::class, $deck);
    }
}