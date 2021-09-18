<?php

namespace App\Entity;

use App\Entity\Conference;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ApiResource(
 * shortName="commentaire",
 * collectionOperations={
 *    "get"={"security"="is_granted('ROLE_ADMIN')"},
 *    "post"},
 * itemOperations={
 *    "get"={"security"="is_granted('ROLE_USER')"},
 *    "put",
 *    "patch",
 *    "delete"={"security"="is_granted('ROLE_ADMIN')"}},
 * normalizationContext={"groups"={"comment:read"}},
 * denormalizationContext={"groups"={"comment:write"}},
 * attributes={
 *  "pagination_items_per_page"=2,
 *  "formats"={"jsonld","json","html","csv"={"text/csv"},"jsonhal"}
 * }
 * )
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"comment:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"comment:read","comment:write"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min=5,
     *  minMessage="L'auteur doit contenir au moins 5 caractères",
     *  max=50,
     *  maxMessage="L'auteur doit contenir au plus 50 caractères"
     * )
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comment:read","comment:write"})
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"comment:read","comment:write"})
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"comment:read","comment:write"})
     * @Assert\Range(
     *  min=1,
     *  max=5,
     *  notInRangeMessage="You must be between {{ min }} and {{ max }} to enter"
     * )
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Conference::class, inversedBy="comments")
     * @Groups({"comment:read","comment:write"})
     */
    private $conference;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    /**
     * @Groups({"comment:read"})
     */
    public function getShorttext(): string
    {
        if (strlen($this->text) < 20) {
            return $this->text;
        }
        return substr($this->text, 0, 20).'...';
    }

    /**
     * @Groups({"comment:read"})
     */
    public function getAge(): string
    {
        $now = new \DateTime();
        $interval = $this->createdAt->diff($now);
        return "Créé il y a " . $interval->days . " jours " . $interval->h." heures et ".$interval->i." minutes";
    }
}