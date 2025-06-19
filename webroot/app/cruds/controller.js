app.controller("CrudController", function ($scope, Crud) {
  // load data

  $scope.currentTab = null;

  $scope.setTab = function (tab) {
    $scope.currentTab = tab;
    $scope.load();
  };


  $scope.load = function (options) {
    options = typeof options !== "undefined" ? options : {};

    // Pass currentTab as 'tab' param to backend if not null
    if ($scope.currentTab !== null) {
      options.tab = $scope.currentTab;
    }

    Crud.query(options, function (e) {
      if (e.ok) {
        $scope.cruds = e.data;

        // pagination
        $scope.paginator = e.paginator;
        $scope.pages = paginator($scope.paginator, 5);
      }
    });
  };

  $scope.load();

  //search

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
          Crud.remove({ id: data.id }, function (e) {
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

app.controller("CrudAddController", function ($scope, Crud, Select, Upload) {
  $("#form").validationEngine("attach");

  Select.get({ code: "crud-status" }, function (e) {
    $scope.status = (e.data || []).filter(function (item) {
      return item.visible != 0; // Exclude the option with id 0
    });
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
  };

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

  $scope.addBeneficiary = function () {
    console.log("log log");
    $("#add_beneficiary").validationEngine("attach");

    $scope.adata = {};

    $("#add-beneficiary-modal").modal("show");
  };

  $scope.saveBeneficiary = function (data) {
    valid = $("#add_beneficiary").validationEngine("validate");

    if (valid) {
      console.log("hiu");

      $scope.data.CrudBeneficiary.push(data);

      $("#add-beneficiary-modal").modal("hide");
    }
  };

  $scope.editBeneficiary = function (index, data) {
    $("#edit_beneficiary").validationEngine("attach");

    data.index = index;

    $scope.adata = data;

    $("#edit-beneficiary-modal").modal("show");
  };

  $scope.updateBeneficiary = function (data, index) {
    valid = $("#edit_beneficiary").validationEngine("validate");

    if (valid) {
      console.log("update beneficiary");
      // data.amount = number_format(data.amount, 2, '.', '');

      $scope.data.CrudBeneficiary[data.index] = data;

      // $scope.compute();

      $("#edit-beneficiary-modal").modal("hide");
    }
  };

  $scope.removeBeneficiary = function (index) {
    $scope.data.CrudBeneficiary.splice(index, 1);

    // $scope.compute();
  };
  $scope.save = function () {
    var valid = $("#form").validationEngine("validate");
    var emailRegex =
      /^[a-zA-Z0-9.!#$%&'+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)$/;

    if (!emailRegex.test($scope.data.Crud.email)) {
      $.gritter.add({
        title: "Invalid Email",
        text: "Please enter a valid email address.",
      });
      return;
    }

    if (valid) {
      Upload.upload({
        url: "/Training/cruds/add",
        data: {
          Crud: $scope.data.Crud,
          CrudBeneficiary: $scope.data.CrudBeneficiary,
          file: $scope.files, // This is the array of files
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
});

app.controller(
  "CrudViewController",
  function ($scope, $routeParams, Crud, Select) {
    Select.get({ code: "crud-condition" }, function (e) {
      $scope.condition = e.data;
    });

    $scope.id = $routeParams.id;

    $scope.updateCondition = function (id) {
      bootbox.confirm(
        "Are you sure you want to choose " +
          (id == 1 ? "Approved" : id == 2 ? "Disapproved" : "Pending") +
          "?",
        function (c) {
          if (c) {
            $scope.data.Crud.crudConditionId = id;
            Crud.update(
              { id: $scope.data.Crud.id },
              { Crud: $scope.data.Crud },
              function (e) {
                if (e.ok) {
                  $.gritter.add({
                    title: "Successful!",
                    text: e.msg,
                  });
                  window.location = "#/cruds";
                }
              }
            );
          }
        }
      );
    };

    $scope.load = function () {
      Crud.get({ id: $scope.id }, function (e) {
        $scope.data = e.data;
      });
    };

    $scope.load();

    // remove

    $scope.remove = function (data) {
      bootbox.confirm(
        "Are you sure you want to remove " + data.name + " ?",
        function (c) {
          if (c) {
            Crud.remove({ id: data.id }, function (e) {
              if (e.ok) {
                $.gritter.add({
                  title: "Successful!",

                  text: e.msg,
                });

                window.location = "#/cruds";
              }
            });
          }
        }
      );
    };
  }
);

app.controller(
  "CrudEditController",
  function ($scope, $routeParams, Crud, Select, Upload) {
    $scope.data = {};

    $scope.bool = [
      { id: true, value: "Yes" },
      { id: false, value: "No" },
    ];

    Select.get({ code: "crud-status" }, function (e) {
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
      bootbox.confirm(
        "Are you sure you want to remove this upload?",
        function (result) {
          if (result) {
            $scope.$apply(function () {
              $scope.data.CrudUpload[index].visible = 0;
            });
            $.gritter.add({
              title: "Removed!",
              text: "The upload has been marked for removal.",
            });
            console.log("remove upload");
          }
        }
      );
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
