<?php

namespace App\Entity;

use App\Repository\ReponsesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponsesRepository::class)]
class Reponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\ManyToOne(inversedBy: 'reponses')]
    // #[ORM\JoinColumn(name:'the_answer', referencedColumnName:'id')]
    // private ?Questions $formAnswer = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Questions $question = null;

    //(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP', 'nullable' => true])
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $laReponse = null;

    #[ORM\Column(nullable: true)]
    private ?string $formNumber = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reponseIA = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getformAnswer(): ?Questions
    // {
    //     return $this->formAnswer;
    // }

    // public function setformAnswer(?Questions $formAnswer): self
    // {
    //     $this->formAnswer = $formAnswer;

    //     return $this;
    // }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestion(): ?Questions
    {
        return $this->question;
    }

    public function setQuestion(?Questions $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLaReponse(): ?string
    {
        return $this->laReponse;
    }

    public function setLaReponse(?string $laReponse): self
    {
        $this->laReponse = $laReponse;

        return $this;
    }

    public function getFormNumber(): ?string
    {
        return $this->formNumber;
    }

    public function setFormNumber(?string $formNumber): self
    {
        $this->formNumber = $formNumber;

        return $this;
    }

    public function getReponseIA(): ?string
    {
        return $this->reponseIA;
    }

    public function setReponseIA(?string $reponseIA): self
    {
        $this->reponseIA = $reponseIA;

        return $this;
    }

}
