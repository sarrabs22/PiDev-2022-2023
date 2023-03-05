<?php

namespace App\Controller;

use App\Entity\Calendar1;
use App\Form\Calendar1Type;
use App\templates;
use App\Repository\Calendar1Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar1')]
class Calendar1Controller extends AbstractController
{
    #[Route('/', name: 'app_calendar1_index', methods: ['GET'])]
    public function index(Calendar1Repository $calendar1Repository): Response
    {
        return $this->render('calendar1/index.html.twig', [
            'calendar1s' => $calendar1Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_calendar1_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Calendar1Repository $calendar1Repository): Response
    {
        $calendar1 = new Calendar1();
        $form = $this->createForm(Calendar1Type::class, $calendar1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendar1Repository->save($calendar1, true);

            return $this->redirectToRoute('app_calendar1_reserve', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar1/new.html.twig', [
            'calendar1' => $calendar1,
            'form' => $form,
        ]);
    }

    #[Route('/reserve', name: 'app_calendar1_reserve', methods: ['GET'])]
    public function reserve(Calendar1Repository $calendar)
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('calendar1/affichage.html.twig', compact('data'));
    }


    #[Route('/{id}', name: 'app_calendar1_show', methods: ['GET'])]
    public function show(Calendar1 $calendar1): Response
    {
        return $this->render('calendar1/show.html.twig', [
            'calendar1' => $calendar1,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendar1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar1 $calendar1, Calendar1Repository $calendar1Repository): Response
    {
        $form = $this->createForm(Calendar1Type::class, $calendar1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendar1Repository->save($calendar1, true);

            return $this->redirectToRoute('app_calendar1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar1/edit.html.twig', [
            'calendar1' => $calendar1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar1_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar1 $calendar1, Calendar1Repository $calendar1Repository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar1->getId(), $request->request->get('_token'))) {
            $calendar1Repository->remove($calendar1, true);
        }

        return $this->redirectToRoute('app_calendar1_index', [], Response::HTTP_SEE_OTHER);
    }



    

}
