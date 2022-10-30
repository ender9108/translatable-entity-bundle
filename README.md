# Translatable Entity

[![License](http://poser.pugx.org/enderlab/translatable-entity/license)](https://packagist.org/packages/enderlab/translatable-entity)
[![Total Downloads](http://poser.pugx.org/enderlab/translatable-entity/downloads)](https://packagist.org/packages/enderlab/translatable-entity)

| /!\ Documentation in progress /!\

# Table of content
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
  * [Update your entity](#update-your-entity)
  * [Use in your code](#use-in-your-code)
  * [Use in twig template](#use-in-twig-template)
  * [Make query on translatable field](#make-query-on-translatable-field)

# Installation
```
composer require enderlab/translatable-entity
```

# Configuration 
```yaml
# File config/packages/translatable_entity.yaml
translatable_entity:
  default_locale: en # replace this value by your default locale
  availables_locales: # replace this value by your availables locales
    - en
    - fr
  default_timezone: Europe\London # replace this value by your default timezone
  availables_timezones: # replace this value by your availables timezones
    en: Europe\London
    fr: Europe\Paris
```

# Usage

## Update your entity

In your entity class
* Create your entity
    * Declare translatable fields in json
* Extends EnderLab\TranslatableEntity\Entity\TranslatableEntity
* Add attribute #[TranslatableField] on translatable field
* Remove getters and setters for translatable properties 

```diff
<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use EnderLab\TranslatableEntity\Entity\TranslatableEntity;
use EnderLab\TranslatableEntity\Attributes\TranslatableField;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
- class Product
+ class Product extends TranslatableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[TranslatableField]
    protected array $name = [];

    #[ORM\Column]
+   #[TranslatableField]
    protected array $description = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    
-   public function getName(): ?string
-   {
-       return $this->name; 
-   }
-   
-   public function setName(string $name): self
-   {
-       $this->name = $name;
-       
-       return $this 
-   }
-   
-   public function getDescription(): ?string
-   {
-       return $this->description; 
-   }
-   
-   public function setDescription(string $description): self
-   {
-       $this->description = $description;
-       
-       return $this 
-  }
}
```

## Use in your code

## Use in twig template

## Make query on translatable field