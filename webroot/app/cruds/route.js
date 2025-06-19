app.config(function($routeProvider) {
  $routeProvider
  .when('/cruds', {
    templateUrl: tmp + 'cruds__index',
    controller: 'CrudController',
  })
  .when('/crud/add', {
    templateUrl: tmp + 'cruds__add',
    controller: 'CrudAddController',
  })
  .when('/crud/edit/:id', {
    templateUrl: tmp + 'cruds__edit',
    controller: 'CrudEditController',
  })
  .when('/crud/view/:id', {
    templateUrl: tmp + 'cruds__view',
    controller: 'CrudViewController',
  })
  ;
});