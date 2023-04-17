<?php

namespace App\Controller;

use App\Model\ArticleManager;

class ArticleController extends AbstractController
{
    /**
     * List articles
     */
    public function articleList(): string
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('title');

        return $this->twig->render('Article/articlelist.html.twig', ['articles' => $articles]);
    }

        /**
     * création premiere page!!!!
     */
    public function test(): string
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('title');

        return $this->twig->render('Article/test.html.twig', ['test' => $articles]);
    }

    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Article/show.html.twig', ['article' => $article]);
    }




    /**
     * Edit a specific item
     */
    public function edit(int $id): ?string
    {
        $itemManager = new ArticleManager();
        $item = $itemManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);

            header('Location: /items/show?id=' . $id);

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Item/edit.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * Add a new item
     */
    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $itemManager = new ArticleManager();
            $id = $itemManager->insert($item);

            header('Location:/items/show?id=' . $id);
            return null;
        }

        return $this->twig->render('Item/add.html.twig');
    }

    /**
     * Delete a specific item
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new ArticleManager();
            $itemManager->delete((int)$id);

            header('Location:/items');
        }
    }
}
