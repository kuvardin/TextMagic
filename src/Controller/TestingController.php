<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Test;
use App\Entity\Testing;
use App\Repository\TestingRepository;
use App\Repository\TestRepository;
use App\Utility\StrictRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class TestingController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entity_manager,
    )
    {

    }

    #[Route('/', name: 'select_test')]
    public function index(): Response
    {
        /** @var TestRepository $tests_repository */
        $tests_repository = $this->entity_manager->getRepository(Test::class);

        $tests = $tests_repository->findAll();

        return $this->render('testing/index.html.twig', [
            'tests' => $tests,
        ]);
    }

    #[Route('/tests/{test_id}', name: 'testing_redirect')]
    public function test(int $test_id): Response
    {
        /** @var TestRepository $test_repository */
        $test_repository = $this->entity_manager->getRepository(Test::class);

        $test = $test_repository->find($test_id);
        if ($test === null) {
            return new Response(status: 404);
        }

        $testing = new Testing(
            test: $test,
        );

        $this->entity_manager->persist($testing);
        $this->entity_manager->flush();

        return $this->redirect("/testing/{$testing->getId()}");
    }

    #[Route('/testing/{uuid}', name: 'testing', methods: ['GET', 'POST'])]
    public function testing(string $uuid, Request $request): Response
    {
        $strict_request = new StrictRequest($request);
        $uuid_obj = Uuid::fromString($uuid);

        /** @var TestingRepository $testing_repository */
        $testing_repository = $this->entity_manager->getRepository(Testing::class);

        $testing = $testing_repository->findOneBy(['id' => $uuid_obj]);
        if ($testing === null) {
            return new Response(status: 404);
        }

        $test = $testing->getTest();

        /** @var Question[] $questions */
        $questions = $test->getQuestions()->toArray();
        shuffle($questions);

        $questions_associative = [];
        foreach ($questions as $question) {
            $questions_associative[$question->getId()] = $question;
        }


        if ($testing->getScore() !== null) {
            return $this->render('testing/testing_finished.html.twig', [
                'test' => $test,
                'testing' => $testing,
                'questions' => $questions,
            ]);
        }

        $answers = $strict_request->getArray('answers');
        if (!empty($answers)) {
            $score = 0;

            foreach ($answers as $question_id => $answer_ids) {
                if ($answer_ids === []) {
                    continue;
                }

                $question = $questions_associative[$question_id] ?? null;
                if ($question === null) {
                    continue;
                }

                $is_correct = true;
                foreach ($answer_ids as $answer_id) {
                    $answer = null;

                    $question_answer_variants = $question->getAnswerVariants()->toArray();
                    foreach ($question_answer_variants as $answer_variant) {
                        if ($answer_variant->getId() === (int)$answer_id) {
                            $answer = $answer_variant;
                            break;
                        }
                    }

                    if ($answer === null) {
                        continue;
                    }

                    if (!$answer->isCorrect()) {
                        $is_correct = false;
                    }
                }

                if ($is_correct) {
                    $score++;
                }
            }

            $testing->setScore($score);
            $this->entity_manager->flush();

            return $this->render('testing/testing_success.html.twig', [
                'test' => $test,
                'testing' => $testing,
                'score' => $score,
            ]);
        }

        return $this->render('testing/testing.html.twig', [
            'test' => $test,
            'testing' => $testing,
            'questions' => $questions,
            'current_timestamp' => time(),
        ]);
    }

}
