<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $bookId = $this->route('book')?->id; // null if creating

        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'isbn' => 'required|string|max:64|unique:books,isbn' . ($bookId ? ",$bookId" : ''),
            'published_at' => 'nullable|date',
            'copies_total' => 'required|integer|min:0',
            'copies_available' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        // Default copies_available to copies_total if missing when creating
        if ($this->filled('copies_total') && !$this->has('copies_available')) {
            $this->merge(['copies_available' => $this->input('copies_total')]);
        }
    }
}
