<?php 
namespace Core\Menu\Tables;

use Core\Crud\Tables\ResourceTransformer;
use Illuminate\Database\Eloquent\Model;  

class MenuTransformer extends ResourceTransformer
{ 
  
    /**
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @return array
     */
    public function transform(Model $resource)
    {   
        $data = parent::transform($resource);


        $data['title'] = "{$data['title']}<small class=clearfix>{$resource->description}</small>"; 
        $data['language'] = "<span class='lang-icon {$resource->language}-icon'></span>";

        return $data;
    }

    public function getDefaultActions($resource)
    {   
        $actions = parent::getDefaultActions($resource);

        $actions['addItem'] = $this->route('item.edit', $resource);

        return $actions; 
    }

    public function actionAddItem($data)
    {
        return $this->link($this->getLink($data), [
            'class' => 'button with-tooltip glossy orange-gradient icon-menu',
            'title' => armin_trans('menu::title.add_menu_item'), 
        ]);
    }
}
