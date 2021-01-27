<?php 
namespace Core\Module\Forms;

use Core\Crud\Concerns\HasPublishing; 
use Core\Crud\Concerns\HasResponsiveButton; 
use Core\Crud\Contracts\TabForm; 
use Core\Crud\Forms\ResourceForm;
use Core\Language\Concerns\HasLanguage; 
use Core\Module\Contracts\InstanceRepository;
use Core\Module\ModuleInstance;
use Core\Template\Concerns\HasThemeFacility;
use Core\Crud\Concerns\HasImage;

class ModuleForm extends ResourceForm implements TabForm
{ 
	use HasPublishing, HasLanguage, HasResponsiveButton, HasThemeFacility, HasImage;

    protected $title = 'module::title.global';

    protected $delays = [
        250, 500, 750, 1000, 1250, 1500, 1750, 2000, 2250, 2500, 2750, 3000, 3250
    ];

    protected $effects = [
        'hinge','flip','flipOutX','flipOutY','bounceIn','bounceOut','bounce','flash','pulse','rubberBand',
        'shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft',
        'bounceInRight','bounceInUp','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp',
        'fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp',
        'fadeInUpBig','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight',
        'fadeOutRightBig','fadeOutUp','fadeOutUpBig','flipInX','flipInY','flipOutX','flipOutY','lightSpeedIn',
        'lightSpeedOut','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight',
        'rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge',
        'jackInTheBox','rollIn','rollOut','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','zoomOut',
        'zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp','slideInDown','slideInLeft','slideInRight',
        'slideInUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp'
    ];

    public function build()
    { 
        $module = $this->getModule();  
        $orderings = $this->getOrderings();

        $this
            ->element('hidden', 'module', $module->name())
            ->element('hidden', 'params', '')
            ->raw('<div class=columns><div class=six-columns>')
            ->field('select', 'position', false, 'module::title.position', collect(config('armin.template.position'))->mapWithKeys(function($position, $key) {
                    if(false === array_get($position, 'hidden')) {
                        $name = array_get($position, 'name', $key);
                        $title = $this->getPositionTitle($name);

                        return [$name => $title];
                    }

                    return [];
                })->toArray()
            )
            ->field('select', 'ordering', false, 'module::title.ordering', 
                $orderings->pluck('title', 'value')->prepend(armin_trans('module::title.last'), 'last')->toArray(), [], [], $orderings->pluck('attributes', 'value')->toArray()
            ) 
            ->pushScript('order-script', view('module::order-script', ['module' => $this->model]))
            ->field('select', 'layout', false, 'module::title.layout', 
                layouts('module')->filter(function($layout) {
                    return in_array('module', (array) $layout->group());
                })->mapWithKeys(function($layout) {
                    return [$layout->name() => $layout->label() ?: $layout->name()];
                })->all()
            )
            ->field('inputSelect', 'title', false, 'module::title.title', [], null, [
                'name' => 'params[_config][_show_title]',
                'values' => [
                    '0' => armin_trans('module::title.hide'),
                    '1' => armin_trans('module::title.show'),
                ] 
            ])
            ->field('inputSelect', 'description', false, 'module::title.description', [], null, [
                'name' => 'params[_config][_show_description]',
                'values' => [
                    '0' => armin_trans('module::title.hide'),
                    '1' => armin_trans('module::title.show'),
                ] 
            ]) 
            ->raw('</div><div class=six-columns>')
            ->addPublishing('status', null, ['only' => ['draft', 'published', 'scheduled']])
            ->raw('</div></div>');

        $form = get_class($module).'Form';
 
        if (class_exists($form)) { 
            $this->childs->put(
                'setting', 
                (new $form)->setPrefix('params')
                            ->setModel($this->getModel())
                            ->setParent($this)
            );
        } /*else if(method_exists($module, 'fields')) {
            $this->child('setting', function($form) use ($module) { 
                $module->fields($form->setTitle('module::title.setting')->setPrefix('params'));
            });     
        }*/ 

        $this->child('display-position', function($form) {
            $this->displayPosition($form->setTitle('module::title.display_place'));
        })
        ->child('columniation', function($form) {
           $this->columniation($form->setTitle('module::title.columniation'));
        })
        ->child('appearance', function($form) {
           $this->appearance($form->setTitle('module::title.appearance'));
        })
        ->child('advanced', function($form) {
           $this->advanced($form->setTitle('module::title.advanced'));
        })
        // ->child('customCss', function($form) {
        //    $this->customCss($form->setTitle('module::title.custom_css'));
        // })
        ->pushScript('selection-script', view('module::selection-script', ['module' => $this->model]));
    }

