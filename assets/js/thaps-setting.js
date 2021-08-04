(function ($){
    var THVSsettingLib = {
        init: function (){
            this.bindEvents();
        },
        bindEvents: function (){
          var $this = this;
            $this.SettingTab();
            $this.ColorPiker();
            $this.ImageAdd();
            $this.RemoveImage();
            $this.SaveSetting();
          
        },
        SettingTab: function (){
          $(document).ready(function(){ 
                 $('#thaps').on('click', '.nav-tab', function (event){
                  event.preventDefault()
                  var target = $(this).data('target')
                  $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active')
                  $('#' + target).show().siblings().hide()
                  $('#_last_active_tab').val(target)
                });
          });
        },
        ColorPiker: function (){
          $(document).ready(function(){ 
                $('.thaps-color-picker').wpColorPicker();
          });
        },

        ImageAdd:function (){
           $(document).on('click', 'button.thaps_upload_image_button', function (event){
                var _this = this;
                event.preventDefault();
                event.stopPropagation();

                var file_frame = void 0;

                if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {

                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.select_image = wp.media({
                        title: THAPSPluginObject.media_title,
                        button: {
                            text: THAPSPluginObject.button_title
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        var attachment = file_frame.state().get('selection').first().toJSON();

                        if ($.trim(attachment.id) !== '') {

                            var url = typeof attachment.sizes.thumbnail === 'undefined' ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;

                            $(_this).prev().val(attachment.id);
                            $(_this).closest('.meta-image-field-wrapper').find('img').attr('src', url);
                            $(_this).next().show();
                        }
                        //file_frame.close();
                    });

                    // When open select selected
                    file_frame.on('open', function () {

                        // Grab our attachment selection and construct a JSON representation of the model.
                        var selection = file_frame.state().get('selection');
                        var current = $(_this).prev().val();
                        var attachment = wp.media.attachment(current);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });

                    // Finally, open the modal.
                    file_frame.open();
                }
          });
        },
        RemoveImage:function (){
          $(document).on('click', 'button.thaps_remove_image_button', function (event){
                 event.preventDefault();
                 event.stopPropagation();

                var placeholder = $(this).closest('.meta-image-field-wrapper').find('img').data('placeholder');
                $(this).closest('.meta-image-field-wrapper').find('img').attr('src', placeholder);
                $(this).prev().prev().val('');
                $(this).hide();
                return false;
              });

        },
        
        SaveSetting:function(){
        $(document).on('keyup change paste', '.thaps-setting-form input, .thaps-setting-form select', function () {
        
              $('#submit').removeAttr("disabled");
              
        });  
        $(document).on("click", ".thaps-setting-form #submit", function (e) {
        e.preventDefault();
        $(this).addClass('loader');
        
        var form_settting = $(".thaps-setting-form").serialize();
        $.ajax({
          url: THAPSPluginObject.ajaxurl,
          type: "POST",
          data: form_settting,
          success: function (response) {
           
            $('#submit').removeClass('loader');
            $('#submit').attr("disabled","disabled");

          },
        });
      });
    },
   
}
THVSsettingLib.init();
})(jQuery);