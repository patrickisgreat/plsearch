var Manager;
window.initLoad = true;
//init hidden div for controls
(function ($) {

  $(function () {
    
    
    Manager = new AjaxSolr.Manager({
      
       // If you are using a local Solr instance with a "reuters" core, use:
      //solrUrl: 'http://patrickisgreat.me:8983/solr/privatelounge/'
      solrUrl: 'http://patrickisgreat.me:8983/solr/pl2/'
       // If you are using a local Solr instance with a single core, use:
       // solrUrl: 'http://localhost:8983/solr/'
    });
    
    Manager.addWidget(new AjaxSolr.ResultWidget({
      id: 'result',
      target: '.search-viewport'
    }));
    
    Manager.addWidget(new AjaxSolr.PagerWidget({
      id: 'pager',
      target: '#pager',
      prevLabel: '&lt;',
      nextLabel: '&gt;',
      innerWindow: 1,
      renderHeader: function (perPage, offset, total) {
        $('#pager-header').html($('<span></span>').text(Math.min(total, offset + 1) + ' to ' + Math.min(total, offset + perPage) + ' of ' + total + ' results'));
      }
    }));
    
    var fields = ['content_autosuggest'];
    
    for (var i = 0, l = fields.length; i < l; i++) {
      Manager.addWidget(new AjaxSolr.TagcloudWidget({
        id: fields[i],
        target: '#' + fields[i],
        field: fields[i]
      }));
    }
    
    Manager.addWidget(new AjaxSolr.CurrentSearchWidget({
      id: 'currentsearch',
      target: '#selection'
    }));
    
    Manager.addWidget(new AjaxSolr.AutocompleteWidget({
      id: 'text',
      target: '#search-form',
      fields: ['content_autosuggest']
    }));
    
    /*Manager.addWidget(new AjaxSolr.SpellcheckWidget({
   }));*/
    
    Manager.addWidget(new AjaxSolr.CalendarWidget({
      id: 'calendar',
      target: '.date-control',
      field: 'dateline'
    }));
    
    Manager.setStore(new AjaxSolr.ParameterHashStore());
    Manager.store.exposed = [ 'fq', 'q', 'start' ];

    Manager.init();
    
    //2006-02-27T19:40:22+00:00
    var qu = Manager.store.get('q').val();
    //alert(qu);
    //this could probably be a recursive function hmmmmm.......
    if (qu === null) {
      Manager.store.addByValue('q', '*:*');
      var params = {
        facet: true,
        'facet.field': [ 'content_autosuggest'],
        'facet.date': 'dateline',
        'facet.date.start': '2006-01-01T00:00:00.000Z/DAY',
        'facet.date.end': '2014-01-20T00:00:00.000Z/DAY+1DAY',
        'facet.date.gap': '+1DAY',
        'facet.limit': 20,
        'facet.mincount': 1,
        'f.title.facet.limit': 20,
        'json.nl': 'map',
        'sort': 'dateline desc'
      };
        
    } else if (qu != null && qu == '*:*') {
      var params = {
        facet: true,
        'facet.field': [ 'content_autosuggest'],
        'facet.date': 'dateline',
        'facet.date.start': '2006-01-01T00:00:00.000Z/DAY',
        'facet.date.end': '2014-01-20T00:00:00.000Z/DAY+1DAY',
        'facet.date.gap': '+1DAY',
        'facet.limit': 20,
        'facet.mincount': 1,
        'f.title.facet.limit': 20,
        'json.nl': 'map',
        'sort': 'dateline desc'
      };
    } else if (qu != null && qu != '*:*') {
      var params = {
        facet: true,
        'facet.field': [ 'content_autosuggest'],
        'facet.date': 'dateline',
        'facet.date.start': '2006-01-01T00:00:00.000Z/DAY',
        'facet.date.end': '2014-01-20T00:00:00.000Z/DAY+1DAY',
        'facet.date.gap': '+1DAY',
        'facet.limit': 20,
        'facet.mincount': 1,
        'f.title.facet.limit': 20,
        'json.nl': 'map',
        'defType': 'dismax',
        'qf': ['title^15.5', 'fullText^5.0']

      };
    }

    
    
for (var name in params) {
      Manager.store.addByValue(name, params[name]);
    }
    
    //don't do request until search ???
    
      Manager.doRequest();

  });

  $.fn.showIf = function (condition) {
    if (condition) {
      return this.show();
    }
    else {
      return this.hide();
    }
  }

})(jQuery);