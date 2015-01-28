(function ($) {

AjaxSolr.CurrentSearchWidget = AjaxSolr.AbstractWidget.extend({
  start: 0,

  rebuildStore: function () {
    /*for (var k in this.manager.store.params) {
          
          this.manager.store.remove(k);
          
    }*/
    
    this.manager.store.removeByValue('q');
    this.manager.store.removeByValue('defType', 'dismax');
    this.manager.store.removeByValue('qf', "['title^15.5', 'fullText^5.0']");
    this.manager.store.addByValue('sort', "dateline desc");
    Manager.store.addByValue('q', '*:*');

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
      };
  
  
      for (var name in params) {
        Manager.store.addByValue(name, params[name]);
      }*/
  },

  afterRequest: function () {
    var self = this;
    var links = [];

    var q = this.manager.store.get('q').val();
    if (q != '*:*') {
      links.push($('<a href="#"></a>').text('(x) ' + q).click(function () {
        //self.manager.store.get('q').val('*:*');
        self.rebuildStore();
        self.doRequest();
        return false;
      }));
    }

    var fq = this.manager.store.values('fq');
    for (var i = 0, l = fq.length; i < l; i++) {
      if (fq[i].match(/[\[\{]\S+ TO \S+[\]\}]/)) {
        var field = fq[i].match(/^\w+:/)[0];
        var value = fq[i].substr(field.length + 1, 10);
        links.push($('<a href="#"></a>').text('(x) ' + value).click(self.removeFacet(fq[i])));
      }
      else {
        links.push($('<a href="#"></a>').text('(x) ' + fq[i]).click(self.removeFacet(fq[i])));
      }
    }

    if (links.length > 1) {
      links.unshift($('<a href="#"></a>').text('remove all').click(function () {
        self.manager.store.get('q').val('*:*');
        self.manager.store.remove('fq');
        self.doRequest();
        return false;
      }));
    }

    if (links.length) {
      var $target = $(this.target);
      $target.empty();
      for (var i = 0, l = links.length; i < l; i++) {
        $target.append($('<li></li>').append(links[i]));
      }
    }
    else {
      $(this.target).html('<li>Viewing all documents!</li>');
    }
  },

  removeFacet: function (facet) {
    var self = this;
    return function () {
      self.rebuildStore();
      if (self.manager.store.removeByValue('fq', facet)) {
        self.doRequest();
      }
      return false;
    };
  }
});

})(jQuery);
