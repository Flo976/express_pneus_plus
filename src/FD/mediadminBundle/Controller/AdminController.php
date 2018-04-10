<?php

namespace FD\mediadminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FD\mediadminBundle\Entity\Media;
use FD\mediadminBundle\Entity\Theme;
use FD\mediadminBundle\Form\ThemeType;
use FD\mediadminBundle\Form\MediaType;
use Gumlet\ImageResize;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminController extends Controller
{
    

     /**
     * @Route("/admin", name="dashboard")
     */
    public function adminAction(Request $request)
    {
          if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw $this->createAccessDeniedException();
        }    
    $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('FDmediadminBundle:admin:index.html.twig');
    }

     /**
     * @Route("/admin/add-medias", name="add-medias")
     */
    public function add_mediasAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $theme = $em->getRepository("FD\mediadminBundle\Entity\Theme")->FindAll();
        return $this->render('FDmediadminBundle:admin:add_medias.html.twig', array(
            'theme' => $theme 
        ));
    }


    /**
     * @Route("/admin/add-theme", name="add-theme")
     */
    public function add_themeAction(Request $request)
    {
        $theme = new Theme();
        $form_object = $this->createForm(ThemeType::class, $theme);

        $form_object->handleRequest($request);
        if ($form_object->isSubmitted() && $form_object->isValid()) {
            $annee = $form_object->get('annee')->getData();
            $nom = $form_object->get('nom')->getData();
            $soustitre = $form_object->get('soustitre')->getData();
            $nom = strtolower($nom);
            $soustitre = strtolower($soustitre);
            $nom = str_replace(' ', '-', $nom);
            $soustitre = str_replace(' ', '-', $soustitre);
            $url = $nom . '-' . $soustitre . '-' . $annee;
            $theme->setUrl($url);
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            return $this->redirectToRoute('list-theme');
        }
        return $this->render('FDmediadminBundle:admin:add_theme.html.twig',array(
            'form_object' => $form_object->createView() 
        ));
    }


    /**
     * @Route("/admin/list-theme", name="list-theme")
     */
    public function list_themeAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $theme = $em->getRepository("FD\mediadminBundle\Entity\Theme")->FindAll();
        $medias = $em->getRepository("FD\mediadminBundle\Entity\Media")->FindBy(array('theme' => $theme ));


        return $this->render('FDmediadminBundle:admin:list_theme.html.twig',array(
            'theme' => $theme,
            'medias' => $medias  
        ));
    }

     /**
     * @Route("/admin/list-theme/delete-{id}", name="delete-theme")
     */
    public function delete_themeAction(Request $request, $id)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $dltheme = $em->getRepository("FD\mediadminBundle\Entity\Theme")->find($id);
       
        $form_object = $this->createFormBuilder($dltheme)
        ->add('submit', SubmitType::class, array('label' => 'Supprimer l\'Article'))
        ->getForm();
        
        $form_object->handleRequest($request);
     
        if ($form_object->isSubmitted() && $form_object->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            
            
     
            if (!$dltheme) {
                throw $this->createNotFoundException('Impossible de trouver l\'Article.');
            }
            foreach ($dltheme->getMedias() as $rmv) {
                $em->remove($rmv);
            }
             
            $em->remove($dltheme);
            $em->flush();
            return $this->redirectToRoute('list-theme');
        }

        
        return $this->render('FDmediadminBundle:admin:delete_theme.html.twig', array(
                                'dltheme' => $dltheme,
                                'form_object' => $form_object->createView()
                                
                                                    
                                ));
    }


    /**
     * @Route("/admin/list-theme/edit-{id}", name="edit-theme")
     */
    public function edit_themeAction(Request $request, $id)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $theme = $em->getRepository("FD\mediadminBundle\Entity\Theme")->find($id);
        $form_object = $this->createForm(ThemeType::class, $theme);

        $form_object->handleRequest($request);
        if ($form_object->isSubmitted() && $form_object->isValid()) {
            $annee = $form_object->get('annee')->getData();
            $nom = $form_object->get('nom')->getData();
            $soustitre = $form_object->get('soustitre')->getData();
            $nom = strtolower($nom);
            $soustitre = strtolower($soustitre);
            $nom = str_replace(' ', '-', $nom);
            $soustitre = str_replace(' ', '-', $soustitre);
            $url = $nom . '-' . $soustitre . '-' . $annee;
            $theme->setUrl($url);
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            return $this->redirectToRoute('list-theme');
        }
        return $this->render('FDmediadminBundle:admin:add_theme.html.twig',array(
            'form_object' => $form_object->createView() 
        ));
    }    


     /**
     * @Route("/admin/list-medias", name="list-medias")
     */
    public function list_mediasAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $medias = $em->getRepository("FD\mediadminBundle\Entity\Media")->FindAll();

       
        return $this->render('FDmediadminBundle:admin:list_medias.html.twig',array(
            'medias' => $medias 
        ));
    }


     /**
     * @Route("/admin/edit-media/{idmedia}", name="edit-media")
     */
    public function edit_mediaAction(Request $request, $idmedia)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $media = $em->getRepository("FD\mediadminBundle\Entity\Media")->findOneById($idmedia);

        $document = $media;

        $form_object = $this->createForm(MediaType::class, $document);

        $form_object->handleRequest($request);
        if ($form_object->isSubmitted() && $form_object->isValid()) {

        $test = $document->getFile();
        $nom = $test->getClientOriginalName();
        $path = "/uploads/medias/$nom";
        $path_dir = "uploads/medias/$nom";
        
                

        $document->setFile($test);
        $document->setPath($path);        
        $document->setName($nom);
        $document->upload();       
        $em->persist($document);
        $em->flush();





        //$image = new ImageResize($path_dir);
        $imglarge = new ImageResize($path_dir);


         $imglarge->addFilter(function ($imageDesc) {
          });
        
        $image18Plus = 'img/test.png';
        $imglarge->addFilter(function ($imageDesc) use ($image18Plus) {
            $logo = imagecreatefrompng($image18Plus);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $image_width = imagesx($imageDesc);
            $image_height = imagesy($imageDesc);
            $image_x = $image_width - $logo_width - 10;
            $image_y = $image_height - $logo_height - 10;
            imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
        });

        $imglarge->resizeToBestFit(1000, 500);
        $imglarge->quality_jpg = 100;
        $imglarge->save("uploads/thumb/large$nom");
        $large = "/uploads/thumb/large$nom";
            
        /*$image->scale(7);
        $imglarge->quality_jpg = 80;
        $image->save("uploads/thumb/thumb$nom") ;

        $thumb = "/uploads/thumb/thumb$nom";
        $path_thumb = "uploads/thumb/thumb$nom";
        

        list($width, $height) = getimagesize($path_thumb);
        
        if ($width > $height) {
            $type = 'paysage';
        }
        elseif ($height > $width) {
            $type = 'portrait';
        }
        elseif ($width == $height){
            $type = 'carre';
        }

        $width = $width * 14.3;
        $height = $height * 14.32;   
        
        
        /*$document->setType($type);
        $document->setHeight($height);
        $document->setWidth($width);
        $document->setThumb($thumb);*/
        $document->setLarge($large);
        $em->persist($document);
        $em->flush();

            return $this->redirectToRoute('edit-media', array('idmedia' => $idmedia));
        }

       
        return $this->render('FDmediadminBundle:admin:edit_media.html.twig',array(
            'media' => $media,
            'form_object' => $form_object->createView() 
        ));
    }


     /**
     *
     * @Method({"GET", "POST"})
     * @Route("/ajax/snippet/image/send/{theme}", name="ajax_snippet_image_send")
     */
    public function ajaxSnippetImageSendAction(Request $request, $theme)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

      
        $document = new Media();
       
        
        $media = $request->files->get('file');
        $nom = $media->getClientOriginalName();
        $path = "/uploads/medias/$nom";
        $path_dir = "uploads/medias/$nom";
        
        if ($theme != 0) {
        $th = $em->getRepository("FD\mediadminBundle\Entity\Theme")->find($theme);

        $th->addMedia($document);

        $document->setTheme($th);
        }
        

        $document->setFile($media);
        $document->setPath($path);        
        $document->setName($media->getClientOriginalName());
        $document->upload();       
        $em->persist($document);
        $em->persist($th);
        $em->flush();





        //$image = new ImageResize($path_dir);
        $imglarge = new ImageResize($path_dir);


         $imglarge->addFilter(function ($imageDesc) {
          });
        
        $image18Plus = 'img/test.png';
        $imglarge->addFilter(function ($imageDesc) use ($image18Plus) {
            $logo = imagecreatefrompng($image18Plus);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $image_width = imagesx($imageDesc);
            $image_height = imagesy($imageDesc);
            $image_x = $image_width - $logo_width - 10;
            $image_y = $image_height - $logo_height - 10;
            imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
        });

       
            $imglarge->resizeToBestFit(1000, 500);
            $imglarge->quality_jpg = 80;
            $imglarge->save("uploads/thumb/large$nom");
            $large = "/uploads/thumb/large$nom";
       /*

 
        $image->scale(7);
        $imglarge->quality_jpg = 80;
        $image->save("uploads/thumb/thumb$nom") ;

        $thumb = "/uploads/thumb/thumb$nom";
        $path_thumb = "uploads/thumb/thumb$nom";
      
        list($width, $height) = getimagesize($path_thumb);
        
        if ($width > $height) {
            $type = 'paysage';
        }
        elseif ($height > $width) {
            $type = 'portrait';
        }
        elseif ($width == $height){
            $type = 'carre';
        }

        $width = $width * 14.3;
        $height = $height * 14.32;   
        
      
        $document->setType($type);
       
        $document->setHeight($height);
        $document->setWidth($width);
        $document->setThumb($thumb);
        */

        $document->setLarge($large);

        $em->persist($document);
        $em->flush();

        
        
        return new JsonResponse();
    }
}
