<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\BookIssue;

class LibraryRepository
{
    /* ================= BOOK ================= */

    public function createBook($data)
    {
        return Book::create($data);
    }

    public function getAllBooks()
    {
        return Book::latest()->get();
    }

    public function findBook($id)
    {
        return Book::findOrFail($id);
    }

    public function deleteBook($id)
    {
        return Book::findOrFail($id)->delete();
    }

    public function decrementStock($bookId)
    {
        return Book::where('id', $bookId)->decrement('available_quantity');
    }

    public function incrementStock($bookId)
    {
        return Book::where('id', $bookId)->increment('available_quantity');
    }

    /* ================= ISSUE ================= */

    public function issueBook($data)
    {
        return BookIssue::create($data);
    }

    public function getAllIssues()
    {
        return BookIssue::with('book','user')->latest()->get();
    }


    public function findIssue($id)
    {
        return BookIssue::findOrFail($id);
    }

    public function markAsReturned($id, $date)
    {
        return BookIssue::where('id', $id)->update([
            'status' => 'returned',
            'return_date' => $date
        ]);
    }
}