<?php 
namespace Core\App\Tables;

use Yajra\DataTables\Services\DataTable;
use Core\App\AppLog; 

class AppLogDataTable extends DataTable
{ 

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {    
        return datatables($query) 
                    ->whitelist(['status', 'created_at', 'mobile', 'app_version', 'imei']) 
                    ->setTransformer(new AppLogTransformer);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \Armin\AppLog $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AppLog $model)
    {  
        return $model->newQuery()->with('metas');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())  
                    ->minifiedAjax() 
                    ->parameters($this->getBuilderParameters())
                    ->parameters();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [    
            'mobile' => ['title' => trans('armin::title.mobile')],         
            'city'   => ['title' => trans('armin::title.city'), 'width' => 100],  
            'app_version'   => ['title' => trans('armin::title.app_version'), 'width' => 100],    
            'imei',    
            'status'    => ['title' => trans('armin::title.last_visited'), 'width' => 200],    
            'created_at'    => [
                'title' => trans('armin::title.install_time'), 'width' => 200
            ],           
        ];
    }  
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return "app_log_" . date('YmdHis');
    }
}
