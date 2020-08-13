<?php 
namespace Core\Crud\Concerns;

trait HasAlias
{ 
	public static function bootHasAlias() 
	{
		static::saving(function($model) {
            if(! $model->aliasIsValid()) {
                $model->setAlias();
            }  
		});
	}

    public function setAlias()
    {  
        $this->fill([
            $this->getAliasColumn() => $this->uniqueAlias($this->newAlias())
        ]); 
    }

    public function newAlias()
    {
        $alias = $this->retrieveAlias();

        if(! $this->isValidAliasString($alias)) {
            $alias = $this->getAlternativeAlias();
        }

        if(! $this->isValidAliasString($alias)) {
            $alias = $this->suggestedAlias();
        }

        return armin_slug($alias); 
    }

    protected function isValidAliasString(string $string = null)
    {
        return ! (is_null($string) || empty(armin_slug($string)));
    }

    protected function getAlternativeAlias()
    { 
        $column = isset($this->aliasAlternative) ? $this->aliasAlternative : 'title';

        $string = array_get($this, $column, $this->suggestedAlias());

        if(empty($string)) {
            $string = $this->suggestedAlias();
        }

        return $string; 
    }

    protected function uniqueAlias($alias)
    {
        $i = 1;

        while (! $this->isUniqueAlias($alias)) {
            $alias = preg_replace('/-[0-9]+$/', '', $alias) .'-'.$i++;
        }

        return $alias;
    } 

    protected function aliasIsValid()
    {
        $alias = $this->retrieveAlias();

        if(! is_string($alias)) {
            return false;
        }

        if(empty(trim($alias))) {
            return false;
        }

        return $this->isUniqueAlias($alias);
    }

    public function retrieveAlias()
    {
        $column = $this->getAliasColumn();

        return $this->{$column};
    }

    public function getAliasColumn()
    {
        return isset($this->aliasColumn) ? $this->aliasColumn : 'alias';
    }

    /**
     * Check alias exists in table or not.
     * 
     * @return boolean
     */
    protected function isUniqueAlias($alias)
    {
        $count = $this->newQueryWithoutScopes()->where(
            'id', '!=', array_get($this->attributes, 'id')
        )->where([$this->getAliasColumn() => trim($alias)])->count(); 
         
        return $count === 0;
    } 

    public function suggestedAlias()
    {
        return $this->getAliasColumn() . time();
    }
	
}