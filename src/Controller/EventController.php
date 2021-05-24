<?php
    ////Controller correspondant aux gestions d'évènement
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\User;
use App\Form\CommentForm;
use App\Form\EventForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/{_locale}/event/create", name="event_create")
     */

    //Permet de crée un évènement
    public function eventCreate(
        Request $request
    ): Response {
        $event = new Event();

        $eventForm = $this->createForm(EventForm::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $event->setUser($this->getUser());
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('Event/add.html.twig', [
            'form' => $eventForm->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/event/modify/{id}", name="event_modify")
     */

    //Permet de modifier un évènement
    public function eventModify(Event $event, Request $request): Response
    {
        $eventForm = $this->createForm(EventForm::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('Event/modify.html.twig', [
            'form' => $eventForm->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/event/delete/{id}", name="event_delete")
     */

    //Permet de supprimer un évènement
    public function deleteEvent(Event $event): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/{_locale}/event/{id}", name="event_show")
     */

    //Afficheage des évènement triée par date de début (asc) limité a 3 évènements
    public function eventShow(Event $event, Request $request)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentForm::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $comment->setDate(new \DateTime());
            $comment->setUser($this->getUser());
            $comment->setEvent($event);

            $em->persist($comment);
            $em->flush();
        }

        $incomingEvents = $this->getDoctrine()->getManager()
            ->getRepository(Event::class)
            ->findBy([], ['dateStart' => 'ASC'], 3, 0);

        return $this->render('Event/show.html.twig', [
            'event' => $event,
            'incomingEvents'=> $incomingEvents,
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/association/user/event/{id}", name="association_user_event")
     */


    //Associe un utilisateur avec un event
    public function associateUserWithEvent(Event $event)
    {
        $event->addParticipant($this->getUser());

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/deleteAssociation/user/event/{id}", name="delete_association")
     */

    //Supprime l'association d'un utilisateur/event
    public function deleteAssociation(Event $event)
    {
        $event->removeParticipant($this->getUser());

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('home');
    }
}
