<?php
    // Fonction liée a la page principale
namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    // la fonction index permet l'affichage des évènement sur la page principale, la ligne commenté correspond a un filtre pour n'afficher que les évènement non commencés.
    // Pour simplfier l'utilisation dans un cadre de test j'ai remplacer ce filtre par un filtre sur date de début décroissante pour que les fixtures puissent s'afficher correctement.
    //Possibilité d'activer la ligne 22 et désactiver la ligne 23
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
