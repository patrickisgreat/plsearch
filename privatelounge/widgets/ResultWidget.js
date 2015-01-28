(function ($) {
AjaxSolr.ResultWidget = AjaxSolr.AbstractWidget.extend({
  start: 0,

  beforeRequest: function () {
    //$(this.target).html($('<img>').attr('src', 'images/ajax-loader.gif'));
    /*if (window.initLoad == false) { 
      window.location.href = 'http://blacqube.net/privatelounge/search';
    }*/
    
  },

  facetLinks: function (facet_field, facet_values) {
    var links = [];
    if (facet_values) {
      for (var i = 0, l = facet_values.length; i < l; i++) {
        if (facet_values[i] !== undefined) {
          links.push(
            $('<a href="#"></a>')
            .text(facet_values[i])
            .click(this.facetHandler(facet_field, facet_values[i]))
          );
        }
        else {
          links.push('no items found in current selection');
        }
      }
    }
    return links;
  },

  facetHandler: function (facet_field, facet_value) {
    var self = this;
    return function () {
      self.manager.store.remove('fq');
      self.manager.store.addByValue('fq', facet_field + ':' + AjaxSolr.Parameter.escapeValue(facet_value));
      self.doRequest();
      return false;
    };
  },

	  afterRequest: function () {
	    //alert(window.initLoad);
	  //if (window.initLoad == false) {
	    
      //window.location.hash = "search";
      //window.addEventListener("hashchange", function (event){
      $(this.target).empty();
        
      //console.log(this.manager.response);
      var controls = '<div class="results-contain"><div class="pager"><ul id="pager"></ul><br /><p>RESULTS FOR:<ul id="selection"></ul></p><div id="pager-header"></div></div></div><div    class="controls"><div class="date-contain"><p class="date-label">Filter by Date</p><div class="date-control" style="display:block;"></div></div><div class="tag-cloud"><p>Filter by Topics</p><div class="tagcloud" id="content_autosuggest"></div></div></div>';
      
      //first append the controls
      $(this.target).append(controls);
      
      //update the widget controls in the primary viewport
      //$('.date-control').css({'display':'block'});
      //$('.current-selection').css({'display':'block'});
      //$('.date-label').css({'display':'block'});
      //$('.tag-cloud').css({'display':'block'});
      
      //then append each document using a function
      for (var i = 0, l = this.manager.response.response.docs.length; i < l; i++) {
        
        var doc = this.manager.response.response.docs[i];
        
        $('.results-contain').append(this.template(doc));
        
        //if the images is broken hide it
        setTimeout(function(){
          $("img").each(function(){ 
        var image = $(this); 
        if(image.context.naturalWidth == 0 || 
        image.readyState == 'uninitialized'){  
          $(image).unbind("error").hide();
          } 
        });
        },700);
          
      var items = [];
        //items = items.concat(this.facetLinks('topics', doc.title));
    
        var $links = $('#links_' + doc.id);
        $links.empty();
        for (var j = 0, m = items.length; j < m; j++) {
          $links.append($('<li></li>').append(items[j]));
        }
      }
        //},false);    
	  //}//if
	  
	},

  template: function (doc) {
    var snippet = '';
    //establish the number of pages of replies based on each page having 10
    var threadcount = Number(doc.threadcount);
    if (threadcount >= 10) {
    
      var replyPages = Math.ceil(threadcount / 10);
    
    } else if (threadcount == 0) {

      var replyPages = 0;

    } else var replyPages = 1;
    
    //console.log(replyPages+' this is replies');
    
    var date = doc.dateline.substring(0, 10);
    //var date = new Date(date * 1000);
    Date.parse(date);

    if (doc.threadpagetext.length > 300) {
      
      snippet += doc.threadpagetext.substring(0, 300);
      snippet += '<span style="display:none;">' + doc.threadpagetext.substring(300);
      snippet += '</span> <a href="#" class="more">more</a>';
    }
    else {
      //alert(doc.pagetext.length);
      snippet += doc.threadpagetext;
    }

    //build thie link to the right privateLounge thread
    var url = 'http://blacqube.net/privatelounge/forums/showthread.php?';
    var id = doc.threadid;
    var hyphenatedTitle = doc.title.replace(/ +/g, '-');
    var hyphenatedTitle = '-'+hyphenatedTitle;
    var mainLink = url+id+hyphenatedTitle;

    //build the link to the image if it exists from an attachment
    
      var imgUrl = doc.attach;
          
    
    var output = '<div class="search-result">';
    //output += '<p id="links_' + doc.id + '" class="links"></p>';
    
    //check what's in attach and spit out html if it's a number.
    if (typeof doc.attach === 'string') 
      {
        output += '<div class="image"><img class="result-image" src="'+imgUrl+'"/></div><div class="result-text"><a href="'+mainLink+'" class="search-title"><h2 class="search-title">' + doc.title + '</h2></a><div class="date-text">'+date+'</div><p class="search-snippet">' + snippet + '</p>';
          
          //generate some links for reply pages much like vbulletin
          if (replyPages > 0) {
          		output += '<div class="replies"><p>Replies: ';
		         for (i=1; i<=replyPages; i++) {
	
	             if (i <= 3) {
	                
	                output +='<a class="reply-thread" href="'+mainLink+'/page'+i+'">'+i+'</a>';
	             
	             } else if (i == replyPages) { 
	                var p = i+1;
	                output +='<a class="reply-thread" href="'+mainLink+'/page'+p+'">....'+p+'</a>';
	             
	             }//else
	          }//for 
          output +='</p></div></div></div>';
          }//if
          
          
       
      } else {
	      
	      output += '<a href="'+mainLink+'" class="search-title"><h2 class="search-title">' + doc.title + '</h2></a><div class="date-text">'+date+'</div><p class="search-snippet">' + snippet + '</p>';

         //generate some links for reply pages much like vbulletin
          if (replyPages > 0) {
          output += '<div class="replies"><p>Replies: ';
		        for (i=1; i<=replyPages; i++) {
	
	             if (i <= 3) {
	                
	                output +='<a class="reply-thread" href="'+mainLink+'/page'+i+'">'+i+'</a>';
	             
	             } else if (i == replyPages) {
	                var p = i+1;
	                output +='<a class="reply-thread" href="'+mainLink+'/page'+p+'">....'+p+'</a>';
	             
	             }
	          }  
			  output +='</p></div></div>';
          }
          
          
      }
    return output;
 },

  init: function () {
    $(document).on('click', 'a.more', function () {
      var $this = $(this),
          span = $this.parent().find('span');

      if (span.is(':visible')) {
        span.hide();
        $this.text('more');
      }
      else {
        span.show();
        $this.text('less');
      }

      return false;
    });
  }
});

})(jQuery);