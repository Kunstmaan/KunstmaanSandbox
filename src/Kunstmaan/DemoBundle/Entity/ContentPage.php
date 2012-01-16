<?php

namespace Kunstmaan\DemoBundle\Entity;

use Kunstmaan\DemoBundle\PagePartAdmin\BannerPagePartAdminConfigurator;

use Kunstmaan\DemoBundle\PagePartAdmin\ContentPagePagePartAdminConfigurator;

use Kunstmaan\AdminNodeBundle\Entity\HasNode;

use Doctrine\ORM\EntityManager;

use Kunstmaan\AdminBundle\Entity\DeepCloneableIFace;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Collections\ArrayCollection;
use Kunstmaan\DemoBundle\Form\ContentPageAdminType;
use Kunstmaan\AdminBundle\Entity\PageIFace;
use Kunstmaan\SearchBundle\Entity\Indexable;

/**
 * @ORM\Entity(repositoryClass="Kunstmaan\DemoBundle\Repository\ContentPageRepository")
 * @ORM\Table(name="democontentpage")
 * @ORM\HasLifecycleCallbacks()
 */

class ContentPage implements PageIFace, Indexable, DeepCloneableIFace
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
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
        return new ContentPageAdminType();
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
    	$array[] = array('name' => 'ContentPage', 'class'=>"Kunstmaan\DemoBundle\Entity\ContentPage");
    	return $array;
    }
    
    public function deepClone(EntityManager $em){
    	$newpage = new ContentPage();
    	$newpage->setTitle($this->getTitle());
    	$em->persist($newpage);
    	$em->flush();
    	$em->getRepository('KunstmaanPagePartBundle:PagePartRef')->copyPageParts($em, $this, $newpage, $context = "main");
    	return $newpage;
    }
    
    public function getPagePartAdminConfigurations(){
    	return array(new ContentPagePagePartAdminConfigurator(), new BannerPagePartAdminConfigurator());
    }
}