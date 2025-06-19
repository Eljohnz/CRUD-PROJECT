<div>
  <div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-dot-circle-o"></i> EDIT USER </div>
    <div class="panel-body">
      <div class="col-md-12">
        <div class="row">
          <form id="form" novalidate>
            <div class="form-group">
              <label>Status Name</label>
              <input
                type="text"
                class="form-control validate[required]"
                ng-model="status.CrudStatus.name"
                name="name" />
            </div>
          </form>
          <tr ng-repeat="data in status | filter:statusFilter">
            <td>{{ data.value }}</td>
            </td>
            <td>
            </td>
          </tr>
        </div>
        <div class="row">
          <div class="col-md-3 pull-right">
            <button class="btn btn-primary btn-sm btn-block" ng-click="update()">UPDATE</button>
          </div>
        </div>
      </div>
      <script>
        $('#form').validationEngine('attach');
      </script>
    </div>
  </div>
</div>