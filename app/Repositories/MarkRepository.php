<?php
namespace App\Repositories;

use App\Models\Mark;

class MarkRepository
{
   public function getAll()
    {
        return Mark::with([
                'student:id,name',
                'subject:id,name',
                'exam:id,name'
            ])
            ->select('id','student_id','subject_id','exam_id','marks','created_at')
            ->latest()
            ->paginate(20);
    }

    public function store($data)
    {
        return Mark::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'subject_id' => $data['subject_id'],
                'exam_id' => $data['exam_id']
            ],
            ['marks' => $data['marks']]
        );
    }

    public function update($id, $data)
    {
        $mark = Mark::findOrFail($id);

        $mark->update($data);

        return $mark;
    }

    public function delete($id)
    {
        return Mark::destroy($id);
    }
}