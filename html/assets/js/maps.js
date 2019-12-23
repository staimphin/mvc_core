//vanilla js

function getNodeindex(elm){ 
    var c = elm.parentNode.children, i = 0;
    for(; i < c.length; i++ )
        if( c[i] == elm ) return i;
}


//jquwery
$(document).ready(function(){
	//
	var halfLight = 'tile half_seen';
	var noLight = 'tile unseen';
	var startLight = 'start tile half_seen';

	console.log($(".start"));

	function setLight( light){
		var active = document.getElementsByClassName("start")[0];
		var base_row = getNodeindex(active);
		var base_columns = getNodeindex(active.parentNode);
		var base_set = [];
		var light_class = light ? halfLight : noLight;
		var prevColumns = active.parentNode.previousElementSibling;
		var nextColumns = active.parentNode.nextElementSibling;

		if(typeof active.previousElementSibling != null){
			base_set.push(active.previousElementSibling) ;
		}
		if(typeof active.nextElementSibling != null){
			base_set.push(active.nextElementSibling);
		}
		if(typeof prevColumns.childNodes[base_row] != null){
			base_set.push(prevColumns.childNodes[base_row]);
		}
		if(typeof nextColumns.childNodes[base_row] != null){
			base_set.push(nextColumns.childNodes[base_row]);
		}

		//change lights for main columns
		base_set.forEach(function(element) {
			if (!isWallOrDoor(element)){
				element.setAttribute('class', light_class);
			}
		});

		//cahnge adjacentelight
		//prevColumns.childNodes[base_row].setAttribute('class', halfLight);
		//nextColumns.childNodes[base_row].setAttribute('class', halfLight);
	}

	function isWallOrDoor(element){
		if(element){
			return ($(element).hasClass('wall') ||  $(element).hasClass('door')|| typeof element == null);
		}
		return true;
	}

	function setCurrentPoss( dir){
		var active = document.getElementsByClassName("start")[0];
		var base_row = getNodeindex(active);
		var base_columns = getNodeindex(active.parentNode);

		switch(dir){
			case 'UP': 
				target = active.previousElementSibling;
			break;
			case 'DOWN':
				target = active.nextElementSibling;
			break;
			case 'LEFT': 
				var prevColumns = active.parentNode.previousElementSibling;
				target = prevColumns.childNodes[base_row];
			break;
			case 'RIGHT': 
				var nextColumns = active.parentNode.nextElementSibling;
				target = nextColumns.childNodes[base_row];
			break;
		}

		if (!isWallOrDoor(target)){
			//console.log('not wall');
			setLight(0);
			active.setAttribute('class', halfLight);
			target.setAttribute('class', startLight);
			setLight(1);
			return true;
		}
		//console.log('is wall');
		return false;
	}

	setLight(1);
	// moves
	$('[data-action]').click(function(){
		var action = $(this).attr('data-action');
		var step = 100;//$(this).attr('css', 'width');
		var grid = $('.grid.main');
		var position = grid.position();
		//var location_x = grid.css('left');
		//var location_y = grid.css('top');
		//var location_x = position.left;
		//var location_y = position.top;
		location_x = grid.offset().top;
		location_y = grid.offset().left;
		console.log('before: X:'+grid.css('left')+' Y:'+grid.css('top'));
		console.log('val y:'+location_y);
		//console.log($(this));
		if (setCurrentPoss( action)){	
			switch (action)
			{
				case 'UP':
					grid.offset({top: location_x + step});

				break;
				case 'DOWN':
					grid.offset({top:  location_x - step});
				break;
				case 'LEFT':
					grid.offset({left: (location_y + step)});
				break;
				case 'RIGHT':
					grid.offset({left: location_y - step});
					//$('body, ul.grid').animate({scrollLeft: 100},400)
				break;
			}
		}

		//console.log('after: X:'+grid.css('left')+' Y:'+grid.css('top'));
	});
});