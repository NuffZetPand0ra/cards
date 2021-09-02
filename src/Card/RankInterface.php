<?php
namespace nuffy\cards\Card;

interface RankInterface
{
    public function getString() : string;
    public function getValue() : int;
}