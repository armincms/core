<?php 
namespace Core\Crud\Concerns;

use Illuminate\Support\HtmlString;

trait HasIcon
{ 
	public function iconField(string $name = 'icon', $label='admin-crud::title.icon', $value = null,  $attributes=[], $wrapper_attributes=[], $help=null)
    {
        return 
            $this->field('text', 'icon', false, 'rental-home::title.icon', 
                new HtmlString('<i class=icon-edit></i>'), 
                $value, $attributes + ['dir' => 'ltr'], $wrapper_attributes, $help)
            ->raw($this->getIconsModal())
            ->pushScript('icon-modal', 'jQuery(document).ready(function($) {$(this).on("click", "i.icon-edit", function(event) {var $input = $(this).closest("p").find("input");var $modalElement = $(".remodal-icon");var $modal = $modalElement.remodal({hasTracking:false});$modal.open();
                $modalElement.find("[role=icon-item]").click(function(event) {$modal.close();$input.val($(this).data("icon")); }); }); });');
    }

    public function getIconsModal()
    {
        if(isset($this->iconsModal)) {
            return;
        }

        $this->iconsModal = true;

        return '<div class="remodal-icon remodal" >' .$this->getUnifiedListIcon(). '</div>';
    }

    public function getUnifiedListIcon()
    { 
        $key = 0; 
        $icons = '';

        foreach (armin_icons() as $icon) { 
            $color = $key++%3 ? 'green' : 'blue';

            $icons .= "<div title={$icon} data-icon={$icon} role=icon-item class='with-tooltip column button {$color}-gradient size1 icon-{$icon}' style='padding:5px; font-size:25px; cursor:pointer'></div>";
        }

        return "<div class=columns>{$icons}</div>";
    }
}