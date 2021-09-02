<?php
namespace nuffy\cards\Card;

/** @package nuffy\cards\Card */
interface CardInterface
{
    /** @return RankInterface  */
    public function getRank() : RankInterface;
    /** @return SuitInterface  */
    public function getSuit() : SuitInterface;
}