<div>
  <div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-dot-circle-o"></i> NEW USER</div>
    <div class="panel-body">
      <div class="col-md-12">
        <form id="form" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Name <i class="required">*</i></label>
                <input type="text" class="form-control" ng-model="data.Crud.name" data-validation-engine="validate[required]">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Age<i class="required">*</i></label>
                <input type="text" disabled="disabled" number class="form-control" ng-model="data.Crud.age" data-validation-engine="validate[required]">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Status <i class="required">*</i></label>
                <select class="form-control" ng-model="data.Crud.crudStatusId" ng-options="opt.id as opt.value for opt in status" data-validation-engine="validate[required]">
                  <option value=""></option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Birthdate<i class="required">*</i></label>
                <input type="text" number class="form-control datepicker" ng-model="data.Crud.birthDate" ng-change="autoCompute(data.Crud.birthDate)" data-validation-engine="validate[required]" placeholder="mm/dd/yyyy">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" ng-model="data.Crud.email" placeholder="me@example.com" required>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Character</label>
                <textarea class="form-control" ng-model="data.Crud.charac"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" ngf-select ng-model="files" name="file" multiple class="form-control">
              </div>
            </div>
          </div>
        </form>
        <hr>

        <div class="col-md-3 pull-left">
          <button type="button" class="btn btn-warning btn-sm btn-block" ng-click="addBeneficiary()">ADD BENEFICIARY</button><br />
        </div>

        <div class="clearfix"></div>

        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th class="w30px text-center">#</th>
                <th class="text-center">Name</th>
                <th class="text-center">BirthDate</th>
                <th class="text-center">Age</th>
                <th class="w100px"></th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="datax in data.CrudBeneficiary">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left">{{ datax.name }}</td>
                <td class="text-center">{{ datax.age}}</td>
                <td class="uppercase text-left">{{ datax.birthDate }}</td>
                <td class="uppercase text-right">{{ datax.amount | number : 2 }}</td>
                <td class="text-center">
                  <div class="btn-group btn-group-xs">
                    <a href="javascript:void(0)" ng-click="editBeneficiary($index,datax)" class="btn btn-success" title="EDIT"><i class="fa fa-edit"></i></a>
                    <a href="javascript:void(0)" ng-click="removeBeneficiary($index)" class="btn btn-danger" title="DELETE"><i class="fa fa-trash"></i></a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      </form>
      <hr>
      <div class="row">
        <div class="col-md-3 pull-right">
          <button class="btn btn-primary btn-sm btn-block" ng-click="save()">SAVE</button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  $('#form').validationEngine('attach');
</script>

<div class="modal fade" id="add-beneficiary-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">ADD BENEFICIARY </h4>
      </div>
      <div class="modal-body">
        <form id="add_beneficiary">
          <div class="col-md-12">
            <div class="form-group">
              <label>NAME<i class="required">*</i></label>
              <input type="text" class="form-control" ng-model="adata.name" data-validation-engine="validate[required]"></textarea>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label> BIRTHDATE <i class="required">*</i></label>
              <input type="text" class="form-control datepicker" ng-model="adata.birthDate" ng-change="autoComputeBeneficiary(adata.birthDate)" data-validation-engine="validate[required]">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>AGE<i class="required">*</i></label>
              <input type="text" disabled="disabled" number class="form-control" ng-model="adata.age" data-validation-engine="validate[required]">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm btn-min" data-dismiss="modal">CANCEL</button>
        <button type="button" class="btn btn-primary btn-sm btn-min" ng-click="saveBeneficiary(adata)">SAVE</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-beneficiary-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">EDIT BENEFICIARY </h4>
      </div>
      <div class="modal-body">
        <form id="edit_beneficiary">

          <div class="col-md-12">
            <div class="form-group">
              <label>NAME<i class="required">*</i></label>
              <input type="text" class="form-control" ng-model="adata.name" data-validation-engine="validate[required]"></textarea>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label> BIRTHDATE <i class="required">*</i></label>
              <input type="text" class="form-control datepicker" ng-model="adata.birthDate" data-validation-engine="validate[required]">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label>AGE<i class="required">*</i></label>
              <input type="text" number class="form-control" ng-model="adata.age" data-validation-engine="validate[required]">
            </div>
          </div>
          <button type="button" class="btn btn-primary btn-sm btn-min" ng-click="updateBeneficiary(adata)">SAVE</button>
      </div>
    </div>

    <script>
      $('#form').validationEngine('attach');
    </script>

  </div>
</div>