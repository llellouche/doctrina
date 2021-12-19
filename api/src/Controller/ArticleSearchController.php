<?php

namespace App\Controller;

use App\Entity\Article;
use App\Handler\SearchArticleHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ArticleSearchController extends AbstractController
{
    private $searchArticleHandler;

    public function __construct(SearchArticleHandler $searchArticleHandler)
    {
        $this->searchArticleHandler = $searchArticleHandler;
    }

    public function __invoke(string $search): array
    {
        return $this->searchArticleHandler->handle($search);
    }
}
