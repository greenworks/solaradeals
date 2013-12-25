
 /**
 * Classe Mootools pour effet Moopuffy
 * @copyright	Copyright (C) 2010 Cédric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr.nf
 * @license		GNU/GPL
 * */

var Moopuffy_ck = new Class({
    Implements: Options,
	
    options: {    //options par défaut si aucune option utilisateur n'est renseignée
        mooDuree : 500,
        ratio : 1.3
    },
			
    initialize: function(items, options) {
        this.setOptions(options);
        var maduree = this.options.mooDuree;
        var ratio = this.options.ratio;



        items.each(function(item){
            item.addEvent('mouseover', function(e) {
                e = new Event(e).stop();
                var itemCoordinates = this.getCoordinates();
 
                var largeur = this.offsetWidth*ratio;
                var hauteur = this.offsetHeight*ratio;
                var clonetop = itemCoordinates['top']-((hauteur - this.offsetHeight)/2);
                var cloneleft = itemCoordinates['left']-((largeur - this.offsetWidth)/2);
                

                var clone = this.clone()
                .setStyles(itemCoordinates) // this returns an object with left/top/bottom/right, so its perfect
                .setStyles({
                    'opacity': 0.7,
                    'position': 'absolute'
                })
                .addEvent('emptydrop', function() {
                    this.remove();
                }).inject(document.body);
			
                var fx = new Fx.Styles(clone, {
                    duration:maduree,
                    wait:false
                });
                fx.start({
                    'width': largeur,
                    'height': hauteur,
                    'top' : clonetop,
                    'left' : cloneleft,
                    'opacity' : 0
                });
		
                animCompPuffy = function(){
                    clone.remove();
                }
				
                fx.addEvent ('onComplete', animCompPuffy.bind(this));
            });
        });
	
		
    }
});

Moopuffy_ck.implement(new Options);