    public function getModule()
    { 
        if(! is_null($this->instance)) {
            return $this->instance;
        }
 
        if(isset($this->model)) {
            $this->instance = app('module')->make($this->model);
        } else if($module = request('instance')) { 
            $this->instance = app('module')->module($module);
        }  else if($module = request()->input('module')) { 
            $this->instance = app('module')->module($module);
        } 

        return $this->instance;
    }

    public function displayPosition($form)
    { 
        $form  
            ->element('hidden', 'locate[]')
            ->raw('<div class=columns><div class=four-columns>')
            ->field('checkable', 'show_on', false, 'module::title.display_position', [
                '*' => [
                    'value' => '*',
                    'label' => 'module::title.all',
                    'attributes' => [
                        'role' => 'selection'
                    ]
                ],
                'selection' => [
                    'value' => 'selection',
                    'label' => 'module::title.selection',
                    'attributes' => [
                        'role' => 'selection'
                    ]
                ],
                'rejection' => [
                    'value' => 'rejection',
                    'label' => 'module::title.rejection',
                    'attributes' => [
                        'role' => 'selection'
                    ]
                ]
            ], optional($this->model)->show_on?: '*', true)
            ->raw('</div><div class=four-columns>')
            ->field(
                'select', 'language', false, 'language::title.language', language()->filter(function($language, $active) { return !$active || $language->status; }
                )->pluck('title', 'alias')->prepend(armin_trans('module::title.all'), '*')->filter()
            ) 
            ->raw('</div><div class=four-columns>')
            ->field('select', 'template', false, 'template::title.template', collect()->prepend(armin_trans('module::title.all'), '*')->filter(), ['*'], ['multiple']
            ) 
            ->raw('</div><div class="twelve-columns hidden" id=selection></div></div>'); 
    }

    public function columniation($form)
    {
        $form
            ->raw('<div class=columns><div class="twelve-columns align-center">')
            ->responsiveButtons('.columniation')
            ->raw('</div><div class=twelve-columns><h4 class="red-gradient underline"></h4>');

        collect(config('armin.template.responsive'))->each(function($responsive, $key) use ($form) {
            $form 
                ->raw("<div class='columns columniation responsive-{$key}'><div class=four-columns>")
                ->field('select', "params[_config][{$key}][column]", false, 'module::title.column_count', 
                    $this->getColumnSizes(), [], [], [], [], ['class' => 'block-label button-height']
                )
                ->raw('</div><div class=four-columns>');

            foreach ($this->getDirections() as $direction => $title) { 
                $form->directionField('margin', $key, $direction, $title);  
            } 

            $form->raw('</div><div class=four-columns>');

            foreach ($this->getDirections() as $direction => $title) { 
                $form->directionField('padding', $key, $direction, $title);  
            } 
            $form->raw('</div></div>');

        });

        $form->raw('</div></div>');
    }

    public function appearance($form)
    {
        $form
            ->raw('<div class=columns><div class=six-columns>')
            ->field('select', 'params[_config][_effect][effect]', false, 'module::title.effect', 
                array_merge(['' => __('Select')], array_combine($this->effects, $this->effects))
            )
            ->field('select', 'params[_config][_effect][delay]', false, 'module::title.delay', 
                array_combine($this->delays, $this->delays)
            )
            ->field('checkable', 'params[_config][_effect][repeat]', false, 'module::title.repeat', [
                'once' => 'module::title.once', 'infinite' => 'module::title.infinite'
            ])
            ->raw('</div><div class=six-columns>')
            ->themeFacilityFields('params[_config][_theme]', ['color', 'image', 'gradient'])
            ->raw('</div></div>');
    }

    public function advanced($form)
    {
        $form
            ->raw('<div class=columns><div class=four-columns>')
            ->field('select', 'params[_config][_class]', false, 'module::title.class', 
                theme_facilities('classes')->pluck('title', 'name')->prepend(armin_trans('armin::title.select'), null)->all()
            )
            ->field('select', 'params[_config][_floating]', false, 'module::title.floating', [
                null        => armin_trans('module::title.default'),
                'pull-left' => armin_trans('module::title.left'),
                'pull-right'=> armin_trans('module::title.right'), 
            ])
            ->field('select', 'params[_config][_direction]', false, 'module::title.direction', [
                null    => armin_trans('module::title.default'),
                'ltr'   => armin_trans('module::title.left'),
                'rtl'   => armin_trans('module::title.right'), 
            ])
            ->field('select', 'params[_config][_align]', false, 'module::title.align', [
                null            => armin_trans('module::title.default'),
                'text-left'    => armin_trans('module::title.left'),
                'text-center'  => armin_trans('module::title.center'),
                'text-right'   => armin_trans('module::title.right'), 
            ]) 
            ->raw('</div></div>');
        return $this;
    }

