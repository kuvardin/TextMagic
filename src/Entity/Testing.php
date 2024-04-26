<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TestingRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: TestingRepository::class)]
class Testing
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Test $test;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    #[ORM\Column]
    private DateTimeImmutable $created_at;

    public function __construct(
        Test $test,
        DateTimeImmutable $created_at = null,
    )
    {
        $this->id = Uuid::v4();
        $this->test = $test;
        $this->created_at = $created_at ?? new DateTimeImmutable();
    }

    public function getId(): Uuid
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;
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
