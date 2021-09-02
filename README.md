# Cards

## Dependency-free abstraction layer for handling playing cards and decks/shoes

Quick and dirty example:

```php
use nuffy\cards\DeckFactory;
use nuffy\cards\Card\CardFactory;

$deck = DeckFactory::createNormalDeck();
$top_card = $deck->draw();
$king_of_spades = $deck->drawSpecific(CardFactory::createFromString('KS'));
```