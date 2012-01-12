<?php
// src/Blogger/BlogBundle/Entity/Blog.php

namespace Kunstmaan\DemoBundle\Entity;

use Kunstmaan\AdminNodeBundle\Entity\HasNode;

use Doctrine\ORM\EntityManager;

use Kunstmaan\AdminBundle\Entity\DeepCloneableIFace;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Kunstmaan\DemoBundle\Form\ExamplePageAdminType;
use Kunstmaan\AdminBundle\Entity\PageIFace;
use Kunstmaan\SearchBundle\Entity\Indexable;

/**
 * @ORM\Entity(repositoryClass="Kunstmaan\DemoBundle\Repository\ExamplePageRepository")
 * @ORM\Table(name="examplepage")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 *
 * @ORM\DiscriminatorMap({ "examplepage" = "ExamplePage" , "myexamplepage" = "MyExamplePage" })
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */

class ExamplePage implements PageIFace, Translatable, Indexable, DeepCloneableIFace
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Versioned
     * @Gedmo\Translatable
     * @ORM\Column(type="string")
     */
    protected $title;
    
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    protected $parent;
    
    public function getParent(){
    	return $this->parent;
    }
    
    public function setParent(HasNode $parent){
    	$this->parent = $parent;
    }


    protected $possiblePermissions = array(
        'read', 'write', 'delete'
    );

    public function __construct()
    {
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
     * @param string $id
     */
    public function setId($num)
    {
        $this->id = $num;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getDefaultAdminType()
    {
        return new ExamplePageAdminType();
    }

    public function isOnline()
    {
        return true;
    }

    public function getContentForIndexing($container, $entity)
    {
        $renderer = $container->get('templating');
        $em = $container->get('doctrine')->getEntityManager();

        $node = $em->getRepository('KunstmaanAdminNodeBundle:Node')->getNodeForSlug($entity->getParent(), $entity->getSlug());
        $page = $node->getRef($em);

        $pageparts = $em->getRepository('KunstmaanPagePartBundle:PagePartRef')->getPageParts($em, $page);

        $classname = explode('\\', get_class($this));
        $classname = array_pop($classname);

        $view = 'KunstmaanDemoBundle:Elastica:'.$classname.'.elastica.twig';

        return $renderer->render($view, array('page' => $entity, 'pageparts' => $pageparts));
    }

    public function setTranslatableLocale($locale)
    {
    	$this->locale = $locale;
    }

    public function getPossiblePermissions()
    {
        return $this->possiblePermissions;
    }

    
    public function getPossibleChildPageTypes()
    {
    	$array[] = array('name' => 'ExamplePage', 'class'=>"Kunstmaan\DemoBundle\Entity\ExamplePage");
    	$array[] = array('name' => 'MyExamplePage', 'class'=>"Kunstmaan\DemoBundle\Entity\MyExamplePage");
    	return $array;
    }
    
    public function deepClone(EntityManager $em){
    	$newpage = new ExamplePage();
    	$newpage->setTitle($this->getTitle());
    	$em->persist($newpage);
    	$em->flush();
    	$em->getRepository('KunstmaanPagePartBundle:PagePartRef')->copyPageParts($em, $this, $newpage, $context = "main");
    	return $newpage;
    }
}