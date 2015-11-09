<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * This action displays the game main board.
     *
     * @Route("/", name="app_game_play")
     * @Method("GET")
     */
    public function playAction()
    {
        $game = $this->get('app.game_runner')->loadGame();

        return $this->render('game/play.html.twig', [ 'game' => $game ]);
    }

    /**
     * This action enables to play a single letter.
     *
     * @Route(
     *   path="/letter/{letter}",
     *   name="app_game_play_letter",
     *   requirements={
     *     "letter"="[a-z]"
     *   }
     * )
     * @Method("GET")
     */
    public function playLetterAction($letter)
    {
        $game = $this->get('app.game_runner')->playLetter($letter);

        if (!$game->isOver()) {
            return $this->redirectToRoute('app_game_play');
        }

        return $this->redirectToRoute($game->isWon() ? 'app_game_win' : 'app_game_lose');
    }

    /**
     * This action plays a word.
     *
     * @Route(
     *   path="/play",
     *   name="app_game_play_word",
     *   condition="request.request.get('word') matches '/^[a-z]+$/i'"
     * )
     * @Method("POST")
     */
    public function playWordAction(Request $request)
    {
        $game = $this->get('app.game_runner')->playWord($request->request->get('word'));

        return $this->redirectToRoute($game->isWon() ? 'app_game_win' : 'app_game_lose');
    }

    /**
     * This action resets the current game.
     *
     * @Route("/reset", name="app_game_reset")
     * @Method("GET")
     */
    public function resetAction()
    {
        $this->get('app.game_runner')->resetGame();

        return $this->redirectToRoute('app_game_play');
    }

    /**
     * This action displays the congratulation page.
     *
     * @Route("/win", name="app_game_win")
     * @Method("GET")
     */
    public function winAction()
    {
        $game = $this->get('app.game_runner')->resetGameOnSuccess();

        return $this->render('game/win.html.twig', [ 'game' => $game ]);
    }

    /**
     * This action displays the "Game Over" page.
     *
     * @Route("/lose", name="app_game_lose")
     * @Method("GET")
     */
    public function loseAction()
    {
        $game = $this->get('app.game_runner')->resetGameOnFailure();

        return $this->render('game/lose.html.twig', [ 'game' => $game ]);
    }
}
