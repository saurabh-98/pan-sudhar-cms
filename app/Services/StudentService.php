<?php
namespace App\Services;

use App\Repositories\StudentRepository;
use App\DTO\StudentDTO;

class StudentService
{
    public function __construct(protected StudentRepository $repo){}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getAllStudents()
    {
        return $this->repo->getAll();
    }

    public function create(
    StudentDTO $dto
) {

    /*
    |--------------------------------------------------------------------------
    | PHOTO
    |--------------------------------------------------------------------------
    |
    | Photo already uploaded from controller
    | using:
    |
    | ->store('students','public')
    |
    | So here photo is already a stored path string.
    |
    */

    $photoPath =
        $dto->photo;

    /*
    |--------------------------------------------------------------------------
    | STORE STUDENT
    |--------------------------------------------------------------------------
    */

    return $this->repo->store(

        $dto->toArray(
            $photoPath
        )
    );
}


    public function update($id, StudentDTO $dto)
    {
        $photoPath = null;

        if ($dto->photo) {
            $photoPath = $dto->photo->store('students','public');
        }

        return $this->repo->update($id, $dto->toArray($photoPath));
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}