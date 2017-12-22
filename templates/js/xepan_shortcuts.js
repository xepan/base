$.each({
  setup_shortcuts: function(shortcuts,popup){
        shortcut.add("Alt+q", function(event) {
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

          $input.keydown(function(event) {
            var options = {
              shouldSort: true,
              tokenize: true,
              matchAllTokens: false,
              findAllMatches: true,
              threshold: 0.5,
              location: 0,
              distance: 1000,
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
                $resultblock = $('<div><h4>'+obj.title+'</h4><p>'+obj.description+'</p><span class="label label-primary">'+obj.normal_access+'</span></div><hr/>');
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
          event.preventDefault();
          event.stopPropagation();
        });


  }
},$.univ._import);