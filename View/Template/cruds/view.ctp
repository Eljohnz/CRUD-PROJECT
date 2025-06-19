<div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-dot-circle-o"></i> VIEW</div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-6">
        <dl class="dl-horizontal dl-data dl-bordered">
          <dt>Name:</dt>
          <dd class="uppercase">{{ data.Crud.name }}</dd>

          <dt>Age:</dt>
          <dd>{{ data.Crud.age }}</dd>

          <dt>Status:</dt>
          <dd>{{ data.CrudStatus.name }}</dd>

          <dt>Birthdate:</dt>
          <dd>{{ data.Crud.birthDate }}</dd>

          <dt>Character:</dt>
          <dd>{{ data.Crud.charac }}</dd>
        </dl>
      </div>
    </div>

    <div class="clearfix"></div>
    <hr>
    <div class="col-md-12">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr class="bg-info">
            <th colspan="5">BENEFICIARY DATA</th>
          </tr>
          <tr>
            <th class="w30px text-center">#</th>
            <th class="text-center">NAME</th>
            <th class="text-center">AGE</th>
            <th class="text-center">BIRTHDATE</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="datax in data.CrudBeneficiary" ng-if="datax.visible != 0">
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-left">{{ datax.name }}</td>
            <td class="text-center">{{ datax.age}}</td>
            <td class="uppercase text-left">{{ datax.birthDate }}</td>
          </tr>
          <tr ng-if="data.CrudBeneficiary == ''">
            <td colspan="6" class="text-center">
              No data available
            </td>
          </tr>
        </tbody>
      </table>
      <div class="uploaded-files mt-3" ng-if="data.CrudUpload && data.CrudUpload.length > 0">
        <label class="font-weight-bold d-block mb-2">Uploaded Files</label>
        <ul class="list-group">
          <li class="list-group-item d-flex align-items-center"
            ng-repeat="file in data.CrudUpload"
            ng-if="file.visible != 0">
            <i class="fa fa-file mr-2 text-primary"></i>
            <a ng-href="{{ file.filepath }}" target="_blank" class="text-decoration-none">
              {{ file.filename || file.name }}
            </a>
            <button class="btn btn-info btn-xs ml-2 pull-right">
              <a ng-href="{{ file.filepath }}" download="{{ file.filename }}" class="text-decoration-none" style="color:white;">
                <i class="fa fa-download"></i> Download
              </a>
            </button>
          </li>
        </ul>
      </div>


      <div class="row mt-4">
        <div class="col-md-12 d-flex flex-wrap justify-content-end align-items-center">
          <div class="btn-group btn-group-sm mb-2 mr-2" role="group" aria-label="Status Actions">
            <button
              class="btn btn-success"
              ng-class="{ active: data.Crud.crudConditionId == 1 }"
              ng-click="updateCondition(1, 'Approved')"
              ng-disabled="data.Crud.crudConditionId == 1">
              <i class="fa fa-check"></i> Approve
            </button>
            <button
              class="btn btn-danger"
              ng-class="{ active: data.Crud.crudConditionId == 2 }"
              ng-click="updateCondition(2, 'Disapproved')"
              ng-disabled="data.Crud.crudConditionId == 2">
              <i class="fa fa-times"></i> Disapprove
            </button>
            <button
              class="btn btn-warning"
              ng-class="{ active: data.Crud.crudConditionId == 3 }"
              ng-click="updateCondition(3, 'Pending')"
              ng-disabled="data.Crud.crudConditionId == 3">
              <i class="fa fa-clock-o"></i> Pending
            </button>
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
          <a href="/Training/print/generate_pdf_view/{{data.Crud.id}}"
            class="btn btn-danger btn-sm mb-2"
            ng-disabled="data.Crud.crudConditionId != 1"
            ng-class="{ disabled: data.Crud.crudConditionId != 1 }"
            target="_blank">
            <i class="fa fa-file-pdf-o"></i> Export to PDF
          </a>
        </div>
      </div>
    </div>
  </div>
</div>


<style>
  .table-wrapper {
    width: 100%;
    height: 500px;
    overflow-y: auto;
  }
</style>