<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

     /**
     * @Route("/voitures-occasion", name="voitureslist")
     */
    public function listvoituresAction(Request $request)
    {
        
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $vip = $em->getRepository("FD\mediadminBundle\Entity\Theme")->FindBy(array('active' => '1', 'vip' => '1'));
        $theme = $em->getRepository("FD\mediadminBundle\Entity\Theme")->FindBy(array('active' => '1', 'vip'=> '0'));
        shuffle($vip);
        shuffle($theme);

        return $this->render('default/voitureslist.html.twig', array('theme' => $theme, 'vip' => $vip ));
    }

     /**
     * @Route("/voitures-occasion/{url}", name="voiture")
     */
    public function VoitureAction(Request $request, $url)
    {
        
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $voiture = $em->getRepository("FD\mediadminBundle\Entity\Theme")->findOneByUrl($url);


        return $this->render('default/voiturespage.html.twig', array('voiture' => $voiture));
    }

    /**
     * @Route("/notre-garage", name="garage")
     */
    public function garageAction(Request $request)
    {
        
            


        return $this->render('default/garage.html.twig');
    }


    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        
            


        return $this->render('default/contact.html.twig');
    }


    /**
     * @Route("/notre-boutique", name="boutique")
     */
    public function BoutiqueAction(Request $request)
    {
        
        $em = $this->container->get("doctrine.orm.default_entity_manager");
      


        return $this->render('default/boutique.html.twig');
    }


    public function occasionsAction(Request $request)
    {
        
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $theme = $em->getRepository("FD\mediadminBundle\Entity\Theme")->FindBy(array('active' => '1'));
        shuffle($theme);
        

        return $this->render('default/occasions.html.twig', array('theme' => $theme ));
    }
}
