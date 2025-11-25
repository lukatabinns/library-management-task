<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // GET /api/books
    // supports pagination and filters: ?q=search&genre=&author=&isbn=&page=
    public function index(Request $req)
    {
        $perPage = (int) $req->query('per_page', 15);
        $q = $req->query('q');
        $genre = $req->query('genre');
        $author = $req->query('author');
        $isbn = $req->query('isbn');

        $query = Book::query();

        if ($q) {
            $query->where(function($builder) use ($q) {
                $builder->where('title', 'like', "%$q%")
                        ->orWhere('author', 'like', "%$q%")
                        ->orWhere('isbn', 'like', "%$q%");
            });
        }

        if ($genre) $query->where('genre', $genre);
        if ($author) $query->where('author', 'like', "%$author%");
        if ($isbn) $query->where('isbn', $isbn);

        // Sort options
        $sortBy = $req->query('sort_by', 'created_at');
        $sortDir = $req->query('sort_dir', 'desc');

        $query->orderBy($sortBy, $sortDir);

        return response()->json($query->paginate($perPage));
    }

    // POST /api/books (librarian)
    public function store(BookRequest $req)
    {
        $data = $req->validated();
        $book = Book::create($data);
        return response()->json($book, 201);
    }

    // GET /api/books/{book}
    public function show(Book $book)
    {
        return response()->json($book);
    }

    // PUT/PATCH /api/books/{book} (librarian)
    public function update(BookRequest $req, Book $book)
    {
        $book->update($req->validated());
        return response()->json($book);
    }

    // DELETE /api/books/{book} (librarian)
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted']);
    }

    // Borrow/Return endpoints could be added here — simple idea below:
    // POST /api/books/{book}/borrow -> reduce copies_available if > 0
    public function borrow(Request $req, Book $book)
    {
        if ($book->copies_available < 1) {
            return response()->json(['message' => 'No copies available'], 400);
        }
        $book->decrement('copies_available');
        // Create a Borrow record in production to track user, due dates, etc.
        return response()->json(['message'=>'Borrowed', 'book' => $book]);
    }

    // POST /api/books/{book}/return
    public function return(Request $req, Book $book)
    {
        if ($book->copies_available >= $book->copies_total) {
            return response()->json(['message' => 'All copies already returned'], 400);
        }
        $book->increment('copies_available');
        return response()->json(['message'=>'Returned', 'book' => $book]);
    }
}

