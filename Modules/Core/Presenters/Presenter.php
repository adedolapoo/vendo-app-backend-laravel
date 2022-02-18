<?php
namespace Modules\Core\Presenters;

use Carbon\Carbon;
use Croppa;
use URL;

abstract class Presenter
{

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Allow for property-style retrieval
     *
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }

        return $this->entity->{$property};
    }

    public function dateLocalized($column = 'date')
    {
        return $this->entity->$column->formatLocalized('%d %B %Y');
    }

    /**
     * Return resource's datetime or curent date and time if empty
     *
     * @param  string $column
     * @return Carbon
     */
    public function datetimeOrNow($column = 'date')
    {
        $date = $this->entity->$column ? : Carbon::now() ;
        return $date->format('Y-m-d G:i:s');
    }

    /**
     * Return resource's date or curent date if empty
     *
     * @param  string $column
     * @return Carbon
     */
    public function dateOrNow($column = 'date')
    {
        $date = $this->entity->$column ? : Carbon::now() ;
        return $date->format('Y-m-d');
    }

    /**
     * Return resource's time or curent time if empty
     *
     * @param  string $column
     * @return Carbon
     */
    public function timeOrNow($column = 'date')
    {
        $date = $this->entity->$column ? : Carbon::now() ;
        return $date->format('G:i');
    }
}
