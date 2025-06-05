<?php

$routes = [
    '/' => 'HomeController@index',
    '/user/panel' => 'UserController@index',
    '/user/login' => 'UserController@login',
    '/user/register' => 'UserController@register',
    '/user/logout' => 'UserController@logout',
    '/user/upgrade' => 'UserController@upgrade',
    '/user/edit' => 'UserController@edit',
    '/partner/register' => 'PartnerController@register',
    '/partner/login' => 'PartnerController@login',
    '/partner/panel' => 'PartnerController@index',
    '/partner/logout' => 'PartnerController@logout',
    '/partner/menu' => 'PartnerController@menu',
    '/partner/profile' => 'PartnerController@profile',
    '/partner/settings' => 'PartnerController@settings',
    '/search' => 'HomeController@search',
    '/menu/{id}' => 'DishController@index',
    '/favorite/toggle/{id}' => 'FavoriteController@toggle',
    '/places/autocomplete' => 'HomeController@getPlacesAutocomplete',
    '/review/submit' => 'ReviewController@submit',
];
