<?php
namespace nuffy\cards\tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Depends;
use nuffy\cards\CardCollection;
use nuffy\cards\Card\CardFactory;
use nuffy\cards\ShufflingStrategy\ShufflingStrategyInterface;

class CardCollectionTest extends TestCase
{
    public function testCardCollectionCreation()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        
        // Act

        // Assert
        $this->assertInstanceOf(CardCollection::class, $deck);
    }

    public function testCanAddCardToCollection()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        
        // Act
        $deck->addCard(CardFactory::createFromString('AH'));
        
        // Assert
        $this->assertCount(1, $deck->getRemainingCards());
    }

    #[Depends('testCanAddCardToCollection')]
    public function testCanAddMultipleCardsToCollection()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        
        // Act
        $deck->addCards([
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('3H'),
        ]);
        
        // Assert
        $this->assertCount(3, $deck->getRemainingCards());
    }

    #[Depends('testCanAddMultipleCardsToCollection')]
    public function testCanShuffleCollection()
    {
        // Arrange
        $shufflingStrategy = $this->createMock(ShufflingStrategyInterface::class);
        $shufflingStrategy->expects($this->once())
                          ->method('shuffle')
                          ->with($this->isType('array'));

        $deck = new class($shufflingStrategy) extends CardCollection{};
        $deck->addCards([
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('3H'),
        ]);
        
        // Act
        $deck->shuffle();
        
        // Assert
        $this->assertCount(3, $deck->getRemainingCards());
    }
}