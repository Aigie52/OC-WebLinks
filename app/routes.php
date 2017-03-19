<?php

// Home page
$app->get('/', "WebLinks\\Controller\\HomeController::indexAction")
    ->bind('home');

// Login form
$app->get('/login', "WebLinks\\Controller\\HomeController::loginAction")
    ->bind('login');

// Add a new link
$app->match('/submit', "WebLinks\\COntroller\\HomeController::addLinkAction")
    ->bind('add_link');

/* Admin zone */
// Index
$app->get('/admin', "WebLinks\\Controller\\AdminController::indexAction")
    ->bind('admin');

// Edit an existing link
$app->match('/admin/link/{id}/edit', "WebLinks\\Controller\\AdminController::editLinkAction")
    ->bind('admin_link_edit');

// Remove a link
$app->get('/admin/link/{id}/delete', "WebLinks\\Controller\\AdminController::deleteLinkAction")
    ->bind('admin_link_delete');

// Edit an existing user
$app->match('/admin/user/{id}/edit', "WebLinks\\Controller\\AdminController::editUserAction")
    ->bind('admin_user_edit');

// Remove a user
$app->match('/admin/user/{id}/delete', "WebLinks\\Controller\\AdminController::deleteUserAction")
    ->bind('admin_user_delete');


/* API */
// Get all links
$app->get('/api/links', "WebLinks\\Controller\\ApiController::getLinksAction")
    ->bind('api_articles');

// Get a link
$app->get('/api/link/{id}', "WebLinks\\Controller\\ApiController::getLinkAction")
    ->bind('api_article');

