<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "product")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: PharmacologicalGroup::class)]
    #[ORM\JoinColumn(name: "pharmacological_group_id", referencedColumnName: "id", nullable: false, onDelete: "RESTRICT")]
    private PharmacologicalGroup $pharmacologicalGroup;

    #[ORM\ManyToOne(targetEntity: AnimalSpecies::class)]
    #[ORM\JoinColumn(name: "animal_species_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?AnimalSpecies $animalSpecies = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $descriptionShort = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descriptionMedium = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descriptionFull = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $pharmacologicalProperties = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $indicationsForUse = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $dosageAndAdministration = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $restrictions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPharmacologicalGroup(): PharmacologicalGroup
    {
        return $this->pharmacologicalGroup;
    }

    public function setPharmacologicalGroup(PharmacologicalGroup $group): self
    {
        $this->pharmacologicalGroup = $group;
        return $this;
    }

    public function getAnimalSpecies(): ?AnimalSpecies
    {
        return $this->animalSpecies;
    }

    public function setAnimalSpecies(?AnimalSpecies $species): self
    {
        $this->animalSpecies = $species;
        return $this;
    }

    public function getDescriptionShort(): ?string
    {
        return $this->descriptionShort;
    }

    public function setDescriptionShort(?string $descriptionShort): self
    {
        $this->descriptionShort = $descriptionShort;
        return $this;
    }

    public function getDescriptionMedium(): ?string
    {
        return $this->descriptionMedium;
    }

    public function setDescriptionMedium(?string $descriptionMedium): self
    {
        $this->descriptionMedium = $descriptionMedium;
        return $this;
    }

    public function getDescriptionFull(): ?string
    {
        return $this->descriptionFull;
    }

    public function setDescriptionFull(?string $descriptionFull): self
    {
        $this->descriptionFull = $descriptionFull;
        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;
        return $this;
    }

    public function getPharmacologicalProperties(): ?string
    {
        return $this->pharmacologicalProperties;
    }

    public function setPharmacologicalProperties(?string $val): self
    {
        $this->pharmacologicalProperties = $val;
        return $this;
    }

    public function getIndicationsForUse(): ?string
    {
        return $this->indicationsForUse;
    }

    public function setIndicationsForUse(?string $val): self
    {
        $this->indicationsForUse = $val;
        return $this;
    }

    public function getDosageAndAdministration(): ?string
    {
        return $this->dosageAndAdministration;
    }

    public function setDosageAndAdministration(?string $val): self
    {
        $this->dosageAndAdministration = $val;
        return $this;
    }

    public function getRestrictions(): ?string
    {
        return $this->restrictions;
    }

    public function setRestrictions(?string $val): self
    {
        $this->restrictions = $val;
        return $this;
    }
}
