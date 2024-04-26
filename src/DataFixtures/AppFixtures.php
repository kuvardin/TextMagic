<?php

namespace App\DataFixtures;

use App\Entity\AnswerVariant;
use App\Entity\Question;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const DATA = [
        [
            'test' => [
                'name' => 'Тест с нечеткой логикой',
                'description' => 'Вводный тест с вопросами с нечеткой логикой',
            ],
            'questions' => [
                [
                    'text' => '2 + 2 = ',
                    'answers' => [
                        [
                            'text' => '4',
                            'correct' => true,
                        ],
                        [
                            'text' => '3+1',
                            'correct' => true,
                        ],
                        [
                            'text' => '10',
                            'correct' => false,
                        ],
                    ],
                ],
                [
                    'text' => '1 + 1 = ',
                    'answers' => [
                        [
                            'text' => '3',
                            'correct' => false,
                        ],
                        [
                            'text' => '2',
                            'correct' => true,
                        ],
                        [
                            'text' => '0',
                            'correct' => false,
                        ],
                    ],
                ],
                [
                    'text' => '3 + 3 = ',
                    'answers' => [
                        [
                            'text' => '1 + 5',
                            'correct' => true,
                        ],
                        [
                            'text' => '1',
                            'correct' => false,
                        ],
                        [
                            'text' => '6',
                            'correct' => true,
                        ],
                        [
                            'text' => '2 + 4',
                            'correct' => true,
                        ],
                    ],
                ],
                [
                    'text' => '4 + 4 = ',
                    'answers' => [
                        [
                            'text' => '8',
                            'correct' => true,
                        ],
                        [
                            'text' => '4',
                            'correct' => false,
                        ],
                        [
                            'text' => '0',
                            'correct' => false,
                        ],
                        [
                            'text' => '0 + 8',
                            'correct' => true,
                        ],
                    ],
                ],
                [
                    'text' => '5 + 5 = ',
                    'answers' => [
                        [
                            'text' => '6',
                            'correct' => false,
                        ],
                        [
                            'text' => '18',
                            'correct' => false,
                        ],
                        [
                            'text' => '10',
                            'correct' => true,
                        ],
                        [
                            'text' => '9',
                            'correct' => false,
                        ],
                        [
                            'text' => '0',
                            'correct' => false,
                        ],
                    ],
                ],
                [
                    'text' => '6 + 6 = ',
                    'answers' => [
                        [
                            'text' => '3',
                            'correct' => false,
                        ],
                        [
                            'text' => '9',
                            'correct' => false,
                        ],
                        [
                            'text' => '0',
                            'correct' => false,
                        ],
                        [
                            'text' => '12',
                            'correct' => true,
                        ],
                        [
                            'text' => '5 + 7',
                            'correct' => false,
                        ],
                    ],
                ],
                [
                    'text' => '7 + 7 = ',
                    'answers' => [
                        [
                            'text' => '5',
                            'correct' => false,
                        ],
                        [
                            'text' => '14',
                            'correct' => true,
                        ],
                    ],
                ],
                [
                    'text' => '8 + 8 = ',
                    'answers' => [
                        [
                            'text' => '16',
                            'correct' => true,
                        ],
                        [
                            'text' => '12',
                            'correct' => false,
                        ],
                        [
                            'text' => '9',
                            'correct' => false,
                        ],
                        [
                            'text' => '5',
                            'correct' => false,
                        ],
                    ],
                ],
                [
                    'text' => '9 + 9 = ',
                    'answers' => [
                        [
                            'text' => '18',
                            'correct' => true,
                        ],
                        [
                            'text' => '9',
                            'correct' => false,
                        ],
                        [
                            'text' => '17 + 1',
                            'correct' => true,
                        ],
                        [
                            'text' => '2 + 16',
                            'correct' => true,
                        ],
                    ],
                ],
                [
                    'text' => '10 + 10 = ',
                    'answers' => [
                        [
                            'text' => '0',
                            'correct' => false,
                        ],
                        [
                            'text' => '2',
                            'correct' => false,
                        ],
                        [
                            'text' => '8',
                            'correct' => false,
                        ],
                        [
                            'text' => '20',
                            'correct' => true,
                        ],
                    ],
                ],
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $test_data) {
            $test = new Test(
                name: $test_data['test']['name'],
                description: $test_data['test']['description'],
            );

            $manager->persist($test);

            foreach ($test_data['questions'] as $question_data) {
                $question = new Question(
                    test: $test,
                    text: $question_data['text'],
                );

                $manager->persist($question);

                foreach ($question_data['answers'] as $answer_data) {
                    $answer_variant = new AnswerVariant(
                        question: $question,
                        text: $answer_data['text'],
                        is_correct: $answer_data['correct'],
                    );

                    $manager->persist($answer_variant);
                }
            }
        }

        $manager->flush();
    }
}
