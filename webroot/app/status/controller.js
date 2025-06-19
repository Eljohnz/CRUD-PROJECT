app.controller("StatusController", function ($scope, CrudStatus, Select) {
  // load data

  // Select.get({ code: "crud-status" }, function (e) {
  //   $scope.status = e.data;
  // });

  $scope.currentTab = null;

  $scope.setTab = function (tab) {
    $scope.currentTab = tab;
  };

  $scope.statusFilter = function (item) {
    return $scope.currentTab == null || item.condition == $scope.currentTab;
  };

  $scope.load = function (options) {
    options = options || {};

    CrudStatus.query(options, function (e) {
      if (e.ok) {
        $scope.status = e.data;

        //pagination
        $scope.paginator = e.paginator;
        $scope.pages = paginator($scope.paginator, 5);
      }
    });
  };
  
  $scope.load();

  $scope.search = function (search) {
    search = typeof search !== "undefined" ? search : "";

    if (search.length > 0) {
      $scope.load({
        search: search,
      });
    } else {
      $scope.load();
    }
  };

  // remove

  $scope.remove = function (data) {
    bootbox.confirm(
      "Are you sure you want to delete " + data.name + " ?",
      function (c) {
        if (c) {
          CrudStatus.remove({ id: data.id }, function (e) {
            if (e.ok) {
              $.gritter.add({
                title: "Successful!",

                text: e.msg,
              });

              $scope.load();
            }
          });
        }
      }
    );
  };
});

app.controller(
  "StatusAddController",
  function ($scope, CrudStatus, Select, Upload) {
    $("#form").validationEngine("attach");

    Select.get({ code: "crud-status" }, function (e) {
      $scope.status = e.data;
    });

    $(".datepicker").datepicker({
      format: "yyyy/mm/dd",

      autoclose: true,

      todayHighlight: true,
    });

    $scope.data = {
      // User : {},
      Crud: {},
      CrudBeneficiary: [],
      CrudStatus: {},
    };

    $scope.remove = function (data) {
      bootbox.confirm(
        "Are you sure you want to delete " + data.name + " ?",
        function (c) {
          if (c) {
            CrudStatus.remove({ id: data.id }, function (e) {
              if (e.ok) {
                $.gritter.add({
                  title: "Successful!",

                  text: e.msg,
                });

                $scope.load();
              }
            });
          }
        }
      );
    };

    $scope.save = function () {
      console.log("save status");
      valid = $("#form").validationEngine("validate");

      if (valid) {
        if ($scope.data.CrudStatus.password != $scope.confirmPassword) {
          $.gritter.add({
            title: "Warning!",

            text: "Password does not match.",
          });
        } else {
          CrudStatus.save($scope.data, function (e) {
            if (e.ok) {
              $.gritter.add({
                title: "Successful!",

                text: e.msg,
              });

              window.location = "#/status";
            } else {
              $.gritter.add({
                title: "Warning!",

                text: e.msg,
              });
            }
          });
        }
      }
    };
  }
);

app.controller(
  "StatusEditController",
  function ($scope, $routeParams, CrudStatus) {
    $scope.status = {};

    $scope.id = $routeParams.id;

    $scope.load = function () {
      CrudStatus.get({ id: $scope.id }, function (e) {
        if (e && e.data) {
          $scope.status = e.data;
        }
      });
    };

    $scope.load();

    $("#form").validationEngine("attach");

    $scope.update = function () {
      valid = $("#form").validationEngine("validate");

      if (valid) {
        CrudStatus.update({ id: $scope.id }, $scope.status, function (e) {
          if (e.ok) {
            $.gritter.add({
              title: "Successful!",
              text: e.msg,
            });
            window.location = "#/status";
          } else {
            $.gritter.add({
              title: "Warning!",
              text: e.msg,
            });
          }
        });
      }
    };
  }
);

