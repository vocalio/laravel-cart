<?php

return [
    /**
     * The name of the table that will be created by the migration.
     */
    'table_name' => 'shopping_cart',

    /**
     * The model that will be used to represent the cart.
     */
    'model' => \Vocalio\LaravelCart\Models\Cart::class,

    /**
     * When you want to connect the cart to a user, you can specify the user model here.
     * For example: `App\Models\User::class`
     * This will allow you to use the `user` relationship on the cart model.
     */
    'user_model' => null,

    /**
     * The session key that will be used to store the cart.
     */
    'session_name' => 'shopping_cart_session',

    /**
     * The item model which id will be stored in the cart.
     * For example: `App\Models\Variant::class` or `App\Models\Product::class`
     */
    'item_model' => null,
];
