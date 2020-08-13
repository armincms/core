<?php 
namespace Core\Crud\Concerns;
use Core\User\Concerns\Ownable;
use Core\Crud\Statuses;

trait HasPublishing
{  
    private $publishingStatus = 'darft';

    public function addPublishing($name='status', $label=null, $filtered=[], $attrs=[], $wrap_attrs=[])
    {  
        $label = $label? $label : 'admin-crud::title.publication_status';

        $this->pushDatepickerScript();

        $statuses = $this->getStatuses($filtered);

        $this
            ->field('select', 'status', false, $label, $statuses, null, [
                'role' => 'publishing', 
                'data-target' => '.persian-datepicker.' .input_name_id($name)
            ] + $attrs, [], [], $wrap_attrs)  
            ->datepickerField(
                'release_date', "admin-crud::title.release_date", $attrs, $wrap_attrs, input_name_id($name)
            )
            ->datepickerField(
                'finish_date', "admin-crud::title.finish_date", $attrs, $wrap_attrs, input_name_id($name)
            )
            ->datepickerField(
                'archive_date', "admin-crud::title.archive_date", $attrs, $wrap_attrs, input_name_id($name)
            ); 

        return $this;
    } 

    public function pushDatepickerScript()
    { 
        return $this->pushScript(
            'persianDatepicker', view('admin-crud::components.persiandatepicker')
        ); 
    }

    protected function datepickerField($name, $label=null, $attrs=[], $wrap_attrs=[], $target=null)
    {  
        $attrs['class'] = "persian-datepicker ltr input-unstyled full-width {$target}";   
        $attrs['role'] = 'auto-width';  

        $this->field(
            'text', $name, false, $label, $this->calendarIcon(), null, $attrs, $wrap_attrs
        );

        return $this; 
    }

    public function getStatuses($filtered)
    {
        $statuses = collect([
            'draft'     => Statuses::key('draft'), 
            'pending'   => Statuses::key('pending'), 
            'published' => Statuses::key('published'), 
            'archived'  => Statuses::key('archived'), 
            'scheduled' => Statuses::key('scheduled'),   
        ]);

        if(isset($filtered['only'])) {
            $statuses = Statuses::all()->only($filtered['only'])->sortBy(
                function($status) use ($filtered) { 
                    return array_search($status, $filtered['only']);
                }
            );
        } else if(isset($filtered['except'])) {
            $statuses = Statuses::all()->except($filtered['except'])->sortBy(
                function($status) use ($filtered) {
                    return array_search($status, $filtered['except']);
                }
            );
        }

        return $statuses->mapWithKeys(function($value, $key) {
            return [$value => armin_trans('admin-crud::status.' . $key)];
        })->toArray(); 
    }

    public function transformStatus($status)
    {
        $this->publishingStatus = Statuses::all()->flip()->has($status) 
                                        ? $status : Statuses::get('draft');

        return $this->publishingStatus;
    }

    public function transformReleaseDate($date)
    {
        if(! $this->isScheduled()) {
            return null;
        }

        return strtotime($date) ? $date : now();
    }

    public function transformFinishDate($date)
    {
        return $this->isScheduled() ? $date : null;
    } 

    public function transformArchiveDate($date)
    {
        return $this->isScheduled() ? $date : null;
    }

    public function isScheduled()
    {
        return $this->publishingStatus === Statuses::key('scheduled');
    }

    public function calendarIcon()
    {
        return new \Illuminate\Support\HtmlString('<span class="icon-calendar"></span>');
    }
}