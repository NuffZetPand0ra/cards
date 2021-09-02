<?php
namespace nuffy\cards\Card;

interface SuitInterface
{
    public function getString() : string;
    public function getValue() : int;
    public function getSymbol() : string;
}