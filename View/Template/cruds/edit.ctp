<div>
  <div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-dot-circle-o"></i> EDIT USER </div>
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
                <input type="text" number class="form-control datepicker" ng-model="data.Crud.birthDate" ng-change="autoCompute(data.Crud.birthDate)" data-validation-engine="validate[required]" placeholder="yyyy/mm/dd">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Character</label>
                <textarea class="form-control" ng-model="data.Crud.charac"></textarea>
              </div>
            </div>
        </form>
        <hr>
        <div class="col-md-3 pull-left">
          <a class="btn btn-warning btn-sm btn-block" id="save" ng-click="addBeneficiary()">EDIT BENEFICIARY</a><br />
        </div>

        <div class="clearfix"></div>

        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th class="w30px text-center">#</th>
                <th class="text-center">NAME</th>
                <th class="text-center">AGE</th>
                <th class="text-center">BIRTHDATE</th>
                <th class="w100px text-center">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="datax in data.CrudBeneficiary" ng-if="datax.visible != 0">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left">{{ datax.name }}</td>
                <td class="text-center">{{ datax.age }}</td>
                <td class="uppercase text-left">{{ datax.birthDate }}</td>
                <td class="text-center">
                  <div class="btn-group btn-group-xs">
                    <a href="javascript:void(0)" ng-click="editBeneficiary($index, datax)" class="btn btn-success" title="EDIT">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="javascript:void(0)" ng-click="removeBeneficiary($index)" class="btn btn-danger" title="DELETE">
                      <i class="fa fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-md-6">
              <label for="file" class="font-weight-bold mb-2">
                <i class="fa fa-upload text-primary mr-1"></i> Upload File
              </label>
              <input type="file" ngf-select ng-model="files" name="file" multiple class="form-control mb-3">
            </div>
            <div class="col-md-6">
              <div class="uploaded-files" ng-if="data.CrudUpload && data.CrudUpload.length > 0">
                <label class="font-weight-bold d-block mb-2">Uploaded Files</label>
              </div>
                <ul class="list-group">
                  <li class="list-group-item d-flex justify-content-between align-items-center"
                    ng-repeat="file in data.CrudUpload track by $index" ng-if="file.visible != 0">
                    <div class="d-flex align-items-center">
                      <i class="fa fa-file text-primary mr-2" style="font-size: 1.3em;"></i>
                      <a ng-href="{{ file.filepath }}" target="_blank" class="text-decoration-none font-weight-bold mr-3">
                        {{ file.filename || file.name }}
                      </a>
                    </div>
                    <div class="d-flex align-items-center">
                      <a ng-href="{{ file.filepath }}" download class="btn btn-outline-info btn-sm mr-2" title="Download">
                        <i class="fa fa-download"></i>
                      </a>
                      <button type="button" ng-click="removeUpload($index)" class="btn btn-danger btn-sm" title="Delete">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </li>
                </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 pull-right">
        <button class="btn btn-primary btn-sm btn-block" ng-click="update()">UPDATE</button>
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
        <h4 class="modal-title">EDIT BENEFICIARY </h4>
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
              <label>AGE<i class="required">*</i></label>
              <input type="text" disabled="disabled" number class="form-control" ng-model="adata.age" data-validation-engine="validate[required]">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label> BIRTHDATE <i class="required">*</i></label>
              <input type="text" class="form-control datepicker" ng-model="adata.birthDate" ng-change="autoComputeBeneficiary(adata.birthDate)" data-validation-engine="validate[required]">
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
        <button type="button" class="btn btn-primary btn-sm btn-min" ng-click="updateBeneficiary(adata)">SAVE</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>