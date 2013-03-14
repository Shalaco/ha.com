(function($){
    function SuperSimpleSlideDeck( deckFrame ){
		var superSimpleDeckFrame = $(deckFrame);
		var superSimpleDeckFrameHeight = superSimpleDeckFrame.outerHeight();
		var footer;
		var footerHeight = 0;
		var footerTitle;
		var superSimpleNext;
		var superSimplePrev;
		var navigation = '<div class="slidedeckFooter"><div class="navigation"><a class="prevSlide" href="#prev">&larr;</a><a class="nextSlide" href="#next">&rarr;</a></div><div class="slideTitle"></div></div>';
		
		var superSimpleDeck = superSimpleDeckFrame.find('.slidedeck').slidedeck();
		
		function updateFooterTitle(){
			var currentTitle = $(superSimpleDeck.spines[superSimpleDeck.current-1]).html();
			footerTitle.html(currentTitle);
		}
		
		function updateDisabledNavigation(){
			if(superSimpleDeck.options.cycle == false){
				superSimpleNext.removeClass('disabled');
				superSimplePrev.removeClass('disabled');
				if( superSimpleDeck.current == 1 ){
					superSimplePrev.addClass('disabled');
				}
				if( superSimpleDeck.current == superSimpleDeck.slides.length ){
					superSimpleNext.addClass('disabled');
				}							
			}				
		}
		
		superSimpleDeck.loaded(function(){
			if( superSimpleDeckFrame.find('.slidedeckFooter').length ){
				superSimpleDeckFrame.find('.slidedeckFooter').remove();
			}
			superSimpleDeckFrame.append(navigation);
			
			footer = superSimpleDeckFrame.find( '.slidedeckFooter' );
			footerHeight = footer.outerHeight();					
			
			footerTitle = superSimpleDeckFrame.find( '.slideTitle' );
			superSimpleNext = superSimpleDeckFrame.find('a.nextSlide');
			superSimplePrev = superSimpleDeckFrame.find('a.prevSlide');
			
			superSimpleNext.click(function(event){
				event.preventDefault();    				
				superSimpleDeck.next();				
			});
			superSimplePrev.click(function(event){
				event.preventDefault();    				
				superSimpleDeck.prev();				
			});			
			
			updateFooterTitle();
			updateDisabledNavigation();
		});	
		
		superSimpleDeck.options.complete = function(){
			updateFooterTitle();
			updateDisabledNavigation();
		}
	}
	
	
	$(document).ready(function(){
		for(var i=0, decks=$('.skin-super-simple'); i<decks.length; i++){
			var thisDeck = decks[i];
			
			if(typeof(thisDeck.SlideDeck_skinSuperSimple) == 'undefined'){
				thisDeck.SlideDeck_skinSuperSimple = SuperSimpleSlideDeck( thisDeck );
			}
		}
	});    
})(jQuery);
