function loadDropdownOptions(dropdownId, url, searchValue, selectedId) {
   $('#' + dropdownId).html('<option value="">Loading...</option>');
   $.ajax({
      url: url,
      type: 'POST',
      dataType: 'json',
      data: {
         search_value: searchValue,
         selected_id: selectedId
      }, 
      beforeSend: function () {
         $('#' + dropdownId).parent().append('');
      },
      success: function (response) {
         $('#' + dropdownId).html(response.options);
      },
      error: function () {
         $('#' + dropdownId).html('<option value="">Error loading options</option>');
      },
      complete: function () {
         $('.loading-spinner').remove();
      }
   });
}