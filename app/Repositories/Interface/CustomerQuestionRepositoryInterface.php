<?php

namespace App\Repositories\Interface;

interface CustomerQuestionRepositoryInterface
{
    public function getAllQuestions();
    public function dataTable();
    public function dataTableWithAjaxSearch($productId);
    public function getAllQuestionsForProduct($productId);
    public function findQuestionById($id);
    public function findAnswerById($id);
    public function updateOrCreateAnswer($request);

    public function destroyQuestion($id);
}
