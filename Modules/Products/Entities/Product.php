<?php namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Base;
use Modules\Core\Presenters\PresentableTrait;
use Modules\Products\Database\factories\ProductFactory;

class Product extends Base {

    use PresentableTrait, HasFactory;

    protected $presenter = 'Modules\Products\Presenters\ModulePresenter';

    protected $fillable = [
        'name',
        'cost',
        'amount_available',
        'seller_id'
    ];

    /**
     * @return ProductFactory
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
    }

}
