<?php
namespace Armincms\Template\Tables;

use League\Fractal\TransformerAbstract;       
use Core\Crud\Tables\HasAction;

class TemplateTransformer extends TransformerAbstract  
{   
    use HasAction;

    protected $resource;
    public $currentItem;

    public function __construct($resource)
    {
        $this->resource = $resource;  
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $template
     * @return array
     */
    public function transform($template)
    {   
        $this->currentItem = $template;   

        return [
            'name'        => $template->name() . '<br><small>' .$template->description(). '</small>',
            'title'       => $template->label(), 
            'action'      => $this->addActions(
                $this->appendResourceToActions($this->getDefaultActions($template), $template)
            )
        ]; 
    } 

    public function getDefaultActions($template)
    {   
        $actions['edit']    = $this->route('edit', $template->name()); 
        $actions['default'] = [
            'href' => $this->route('default', $template->name()),
            'class' => 'button with-tooltip glossy white-gradient icon-star  confirm-resource '. 
                        ($template->isDefault() ? 'orange' : ''),
            'title' => 'Set As Default',
            'data-input' => json_encode(['method' => 'post']),
            'data-confirm-options' => json_encode([
                'message' => armin_trans("template::title.set_as_default")
            ])
        ]; 

        return $actions;
    }
 
 

    protected function appendResourceToActions($actions, $resource)
    { 
        return collect($actions)->map(function($data) use ($resource) {
            $data = is_array($data) ? $data : ['href' => $data];
            $data['resource'] = $resource; 

            return $data;
        })->toArray(); 
    } 
  

    public function route($action, $resource)
    {
        $name = $this->resource->name();

        return route("{$name}.{$action}", $resource);
    } 
 
}