<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\ArticleSearchController;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
        'post',
        'put',
        'search_articles' => [
            'method' => 'get',
            'path' => '/articles/search/{search}',
            'controller' => ArticleSearchController::class,
            'normalization_context' => ['groups' => ["read"]],
            "read" => false
        ]
    ],
    denormalizationContext: ["groups" => ["write"]],
    normalizationContext: ["groups" => ["read"]],
)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\SequenceGenerator(sequenceName: "article_id_seq", allocationSize: 1, initialValue: 1)]
    #[ORM\Column(type: 'integer')]
    #[Groups(["write", "read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["write", "read"])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["write", "read"])]
    private $reference;

    #[ORM\Column(type: 'string', length: 1500)]
    #[Groups(["write", "read"])]
    private $content;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["write", "read"])]
    private $draft;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(["write", "read"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(["write", "read"])]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Tag::class, cascade: ["all"])]
    #[Groups(["write", "read"])]
    #[ApiSubresource]
    private $tags;

    #[ORM\OneToMany(mappedBy: "article", targetEntity: Comment::class, cascade: ["all"])]
    #[ApiSubresource]
    #[Groups(["read"])]
    private $comments;

    #[ORM\OneToMany(mappedBy: "article", targetEntity: Reaction::class, cascade: ["all"])]
    #[ApiSubresource]
    #[Groups("read")]
    private $reactions;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ApiSubresource]
    #[Groups(["write", "read"])]
    private $author;

    public function __construct()
    {
        $this->tags      = [];
        $this->comments  = [];
        $this->reactions = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDraft(): ?bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): self
    {
        $this->draft = $draft;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getReactions()
    {
        return $this->reactions;
    }

    /**
     * @return mixed
     */
    #[Groups("read")]
    public function getReactionsCount(): array
    {
        $reactions = [];
        foreach ($this->getReactions() as $reaction) {
            if (!array_key_exists($reaction->getType(), $reactions)) {
                $reactions[$reaction->getType()] = 1;
            } else {
                $reactions[$reaction->getType()]++;
            }
        }

        return $reactions;
    }

    /**
     * @param mixed $reactions
     */
    public function setReactions($reactions): void
    {
        $this->reactions = $reactions;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    #[Groups("read")]
    public function getAuthorUsername()
    {
        return $this->getAuthor()->getUsername();
    }

    /**
     * @return mixed
     */
    #[Groups("read")]
    public function getAuthorId()
    {
        return $this->getAuthor()->getId();
    }

}
