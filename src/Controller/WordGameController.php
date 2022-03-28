<?php

namespace App\Controller;

use App\ddd\Application\DataTransformer\Game\GameDtoDataTransformer;
use App\ddd\Application\Service\Game\GameService;
use App\ddd\Application\Service\Game\WordRequest;
use App\ddd\Application\Service\PspellDictionaryService;
use App\ddd\Application\Service\TransactionalApplicationService;
use App\ddd\Domain\Model\Game\Game;
use App\ddd\Domain\Model\Game\GameId;
use App\ddd\Domain\Model\Game\GameStatus;
use App\ddd\Infrastructure\Application\Service\DoctrineSession;
use App\ddd\Infrastructure\Domain\Model\Game\GameRepository as InfraGameRepository;
use App\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class WordGameController extends AbstractController
{
    #[Route('/', name: 'word_game_homepage', methods: ['GET', 'POST'])]
    public function game(
        Request $request,
        InfraGameRepository $gameRepository,
        GameDtoDataTransformer $gameDtoDataTransformer,
        PspellDictionaryService $dictionaryService,
        DoctrineSession $doctrineSession
    ): Response
    {
        $gameArr = [];

        $wordGame = new Game(
            new GameId(),
            '',
            TRUE
        );
        $form = $this->createForm(GameType::class, $wordGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $word = $form->getData()->getWordContent();

            if (strlen($word) === 0) {
                $this->addFlash('error', "Word in this game can't be empty!");
                return $this->redirectToRoute('word_game_homepage', [], Response::HTTP_SEE_OTHER);
            }

            $gameService = new GameService($gameRepository, $gameDtoDataTransformer, $dictionaryService);
            $txAppService = new TransactionalApplicationService($gameService, $doctrineSession);
            $gameArr = $txAppService->execute(
                new WordRequest($word)
            );

            if (!empty($gameArr['existing']) && $gameArr['existing'] === GameStatus::Existing) {
                $this->addFlash('error', "Word already checked!");
            }
        }

        return $this->renderForm('word_game/game.html.twig', [
            'word_processor' => $wordGame,
            'form' => $form,
            'submited_word' => $word ?? NULL,
            'length_points' => $gameArr['points'] ?? 0,
            'extra_points' => (!empty($gameArr['existing']) && $gameArr['existing'] === GameStatus::New) ? 'yes' : 'no',
        ]);
    }

    #[Route('/words', name: 'word_game_index', methods: ['GET', 'POST'])]
    public function words(InfraGameRepository $gameRepository): Response
    {
        return $this->render('word_game/index.html.twig', [
            'words' => $gameRepository->findAllWords(),
        ]);
    }

}
