<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_create_update_delete_book()
    {
        $librarian = User::factory()->create(['role' => 'librarian']);
        $token = auth('api')->login($librarian);

        // Create book
        $resp = $this->withHeader('Authorization', "Bearer $token")
                     ->postJson('/api/books', [
                        'title' => 'Test Book',
                        'author' => 'Author',
                        'isbn' => 'ISBN-1234',
                        'copies_total' => 3,
                        'copies_available' => 3,
                     ]);
        $resp->assertStatus(201)->assertJsonFragment(['title'=>'Test Book']);

        $bookId = $resp->json('id');

        // Update
        $this->withHeader('Authorization', "Bearer $token")
             ->putJson("/api/books/{$bookId}", ['title' => 'Updated Title','isbn'=>'ISBN-1234','author'=>'Author','copies_total'=>3])
             ->assertStatus(200)
             ->assertJsonFragment(['title'=>'Updated Title']);

        // Delete
        $this->withHeader('Authorization', "Bearer $token")
             ->deleteJson("/api/books/{$bookId}")
             ->assertStatus(200);

        $this->assertDatabaseMissing('books', ['id' => $bookId, 'deleted_at' => null]);
    }

    public function test_regular_user_cannot_create_book()
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = auth('api')->login($user);

        $this->withHeader('Authorization', "Bearer $token")
             ->postJson('/api/books', [
                'title'=>'X','author'=>'A','isbn'=>'ISBN-999','copies_total'=>1
             ])->assertStatus(403);
    }

    public function test_public_can_view_books_and_filter()
    {
        Book::factory()->create(['title'=>'Alpha','author'=>'Bob','isbn'=>'A1']);
        Book::factory()->create(['title'=>'Beta','author'=>'Carol','isbn'=>'B2']);

        $this->getJson('/api/books?q=Alpha')->assertStatus(200)->assertJsonFragment(['title'=>'Alpha']);
    }
}
