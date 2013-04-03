<?php
/**
 * Shopware 4.0
 * Copyright © 2013 shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Models\Category;

use Shopware\Components\Model\ModelEntity;
use Shopware\Models\Article\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Shopware Categories
 *
 * @category  Shopware
 * @package   Shopware\Models
 * @copyright Copyright (c) 2013, shopware AG (http://www.shopware.de)
 *
 * @ORM\Table(name="s_categories")
 * @ORM\Entity(repositoryClass="Repository")
 */
class Category extends ModelEntity
{
    /**
     * Identifier for a single category. This is an autoincrement value.
     *
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * The id of the parent category
     *
     * @var integer $parentId
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * The parent category
     *
     * OWNING SIDE
     *
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent", nullable=true, referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * String representation of the category
     *
     * @var string $name
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * Integer value on which the return values are ordered (asc)
     *
     * @var integer $position
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * Keeps the meta keywords which are displayed in the HTML page.
     *
     * @var string $metaKeywords
     *
     * @ORM\Column(name="metakeywords", type="text", nullable=true)
     */
    private $metaKeywords;

    /**
     * Keeps the meta description which is displayed in the HTML page.
     *
     * @var string $metaDescription
     *
     * @ORM\Column(name="metadescription", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * Keeps the CMS Headline for this category
     *
     * Max chars: 255
     *
     * @var string $cmsHeadline
     *
     * @ORM\Column(name="cmsheadline", type="string", length=255, nullable=true)
     */
    private $cmsHeadline;

    /**
     * Keeps the CMS Text for this category
     *
     * @var string $cmsText
     *
     * @ORM\Column(name="cmstext", type="text", nullable=true)
     */
    private $cmsText;

    /**
     * Flag which shows if the category is active or not. 1= active otherwise inactive
     *
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * If this field is set the category page will uses this template
     *
     * @var string $template
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    private $template;

    /**
     * @var boolean $blog
     *
     * @ORM\Column(name="blog", type="boolean", nullable=false)
     */
    private $blog = false;

    /**
     * Flag shows if the category filterable
     *
     * @var integer $showFilterGroups
     *
     * @ORM\Column(name="showfiltergroups", type="boolean", nullable=false)
     */
    private $showFilterGroups = true;

    /**
     * Is this category based outside from the shop?
     *
     * @var string $external
     *
     * @ORM\Column(name="external", type="string", length=255, nullable=true)
     */
    private $external;

    /**
     * Should any filter shown on the category page be hidden?
     *
     * @var integer $hideFilter
     *
     * @ORM\Column(name="hidefilter", type="boolean", nullable=false)
     */
    private $hideFilter = false;

    /**
     * Should the top part of that category be displayed?
     *
     * @var integer $hideTop
     *
     * @ORM\Column(name="hidetop", type="boolean", nullable=false)
     */
    private $hideTop = false;

    /**
     * Can this category used even there is no view selected?
     *
     * @var integer $noViewSelect
     *
     * @ORM\Column(name="noviewselect", type="boolean", nullable=false)
     */
    private $noViewSelect;


    /**
     * INVERSE SIDE
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"all"}))
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $children;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinTable(name="s_articles_categories",
     *      joinColumns={
     *          @ORM\JoinColumn(name="categoryID", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="articleID", referencedColumnName="id")
     *      }
     * )
     */
    private $articles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Customer\Group")
     * @ORM\JoinTable(name="s_categories_avoid_customergroups",
     *      joinColumns={
     *          @ORM\JoinColumn(name="categoryID", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="customergroupID", referencedColumnName="id", unique=true)
     *      }
     * )
     */
    protected $customerGroups;

    /**
     * @var \DateTime $changed
     *
     * @ORM\Column(name="changed", type="datetime", nullable=false)
     */
    private $changed;

    /**
     * @var \DateTime $added
     *
     * @ORM\Column(name="added", type="datetime", nullable=false)
     */
    private $added;

