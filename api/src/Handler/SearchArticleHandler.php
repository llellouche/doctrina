<?php
namespace App\Handler;

use App\Entity\Article;
use App\Repository\ArticleRepository;

class SearchArticleHandler {
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return Article[]
     */
    public function handle(string $search): array
    {
        $search = json_decode($search);

        $searchQuery = $search->query ?? '';
        $searchTags  = $search->tags ?? [];
//
//        dump(count($this->articleRepository->findArticles('sqe', $searchTags)));
//        exit();
        return $this->articleRepository->findArticles($searchQuery, $searchTags);
    }
}
