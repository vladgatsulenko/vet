<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Entity\Manufacturer;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Manufacturer::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Manufacturer $manufacturer = null;

    #[ORM\ManyToOne(targetEntity: PharmacologicalGroup::class)]
    #[ORM\JoinColumn(name: "pharmacological_group_id", referencedColumnName: "id", nullable: false, onDelete: "RESTRICT")]
    private PharmacologicalGroup $pharmacologicalGroup;

    #[ORM\ManyToOne(targetEntity: AnimalSpecies::class)]
    #[ORM\JoinColumn(name: "animal_species_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]

    private ?AnimalSpecies $animalSpecies = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descriptionShort = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionMedium = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionFull = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $pharmacologicalProperties = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $indicationsForUse = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dosageAndAdministration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $restrictions = null;

    public function __construct(string $name, PharmacologicalGroup $pharmacologicalGroup, ?AnimalSpecies $animalSpecies = null)
    {
        $this->name = $name;
        $this->pharmacologicalGroup = $pharmacologicalGroup;
        $this->animalSpecies = $animalSpecies;
    }

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

    public function setPharmacologicalProperties(?string $pharmacologicalProperties): self
    {
        $this->pharmacologicalProperties = $pharmacologicalProperties;

        return $this;
    }

    public function getIndicationsForUse(): ?string
    {
        return $this->indicationsForUse;
    }

    public function setIndicationsForUse(?string $indicationsForUse): self
    {
        $this->indicationsForUse = $indicationsForUse;

        return $this;
    }

    public function getDosageAndAdministration(): ?string
    {
        return $this->dosageAndAdministration;
    }

    public function setDosageAndAdministration(?string $dosageAndAdministration): self
    {
        $this->dosageAndAdministration = $dosageAndAdministration;

        return $this;
    }

    public function getRestrictions(): ?string
    {
        return $this->restrictions;
    }

    public function setRestrictions(?string $restrictions): self
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;
        
        return $this;
    }
}