    /**
     * INVERSE SIDE
     *
     * @var \Shopware\Models\Attribute\Category
     *
     * @ORM\OneToOne(targetEntity="Shopware\Models\Attribute\Category", mappedBy="category", cascade={"persist", "update"})
     */
    protected $attribute;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Emotion\Emotion", mappedBy="categories")
     * @ORM\JoinTable(name="s_emotion_categories",
     *      joinColumns={
     *          @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="emotion_id", referencedColumnName="id")
     *      }
     * )
     */
    protected $emotions;

    /**
     * OWNING SIDE
     *
     * @var \Shopware\Models\Media\Media
     *
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Media\Media")
     * @ORM\JoinColumn(name="mediaID", referencedColumnName="id")
     */
    protected $media;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->emotions = new ArrayCollection();
        $this->changed  = new \DateTime();
        $this->added    = new \DateTime();
    }

    /**
     * Sets the primary key
     *
     * @param $id
     */
    public function setPrimaryIdentifier($id)
    {
        $this->id = (int) $id;
    }

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
     * Set id
     *
     * @param $id
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;

        return $id;
    }

    /**
     * Get parent id
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets the id of the parent category
     *
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parents category id
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int $level
     * @return int
     */
    public function getLevel($level = 0)
    {
        $parent = $this->getParent();

        if ($parent) {
            $level = $parent->getLevel($level + 1);
        }

        return $level;
    }

    /**
     * @param Category[] $children
     * @return Category
     */
    public function setChildren($children)
    {
        foreach ($children as $child) {
            $child->setParent($this);
        }
        $this->children = $children;
        return $this;
    }

    /**
     * Get parents category id
     *
     * @return Category[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the string representation of the category
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns description string
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets an integer value on which the return values are ordered (asc)
     *
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;

        return $this;
    }

    /**
     * Returns position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set changed
     *
     * @param \DateTime|string $changed
     * @return Article
     */
    public function setChanged($changed = 'now')
    {
        if (!$changed instanceof \DateTime) {
            $this->changed = new \DateTime($changed);
        } else {
            $this->changed = $changed;
        }

        return $this;
    }

    /**
     * Get changed
     *
     * @return \DateTime
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * Get added
     *
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set the meta keywords.
     *
     * @param string $metaKeywords
     * @return Category
     */
    public function setMetaKeywords($metaKeywords)
    {
        if (empty($metaKeywords)) {
            $metaKeywords = null;
        }

        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Returns the meta keywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Sets the  meta description text.
     *
     * @param string $metaDescription
     * @return Category
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Gets the meta description text.
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Sets the CMS headline
     *
     * @param string $cmsHeadline
     * @return Category
     */
    public function setCmsHeadline($cmsHeadline)
    {
        $this->cmsHeadline = $cmsHeadline;

        return $this;
    }

    /**
     * Gets the CMS headline
     *
     * @return string
     */
    public function getCmsHeadline()
    {
        return $this->cmsHeadline;
    }

    /**
     * Sets the CMS text
     *
     * @param string $cmsText
     * @return Category
     */
    public function setCmsText($cmsText)
    {
        $this->cmsText = $cmsText;

        return $this;
    }

    /**
     * Gets CMS text
     *
     * @return string
     */
    public function getCmsText()
    {
        return $this->cmsText;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Category
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set no view select
     *
     * @param bool $noViewSelect
     * @return Category
     */
    public function setNoViewSelect($noViewSelect)
    {
        $this->noViewSelect = (bool) $noViewSelect;

        return $this;
    }

    /**
     * Get no view select
     *
     * @return integer
     */
    public function getNoViewSelect()
    {
        return $this->noViewSelect;
    }

    /**
     * Set active
     *
     * @param bool $active
     * @return Category
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;

        return $this;
    }

    /**
     * Returns if the category is active or nor
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Returns if the category is blog category or nor
     *
     * @return boolean
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set category as a blog category
     *
     * @param boolean $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }

    /**
     * Set the flag if filter groups should be displayed
     *
     * @param boolean $showFilterGroups
     * @return Category
     */
    public function setShowFilterGroups($showFilterGroups)
    {
        $this->showFilterGroups = (bool) $showFilterGroups;

        return $this;
    }

    /**
     * Get the flag if the filter groups should be displayed
     *
     * @return boolean
     */
    public function getShowFilterGroups()
    {
        return $this->showFilterGroups;
    }

    /**
     * Sets the flag if this category goes to an  external source
     *
     * @param string $external
     * @return Category
     */
    public function setExternal($external)
    {
        $this->external = $external;

        return $this;
    }

    /**
     * Gets the flag if this category is linked to an external source
     *
     * @return string
     */
    public function getExternal()
    {
        return $this->external;
    }

    /**
     * Set the flag which hides the filter
     *
     * @param boolean $hideFilter
     * @return Category
     */
    public function setHideFilter($hideFilter)
    {
        $this->hideFilter = (boolean) $hideFilter;

        return $this;
    }

    /**
     * Returns if the filters should be displayed
     *
     * @return boolean
     */
    public function getHideFilter()
    {
        return $this->hideFilter;
    }

    /**
     * Sets the flag if the top of the category should be hidden
     *
     * @param boolean $hideTop
     * @return Category
     */
    public function setHideTop($hideTop)
    {
        $this->hideTop = (bool) $hideTop;

        return $this;
    }

    /**
     * Returns the flag if the should be shown or not
     *
     * @return boolean
     */
    public function getHideTop()
    {
        return $this->hideTop;
    }

    /**
     * Return all Articles associated with this category
     *
     * @return ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Sets all Articles associated with this category
     *
     * @param ArrayCollection $articles
     * @return Category
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * Returns the Attributes
     *
     * @return \Shopware\Models\Attribute\Category
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Returns the category attribute
     *
     * @param \Shopware\Models\Attribute\Category|array|null $attribute
     * @return Category
     */
    public function setAttribute($attribute)
    {
        return $this->setOneToOne($attribute, '\Shopware\Models\Attribute\Category', 'attribute', 'category');
    }

    /**
     * Sets all Customer group associated data to this category
     *
     * @return ArrayCollection
     */
    public function getCustomerGroups()
    {
        return $this->customerGroups;
    }

    /**
     * Returns all Customer group associated data
     *
     * @param ArrayCollection $customerGroups
     * @return Category
     */
    public function setCustomerGroups($customerGroups)
    {
        $this->customerGroups = $customerGroups;

        return $this;
    }

    /**
     * Returns the Media model
     *
     * @return \Shopware\Models\Media\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Sets the Media model
     *
     * @param \Shopware\Models\Media\Media $media
     * @return Category
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEmotions()
    {
        return $this->emotions;
    }

    /**
     * @param ArrayCollection $emotions
     * @return Category
     */
    public function setEmotions($emotions)
    {
        $this->emotions = $emotions;

        return $this;
    }

    /**
     * Helper function which checks, if this category is child of a given parent category
     * @param $parent \Shopware\Models\Category\Category
     * @return bool
     */
    public function isChildOf(\Shopware\Models\Category\Category $parent)
    {
        return $this->isChildOfInternal($this, $parent);
    }

    /**
     * Helper function for the isChildOf function. This function is used for a recursive call.
     *
     * @param $category Category
     * @param $searched Category
     * @return bool
     */
    protected function isChildOfInternal(Category $category, Category $searched)
    {
        if ($category->getParent()->getId() === $searched->getId()) {
            return true;
        }

        if ($category->getParent() instanceof Category) {
            return $this->isChildOfInternal($category->getParent(), $searched);
        }

        return false;
    }
}
