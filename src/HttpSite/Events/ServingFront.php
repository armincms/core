<?php 
namespace Core\HttpSite\Events; 
 
use Illuminate\Queue\SerializesModels; 
use Illuminate\Foundation\Events\Dispatchable; 
use Core\HttpSite\Component;

class ServingFront
{
    use Dispatchable, SerializesModels; 
}
 