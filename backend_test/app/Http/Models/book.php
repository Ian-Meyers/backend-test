<?php

require_once(dirname(__DIR__, 3) . "/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php");
use Illuminate\Database\Eloquent\Model;


class book extends Model 
{

	protected $table = 'book';

	/**
	 * Serializes the book object so it can be sent to the
	 * client. 
	 */
    public function jsonSerialize()
    {
    	// This will take the parent model object, and 
    	// remove those from the book object before sending 
    	$reflect = new ReflectionClass('book');
		$props = $reflect->getProperties();
		$ownProps = [];
		foreach ($props as $prop) {
		    if ($prop->class === 'book') {
		    	$property = $prop->getName();
		        $ownProps[$prop->getName()] = $this->$property;
		    }
		}

		$objectVariables = get_object_vars($this);

    	return array_intersect_key($ownProps, $objectVariables);
    }


	private $id;

	public function getId() {
		return $this->id;
	}


	private $title;

	public function getTitle() {
		return $this->$title;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	private $author;

		public function getAuthor() {
		return $this->author;
	}

	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}


	private $publicationDate;

		public function getPublicationDate() {
		return $this->publicationDate;
	}

	public function setPublicationDate($publicationDate) {
		$this->publicationDate = $publicationDate;
		return $this;
	}

	private $order;

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
		return $this;
	}



}