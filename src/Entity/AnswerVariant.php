<?php

namespace App\Entity;

use App\Repository\AnswerVariantRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerVariantRepository::class)]
class AnswerVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answerVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private Question $question;

    #[ORM\Column(length: 1023)]
    private string $text;

    #[ORM\Column]
    private bool $is_correct;

    #[ORM\Column]
    private DateTimeImmutable $created_at;

    public function __construct(
        Question $question,
        string $text,
        bool $is_correct,
        DateTimeImmutable $created_at = null,
    )
    {
        $this->question = $question;
        $this->text = $text;
        $this->is_correct = $is_correct;
        $this->created_at = $created_at ?? new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        $this->question = $question;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->is_correct;
    }

    public function setCorrect(bool $is_correct): static
    {
        $this->is_correct = $is_correct;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }
}