app.controller(
  "StatusViewController",
  function ($scope, $routeParams, Crud, Select, Upload) {
    $scope.data = {};

    $scope.bool = [
      { id: true, value: "Yes" },
      { id: false, value: "No" },
    ];

    Select.get({ code: "crud-condition" }, function (e) {
      $scope.status = e.data;
    });

    $(".datepicker").datepicker({
      format: "yyyy/mm/dd",

      autoclose: true,

      todayHighlight: true,
    });

    $scope.id = $routeParams.id;

    $("#form").validationEngine("attach");

    // load

    $scope.load = function () {
      Crud.get({ id: $scope.id }, function (e) {
        $scope.data = e.data;
      });
    };

    $scope.load();

    $scope.autoCompute = function (date_value) {
      if (date_value) {
        const today = new Date();
        const birthDate = new Date(date_value);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }

        if (age >= 0) {
          $scope.data.Crud.age = age;
        } else {
        }

        if (age >= 0) {
          $scope.data.Crud.age = age;
        } else {
          $scope.data.Crud.age = null;
        }
      } else {
        $scope.data.Crud.age = null;
      }
    };

    $scope.autoComputeBeneficiary = function (date_value) {
      if (date_value) {
        const today = new Date();
        const birthDate = new Date(date_value);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }

        if (age >= 0) {
          $scope.adata.age = age;
        } else {
        }

        if (age >= 0) {
          $scope.adata.age = age;
        } else {
          $scope.adata.age = null;
        }
      } else {
        $scope.adata.age = null;
      }
    };

    $scope.getBeneficiary = function (id) {
      if ($scope.beneficiaries.length > 0) {
        $.each($scope.beneficiaries, function (key, val) {
          if (id == val.id) {
            $scope.adata.beneficiaries = val.value;
          }
        });
      }
    };

    $scope.putIndex = function () {
      if ($scope.data.CrudBeneficiary.length > 0) {
        index = 0;

        $.each($scope.data.CrudBeneficiary, function (key, val) {
          if (val.visible != 0) {
            index += 1;

            $scope.data.CrudBeneficiary[key].index = index;
          }
        });
      }
    };

    $scope.addBeneficiary = function () {
      $("#add_beneficiary").validationEngine("attach");

      $scope.adata = {};
      $("#add-beneficiary-modal").modal("show");
      console.log("dshbsdhabhas");
    };

    // save for adding another beneficiary
    $scope.saveBeneficiary = function (data) {
      let valid = $("#add_beneficiary").validationEngine("validate");

      if (valid) {
        console.log("hiu");

        $scope.data = $scope.data || {};
        $scope.data.CrudBeneficiary = $scope.data.CrudBeneficiary || [];

        $scope.data.CrudBeneficiary.push(data);

        $("#add-beneficiary-modal").modal("hide");
      }
    };

    $scope.editBeneficiary = function (index, data) {
      $("#edit_beneficiary").validationEngine("attach");
      console.log("its me editBeneficiary");
      data.index = index;

      $scope.adata = data;

      $("#edit-beneficiary-modal").modal("show");
    };

    $scope.updateBeneficiary = function (data, index) {
      console.log("seyb updateBeneficiary");
      valid = $("#edit_beneficiary").validationEngine("validate");

      if (valid) {
        $scope.data.CrudBeneficiary[data.index] = data;

        $scope.putIndex();

        $("#edit-beneficiary-modal").modal("hide");
      }
    };

    $scope.removeBeneficiary = function (index) {
      console.log("remove");
      $scope.data.CrudBeneficiary[index].visible = 0;
    };

    $scope.removeUpload = function (index) {
      console.log("remove upload");
      $scope.data.CrudUpload[index].visible = 0;
    };

    $scope.update = function () {
      console.log("hi update");
      valid = $("#form").validationEngine("validate");

      if (valid) {
        Upload.upload({
          url: "/Training/cruds/edit",
          data: {
            Crud: $scope.data.Crud,
            CrudBeneficiary: $scope.data.CrudBeneficiary,
            CrudUpload: $scope.data.CrudUpload,
            file: $scope.files,
          },
        }).then(function (resp) {
          var e = resp.data;
          if (e && e.ok) {
            $.gritter.add({
              title: "Successful!",
              text: e.msg,
            });
            window.location = "#/cruds";
          } else {
            $.gritter.add({
              title: "Warning!",
              text: (e && e.msg) || "An error occurred while saving.",
            });
          }
        });
      }
    };
  }
);
