<?php

/* -[{GET}]- */
$router->get('/', 'Index#getHomepage', 'home');
$router->get('/404', 'Index#getNotFound', 'default');

// USER \\
$router->get('/account/dashboard', 'User#getDashboard', 'dashboard');
$router->get('/account/logout', 'Index#getLogout', 'logout');

// USERS \\
$router->get('/user/:id/profile', 'User#getProfile', 'profile');
$router->get('/users/all', 'User#getAllUsers', 'allUsers');
$router->get('/users/edit/global/:id', 'User#getEditUser', 'editUser')->with('id', '[\d]+');
$router->get('/users/edit/password/:id', 'User#getEditUserPassword', 'editUserPassword')->with('id', '[\d]+');
$router->get('/user/delete/:id/:csrf', 'User#deleteUser', 'deleteUser')->with('id', '[\d]+')->with('csrf', '[a-z0-9]+');
$router->get('/user/profile/:id', 'User#getProfile', 'userProfile')->with('id', '[\d]+');

// PERMISSIONS \\
$router->get('/permissions/edit', 'Permissions#getEdition', 'permissions');

// RANKS \\
$router->get('/ranks/list', 'Ranks#getList', 'ranks');
$router->get('/rank/:id/edit', 'Ranks#getEdition', 'editRank')->with('id', '[\d]+');

// PROJECTS \\
$router->get('/projects/list', 'Projects#getList', 'projects');
$router->get('/project/:id/view', 'Projects#getProject', 'project')->with('id', '[\d]+');
$router->get('/project/validate/:id/:csrf', 'Projects#validateProject', 'validateProject')->with('id', '[\d]+')->with('csrf', '[a-z0-9]+');
$router->get('/project/delete/:id/:csrf', 'Projects#deleteProject', 'deleteProject')->with('id', '[\d]+')->with('csrf', '[a-z0-9]+');

/* -[{POST}]- */

// AUTH \\
//Exemple d'une route avec la méthode POST
$router->post('/', 'Index#postLogin', 'plogin');

// USERS \\
$router->post('/user/edit/global', 'User#postEditUser', 'pEditUser');
$router->post('/user/edit/password', 'User#postEditUserPassword', 'pEditUserPassword');

// PERMISSIONS \\
$router->post('/permissions/edit', 'Permissions#postEdition', 'pEditPermission');

// RANKS \\
$router->post('/rank/edit', 'Ranks#postEdition', 'pEditRank');