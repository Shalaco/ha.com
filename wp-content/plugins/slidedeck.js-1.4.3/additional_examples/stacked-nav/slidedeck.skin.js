(function($){
    SlideDeckSkin['stacked-nav'] = function(slidedeck){
        var ns = 'stacked-nav';
        var elems = {};
            elems.slidedeck = $(slidedeck);
            elems.frame = elems.slidedeck.closest('.skin-' + ns);
            elems.horizontalSlides = elems.slidedeck.slidedeck().slides;
        
        var dimensions = {
            slidedeckWidth: elems.slidedeck.width(),
            slidedeckHeight: elems.slidedeck.height()
        };
        
        // Set width of SlideDeck's frame to width of SlideDeck
        elems.frame.width(dimensions.slidedeckWidth);
        
        // Loop through each horizontal slide to apply vertical modifications on a per slide basis
        elems.horizontalSlides.each(function(){
            var $horizontalSlide = $(this);
            var slideElems = {};
                slideElems.verticalElems = $horizontalSlide.find('ul.verticalSlideNav li').not('.arrow');
                slideElems.verticalLinks = slideElems.verticalElems.find('a');
                slideElems.verticalLinks.wrapInner('<span class="inner" />');
                slideElems.verticalLinksInner = slideElems.verticalLinks.find('span.inner');
            
            var navDimensions = {
                elemHeight: Math.floor(dimensions.slidedeckHeight/slideElems.verticalElems.length)
            };
            navDimensions.heightTotal = navDimensions.elemHeight * slideElems.verticalElems.length;
            navDimensions.remainder = dimensions.slidedeckHeight - navDimensions.heightTotal;
            
            // Center the spans inside the vertical anchor tags so the text centers
            // no matter if it line wraps or not
            slideElems.verticalLinksInner.each(function(){
                var $elem = $(this);
                var elHeight = $elem.height();
                var elWidth = $elem.closest('a').width();
                $elem.css({
                    position: 'absolute',
                    top: '50%',
                    marginTop: 0 - (elHeight / 2),
                    width: elWidth
                });
            });
            
            slideElems.verticalElems.each(function(){
                var $elem = $(this);
                var borderTop = parseInt( $elem.css('border-top-width') );
                var borderBottom = parseInt( $elem.css('border-bottom-width') );
                var borderHeight = borderTop + borderBottom
                var elemLink = $elem.find('a');
                var elemLinkBorderTop = parseInt( elemLink.css('border-top-width') );
                var elemLinkBorderBottom = parseInt( elemLink.css('border-bottom-width') );
                var elemLinkBorderHeight = elemLinkBorderTop + elemLinkBorderBottom;
                
                $elem.height( navDimensions.elemHeight - borderHeight );
                elemLink.height( (navDimensions.elemHeight - borderHeight) - elemLinkBorderHeight );
            });
            
            var i = 0;
            while( navDimensions.remainder > 0 ){
                var elHeight = $( slideElems.verticalElems[i] ).height();
                var elLinkHeight = $( slideElems.verticalElems[i] ).find('a').height();
                $( slideElems.verticalElems[i] ).css({
                    height: ( elHeight + 1 ) + 'px'
                });
                $( slideElems.verticalElems[i] ).find('a').css({
                    height: ( elLinkHeight + 1 ) + 'px'
                });
                
                i++;
                if (i >= (slideElems.verticalElems.length)) {
                    i = 0;
                }       
                navDimensions.remainder--;
            };
        });
        
    };
    
    $(document).ready(function(){
        $('.slidedeck').each(function(){
            new SlideDeckSkin['stacked-nav'](this);
        });
    });
})(jQuery);
