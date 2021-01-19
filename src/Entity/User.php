<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     *  @var array<string>
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $birthday = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $phoneNumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatar;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $friends;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="organizer", orphanRemoval=true)
     */
    private $eventOrganized;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="participants")
     */
    private $eventGoing;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
        $this->eventOrganized = new ArrayCollection();
        $this->eventGoing = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
    * @see UserInterface
    */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
    * @param array<string> $roles
    */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTime $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        $this->friends->removeElement($friend);

        return $this;
    }

    public function hasFriend(self $friend): bool
    {
        $found = false;

        foreach ($this->friends as $myFriend) {
            if ($myFriend->getId() == $friend->getId()) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
    * @see UserInterface
    */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventOrganized(): Collection
    {
        return $this->eventOrganized;
    }

    public function addEventOrganized(Event $eventOrganized): self
    {
        if (!$this->eventOrganized->contains($eventOrganized)) {
            $this->eventOrganized[] = $eventOrganized;
            $eventOrganized->setOrganizer($this);
        }

        return $this;
    }

    public function removeEventOrganized(Event $eventOrganized): self
    {
        if ($this->eventOrganized->removeElement($eventOrganized)) {
            // set the owning side to null (unless already changed)
            if ($eventOrganized->getOrganizer() === $this) {
                $eventOrganized->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventGoing(): Collection
    {
        return $this->eventGoing;
    }

    public function addEventGoing(Event $eventGoing): self
    {
        if (!$this->eventGoing->contains($eventGoing)) {
            $this->eventGoing[] = $eventGoing;
            $eventGoing->addParticipant($this);
        }

        return $this;
    }

    public function removeEventGoing(Event $eventGoing): self
    {
        if ($this->eventGoing->removeElement($eventGoing)) {
            // set the owning side to null (unless already changed)
            if ($eventGoing->getParticipants() === $this) {
                $eventGoing->removeParticipant($this);
            }
        }

        return $this;
    }
}
