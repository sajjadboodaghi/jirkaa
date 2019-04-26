<?php return array(

    // The title of the application. It can be a translation key.
    'brand' => 'جیرکا',

    // The link to the main page
    'brand_url' => '/',

    // The name of the view that is used to render the dashboard.
    // You can specify an entity id prefixing it with `@` like so: `@users`.
    //'dashboard' => 'cruddy::dashboard',
    'dashboard' => '@reports',

    // The URI that is prefixed to all routes of Cruddy.
    'uri' => 'backend',

    // The class name of permissions driver.
    'permissions' => 'Kalnoy\Cruddy\Service\PermitsEverything',

    // The middleware that wraps every request to Cruddy. Can be used for authentication.
    'middleware' => 'admin',

    // Main menu items.
    //
    // How to define menu items: https://github.com/lazychaser/cruddy/wiki/Menu
    'menu' => [
        [
            'label' => 'گزارش‌ها',
            'entity' => 'reports'
        ],
        [
            'label' => 'برچسب‌ها',
            'entity' => 'tags'
        ],
        [
            'label' => 'پیوندها',
            'entity' => 'links'
        ],
        [
            'label' => 'کاربرها',
            'entity' => 'users'
        ],

    ],

    // The menu that is displayed to the right of the main menu.
    'service_menu' => [

    ],

    // The list of key value pairs where key is the entity id and value is
    // an entity class name. For example:
    //
    // 'users' => 'App\Entities\User'
    //
    // Class is resolved out of IoC container.
    'entities' => [
        'reports' => 'App\\Entities\\ReportSchema',
        'tags' => 'App\\Entities\\TagSchema',
        'links' => 'App\\Entities\\LinkSchema',
        'users' => 'App\\Entities\\UserSchema',
    ],
);