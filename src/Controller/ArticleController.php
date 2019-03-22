<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        return $this->render('article/homepage.html.twig', [
            'articles' => $repository->findAllPublishedOrderedByNewest()
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug, SlackClient $slack, ArticleRepository $repository)
    {
        $article = $repository->findOneBySlugWithJoinComments($slug);

        if ($article->getSlug() === 'khaaaaaan') {
            $slack->sendMessage('Ah, Kirk, my old friend...');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $logger->info('Article is being hearted ;)');
        $article->incrementHeartCount();
        $em->flush();

//        return new JsonResponse(['hearts' => rand(5, 100)]);
        return $this->json(['hearts' => $article->getHeartCount()]);
    }
}
