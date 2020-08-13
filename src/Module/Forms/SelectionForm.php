<?php 
namespace Core\Module\Forms;

use Annisa\Form\AnnisaBuilder;
use Core\Crud\Forms\ResourceForm; 

class SelectionForm extends ResourceForm  
{  
    protected $title = '';


    public function __construct($callback = null)
    {
        parent::__construct($callback);

        $this->component = 'amd';
    }

    public function title($title = null)
    {
        if(is_null($title)) {
            return $this->title;
        }

        $this->title = $title;

        return $this;
        
    }

    public function build()
    {
        $this->prefix('locates');

        collect(config('module.selection', $this->sites()))->each(function($selection, $group) {
            $this->child($group, function($form) use ($selection, $group) {
                $form->title(array_get($selection, 'title', $group));
                $callback = array_get($selection, 'items'); 

                if(is_callable($callback) && $items = $callback()) { 
                    foreach ($items as $item) {
                        $id = array_get($item, 'id'); 
                        $form->element('checkbox', "page[{$id}]");
                    }
                } 
            });
        }); 
    }

    public function sites()
    {
        return [
            'site' => [
                'title' => 'sites',
                'items' => function() {
                    return \Component\Blog\Blog::get()->map(function($blog) {
                        return [
                            'id'    => $blog->id,
                            'name'  => $blog->type,
                            'title' => $blog->title,
                            'url'   => $blog->url(),
                            'childrens' => [],
                        ];
                    });
                }
            ]
        ];
    }


    public function generalMap()
    {
        return [];
    }

    public function relationMap()
    {
        return [];
    }

    
}
