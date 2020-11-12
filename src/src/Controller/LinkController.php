<?php

namespace App\Controller;

use App\Entity\ShardOne\LinkOdd;
use App\Entity\ShardTwo\LinkEven;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends AbstractController
{
    /**
     * @Route("/link/revert", name="link_revert")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function revert(Request $request)
    {
        if ($request->request->has('form')) {
            $url = $request->request->get('form')['url'];
            $url = explode('/', $url);
            $hash = end($url);
            $hash = explode('-', $hash);
            $shard = reset($hash);

            switch ($shard) {
                case LinkEven::SHARD:
                    $class = LinkEven::class;
                    break;
                default:
                    $class = LinkOdd::class;
            }

            $entityManager = $this->getDoctrine()->getManager($shard);
            $link = $entityManager->getRepository($class)->findOneBy(['url_hash' => $hash]);

            return $this->redirectToRoute('link_show', ['hash' => $link->getShard() . '-' . $link->getUrlHash()]);
        }

        $form = $this->createFormBuilder(new LinkEven())
            ->add('url', UrlType::class)
            ->add('save', SubmitType::class, ['label' => 'Convert to real Link'])
            ->getForm();
        return $this->render('link/revert.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/link/show/{hash}", name="link_show")
     * @param Request $request
     * @param $hash
     * @return Response
     */
    public function show($hash)
    {
        $hash = explode('-', $hash);
        $shard = reset($hash);
        $hash = $hash[1];
        switch ($shard) {
            case LinkEven::SHARD:
                $class = LinkEven::class;
                break;
            default:
                $class = LinkOdd::class;
        }
        $entityManager = $this->getDoctrine()->getManager($shard);
        $link = $entityManager->getRepository($class)->findOneBy(['url_hash' => $hash]);
        if (isset($link)) {
            return $this->render('link/show.html.twig', [
                'controller_name' => 'LinkController',
                'url' => $link->getUrl(),
                'shortened_url' => 'https://bitzy.com/' . $link->getShard() . '-' . $link->getUrlHash()
            ]);
        } else {
            return $this->render('404.html.twig', [
                'controller_name' => 'LinkController',
            ]);
        }
    }

    /**
     * @Route("/link/new", name="link_new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        if ($request->request->has('form')) {
            $url = $request->request->get('form')['url'];
            if (strlen($url) % 2 == 0) {
                $shard = LinkEven::SHARD;
                $link = new LinkEven();
            } else {
                $shard = LinkOdd::SHARD;
                $link = new LinkOdd();
            }
        } else {
            $shard = LinkEven::SHARD;
            $link = new LinkEven();
        }

        $form = $this->createFormBuilder($link)
            ->add('url', UrlType::class)
            ->add('save', SubmitType::class, ['label' => 'Create shortened Link'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $link = $form->getData();
            $entityManager = $this->getDoctrine()->getManager($shard);
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_show', ['hash' => $link->getShard() . '-' . $link->getUrlHash()]);
        }

        return $this->render('link/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
