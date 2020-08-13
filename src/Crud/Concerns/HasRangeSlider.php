<?php 
namespace Core\Crud\Concerns;


trait HasRangeSlider
{  
    public function slider($name='image', $label=[], int $min= 10, int $max =100, int $value=null, $options=[], $attrs=[], $wrapper_attrs=[], $help=null)
    {  
        $options['min'] = $min;
        $options['max'] = $max;
        $label = is_array($label) ? $label : compact('label');
        $label['attributes']['class'] = array_get($label, 'attributes.class') . 'label  margin-bottom';
        
        $attrs['data-slider-options'] = $this->parseOptions($options);
        $attrs['role'] = 'progress-slider';
        $attrs['min'] = $min;
        $attrs['max'] = $max;
        $attrs['type'] = 'number';

    	$this->field(
    		'text', $name, false, $label, [], $value, $attrs, $wrapper_attrs, $help, 'number'
    	);

    	$this->pushStyle(
            'progress-slider', '/admin/rtl/css/styles/progress-slider.css?v=1', true
        )->pushScript(
            'progress-slider', '/admin/rtl/js/developr.progress-slider.js', true
        )->pushScript(
            'progress-init', "jQuery(document).ready(function($) {
                $('input[role=\"progress-slider\"]').slider();
                $(\"input[role='progress-slider']\").closest('span').removeClass('input');
            });"
        ); 
    	

    	return $this;
    } 

    public function parseOptions($options = [])
    {
        return collect([
            'hideInput' => true,
            'topMarks'  => 20, 
            'topLabel'  => "[value]%",
            'innerMarks'=> 20,
            'innerMarksOverBar' => true,
            'min' => $options['min'],
            'max' => $options['max'],
            'insetExtremes' => true,
            'bottomMarks' => [
                0 => [
                    "value" => $options['min'],
                    "label" => "حداقل",
                ],
                1 => [
                    "value" => $options['max'],
                    "label" => "حداکثر",
                ],
                "stripesSize" => "thin",
            ],
        ])->merge((array) $options);  
    }  
}