    public function customCss($form)
    {  
        $form
            ->field('textarea', 'params[_config][_css]', false, null, null, null, ['id' => 'custom-css-editor'], ['class' => 'block-label'])  
            ->pushScript('custom-css-codemirror-js', '/admin/rtl/js/codemirror/lib/codemirror.js', true)   
            ->pushScript('custom-css-css-js', '/admin/rtl/js/codemirror/mode/css/css.js', true) 
            ->pushScript('custom-css-scroll-js', '/admin/rtl/js/codemirror/addon/scroll/simplescrollbars.js', true)
            ->pushScript('custom-css-editor', 'jQuery(document).ready(function($) { 
        var customEditor = CodeMirror.fromTextArea(document.getElementById("custom-css-editor"), { 
            matchBrackets: true,
            mode: "text/x-scss",
            theme: "developer",
            selectionPointer: true, 
        });
    });')
            ->pushStyle('custom-css-codemirror-css', '/admin/rtl/js/codemirror/lib/codemirror.css', true)
            ->pushStyle('custom-css-codemirror-theme', '/admin/rtl/js/codemirror/developer.css', true)
            ->pushStyle('custom-css-scroll-css', '/admin/rtl/js/codemirror/addon/scroll/simplescrollbars.css', true);
            return $this;
        
    }

    public function directionField($type, $responsive, $direction, $title)
    {
        return $this->field(
            'text', "params[_config][{$responsive}][{$type}][{$direction}]", false, 
            $direction == 'top'? "module::title.{$type}" : null, 
            null, null, ['placeholder' => armin_trans($title)], ['class' => 'block-label button-height'
        ]);
    } 

    public function getOrderings()
    {
        return ModuleInstance::get()->sortBy('ordering')->map(function($instance) {
            $title = armin_trans('module::title.module')
                    ." {$instance->id} "
                    . ($instance->title ? "[{$instance->title}]" : '');

            $ordering = "{$instance->id}-{$instance->ordering}";

            if(optional($this->model)->id === $instance->id) {
                $ordering = $this->model->ordering;
            }

            return [ 
                'value'  => $ordering,
                'title'     => $title,
                'attributes'=> [
                    'data-module' => $instance->module,
                    'data-position' => $instance->position
                ]
            ]; 
        }); 
    }

    public function getPositionTitle(string $position)
    { 
        preg_match('/([a-zA-z]+)([0-9]*)/', $position, $matches); 

        return armin_trans("template::position.{$matches[1]}", [
            'number' => $matches[2]
        ]);
    }

    public function getColumnSizes()
    {
        $columns[0] = armin_trans('module::title.default');
        $columns['hidden'] = armin_trans('module::title.hide');

        for ($i = 1; $i < 13; $i++) {
            $columns[$i] = armin_trans('module::title.pillars', ['pillar' => $i]);
        }

        return $columns;
    }

    public function getDirections()
    {
        return [
            'top'   => 'module::title.top',
            'right' => 'module::title.right',
            'bottom'=> 'module::title.bottom',
            'left'  => 'module::title.left', 
        ];
    }

    public function generalMap()
    {
    	return [
    		'language', 'title', 'layout', 'description', 'status', 'module', 'params','position', 
            'ordering', 'show_on', 'locate', 'release_date', 'finish_date', 'archive_date',
    	];
    }

    public function relationMap()
    {
    	return [];
    }

    public function transformParamsConfigTheme($data)
    {  
        return $this->transformThemeFacility((array) $data);
    }

    public function transformLocate($locate = [])
    {
        if(! in_array(request()->input('show_on'), ['selection', 'rejection'])) {
            return [];
        }

        return collect(request()->input('locate'))->map(function($locates) {
            return collect($locates)->filter(function($checked) {
                return (int) $checked;
            })->keys()->map(function($value) {
                return is_numeric($value) ? (int) $value : (string) $value;
            })->all();
        })->filter()->all();
    }
}
