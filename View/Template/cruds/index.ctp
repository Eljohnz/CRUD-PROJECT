<div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-dot-circle-o"></i> CRUD MANAGEMENT </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-3">
        <a href="#/crud/add" class="btn btn-primary btn-sm btn-block"><i class="fa fa-plus"></i> ADD USER</a>
      </div>
      <div class="col-md-3">
        <div class="btn-group-sm">
          <a href="/Training/print/generate_pdf?tab={{ currentTab }}&search={{ strSearch }}&page={{ paginator.page }}" class="btn btn-danger" target="_blank">
            <i class="fa fa-file-pdf-o"></i> EXPORT TO PDF
          </a>
        </div>
      </div>
      <div class="col-md-4 pull-right">
        <input type="text" class="form-control search" placeholder="SEARCH HERE" ng-model="strSearch" ng-enter="search(strSearch)">
        <sup style="font-size:10px;color:gray">Press Enter to search</sup>
      </div>
      <div class="clearfix"></div>
      <hr>


      <div class="col-md-12">
        <ul class="nav nav-pills justify-content-center">
          <li class="nav-item" ng-class="{ active: currentTab == null }">
            <a class="nav-link" href="" ng-click="setTab(null)">
              <i class="fa fa-list mr-1"></i> All
            </a>
          </li>
          <li class="nav-item" ng-class="{ active: currentTab == 1 }">
            <a class="nav-link text-success" href="" ng-click="setTab(1)">
              <i class="fa fa-check-circle mr-1"></i> Approved
            </a>
          </li>
          <li class="nav-item" ng-class="{ active: currentTab == 2 }">
            <a class="nav-link text-danger" href="" ng-click="setTab(2)">
              <i class="fa fa-times-circle mr-1"></i> Disapproved
            </a>
          </li>
          <li class="nav-item" ng-class="{ active: currentTab == 3 }">
            <a class="nav-link text-warning" href="" ng-click="setTab(3)">
              <i class="fa fa-clock-o mr-1"></i> Pending
            </a>
          </li>
        </ul>
      
        <table class="table table-bordered center">
          <thead>
            <tr>
              <th class="w30px">#</th>
              <th>NAME</th>
              <th>AGE</th>
              <th>EMAIL</th>
              <th>STATUS</th>
              <th class="w100px"></th>
            </tr>
          </thead>
      </div>
      <tbody>
        <tr ng-repeat="data in cruds">
          <td class="text-center">{{ (paginator.page - 1) * paginator.limit + $index + 1}}</td>
          <td>{{ data.name }}</td>
          <td>{{ data.age }}</td>
          <td>{{ data.email }}</td>
          <td>{{ data.condition == 1 ? 'Approved' : 
                 data.condition == 2 ? 'Disapproved' : 
                 data.condition == 3 ? 'Pending' : 'N/A' }}
          <td>
            <div class="btn-group btn-group-xs" style="display:flex; justify-content: space-between;">
              <a href="#/crud/view/{{ data.id }}" class="btn btn-success" title="VIEW"><i class="fa fa-eye"></i></a>
              <a href="#/crud/edit/{{ data.id }}" class="btn btn-primary" title="EDIT" ng-class="{ 'disabled' : data.condition == 1 || data.condition == 2 }"><i class="fa fa-edit"></i></a>
              <a href="javascript:void(0)" ng-click="remove(data)" class="btn btn-danger" title="DELETE" ng-class="{ 'disabled' : data.condition == 1 || data.condition == 2 }"><i class="fa fa-trash"></i></a>
            </div>
          </td>
        </tr>
      </tbody>
      </table>

      <ul class="pagination pull-right">
        <li class="pagination-page">
          <a href="javascript:void(0)" ng-click="load({ page: 1, search: searchTxt })"><sub>&laquo;&laquo;</sub></a>
        </li>
        <li class="prevPage {{ !paginator.prevPage? 'disabled':'' }}">
          <a href="javascript:void(0)" ng-click="load({ page: paginator.page - 1, search: searchTxt })">&laquo;</a>
        </li>
        <li ng-repeat="page in pages" class="pagination-page {{ paginator.page == page.number ? 'active':''}}">
          <a href="javascript:void(0)" class="text-center" ng-click="load({ page: page.number, search: searchTxt })">{{ page.number }}</a>
        </li>
        <li class="nextPage {{ !paginator.nextPage? 'disabled':'' }}">
          <a href="javascript:void(0)" ng-click="load({ page: paginator.page + 1, search: searchTxt })">&raquo;</a>
        </li>
        <li class="pagination-page">
          <a href="javascript:void(0)" ng-click="load({ page: paginator.pageCount, search: searchTxt })"><sub>&raquo;&raquo;</sub> </a>
        </li>
      </ul>
    </div>
  </div>
</div>