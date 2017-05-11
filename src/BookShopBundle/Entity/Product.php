<?php


namespace BookShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Product
*
* @ORM\Table(name="product")
* @ORM\Entity(repositoryClass="BookShopBundle\Repository\UserRepository")
*/
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Задайте име на книгата!")
     * @Assert\Length(min="2",minMessage="Името на книгата трябва да съдържа поне {{ limit }} символа !")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Въведете автор")
     * @Assert\Length(min="2",minMessage="Името на автора на книгата трябва да съдържа поне {{ limit }} символа !")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=11, scale=2)
     *  @Assert\NotBlank(message="Необходимо е да попълните цена на продукта в лева !")
     * * @Assert\Range(
     *      min = 0.01,
     *      max = 20000,
     *      minMessage = "Цената на книгата трябва да бъде по-голяма от {{ limit }} лв.",
     *      maxMessage = "Цената на една книга не трябва да надвишава {{ limit }} лв."
     * )
     *
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg"}, maxSize="5M")
     */
    private $image_form;


    /**
     * @ORM\ManyToOne(targetEntity="BookShopBundle\Entity\User",inversedBy="products")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var bool
     * @ORM\Column(name="published",type="boolean")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\NotBlank(message="Quantity cannot be blank!")
     * @Assert\GreaterThan(value="0", message="Quantity cannot be negative number!")
     */
    private $quantity;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="BookShopBundle\Entity\Category",inversedBy="products")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    private $category;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BookShopBundle\Entity\Promotion",mappedBy="product",cascade={"persist"})
     */
//cascade={persist})
    private $promotions;

    public function __construct()
    {
        $this->promotions=new ArrayCollection();
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImageForm()
    {
        return $this->image_form;
    }

    /**
     * @param mixed $image_form
     */
    public function setImageForm($image_form)
    {
        $this->image_form = $image_form;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param bool $published
     */
    public function setPublished(bool $published)
    {
        $this->published = $published;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param mixed $promotions
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;
    }


    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function addPromotion(Promotion $promotion)
    {
        $this->promotions->add($promotion);
    }
    public function removePromotion(Promotion $promotion)
    {
        $this->promotions->remove($promotion);
    }






}


