// TODO : créer une classe


window.addEvent('domready', function() {

    $$('.moocorners').each(function(el){
        var tag = el.getTag();
        if (tag == 'img') {
            var divimg = new Element('div', {
                'styles': {
                    'position': 'absolute',
                    'border': el.getStyle('border'),
                    'background-color' : '#fff',
                    'background-image' : 'url('+el.getProperty('src')+')'

                },
                'class': el.className
            });
            var ic = el.getCoordinates();
            divimg.setStyles(ic);
            divimg.setStyles({
                width : el.getStyle('width'),
                height : el.getStyle('height')
            });
            divimg.inject(document.body);
            //el.remove();
            el.setStyle('opacity',0);
        }
    });

});