<?php

namespace Core\Document\Events;
 

class Rendering
{ 
    /**
     * The document instance.
     * 
     * @var 
     */
    public $document;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($document)
    {
        $this->document = $document;
    } 
}
