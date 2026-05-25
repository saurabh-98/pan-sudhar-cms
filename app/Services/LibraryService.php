<?php
namespace App\Services;

use App\Repositories\LibraryRepository;
use App\DTO\LibraryDTO;
use Carbon\Carbon;

class LibraryService
{
    public function __construct(private LibraryRepository $repo) {}

    public function addBook(LibraryDTO $dto)
    {
        return $this->repo->createBook([
            'title' => $dto->title,
            'author' => $dto->author,
            'quantity' => $dto->quantity,
            'available_quantity' => $dto->quantity,
        ]);
    }

    public function getIssues()
    {
        return $this->repo->getAllIssues();
    }

    public function getBooks()
    {
        return $this->repo->getAllBooks();
    }

    public function issueBook(LibraryDTO $dto)
    {
        $book = $this->repo->findBook($dto->book_id);

        if ($book->available_quantity <= 0) {
            throw new \Exception("Out of stock");
        }

        $this->repo->issueBook([
            'book_id' => $dto->book_id,
            'user_id' => $dto->user_id,
            'issue_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        $book->decrement('available_quantity');
    }

    public function returnBook($id)
    {
        $issue = $this->repo->findIssue($id);

        if ($issue->status === 'returned') {
            return ['fine'=>0];
        }

        $today = Carbon::today();
        $due = Carbon::parse($issue->due_date);

        $fine = 0;

        if ($today->gt($due)) {
            $days = $due->diffInDays($today);
            $fine = $days * 10;
        }

        $issue->update([
            'status'=>'returned',
            'return_date'=>$today
        ]);

        $issue->book->increment('available_quantity');

        return ['fine'=>$fine];
    }

    public function deleteBook($id)
    {
        return $this->repo->deleteBook($id);
    }
}