function loadDropdownOptions(dropdownId, url, searchValue, selectedId) {
    $("#" + dropdownId).html('<option value="">Loading...</option>');
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {
            search_value: searchValue,
            selected_id: selectedId,
        },
        beforeSend: function () {
            $("#" + dropdownId)
                .parent()
                .append("");
        },
        success: function (response) {
            $("#" + dropdownId).html(response.options);
        },
        error: function () {
            $("#" + dropdownId).html('<option value="">Error loading options</option>');
        },
        complete: function () {
            $(".loading-spinner").remove();
        },
    });
}

function showToast(type, message, heading = "") {
    $.toast({
        heading: heading || (type === "success" ? "Success" : "Error"),
        text: message,
        icon: type,
        position: "top-center",
        loaderBg: type === "success" ? "#5cb85c" : "#d9534f",
        hideAfter: 3000,
    });
}

function toggleButtonLoader($btn, isLoading, defaultText = "Submit") {
    if (isLoading) {
        $btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Submitting...');
    } else {
        $btn.prop("disabled", false).html(defaultText);
    }
}

function showLoader() {
  console.log("showLoader called");
  if ($("#globalLoader").length === 0) {
    console.log("Appending #globalLoader div");
    $("body").append(`
      <div id="globalLoader" style="
          position: fixed;
          top: 0; left: 0;
          width: 100%; height: 100%;
          background: rgb(10 10 10 / 49%);
          z-index: 9999;
          display: flex;
          justify-content: center;
          align-items: center;
      ">
        <span class="loader"></span>
      </div>
    `);
  } else {
    console.log("#globalLoader already exists");
  }
}

function hideLoader() {
  console.log("hideLoader called");
  $("#globalLoader").remove();
}
