jQuery(document).ready(function( $ ) {
  // $('#show-settings-link').click();

  $('<div id="wp_divider" class="divider">WordPress</div>').insertAfter('#adv-settings fieldset.metabox-prefs legend');

  $('<div id="strong_testimonials_divider" class="divider">Strong Testimonials</div>').insertAfter('#adv-settings fieldset.metabox-prefs legend');

  $('<div id="strong_testimonials_views_divider" class="divider">Strong Testimonials &bull; Views</div>').insertBefore('label[for="strongdashboard_view_1-hide"]');

  $('input:checked', 'fieldset.metabox-prefs').closest('label').addClass('hilite');
  $('input', 'fieldset.metabox-prefs').on('change', function() {
    $(this).closest('label').toggleClass('hilite');
  });
});
