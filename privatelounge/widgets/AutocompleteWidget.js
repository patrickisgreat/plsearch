(function ($) {


AjaxSolr.AutocompleteWidget = AjaxSolr.AbstractTextWidget.extend({
//grab the params so we can rebuild after an autocomplete query
	rebuildStore: function () {
		/*for (var k in this.manager.store.params) {
          
          this.manager.store.remove(k);
          
    }*/
    //this.manager.store.removeByValue('q');
    this.manager.store.removeByValue('defType', 'dismax');
    this.manager.store.removeByValue('qf', "['title^15.5', 'fullText^5.0']");
    this.manager.store.addByValue('sort', "dateline desc");
    //Manager.store.addByValue('q', '*:*');
		/*var params = {
			facet: true,
        'facet.field': ['title'],
        'facet.date': 'dateline',
        'facet.date.start': '2006-01-01T00:00:00.000Z/DAY',
        'facet.date.end': '2014-01-20T00:00:00.000Z/DAY+1DAY',
        'facet.date.gap': '+1DAY',
        'facet.limit': 20,
        'facet.mincount': 1,
        'f.title.facet.limit': 50,
        'json.nl': 'map'
	    };*/
	
	
	    /*for (var name in params) {
	      Manager.store.addByValue(name, params[name]);
	    }*/
	},
	
  buildNonFacetStore: function (value) {
    //this can remove all parameters from the store.
    /*for (var k in this.manager.store.params) {
          
        this.manager.store.remove(k);
          
    }*/

    this.manager.store.removeByValue('q');
    this.manager.store.removeByValue('sort', "dateline desc");
    Manager.store.addByValue('q', value);
      var params = { 
        'defType': 'dismax',
        'qf': ['title^15.5', 'fullText^5.0']
      };

      for (var name in params) {
        Manager.store.addByValue(name, params[name]);
      }
  },
  
  afterRequest: function () {
    	
    $(this.target).find('input').unbind().removeData('events').val('');

    var self = this;
	
    var callback = function (response) {
      var list = [];
      for (var i = 0; i < self.fields.length; i++) {
        var field = self.fields[i];
        for (var facet in response.facet_counts.facet_fields[field]) {
          list.push({
            field: field,
            value: facet,
            label: facet + ' (' + response.facet_counts.facet_fields[field][facet] + ')'
          });
        }
      }
      
      /*http://patrickisgreat.me:8983/solr/privatelounge/select?facet=true&q=(asphalt%20OR%20eating%20OR%20beast%20)&facet.field=title&facet.date=dateline&facet.date.start=2006-01-01T00%3A00%3A00.000Z%2FDAY&facet.date.end=2014-01-20T00%3A00%3A00.000Z%2FDAY%2B1DAY&facet.date.gap=%2B1DAY&facet.limit=20&facet.mincount=1&f.title.facet.limit=50&json.nl=map&wt=json&json.wrf=?&fq=forumid:[*%20TO%20232]*/
	  
	 /* self.requestSent = false;
	  $(self.target).find('input').autocomplete('destroy').autocomplete({
		 source: function( request, response ) {
			 $.ajax({
				 url: "http://patrickisgreat.me:8983/solr/privatelounge/suggest?&json.wrf=?",
				 dataType: "jsonp",
				 data: {
					 q: request.term,
					 wt: "json",
					 Rows: 12,
					 start:0,
				},
				success: function( data ) {
		        //console.log(data);
		        response( 
		        $.map(data.spellcheck.suggestions[1].suggestion, function(item,i) {
			    	return {
			            label: item,
			            value: item
		              
		            }
		          })
		        );
		      }
		    });
		  },
		  minLength: 2,
		  select: function(event, ui) {
          
          if (ui.item) {
            self.requestSent = true;
		 	  //remove everything from the parameter store           
		      /*for (var k in self.manager.store.params) {
				  
				  self.manager.store.remove(k);
				  
			  }*/
            
            //rebuild for just this query
            //if (self.manager.store.addByValue('fq', 'content_autosuggest:'+AjaxSolr.Parameter.escapeValue(ui.item.value))) {
            //  	self.doRequest();
			  	
            //}
            
          //}
        //}
		//});
	
	//self.rebuildStore();
    
      //check URL and see if we are in the search view already
      var uri = document.URL;
      if (uri.match('/search/')) {
        window.viewIsSearch = true;
      } else {
        window.viewIsSearch = false;
      }
      var host = document.location.hostname;
      self.requestSent = false;
      $(self.target).find('input').autocomplete('destroy').autocomplete({
        source: list,
        select: function(event, ui) {
          if (ui.item) {
            self.requestSent = true;
            self.rebuildStore();
            if (self.manager.store.addByValue('fq', ui.item.field + ':' + AjaxSolr.Parameter.escapeValue(ui.item.value)) && window.viewIsSearch === true) {
                self.doRequest();
            //if we're not already in search go to search and just load the param string
            } else if (window.viewIsSearch == false){  
              var paramString = self.manager.store.string();
              window.location.href = 'http://'+host+'/privatelounge/search/#q='+paramString;
            }
          }
        }
      });

      //This has lower priority so that requestSent is set.
      $(self.target).find('input').bind('keydown', function(e) {
        
        if (self.requestSent === false && e.which == 13) {
          
          var value = $(this).val();
          if (value && window.viewIsSearch === true) {
            
            self.buildNonFacetStore(value);
              self.doRequest();
                //self.rebuildStore();
          
          //if we're not already in search go to search and just load the param string
          } else if (value && window.viewIsSearch === false) {
            window.location.href = 'http://'+host+'/privatelounge/search/#q='+value;
          }
        }
      });
    } // end callback
	
	//http://patrickisgreat.me:8983/solr/privatelounge/suggest?q=me&wt=json&indent=true
    var params = [ 'rows=0&facet=true&facet.limit=500&facet.mincount=1&json.nl=map' ];
    for (var i = 0; i < this.fields.length; i++) {
      params.push('facet.field=' + this.fields[i]);
    }
    var values = this.manager.store.values('fq');
    console.log(values);
        for (var i = 0; i < values.length; i++) {
      params.push('fq=' + encodeURIComponent(values[i]));
    }
    params.push('q=' + this.manager.store.get('q').val());
    $.getJSON(this.manager.solrUrl + 'select?' + params.join('&') + '&wt=json&json.wrf=?', {}, callback);
  
  }
});

})(jQuery);