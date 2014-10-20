/***********************************************
* Parse Twitter tweets functions
* Created by: Ye Henry Tian
* Date: July 15, 2010
***********************************************/

String.prototype.parseURL = function() {
	return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/, function(url) {
		return url.link(url);
	});
};

String.prototype.parseUsername = function() {
	return this.replace(/[@]+[A-Za-z0-9-_]+/, function(u) {
		var username = u.replace("@","")
		return u.link("http://twitter.com/"+username);
	});
};

String.prototype.parseHashtag = function() {
	return this.replace(/[#]+[A-Za-z0-9-_]+/, function(t) {
		var tag = t.replace("#","%23")
		return t.link("http://search.twitter.com/search?q="+tag);
	});
};
