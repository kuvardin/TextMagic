<?php

namespace App\Entity;

use App\Repository\TestEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestEntityRepository::class)]
class TestEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $test_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTestId(): ?int
    {
        return $this->test_id;
    }

    public function setTestId(int $test_id): static
    {
        $this->test_id = $test_id;

        return $this;
    }
}
