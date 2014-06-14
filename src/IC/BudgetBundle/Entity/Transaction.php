<?php

namespace IC\BudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="transaction")
 */
class Transaction
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $budget_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="categories")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     */
    protected $subcategory_id;
        
    /**
     * @ORM\Column(type="date")
     */
    protected $date;
    
    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $value;    
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $description;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subcategory_id
     *
     * @param integer $subcategoryId
     * @return Transaction
     */
    public function setSubcategoryId($subcategoryId)
    {
        $this->subcategory_id = $subcategoryId;

        return $this;
    }

    /**
     * Get subcategory_id
     *
     * @return integer 
     */
    public function getSubcategoryId()
    {
        return $this->subcategory_id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Transaction
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Transaction
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
     * Set user_id
     *
     * @param integer $userId
     * @return Transaction
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set budget_id
     *
     * @param integer $budgetId
     * @return Transaction
     */
    public function setBudgetId($budgetId)
    {
        $this->budget_id = $budgetId;

        return $this;
    }

    /**
     * Get budget_id
     *
     * @return integer 
     */
    public function getBudgetId()
    {
        return $this->budget_id;
    }
}
