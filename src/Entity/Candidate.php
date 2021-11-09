<?php

namespace App\Entity;


use App\Entity\Cv;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidateRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ApiResource(
 *        collectionOperations={
 *        "get"= {"access_control"="is_granted('ROLE_ADMIN')"},
 *        "post"={"access_control"="is_granted('ROLE_USER')"},
 *       },
 *        itemOperations={
 *        "get"={"access_control"="is_granted('ROLE_USER')"},
 *        "put"={"access_control"="is_granted('ROLE_USER')"},
 *        "delete"={"access_control"="is_granted('ROLE_ADMIN')"},
 *       },
 *        normalizationContext={"groups"={"user:read"}},
 *        denormalizationContext={"groups"={"user:write"}})
 * @ORM\Entity(repositoryClass=CandidateRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"city":"partial", "seekingJobType":"partial", "seekingJobContract":"partial", "availability":"partial"})
 */
#[ApiResource(
    normalizationContext: ["groups" => [
        "candidateread"
    ]]
)]
class Candidate implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("user:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez fournir un mail")
     * @Assert\Email(message="l'adresse mail doit être dans un format valide")
     */
    #[Groups(["candidateread"])]
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("user:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups("user:write")
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez fournir votre nom de famille")
     */
    #[Groups(["candidateread"])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez fournir votre prénom")
     */
    #[Groups(["candidateread"])]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez fournir un numéros de téléphone")
     */
    #[Groups(["candidateread"])]
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez fournir votre adresse postal")
     */
    #[Groups(["candidateread"])]
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez renseigner votre ville de résidence")
     */
    #[Groups(["candidateread"])]
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez renseigner votre code postal")
     */
    #[Groups(["candidateread"])]
    private $zip;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez fournir une photo")
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez reinseigner le poste rechercher")
     */
    #[Groups(["candidateread"])]
    private $seekingJobType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez renseigner le type de contrat voulue")
     */
    #[Groups(["candidateread"])]
    private $seekingJobContract;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Veuillez renseigner vos disponibilités")
     */
    #[Groups(["candidateread"])]
    private $availability;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read", "user:write"})
     */
    #[Groups(["candidateread"])]
    private $registrationDate;

    /**
     * @ORM\OneToOne(targetEntity=Cv::class, cascade={"persist", "remove"})
     * @Groups({"user:read", "user:write"})
     * @ApiSubresource
     */
    #[Groups(["candidateread"])]
    private $cv;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getSeekingJobType(): ?string
    {
        return $this->seekingJobType;
    }

    public function setSeekingJobType(string $seekingJobType): self
    {
        $this->seekingJobType = $seekingJobType;

        return $this;
    }

    public function getSeekingJobContract(): ?string
    {
        return $this->seekingJobContract;
    }

    public function setSeekingJobContract(string $seekingJobContract): self
    {
        $this->seekingJobContract = $seekingJobContract;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public static function createFromPayload($id, array $payload)
    {
        return (new Candidate())->setId($id);
    }
}
