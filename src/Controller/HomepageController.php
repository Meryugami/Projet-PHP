<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/{_locale}/home", name="home")
     */
    public function index(): Response
    {
        $events = $this->getDoctrine()->getManager()
            ->getRepository(Event::class)
            //->findRecentEvents();
            ->findBy([], ['dateStart' => 'DESC'], 10, 0);

        return $this->render('Home/index.html.twig', [
            'events' => $events
        ]);
    }
}
