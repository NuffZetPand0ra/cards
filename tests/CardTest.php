<?php
namespace nuffy\cards\tests;

use PHPUnit\Framework\TestCase;
use nuffy\cards\Deck;
use nuffy\cards\Card\{Card, Rank, Suit};

class CardTest extends TestCase
{
    public function testCardCreation()
    {
        // Arrange
        $rank = Rank::create('A');
        $suit = Suit::create('H');

        // Act
        $card = new Card($rank, $suit);

        // Assert
        $this->assertInstanceOf(Card::class, $card);
    }

    public function testCardRank()
    {
        // Arrange
        $rank = Rank::create('A');
        $suit = Suit::create('H');
        $card = new Card($rank, $suit);

        // Act
        $cardRankString = $card->getRank()->getString();
        $cardRankValue = $card->getRank()->getValue();
        $cardString = (string)$card;

        // Assert
        $this->assertEquals('A', $cardRankString);
        $this->assertEquals(12, $cardRankValue);
        $this->assertStringContainsString('A', $cardString);
    }

    public function testCardSuit()
    {
        // Arrange
        $rank = Rank::create('A');
        $suit = Suit::create('H');
        $card = new Card($rank, $suit);

        // Act
        $cardSuitString = $card->getSuit()->getString();
        $cardSuitSymbol = $card->getSuit()->getSymbol();
        $cardSuitValue = $card->getSuit()->getValue();
        $cardString = (string)$card;

        // Assert
        $this->assertEquals('H', $cardSuitString);
        $this->assertEquals('♡', $cardSuitSymbol);
        $this->assertEquals(2, $cardSuitValue);
        $this->assertStringContainsString('♡', $cardString);
    }
}