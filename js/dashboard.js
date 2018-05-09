jQuery(document).ready(function( $ ) {

  // $('#show-settings-link').click();

  $('input:checked', 'fieldset.metabox-prefs').closest('label').addClass('hilite');

  $('input', 'fieldset.metabox-prefs').on('change', function() {
    $(this).closest('label').toggleClass('hilite');
  });

});
