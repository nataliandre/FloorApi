/*
 *
 * FLOOR API v.1.0
 *
 FLOOR API by Andrii Moroz  V.1
 *
 *
 *====================================
 *= MY CONTACTS                     =
 *= vk.com/id145762514              =
 *= MAIL                            =
 *= moroz97andre@mail.ua            =
 *= Telefon        733913212        =
 *====================================
 *
 *
 */
(function( $ ) {
      $.fn.floorDraw=function() {


	  		initSVGHolst();


            window.httpRequest = "php/index.php";
            //tunes
            window.logs = true;
            window.tileIsLoaded = false;
            window.buttonOk =".js__floor__draw";
            window.buttonReset = ".js__floor__reset";
            window.inputW = "[data-type=\"width\"]";
		  	window.inputL = "[data-type=\"lenght\"]";
            window.wallWithOpenings = 0;
            window.rooms__count = 0;
			window.poligonRoom = '#poligons poligon';
			window.SVGFiled = $('#holst');
			window.heightSVGField = 0;
			window.summaryCount = $("#summaryCount");
			window.summaryPrice = $("#summaryPrice");
			window.borderSVGClass = 'line__style__API';
			window.borderSVG = $('.'+window.borderSVGClass);
			window.poligonSVGClass = 'poligon__style__API';
			window.poligonSVGId = 'room';
			window.poligonSVG = $('.'+window.poligonSVGClass);
			window.tileSVGClass = 'tileSVGClass';
			window.tilePreloaderAPI = $('.tilePreloaderApi');
			window.tilePreloaderAPIClass = 'tilePreloaderApi';

		  	window.tileTypes = [];


		   /**
		   *
		   * @param event
           */
			window.catchDataApi = function(event){
                  window.dataJSON=event.data; 
                  if(window.logs){console.log(event.data);}
                  wallsStartDraw();
              }
			
			
			
			//holders SVG holst holders
			window.borderSVGHolder = $('#borders');
			window.poligonSVGHolder = $('#poligons');
			window.tileSVGHolder = $("#rooms");

            //tunesLayType
            window.typeAngelwiseCube = ".floor__types__list li:nth-child(2)";
            window.typeAngelwiseRect = ".floor__types__list li:nth-child(5)";
            window.roomSelect = ".floor__room__num li";
            window.typeSelect = ".floor__types__list li";
            window.roomSelectHolder = ".floor__room__num";
            window.wallSelect = ".floor__wall__start";
            window.wallSelector = ".floor__wall__start li";
            window.openingsTile = "opening";
            window.centerTile = "center";
            window.floorWallNoSelected = ".floor__wall__noselect";
            window.tileCountPlaceHolder = ".tileCountPlaceHolder";
            window.tileCountHandler = ".js__tile__count__handler"; //tbody from table
            window.poligonRoom = "polygon ";

            //validationMessages
            window.errorNoSize = "Incorrect size value";
            window.errorNoRoomSelected = "Incorrect Room Selected";
            window.errorNoTypeSelected = "Incorrect Type Selected";
            
                          //
             

              //PostMessage
              if (window.addEventListener) {
	              
                  window.addEventListener("message", window.catchDataApi);
              } else {
                  // IE8
                  window.attachEvent("onmessage", window.catchDataApi);
              }
              
             



            //cut the input coordinats to the frame
            window.cut = {
              "x" : 600, //static params
              "y" : 800, // static params
            };
            //indent from top of canvas field
            window.field = 30;
            window.tile_count_array = [];
            window.tile_count = 0;


            //window tabs menu^field
            window.menuTab = ".menu__body";
            window.resultTab = ".canvas__body";




            //init
            $(window.resultTab).hide();
            $(window.typeAngelwiseCube).hide();
            $(window.typeAngelwiseRect).hide();


           /**
		   *rooms ajax request
		   */
            var rooms = $.ajax({
                method: "POST",
                url: window.httpRequest,
                data: {getRooms:true}

            });
            
            
            
            /*
	        *function that display gif before room load
	        *
	        */
	        function initSVGHolst(){
		        $('.boordFloorApi').hide();
		        $(window.tilePreloaderAPIClass).hide();
		        setTimeout(function(){$('.preloaderFloorApi').remove();$('.boordFloorApi').show();},2000);
	        }
            
            
            
        /*
	    * function that print all walls into SVG holst
        *
        */    
        function wallsStartDraw(){
	            
	        console.log('data-JSON:'+dataJSON);
	        //get the data of walls    
            var walls = $.ajax({
	            method: "POST",
	            url: window.httpRequest,
	            data: {roomDraw:true,dataJson:window.dataJSON}
        	});

	        
	        walls.done(function(data) {
		        
		        //convert data walls to Object Array
	      		walls = jQuery.parseJSON(data);
	      		
	      		//log the wall data
	      	    if(window.logs){console.log(walls);}
	      	    
	      	    //searc the cutpoints to valid print coordint objects
	      		$.each(walls,function(iteration,wall){
		      		$.each(wall,function(index,value){
		      					$.each(value,function(i,v){
		      			            if(v.x < window.cut.x){
		      				                window.cut.x = v.x;
		      			            }
		      			            if(v.y < window.cut.y){
		      				                window.cut.y = v.y;
		      			            }
		      			            if(window.heightSVGField < v.y){
			      			            window.heightSVGField = v.y;
		      			            }
		      					});
		      		});
	      		});
	      		
	      		
	      		
	      		window.heightSVGField-=120;
	      		
	      		//change the height of SVG holst
	      		window.SVGFiled.css('height',window.heightSVGField);
	      		window.tilePreloaderAPI.css('height',window.heightSVGField);
	      		//log the height of SVG holst
	      		if(window.logs){ console.log(window.heightSVGField); }
	      		
	      		//var of rooms count
	      		var room_iterator = 0;
	      		
	            $.each(walls,function(iteration,wall){
		            
		            //coords - line of coords poligon
			        var coords = '';
			        
			        //var for wall count
			        var wall_iterator = 0;
			        
		      				$.each(wall,function(index,value){
		      					
		      					//coordinats of Wall start
		      					var start_x = value.start.x - window.cut.x + window.field;
		      					var start_y = value.start.y - window.cut.y + window.field;
		      					
		      					//coordinats of Wall end
		      					var end_x = value.end.x - window.cut.x + window.field;
		      					var end_y = value.end.y - window.cut.y + window.field;
		      					
		      					//make the border of room(wall)
		      					var border = "<line x1=\""
			      					border+= start_x;
			      					border+= "\" y1=\"";
			      					border+= start_y;
			      					border+= "\" x2=\"";
			      					border+= end_x;
			      					border+= "\" y2=\"";
			      					border+= end_y;
			      					border+= "\" data-num=\"";
			      					border+= wall_iterator;
			      					border+= "\" data-room=\"";
			      					border+= room_iterator;
			      					border+= "\" class=\""+window.borderSVGClass+"\" style=\"stroke-width:3\" />";
			      			     	console.log(border);
			      			     	
			      			    //print border to SVG
		      					var html = window.borderSVGHolder.html();
			  					    window.borderSVGHolder.html(html+border);
			  					    
		      					//set coordinats of wall Poligon
		      					coords += start_x+','+start_y+' ';
		      					
		      					//change the room and wall iterator
		      					wall_iterator++;
		      					
		      			});
		      			room_iterator++;
		      			//make the room borders poligon
		      			var poligon = '<polygon class="';
		      			    poligon+= window.poligonSVGClass;
		      			    poligon+= '" data-layType="" data-wall="" data-sizeL="" data-sizeW="" id="'+poligonSVGId;
		      			    poligon+= iteration;
		      			    poligon+= '" data-num="'+iteration+'"  points="';
		      			    poligon+= coords;
		      			    poligon+= '" style="fill:transparent;stroke:#000;stroke-width:3" />';
		      			    
		      			    
		      			//log the reults poligons
		      			if(window.logs){console.log(poligon);}
		      			
		      			
		      			// draw poligons to the holst
		      			var html = window.poligonSVGHolder.html();
		      				window.poligonSVGHolder.html(html+poligon);
		      			
		      			//count of rooms
		      			window.rooms__count++;
		      			
		          		
		          	});
	          	});
	          	setTimeout(function(){
					  bindActionsAfterRoomLoad();     	
	          	}, 1000);
	    }
	    
	    
	    
		function bindActionsAfterRoomLoad(){      
			    //set room click event
			    $('.'+window.poligonSVGClass).bind('click',function(){
				    
					var w = $(this).attr('data-sizeW');
					var l = $(this).attr('data-sizeL');
					var type = $(this).attr('data-layType');
					var room  =  $(this).attr('data-num');
					var wall  =  $(this).attr('data-wall');
					if(type==""){
						$(window.typeSelect).attr('data-room',room);
					}else{
						$('.'+window.borderSVGClass).removeClass('active');
						if(wall==""){wall=0}
						
						//JSON decode Exeption
						//wall++;
						//get parameters to display
						layTile(l,w,type,room,wall);
					}
				});
					          		
				//set border click event          		
				$('.'+window.borderSVGClass).bind('click',function(){
					var num_wall = $(this).attr('data-num');
					var num_room = $(this).attr('data-room');
					$('.'+window.borderSVGClass).removeClass('active');
					$(this).addClass('active');
					$(window.typeSelect).attr('data-wall',num_wall);
					$('#'+window.poligonSVGId+num_room).attr('data-wall',num_wall);
					
						          		
				});
			    
			    //select type ivent handler
	            $(window.typeSelect).bind('click',function(){
	                var type = $(this).attr('data-type');
	                $(window.typeSelect).removeClass('active');
	                $(this).addClass('active');
	                $(window.poligonRoom).attr('data-layType',type);
	                if($('.'+window.poligonSVGClass).attr('data-layType') == "" || $('.'+window.poligonSVGClass).attr('data-layType') === undefined ){
						var w = $(this).attr('data-sizeW');
						var l = $(this).attr('data-sizeL');
						var type = $(this).attr('data-layType');
						var room  =  $(this).attr('data-num');
						var wall  =  $(this).attr('data-wall');
						if(wall==""){wall=0}
						layTile(l,w,type,room,wall);
					}
	            });
	             
	            //input length ivent handler
	            $(window.inputL).bind('input',function(){
	                      var a = $(this).val();
	                      var b = $(window.inputW).val();
	                      checkSizes(a,b);
	                      $(window.poligonRoom).attr('data-sizeL',a);
	                      $(window.typeSelect).attr('data-sizeL',b);
	            });

	            //input width ivent handler
	            $(window.inputW).bind('input',function(){
	                var b = $(this).val();
	                var a = $(window.inputL).val();
	                checkSizes(a,b);
	                $(window.poligonRoom).attr('data-sizeW',b);
	                $(window.typeSelect).attr('data-sizeW',b);
	            });	    
		}


		function bindActionsAfterTileLoad(){      
						$("."+window.tileSVGClass).bind('click',function(){
							var room = $(this).attr('data-room');
							$('.'+window.poligonSVGClass+'[data-num='+room+']').click();
							$("."+window.tileSVGClass).remove();
							
						});
		}

            /*
            *@author Andrii Moroz
            *@params a{int} - length, b{int} - width, display or hide the tile types
            *
            */
            function checkSizes(a,b){
              if(a == b && a!=0 && b!=0){
                    $(window.typeAngelwiseCube).show();
                    $(window.typeAngelwiseRect).hide();
              }else if(a== (b/2)  && a!=0 && b!=0){
                    $(window.typeAngelwiseRect).show();
                    $(window.typeAngelwiseCube).hide();
              }else if((a/2) == b  && a!=0 && b!=0){
                    $(window.typeAngelwiseRect).show();
                    $(window.typeAngelwiseCube).hide();
              }else{
                    $(window.typeAngelwiseCube).hide();
                    $(window.typeAngelwiseRect).hide();
              }
            }





            //keypress ivent handler
            $(document).keydown(function(e) {
                  if( e.keyCode === 27 ) {
                    e.preventDefauls;
                    if(  window.tileIsLoaded == true){$(window.buttonReset).click();}
                  }
                  if( e.keyCode === 13 ) {
                    e.preventDefauls;
                    if(  window.tileIsLoaded == false){$(window.buttonOk).click();}
                  }
            });





	/*
	*@autor Andrii Moroz
	*@params -none
	*@returns {boolean} false if no Rooms selected or no tile typeSelected, true where everything is ok
	*/	 
    function layTile(l,w,type,room,wall){
	    
	    // get variables parameters
	   if(window.logs){console.log('parameters: '+l+w+type+room);}
	   
	   //get rooms and set the room holst
	   var html =  window.tileSVGHolder.html;
	   window.tileSVGHolder.html(html+" <g id=\"room-c"+room+"\" style=\"clip-path: url(#room"+room+");\" >");
	    
	   //get the tile coordinats
	   var tile = $.ajax({
          method: "POST",
          url: window.httpRequest,
          data: {layType:type, tileL:l,tileW:w,roomNum:room,startWall:wall,dataJson:window.dataJSON}
	   });

	   if(window.logs){
		   var log = 'AJAX Request Done with parameters: layType - ';
		       log+= type;
		       log+= '; tileL - ';
		       log+= l;
		       log+= '; tileW - ';
		       log+= w;
		       log+= '; roomNum - ';
		       log+= room;
		       log+= '; startWall - ';
		       log+= wall;
		       log+= ';';
			   console.log(log);
	    }

 	   //parse get tile coordinats result
	   tile.done(function(data) {
		    window.tileTypes = [];
            console.log(data);
		   	/*var saveJson = $.ajax({
			   method: "POST",
			   url: window.httpRequest,
			   data: {jsonData:data,saveJson:true}
		   	});
		    saveJson.done(function(d){alert(d);}); //saveDataTileToJSONFormat
		    console.log('file_not_saved');*/
            tile = jQuery.parseJSON(data);
          
            var price = parseInt($('[data-type="price"]').val());
		    var sC = parseInt(tile.length);
	                
		    window.summaryCount.html(sC);
		    window.summaryPrice.html(sC*price);
            $.each(tile,function(index,value){
              if(value !== null){
                var start_o = 0;
                var end_o;
                var prev_o = 0;
                var i = 1;
               
                window.tile_count++;
				setTileType(value);
                coords = '';

                $.each(value,function(i,v){
                          var start_x = v.x - window.cut.x + window.field;
                          var start_y = v.y - window.cut.y + window.field;
                          coords += start_x+','+start_y+' ';
                });

                //draw the poligon
                var poligon = '<polygon  points="';
	      	    poligon+= coords;
		  		poligon+= '" class="';
		  		poligon+= window.tileSVGClass;
		  		poligon+= '" data-room="';
		  		poligon+= room;
		  		poligon+= '" style="fill:transparent;stroke:#00f;stroke-width:1" />';
	      		
	      		
	      		//set the room tiles
	      		var html = window.tileSVGHolder.html();
	      		window.tileSVGHolder.html(html+poligon);
              }
          });
       });
        setTimeout(function(){bindActionsAfterTileLoad();},2000);
		setTimeout(function(){console.log(window.tileTypes.length);},2000);
    }

		  /**
		   *
		   * @param c
           */
			function setTileType(c){
				cut = getCutParams(c);
				var tile = [];
				var iterator = 0;
				$.each(c,function(i,v){
					tile[iterator] = {
						'x':v.x - cut.left,
						'y':v.y - cut.bottom
					}
				});
				if(findInTileTypesIfExistReturnFalse(tile)){
					var elem = {
						'coords':tile,
						'count': 1
					};
					window.tileTypes.push(elem);
				}
			}

		  /**
		   *
 		   * @param c
		   * @returns {{left: number, bottom: number}}
           */
			function getCutParams(c){
				var left = 10000;
				var bottom = 10000;//take the bigest value for check the smallest
				$.each(c,function(i,v) {
					if (v.x < left) {left = v.x;}
					if (v.y < bottom) {bottom = v.y;}
				});//search cut from coordinats
				var cut = {
					'left': left,
					'bottom': bottom
				}//cut coordinats
				return cut;//return coordinats
			}


		  /**
		   *
		   * @param tile
		   * @returns {boolean}
           */
			  function findInTileTypesIfExistReturnFalse(tile){
				  if(window.tileTypes.length == 0){ return true; }
				  var isset = false;

				  $.each(window.tileTypes,function(i,v){
					  if(isset){
						 return;
					  }
					  $.each(v.coords,function(iter,sides) {
						  if(tile[iter]){
							  if(tile[iter].x == sides.x && sides.y == tile[iter].y ){
								  isset = i;
							  }
						  }else{
							    isset = false;
								return;
						  }


					  });
				  });
				  if(isset){
					  window.tileTypes[isset].count++;
					  return false;
				  }
				  return true;

			  }

    /*
    *@autor Andrii Moroz
    *@params -none
    *@returns {boolean} false if no Rooms selected or no tile typeSelected, true where everything is ok
    */
    function validate(){
        if($(window.buttonOk).attr('data-tileType') == ''){
          alert(window.errorNoTypeSelected);
          return false;
        }
        if($(window.buttonOk).attr('data-room') == ''){
          alert(window.errorNoRoomSelected);
          return false;
        }
        return true;
    }
  };
})(jQuery);
