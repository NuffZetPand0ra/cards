# Cards

A dependency-free PHP library for handling playing cards, providing an object-oriented abstraction layer for cards, decks, and card collections.

## Features

- Create and manage standard 52-card decks
- Draw cards from specific positions (top, specific index)
- Draw specific cards by value
- Draw multiple cards at once
- Manage drawn vs. remaining cards
- Shuffle cards using customizable shuffling strategies
- Iterate through cards (implements Iterator interface)
- Card comparison and validation
- Sort cards in various ways
- No external dependencies

## Installation

```sh
composer require nuffy/cards
```

## Basic Usage

```php
use nuffy\cards\DeckFactory;
use nuffy\cards\Card\CardFactory;

// Create a standard 52-card deck
$deck = DeckFactory::createNormalDeck();

// Shuffle the deck
$deck->shuffle();

// Draw the top card
$top_card = $deck->draw();

// Draw specific card if it exists in the deck
$king_of_spades = $deck->drawSpecific(CardFactory::createFromString('KS'));
```

## Detailed Usage Guide

### Card Notation

Cards are represented using a two-character string notation:

```
[Rank][Suit]
```

- **Rank**: 2-9, T (Ten), J (Jack), Q (Queen), K (King), A (Ace)
- **Suit**: S (Spades), H (Hearts), D (Diamonds), C (Clubs)

Examples:
- `AS` = Ace of Spades  
- `TH` = Ten of Hearts (note: Ten uses 'T', not '10')
- `QD` = Queen of Diamonds
- `2C` = Two of Clubs

### Working with Cards

```php
use nuffy\cards\Card\CardFactory;
use nuffy\cards\Card\Rank;
use nuffy\cards\Card\Suit;
use nuffy\cards\Card\Card;

// Create a card using the factory
$ace_of_spades = CardFactory::createFromString('AS');

// Create a card directly
$king_of_hearts = new Card(Rank::create('K'), Suit::create('H'));

// Get card components
$rank = $ace_of_spades->getRank();
$suit = $ace_of_spades->getSuit();

// Cards have string representation
echo $ace_of_spades; // Outputs: A♤
```

### Creating and Managing Decks

```php
use nuffy\cards\DeckFactory;
use nuffy\cards\Card\CardFactory;

// Create a standard 52-card deck
$deck = DeckFactory::createNormalDeck();

// Shuffle the deck
$deck->shuffle();

// Count remaining cards
$count = count($deck); // or $deck->count();

// Draw the top card
$top_card = $deck->draw();

// Draw from a specific position (0-based index)
$card_at_position_5 = $deck->draw(5);

// Draw multiple cards at once
$five_cards = $deck->drawMultiple(5);

// Draw multiple cards starting from a specific position
$three_cards_from_pos_2 = $deck->drawMultiple(3, 2);

// Draw all remaining cards
$all_remaining = $deck->drawRemaining();

// Check drawn cards
$drawn = $deck->getDrawnCards();

// Get remaining cards
$remaining = $deck->getRemainingCards();

// Return all drawn cards back to the deck
$deck->rewind();

// Only clear the list of drawn cards (don't return them to deck)
$deck->flushDraws();

// Search for a specific card (returns position or null if not found)
$position = $deck->search(CardFactory::createFromString('JD'));

// Sort the deck (default sort or custom sorting function)
$deck->sort();
// Or with custom sort function
$deck->sort(function($a, $b) {
    // Custom sort logic
    return $a->getRank()->getValue() <=> $b->getRank()->getValue();
});
```

### Iterating Through a Deck

The `Deck` class implements PHP's `Iterator` interface, allowing iteration:

```php
$deck = DeckFactory::createNormalDeck();

foreach ($deck as $key => $card) {
    // Each iteration draws the top card
    echo "Position $key: $card\n";
    
    // After this loop, all cards will be in the "drawn" pile
}
```

### Custom Shuffling Strategies

The library uses the Strategy pattern for shuffling:

```php
use nuffy\cards\DeckFactory;
use nuffy\cards\Deck;
use nuffy\cards\ShufflingStrategy\ShufflingStrategyInterface;

// Create a custom shuffling strategy
class MyShufflingStrategy implements ShufflingStrategyInterface {
    public function shuffle(array &$cards): void {
        // Custom shuffling logic
    }
}

// Create a deck with custom shuffling
$deck = new Deck(new MyShufflingStrategy());
$deck->shuffle();
```

## Requirements

- PHP 8.0 or higher

## Documentation

If you have PHPDocumentor, you can generate the API documentation with:

```sh
composer run-script update-docs
```

Then find the documentation in the `./docs` directory.

## License

MIT License

## Author

NuffZetPand0ra (esbentind@gmail.com)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.