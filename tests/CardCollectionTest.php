<?php
namespace nuffy\cards\tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Depends;
use nuffy\cards\CardCollection;
use nuffy\cards\Card\CardFactory;
use nuffy\cards\CardCollectionException;
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

    #[Depends('testCanAddCardToCollection', 'testCanAddMultipleCardsToCollection')]
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
    
    public function testDrawFromEmptyCollection()
    {
        // Arrange
        $deck = new class extends CardCollection{};

        // Assert
        $this->expectException(CardCollectionException::class);

        // Act
        $deck->draw();
    }

    public function testDrawFromNonEmptyCollection()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $card = CardFactory::createFromString('AH');
        $deck->addCard($card);

        // Act
        $drawnCard = $deck->draw();

        // Assert
        $this->assertSame($card, $drawnCard);
        $this->assertCount(0, $deck->getRemainingCards());
    }

    public function testDrawSpecificCardExists()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $card = CardFactory::createFromString('AH');
        $deck->addCard($card);

        // Act
        $drawnCard = $deck->drawSpecific($card);

        // Assert
        $this->assertSame($card, $drawnCard);
        $this->assertCount(0, $deck->getRemainingCards());
    }

    public function testDrawSpecificCardNotExists()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $card = CardFactory::createFromString('AH');

        // Assert
        $this->expectException(CardCollectionException::class);

        // Act
        $deck->drawSpecific($card);
    }

    public function testDrawMultipleCards()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $cards = [
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('3H'),
        ];
        $deck->addCards($cards);

        // Act
        $drawnCards = $deck->drawMultiple(2);

        // Assert
        $this->assertCount(2, $drawnCards);
        $this->assertCount(1, $deck->getRemainingCards());
    }

    public function testDrawMultipleCardsMoreThanAvailable()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $cards = [
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
        ];
        $deck->addCards($cards);

        // Assert
        $this->expectException(CardCollectionException::class);

        // Act
        $deck->drawMultiple(3);
    }

    public function testDrawRemainingCards()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $cards = [
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('3H'),
        ];
        $deck->addCards($cards);

        // Act
        $remainingCards = $deck->drawRemaining();

        // Assert
        $this->assertCount(3, $remainingCards);
        $this->assertCount(0, $deck->getRemainingCards());
    }

    public function testFlushDraws()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $cards = [
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
        ];
        $deck->addCards($cards);
        $deck->draw();

        // Act
        $deck->flushDraws();

        // Assert
        $this->assertCount(0, $deck->getDrawnCards());
    }

    public function testSearchCardExists()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $card = CardFactory::createFromString('AH');
        $deck->addCard($card);

        // Act
        $position = $deck->search($card);

        // Assert
        $this->assertSame(0, $position);
    }

    public function testSearchCardNotExists()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $card = CardFactory::createFromString('AH');

        // Act
        $position = $deck->search($card);

        // Assert
        $this->assertNull($position);
    }

    public function testSortWithCustomFunction()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $cards = [
            CardFactory::createFromString('3H'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('AH'),
        ];
        $deck->addCards($cards);

        // Act
        $deck->sort(function($a, $b) {
            return $a->getRank()->getValue() <=> $b->getRank()->getValue();
        });

        // Assert
        $sortedCards = $deck->getRemainingCards();
        $this->assertEquals(CardFactory::createFromString('2H'), $sortedCards[0]);
        $this->assertEquals(CardFactory::createFromString('3H'), $sortedCards[1]);
        $this->assertEquals(CardFactory::createFromString('AH'), $sortedCards[2]);
    }

    public function testSortWithDefaultFunction()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $cards = [
            CardFactory::createFromString('3H'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('AH'),
        ];
        $deck->addCards($cards);

        // Act
        $deck->sort();

        // Assert
        $sortedCards = $deck->getRemainingCards();
        $this->assertEquals(CardFactory::createFromString('AH'), $sortedCards[0]);
        $this->assertEquals(CardFactory::createFromString('3H'), $sortedCards[1]);
        $this->assertEquals(CardFactory::createFromString('2H'), $sortedCards[2]);
    }

    public function testCount()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $deck->addCards([
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
            CardFactory::createFromString('3H'),
        ]);

        // Act
        $count = $deck->count();

        // Assert
        $this->assertEquals(3, $count);
    }

    public function testCurrent()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $card = CardFactory::createFromString('AH');
        $deck->addCard($card);

        // Act
        $currentCard = $deck->current();

        // Assert
        $this->assertSame($card, $currentCard);
    }

    public function testKey()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $deck->addCards([
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
        ]);
        $deck->draw();

        // Act
        $key = $deck->key();

        // Assert
        $this->assertEquals(1, $key);
    }

    public function testNext()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $deck->addCards([
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
        ]);

        // Act
        $deck->next();
        $remainingCards = $deck->getRemainingCards();

        // Assert
        $this->assertCount(1, $remainingCards);
    }

    public function testRewind()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $deck->addCards([
            CardFactory::createFromString('AH'),
            CardFactory::createFromString('2H'),
        ]);
        $deck->draw();

        // Act
        $deck->rewind();
        $remainingCards = $deck->getRemainingCards();

        // Assert
        $this->assertCount(2, $remainingCards);
    }

    public function testValid()
    {
        // Arrange
        $deck = new class extends CardCollection{};
        $deck->addCard(CardFactory::createFromString('AH'));

        // Act & Assert
        $this->assertTrue($deck->valid());

        // Draw the only card
        $deck->draw();

        // Act & Assert
        $this->assertFalse($deck->valid());
    }
}
