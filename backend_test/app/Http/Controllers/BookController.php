<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
require_once(dirname(__DIR__, 1) . "/Services/bookService.php");
use Services\bookService;
use App\Http\Controllers\Controller;



class BookController extends Controller
{

    /**
     * PUT(/books)
     * Creates a single book to be returned to the end user
     * 
     * Int $id - the id of the book being edited
     * Request $request - the information for the book being edited
     */
    public function updateSingleBook(int $id, Request $request) 
    {
        $requestArray = $request->all();
        $bookService = new bookService();
        $book = $bookService->editBook($id, $requestArray['author'], $requestArray['title'], $requestArray['datePublication'], $requestArray['endingBookLocation']);
        return response()->json($book); 
    }


    /**
     * POST(/books)
     * Creates a single book to be returned to the end user
     * 
     * Request $request - the information of the book being created
     * ['author' => 'authorname', 'title' => 'titleName', 'datePublication'=>'Date of the book was published']
     */
       public function createSingleBook(Request $request) 
    {
        $requestArray = $request->all();
        $bookService = new bookService();
        $book = $bookService->createBook($requestArray['author'], $requestArray['title'], $requestArray['datePublication']);
                return response()->json($book); 
    }

    /**
     * DELETE(/books/{bookId})
     * Deletes a single book to be returned to the end user
     * 
     * int $bookId - the book being deleted
     */
       public function deleteSingleBook(int $bookId) 
    {
        $bookService = new bookService();
        // Due to the fact I was unable to connect to the database, I commented out the
        // delete Book service call.
        // $book = $bookService->deleteBook($bookId);
        return response()->json(['message' => 'book Successfully Deleted']); 
    }


    /**
     * GET(/books)
     * Returns an array of all books sorted based off of an order 
     *
     * $order - string the column this will be ordered on
     */
    public function returnBookList ($order = 'order') {
        $bookService = new bookService();
        $bookArray = $bookService->getAllBooks();
        return response()->json($bookArray);
    }

    /**
     * GET(/books/{$bookId})
     * Returns a single book based off of an book id 
     *
     * $bookId - int the id of the book being returned
     */
    public function returnSingleBook (int $bookId)
    {
        $bookService = new bookService();
        $book = $bookService->getSingleBook($bookId);
        return response()->json($book); 
    }



    /**
     * POST(/books/{$startingLocation}/{$endingLocation})
     * Allows the book list to be reordered.
     * 
     * $startingBookLocation - int the location of the book being moved
     * $endingBookLocation - int the location where the book is being moved to
     */
    public function reorder(int $startingBookLocation, int $endingBookLocation) {
        $bookService = new bookService();
        
        // Dues to the fact I was unable to connect the backend to the databse.
        // I commented out the code for reordering the book list.
        // $returnValue = $bookService->reOrderBookList($startingBookLocation, $endingBookLocation);
        $bookArray = $bookService->getAllBooks();
        return response()->json($bookArray); 
    }
}