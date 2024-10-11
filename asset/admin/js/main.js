var frame;
var frames;
;(($)=>{
  var img_url = $("#input_url").val();
  if(img_url) {
   $("#img_container").html(`<img src="${img_url}"/>`);
  }
    if (frame) {
      frame.open(); 
      return;
    };
  $("#btn_id_1").on("click",function(){
    frame = wp.media({
      title:"Upload Image",
      button:{
        text:"Select Image"
      },
      multiple:false
    });
    
    frame.on('select', function(){
      var attachment = frame.state().get('selection').first().toJSON();
      console.log(attachment);
      $("#input_id").val(attachment.id);
      $("#input_url").val(attachment.url);
      $('#img_container').html('<img src="{attachment.url}"/>');
    });
    frame.open();
  });


// Galary of images 



var img_urls = $("#input_url_s").val();
  if(img_urls) {
   $("#img_container_s").html(`<img src="${img_urls}"/>`);
  }
    if (frames) {
      frames.open(); 
      return;
    };
  $("#btn_id_1_s").on("click",function(){
    frames = wp.media({
      title:"Upload Images",
      button:{
        text:"Select Images"
      },
      multiple:true
    });
    
    frames.on('select', function(){
      var attachments = frame.state().get('selection').first().toJSON();
      console.log(attachments);
      $("#input_id_s").val(attachments.id);
      $("#input_url_s").val(attachments.url);
      $('#img_container_s').html('<img src="{attachments.url}"/>');
    });
    frames.open();
 });





























})(jQuery);

