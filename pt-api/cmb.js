
jQuery(document).ready(function($){
  $('.cmb_link_button').on('click', function(event) {
    wpActiveEditor = true;	// We need to override this var as the link dialogue is expecting an actual wp_editor instance
    wpLink.open();	// Open the link popup
    return false;
  });
  $('#wp-link-submit').on('click', function(event) {
    var linkAtts = wpLink.getAttrs();	// The links attributes (href, target) are stored in an object, which can be access via  wpLink.getAttrs()
    $('.cmb_text_link').val(linkAtts.href);	// Get the href attribute and add to a textfield, or use as you see fit
    wpLink.textarea = $('.cmb_text_link');	// To close the link dialogue, it is again expecting an wp_editor instance, so you need to give it something to set focus back to.
    wpLink.close();	// Close the dialogue
    event.preventDefault ? event.preventDefault() : event.returnValue = false;  // Trap any events
    event.stopPropagation();  // Trap any events
    return false;
  });
  $('#wp-link-cancel').on('click', function(event) {
    wpLink.textarea = $('.cmb_text_link');
    wpLink.close();
    event.preventDefault ? event.preventDefault() : event.returnValue = false;    // Trap any events
    event.stopPropagation();  // Trap any events
    return false;
  });
  $('#wp-link-close').on('click', function(event) {
    wpLink.textarea = $('.cmb_text_link');
    wpLink.close();
    event.preventDefault ? event.preventDefault() : event.returnValue = false;    // Trap any events
    event.stopPropagation();  // Trap any events
    return false;
  });
});