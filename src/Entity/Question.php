<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private Test $test;

    #[ORM\Column(length: 1023)]
    private string $text;

    #[ORM\Column]
    private DateTimeImmutable $created_at;

    /**
     * @var Collection<int, AnswerVariant>
     */
    #[ORM\OneToMany(targetEntity: AnswerVariant::class, mappedBy: 'question', orphanRemoval: true)]
    private Collection $answerVariants;

    public function __construct(
        Test $test,
        string $text,
        DateTimeImmutable $created_at = null,
    )
    {
        $this->test = $test;
        $this->text = $text;
        $this->created_at = $created_at ?? new DateTimeImmutable();
        $this->answerVariants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): Test
    {
        return $this->test;
    }

    public function setTest(Test $test): static
    {
        $this->test = $test;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return Collection<int, AnswerVariant>
     */
    public function getAnswerVariants(): Collection
    {
        return $this->answerVariants;
    }

    public function addAnswerVariant(AnswerVariant $answerVariant): static
    {
        if (!$this->answerVariants->contains($answerVariant)) {
            $this->answerVariants->add($answerVariant);
            $answerVariant->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswerVariant(AnswerVariant $answerVariant): static
    {
        if ($this->answerVariants->removeElement($answerVariant)) {
            // set the owning side to null (unless already changed)
            if ($answerVariant->getQuestion() === $this) {
                $answerVariant->setQuestion(null);
            }
        }

        return $this;
    }
}
