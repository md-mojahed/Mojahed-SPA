<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Asset Path
    |--------------------------------------------------------------------------
    | The public path where spa:setup copies all JS/CSS assets.
    | Used by @spacss and @spajs directives.
    */
    'asset_path' => 'assets/spa',

    /*
    |--------------------------------------------------------------------------
    | Asset Version
    |--------------------------------------------------------------------------
    | Append ?v=xxx to asset URLs for cache busting.
    | Set to null to disable.
    */
    'asset_version' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Loader Type
    |--------------------------------------------------------------------------
    | Used when no loader-type is specified on a component.
    | Options: spinner | skeleton | table | card
    */
    'default_loader' => 'spinner',

    /*
    |--------------------------------------------------------------------------
    | Auto Run Scripts
    |--------------------------------------------------------------------------
    | Automatically execute <script> tags found in loaded HTML fragments.
    */
    'auto_run_scripts' => true,

    /*
    |--------------------------------------------------------------------------
    | Confirm Dialog Defaults
    |--------------------------------------------------------------------------
    | Default SweetAlert2 confirm dialog options used by spa-btn / spa-link.
    | All can be overridden per-component via attributes.
    */
    'confirm' => [
        'title'  => 'Are you sure?',
        'text'   => "You won't be able to revert this!",
        'type'   => 'warning',
        'ok'     => 'Yes, proceed!',
        'cancel' => 'Cancel',
    ],

];
