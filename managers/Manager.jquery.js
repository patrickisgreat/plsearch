(function (callback) {
  if (typeof define === 'function' && define.amd) {
    define(['core/AbstractManager'], callback);
  }
  else {
    callback();
  }
}(function () {

/**
 * @see http://wiki.apache.org/solr/SolJSON#JSON_specific_parameters
 * @class Manager
 * @augments AjaxSolr.AbstractManager
 */
AjaxSolr.Manager = AjaxSolr.AbstractManager.extend(
  /** @lends AjaxSolr.Manager.prototype */
  {
  executeRequest: function (servlet, string, handler, errorHandler) {
    var self = this,
        options = {dataType: 'json'};
		
		string = string || this.store.string();
    	
    	console.log('THIS IS THE PARAM STRING'+ string);
    
		handler = handler || function (data) {
    
      self.handleResponse(data);
    
    };
    
    errorHandler = errorHandler || function (jqXHR, textStatus, errorThrown) {
      self.handleError(textStatus + ', ' + errorThrown);
    };

    if (this.proxyUrl) {
      options.url = this.proxyUrl;
      options.data = {query: string};
      options.type = 'POST';
    }

    else {
      // just add this to sort by date '&sort=dateline%20desc'
      
      //alert(session);
      
      var filter;
      
      //this is coming from PL usegroupid and can be extended to further support different language scenarios
      //fully intend to integrate this into the class methods for building or altering the parameter store
      if (window.usergroupid == "2") {
	      
	      filter = '&fq=lang:1';
	      	
      } else {
	      
	      filter = '&fq=lang:2';
	  
	  }
      
      //testing
      //var filter = '&fq=forumid:[* TO 232]';
      //var filter = '&fq=forumid:[232 TO 863]';
      //var filter = '&sort=forumid%20asc';
    
        options.url = this.solrUrl + servlet + '?' + string + '&wt=json&json.wrf=?'+filter;
        console.log(options);
        
    }

    jQuery.ajax(options).done(handler).fail(errorHandler);
  }
});

}));
