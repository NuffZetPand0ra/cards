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

        // Assert
        $this->assertEquals('A', $cardRankString);
        $this->assertEquals(12, $cardRankValue);
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

        // Assert
        $this->assertEquals('H', $cardSuitString);
        $this->assertEquals('♡', $cardSuitSymbol);
        $this->assertEquals(2, $cardSuitValue);
    }

    public function testCardStringRepresentation()
    {
        // Arrange
        $rank = Rank::create('A');
        $suit = Suit::create('H');
        $card = new Card($rank, $suit);

        // Act
        $cardString = (string)$card;

        // Assert
        $this->assertEquals('A♡', $cardString);
    }

    public function testCardEquality()
    {
        // Arrange
        $rank1 = Rank::create('A');
        $suit1 = Suit::create('H');
        $card1 = new Card($rank1, $suit1);

        $rank2 = Rank::create('A');
        $suit2 = Suit::create('H');
        $card2 = new Card($rank2, $suit2);

        // Act & Assert
        $this->assertTrue($card1 == $card2);
    }

    public function testCardInequality()
    {
        // Arrange
        $rank1 = Rank::create('A');
        $suit1 = Suit::create('H');
        $card1 = new Card($rank1, $suit1);

        $rank2 = Rank::create('K');
        $suit2 = Suit::create('S');
        $card2 = new Card($rank2, $suit2);

        // Act & Assert
        $this->assertFalse($card1 == $card2);
    }
}