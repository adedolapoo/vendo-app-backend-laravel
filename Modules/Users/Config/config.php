<?php

return [
    'name' => 'Users',
    'driver' => 'Sanctum',
    'deposit_range' => [5,10,20,50,100],
    /*
    |--------------------------------------------------------------------------
    | Fillable user fields
    |--------------------------------------------------------------------------
    | Set the fillable user fields, those fields will be mass assigned
    */
    'fillable' => [
        'email',
        'password',
        'name',
        'role',
        'deposit',
    ]
];
