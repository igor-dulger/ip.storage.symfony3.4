<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\IP;
use AppBundle\Form\IP as FormIp;
use AppBundle\Form\IPSearch as FormIpSearch;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction(Request $request, ValidatorInterface $validator)
    {
        // replace this example code with whatever you need

        $createForm = $this->createForm(FormIp::class, new IP(), [
            'action' => $this->generateUrl('ip.save'),
            'method' => 'POST',
        ]);

        $searchForm = $this->createForm(FormIpSearch::class, new IP());
        $searchForm->handleRequest($request);


        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $task = $searchForm->getData();
            dump($task);
        }

        return $this->render('default/index.html.twig', [
            'createForm' => $createForm->createView(),
            'searchForm' => $searchForm->createView(),
        ]);
    }

    public function saveAction(Request $request, ValidatorInterface $validator)
    {
        $ip = new IP();
        $form = $this->createForm(FormIp::class, $ip);

        $form->handleRequest($request);
        $errors = $validator->validate($ip);

        if ($form->isSubmitted()) {
            if (count($errors) > 0) {
                foreach($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            $count = 1;
            $ip->setCounter(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ip);
            $entityManager->flush();
            $this->addFlash('success', "Ip was added, count" . $count);
        }
        return $this->redirectToRoute('ip.search');

    }
}
