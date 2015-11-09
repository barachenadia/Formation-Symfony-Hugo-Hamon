<?php

namespace AppBundle\Game;

use Symfony\Component\EventDispatcher\Event;

class GameEvent extends Event
{
    private $requiredAmountOfCredits;
    private $game;

    public function __construct(Game $game, $requiredAmountOfCredits = 100)
    {
        $this->game = $game;
        $this->requiredAmountOfCredits = (int) $requiredAmountOfCredits;
    }

    public function getRequiredAmountOfCredits()
    {
        return $this->requiredAmountOfCredits;
    }

    public function getGame()
    {
        return $this->game;
    }
}
