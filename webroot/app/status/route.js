app.config(function($routeProvider) {
  $routeProvider
  .when('/status', {
    templateUrl: tmp + 'status__index',
    controller: 'StatusController',
  })
  .when('/status/add', {
    templateUrl: tmp + 'status__add',
    controller: 'StatusAddController',
  })
  .when('/status/edit/:id', {
    templateUrl: tmp + 'status__edit',
    controller: 'StatusEditController',
  })
  .when('/status/view/:id', {
    templateUrl: tmp + 'status__view',
    controller: 'StatusViewController',
  })
  ;

});


