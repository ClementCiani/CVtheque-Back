<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraint as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CvRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"title":"partial", "formation":"partial", "experiences":"partial", "skills":"partial"})
 */
#[ApiResource(
    normalizationContext: ["groups" => [
        "cvread"
    ]],
    denormalizationContext: ["groups" => [
        "cvwrite"
    ]]
)]
class Cv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["candidateread", "cvread", "cvwrite"])]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["candidateread", "cvread", "cvwrite"])]
    private $urlPerso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["candidateread", "cvread", "cvwrite"])]
    private $video;

    /**
     * @ORM\OneToMany(targetEntity=Formations::class, mappedBy="cv")
     */
    #[Groups(["candidateread", "cvread"])]
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity=Experiences::class, mappedBy="cv")
     */
    #[Groups(["candidateread", "cvread"])]
    private $experiences;

    /**
     * @ORM\OneToMany(targetEntity=Skills::class, mappedBy="cv")
     */
    #[Groups(["candidateread", "cvread"])]
    private $skills;

    public function __construct()
    {
        $this->formation = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUrlPerso(): ?string
    {
        return $this->urlPerso;
    }

    public function setUrlPerso(string $urlPerso): self
    {
        $this->urlPerso = $urlPerso;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return Collection|Formations[]
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(Formations $formation): self
    {
        if (!$this->formation->contains($formation)) {
            $this->formation[] = $formation;
            $formation->setCv($this);
        }

        return $this;
    }

    public function removeFormation(Formations $formation): self
    {
        if ($this->formation->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getCv() === $this) {
                $formation->setCv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Experiences[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experiences $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setCv($this);
        }

        return $this;
    }

    public function removeExperience(Experiences $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getCv() === $this) {
                $experience->setCv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Skills[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skills $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
            $skill->setCv($this);
        }

        return $this;
    }

    public function removeSkill(Skills $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getCv() === $this) {
                $skill->setCv(null);
            }
        }

        return $this;
    }
}
