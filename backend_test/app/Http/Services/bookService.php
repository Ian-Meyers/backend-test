<?php

namespace Services;

require_once(dirname(__DIR__, 1) . "/Models/book.php");
use book;
use Illuminate\Support\Facades\DB;


class bookService {

	/**
	 * Returns a single book based off of an id
	 * 
	 * $bookId int - The id of the single book 
	 * return Book returns a book with the value
	 */
	public function getSingleBook($bookId)
	{
		// I am unable to connect the backend to the database,
		// as such, I comented out the db call and returned a default book
		//$returnBook = Book::find($bookId);
		
		$returnBook = new Book();
		$returnBook->setOrder(1);
		$returnBook->setAuthor("Ian Meyers");
		$returnBook->setTitle("My project");


		if ($returnBook) {
			return $returnBook;
		} else {
			throw new error("book not found!");
		}
	}

	/**
	 * Returns all books ordered by the column given
	 * currently reuturns two defaulted values.
	 * 
	 * $order string - the column that will be ordered by the book
	 * returns Array pf the books given
	 */
	public function getAllBooks($order = 'order')
	{
		$bookArray = array();

		// I am unable to connect the backend to the database,
		// as such, I comented out the dbcall and returned an array book
		//$bookArray = Book::all()->orderBy($order, 'ASC')->get();
		

		$book = new Book();
		$book->setOrder(1);
		$book->setAuthor("Ian Meyers");
		$book->setTitle("My project");
		$bookArray[] = $book;

		$book2 = new Book();
		$book2->setOrder(2);
		$book2->setAuthor("Another Author");
		$book2->setTitle("Another Book");
		$bookArray[] = $book2;

		return ($bookArray);
	}

	/**
	 * Creates a book and returns a prepresentaiton of the book
	 * puts the book to the end of the list
	 * (currently cannot save the book and get the order correct)
	 * 
	 * $author string - a string represtation of the author
	 * $title string - a string represation of the title
	 * $datePublication string - a string represation of the date it was published
	 * returns Book 
	 */
	public function createBook($author, $title, $datePublication)
	{
		$book = new Book();
		$book->setAuthor($author);
		$book->setTitle($title);
		$book->setPublicationDate($datePublication);
		$book->setOrder(100);


		// Due to the fact I was unable to connect the database, I 
		// am commenting out the db call sections and put in a default
		// Grabs the last order in the database and moves the new book one
		// after the last book.
		//$lastBook = Book::all()->orderBy('order', 'desc')->get()->first();
		//$lastBookOrder = $lastBook->getOrder();
		// $book->setOrder($lastBookOrder + 1);
		//$book->save();

		return $book;
	}

	/**
	 * Updates a book and returns a prepresentaiton of the book
	 * 
	 * $id int - the id of the book being edited
	 * $author string - a string represtation of the author
	 * $title string - a string represation of the title
	 * $datePublication string - a string represation of the date it was published
	 * endingBookLocation int - an int of where the book will be located
	 * returns Book 
	 */	
	public function editBook($id, $author, $title, $datePublication, $endingBookLocation)
	{
		$book = $this->getSingleBook($id);
		$book->setAuthor($author);
		$book->setTitle($title);
		$book->setPublicationDate($datePublication);

		$startingBookLocation = $book->getOrder();

		// Due to the fact I am unable to connect to the database,
		// I removed the call to the database
		//$this->reOrderBookList($startingBookLocation, $endingBookLocation);
		//$book->save();

		return $book;
	}

	/**
	 * Takes the list of all books in the database and reoders the book at location
	 * $startingBookLocation and moves it into the $endingBookLocation. This is done by
	 * moving all book below the ending location down one spot, placing the starting 
	 * location into the ending location and moving everything below the starting location
	 * up by one spot.
	 *
	 * $startingBookLocation - int the location of where the book starts
	 * $endingBookLocation - int the location wher ethe book should end up
	 */
	public function reOrderBookList($startingBookLocation, $endingBookLocation)
	{
		// makes sure the user does not move the book out of bounds.
		$endingBookLocation = $this->limitOrder($endingBookLocation);

		// This algorithm has a quirk where you have to add one to either the 
		// starting or ending location depending on which one is bigger
		if ($endingBookLocation > $startingBookLocation) {
			
			// The ending is below the starting location which means it needs to be 
			// down one spot to be moved up in the second query
			$endingBookLocation = $endingBookLocation + 1;
		} else {

			// The starting location is below the ending location. It needs to be 
			// moved up one spot so it is tracked when everything is moved down to
			// let the book move in
			$startingBookLocation = $startingBookLocation + 1;
		} 

		// grab all books that are above the ending locaiton of book and moves them down one spot
		$bookArray = Book::all()->where("order > ".(int)$endingBookLocation)->orderBy('order', 'desc')->get();
		foreach ($bookArray as $order => $book)
		{
			$bookOrder = $book->getOrder();
			$book->setOrder($bookOrder+1);
			$book->save();
		}

		// takes the book that is being reordered and puts it into the ending location
		$book = Book::find()->where('order', $startingBookLocation)->first();
		$book->setOrder($endingBookLocation);
		$book->save();

		$this->moveAllBooksUp($startingBookLocation);

		return true;
	}

	/**
	 * Used when ordering books, takes the max limit
	 * and makes sure the use is within the max and limt
	 * 
	 * $endingBookLocation - int where the book is ending
	 * return int - the modified books ending location
	 */
	private function limitOrder($endingBookLocation){
		$maxBookOrder = Book::all()->orderBy('order', 'ASC')->get()->first();
		$maxBookOrder = $maxBookOrder->getOrder();

		if ($endingBookLocation < 0) {
			$endingBookLocation = 1;
		} else if ($endingBookLocation > $maxBookOrder) {
			$endingBookLocation = $maxBookOrder;
		}
		return $endingBookLocation;
	}

	/**
	* Takes the list of all records in the database below a location and moves it up one
	* 
	* int $bookLocation - the location of the books that need to be moved up
	*/
	private function moveAllBooksUp ($bookLocation) {
				
		// takes all of places above the starting location book that was moved and move it up one spot.
		$bookArray = Book::all()->where("order > ".(int)$bookLocation)->orderBy('order', 'ASC')->get();
		foreach ($bookArray as $order => $book)
		{
			$bookOrder = $book->getOrder();
			$book->setOrder($bookOrder-1);
			$book->save();
		}
	}

	/**
	 * Deletes the book from the database
	 *
	 * $bookId - int the id of hte book being deleted
	 */
	public function deleteBook ($bookId)
	{
		$book = $this->getSingleBook($bookId);
		$bookOrder = $book->getOrder();
		$book->delete();

		// moves all books up to maintain consistentcy
		$this->moveAllBooksUp($bookOrder);

		return true;

	}

}