<div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-dot-circle-o"></i> VIEW</div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Status ID</th>
              <th>Status Name</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ data.id }}</td>
              <td>{{ data.value }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="btn-group btn-group-sm mb-2 mr-2 pull-right" role="group" aria-label="Edit/Delete">
      <a href="#/crud/edit/{{ data.Crud.id }}"
        class="btn btn-primary"
        ng-disabled="data.Crud.crudConditionId == 1 || data.Crud.crudConditionId == 2"
        ng-class="{ disabled: data.Crud.crudConditionId == 1 || data.Crud.crudConditionId == 2 }">
        <i class="fa fa-edit"></i> Edit
      </a>
      <button class="btn btn-danger"
        ng-click="remove(data.Crud)"
        ng-disabled="data.Crud.crudConditionId == 1 || data.Crud.crudConditionId == 2"
        ng-class="{ disabled: data.Crud.crudConditionId == 1 || data.Crud.crudConditionId == 2 }">
        <i class="fa fa-trash"></i> Delete
      </button>
    </div>
  </div>
</div>