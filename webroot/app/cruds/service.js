 app.factory("Crud", function($resource) {
  return $resource( api + "cruds/:id", { id: '@id', search: '@search' }, {
    query: { method: 'GET', isArray: false },
    update: { method: 'PUT' },
    search: { method: 'GET' },
  });
});

app.factory("CrudBeneficiary", function($resource) {
  return $resource( api + 'crud-beneficiaries/:id', {id:'@id'}, {
    query: { method: 'GET', isArray: false },
    update: { method: 'PUT' }
  });
});

app.factory("CrudUpload", function($resource) {
  return $resource( api + 'crud-upload/:id', {id:'@id'}, {
    query: { method: 'GET', isArray: false },
    update: { method: 'PUT' }
  });
});

// app.factory("DeleteSelected", function($resource) {
//   return $resource( api + 'user_permissions/deleteSelected/:id', {id:'@id'}, {
//     query: { method: 'GET', isArray: false },
//     update: { method: 'PUT' }
//   });
// });

