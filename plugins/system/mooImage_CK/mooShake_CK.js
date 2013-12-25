
/**
 * Classe Mootools pour effet MooShake
 * @copyright	Copyright (C) 2010 Cédric KEIFLIN alias ced1870
 * http://www.ck-web-creation-alsace.com
 * http://www.joomlack.fr.nf
 * @license		GNU/GPL
 * */

var MooShake_ck = new Class ({
    Implements: Options,
	
    options: {    //options par défaut si aucune option utilisateur n'est renseignée
        mooDuree : 100,
        decalage : 10
    },
			
    initialize: function(items, options) {
        this.setOptions(options);
        var maduree = this.options.mooDuree;
        var decalage = this.options.decalage;

        items.each(function(item){

            item.addEvent('mouseover', function(e) {
                //alert(this.moving);
                e = new Event(e).stop();
				
                var itemCoordinates = this.getCoordinates();
 
                var clonetop = itemCoordinates['top']-decalage;
                var cloneleft = itemCoordinates['left']-decalage;

                var clone = this.clone()
                .setStyles(itemCoordinates) // this returns an object with left/top/bottom/right, so its perfect
                .setStyles({
                    'position': 'absolute'
                }).inject(document.body);
				
                this.setStyle('opacity',0);
                var fx = new Fx.Styles(clone, {
                    duration:maduree,
                    wait:false
                });
		
                fx.start({
                    'left' : cloneleft
                });
		
		
		
		
                var fx2 = new Fx.Styles(clone, {
                    duration:maduree,
                    wait:false
                });
                var fx3 = new Fx.Styles(clone, {
                    duration:maduree,
                    wait:false
                });

		
                animComp = function(){
			
                    fx2.start({
                        'left' : itemCoordinates['left']+10
                    });
					
                }
				
                fx.addEvent ('onComplete', animComp.bind(this));
		
                animComp2 = function(){
					
                    fx3.start({
                        'left' : itemCoordinates['left']
                    });
                }
				
                fx2.addEvent ('onComplete', animComp2.bind(this));
		
                animComp3 = function(){
					
                    clone.remove();
                    this.setStyle('opacity',1);
                }
				
                fx3.addEvent ('onComplete', animComp3.bind(this));
		
		
            });
			
			
        });
	
		
    }
});

MooShake_ck.implement(new Options);


