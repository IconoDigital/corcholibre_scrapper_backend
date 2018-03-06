var webPage = require('webpage');
var fs = require('fs');
var page = webPage.create();
var args = require('system').args;
var address = args[1];
 page.open(address, function() {
      page.evaluate(function() {
      });
    });
	
	 page.onLoadFinished = function() {
      //console.log("page load finished");
      /*page.render('export.png');
      fs.write('1.html', page.content, 'w');*/
	  var page_html = page.evaluate(function(s) {
		return document.documentElement.outerHTML;
		}, 'title');
		//console.log(page_html);
		fs.write('/var/www/html/scraper/scrap.html', page_html, 'w');
      phantom.exit();
    };