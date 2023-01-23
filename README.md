# Translatable Entity

[![License](http://poser.pugx.org/enderlab/translatable-entity-bundle/license)](https://packagist.org/packages/enderlab/translatable-entity-bundle)
[![Total Downloads](http://poser.pugx.org/enderlab/translatable-entity-bundle/downloads)](https://packagist.org/packages/enderlab/translatable-entity-bundle)

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
* Extends EnderLab\TranslatableEntityBundle\Entity\TranslatableEntity
* Add attribute #[TranslatableField] on translatable field
* Remove getters and setters for translatable properties 

```diff
<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use EnderLab\TranslatableEntityBundle\Entity\TranslatableEntityInterface;
use EnderLab\TranslatableEntityBundle\Traits\TranslatableEntityTrait;
use EnderLab\TranslatableEntityBundle\Attributes\TranslatableField;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
- class Product
+ class Product implements TranslatableEntityInterface
{
    use TranslatableEntityTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
+   #[TranslatableField]
    private array $name = [];

    #[ORM\Column]
+   #[TranslatableField]
    private array $description = [];

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

To help the autocompletion of your ide, you can add the following comments
```diff, php
# Product class
<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use EnderLab\TranslatableEntityBundle\Entity\TranslatableEntityInterface;
use EnderLab\TranslatableEntityBundle\Traits\TranslatableEntityTrait;
use EnderLab\TranslatableEntityBundle\Attributes\TranslatableField;

+ /**
+  * @method string getName()
+  * @method Product setName(?string $name)
+  * @method string getDescription()
+  * @method Product setDescription(?string $description)
+  */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements TranslatableEntityInterface
{
    use TranslatableEntityTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[TranslatableField]
    protected array $name = [];

    #[ORM\Column]
    #[TranslatableField]
    protected array $description = [];

    public function getId(): ?int
    {
        return $this->id;
    }
}
```

## Use in your code
```php
# Product class
<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use EnderLab\TranslatableEntityBundle\Entity\TranslatableEntityInterface;
use EnderLab\TranslatableEntityBundle\Traits\TranslatableEntityTrait;
use EnderLab\TranslatableEntityBundle\Attributes\TranslatableField;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements TranslatableEntityInterface
{
    use TranslatableEntityTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[TranslatableField]
    protected array $name = [];

    #[ORM\Column]
    #[TranslatableField]
    protected array $description = [];

    public function getId(): ?int
    {
        return $this->id;
    }
}

# Use product object
$product = new Product();
$product
    ->setName('Produit test') # Set value for current locale
    ->setNameFr('Produit test') # Set value for fr locale
    ->setNameEn('Test product') # Set value for en locale
    ->setDescriptionFr('Super produit test')
    ->setDescriptionEn('Great test product')
;

// Display product name with current locale
echo $product->getName();

// Display product name with fr locale
echo $product->getNameFr();

// Display product name with en locale
echo $product->getNameEn();

// Display array of all locales
echo print_r($product->getNameAll());
```

## Use in twig template
```html
// Display product name with the current locale
<div>{{ product.name }}</div>

// Display product name with the fr locale
<div>{{ product.nameFr }}</div>

Display product name with the en locale
<div>{{ product.nameEn }}</div>

// Display product name all locales
<div>{{ product.nameAll|join(',') }}</div>
```

## Use form with translatable field
```php

```