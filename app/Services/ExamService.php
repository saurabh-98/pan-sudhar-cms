<?php
namespace App\Services;

use App\Repositories\ExamRepository;
use App\DTO\ExamDTO;

class ExamService
{
    public function __construct(protected ExamRepository $repo){}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getAllExams()
    {
        return $this->repo->getAll();
    }

    public function store(ExamDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, ExamDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}