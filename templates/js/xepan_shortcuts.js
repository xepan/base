$.each({
  setup_shortcuts: function(shortcuts,popup){
        shortcut.add("Ctrl+q", function(event) {
          $(popup).find('.modal-body').html('');
          $input = $('<input>');
          $resultdiv= $('<div>');
          $(popup).find('.modal-body').append($input);
          $(popup).find('.modal-body').append($resultdiv);
          $(popup).modal();

          $(popup).on('shown.bs.modal', function () {
              $input.focus();
              $resultdiv.html('');
          }) 

          $input.keypress(function(event) {
            var options = {
              shouldSort: true,
              threshold: 0.4,
              location: 0,
              distance: 100,
              maxPatternLength: 32,
              minMatchCharLength: 1,
              keys: [
                "title",
                "keywords",
                "description"
            ]
            };
            var fuse = new Fuse(shortcuts, options); // "list" is the item array
            var result = fuse.search($(this).val());
            $resultdiv.html('');
            $.each(result, function(index, obj) {
                // console.log(obj);
                $resultblock = $('<div><h4>'+obj.title+'</h4><p>'+obj.description+'</p></div>');
                $resultdiv.append($resultblock);
                $resultblock.click(function(event) {
                  if(obj.mode =='frame'){
                    $.univ().frameURL(obj.title, obj.url);
                    return;
                  }

                  if(obj.mode =='fullframe'){
                    $.univ().frameURL(obj.title, obj.url,{'width':$(window).width(), 'height': $(window).height(), 'left':'0px','top':'0px'});
                    return;
                  }
                  document.location=obj.url;
                });
            });
          });

          event.stopPropagation();
        });


  }
},$.univ._import);