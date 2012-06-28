<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Focus Business Documentation</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <!-- Styles -->
    
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    
    <link href="./css/docs.css" rel="stylesheet">  
    
    <link href="./js/google-code-prettify/prettify.css" rel="stylesheet"> 
	<script src="./js/google-code-prettify/prettify.js"></script>
        
    <!-- Javascript -->
    
	<script src="../js/jquery-1.7.2.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	
	
	<script src="./js/jquery.ba-bbq.min.js"></script>
	<script src="./js/jquery.scrollTo-min.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
  </head>

<body>
	
	
	
<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#home">Documentation Home</a>
         
          <ul class="nav">
	        <li><a href="javascript:;">Version 1.0</a></li>
	        <li class="demo"><a href="../index.html" target="_blank">View Demo</a></li>
	      </ul>
	      
          <ul class="nav pull-right">
	        <li><a href="http://propelui.com" target="_blank">Built by Propel.</a></li>
	      </ul>
        </div>
      </div>
    </div>
	
	
	
	
<div id="wrapper">
	
	<div id="nav">
		
		<ul class="nav nav-list">
			
			<li class="misc"><a href="#home"><i class="icon-home"></i> Home</a></li>
			
			<li class="nav-header">
			Styles
			</li>
			<li><a href="#grid"><i class="icon-th"></i> Grid</a></li>				
			<li><a href="#masthead"><i class="icon-picture"></i> Masthead</a></li>
			<li><a href="#pricing"><i class="icon-money"></i> Pricing Plans</a></li>
			<li><a href="#faq"><i class="icon-question-sign"></i> Faq</a></li>
			<li><a href="#misc"><i class="icon-asterisk"></i> Miscellaneous</a></li>
				
		</ul>
		
	</div> <!-- /nav -->
	
	
	<div id="content">		


<?php

if ($handle = opendir('./pages/')) {

    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
    	
    	if (strlen ($entry) < 3) { continue; }
    	
    	$page = explode ('.', $entry);
    	$page = $page[0];
        
    	echo '<div id="' . $page . '-content" class="content">';
        include ('./pages/' . $entry);
        echo '</div>';
    }

    closedir($handle);
}

?>

</div> <!-- /content -->
	
	
</div> <!-- /wrapper -->
	
<script>

$(function () {
	
	$('pre').addClass ('prettyprint linenums');
	prettyPrint ();
	
	getToc ();
	
	routeContent ();
	
	$('#nav a').each (function (i) {
		
		if ($(this).parent ().is ('.misc')) { return; }
		
		var a = $(this).clone ();
		
		var shortcut = $('<a>', {
			'href': a.attr ('href'),
			'class': 'shortcut'			
		});
		
		var icon = $('<i>', {
			'class': 'shortcut-icon ' + a.find ('i').attr ('class')
		});
		
		var label = $('<span>', {
			'class': 'shortcut-label',
			'text': a.text ()
		});		
		
		icon.appendTo (shortcut);
		label.appendTo (shortcut);		
		shortcut.appendTo ('.shortcuts');
		
	});	
	
	$('body').append ('<div id="toTop">^ Back to Top</div>');
	
	
	$(window).bind ('hashchange', function (e) {
		routeContent ();
	});
	
	
	$('#nav a, .shortcuts a, .brand').live ('click', function (e) {		
		e.preventDefault ();		
		location.hash = $(this).attr ('href').split ('#')[1];		
	});
	
	$('#toTop').click(function() {
		$('body,html').animate({scrollTop:0},800);
	});
	
	$('a.third-party-doc').attr ('target', '_blank');
	
	
	
});

function routeContent () {
	
	var hash = location.hash;
	hash = hash.split ('#')[1];
	
	if (hash === undefined || hash === '') {
		hash = 'home';
	}
	
	$('#nav li').removeClass ('active');
	
	$('a[href=#' + hash + ']').parent ().addClass ('active');
	
	$('.content').hide ();
	
	var newPage = $('#' + hash + '-content').show ();
	
	if (newPage.length < 1) {
		$("#error-content").show ();
	}
		
			
	$('body,html').animate({scrollTop:0},0);
	
	$(window).scroll(function() {
		if($(this).scrollTop() != 0) {
			$('#toTop').fadeIn();	
		} else {
			$('#toTop').fadeOut();
		}
	});
 
	
}

function getToc () {
		
		
	$('.content').each (function (i) {
		
		var toc = '<ol class="toc">';
		
		$(this).find ('h3').each (function (i) {
			var h3, id, pageSlug, slug;
			
			h3 = $(this);
			pageSlug = slugify ($.trim ($(this).parents ('.content').find ('h1').text ()));
			id = slugify (h3.text ());
			slug = pageSlug + '-' + id;
			
			
			
			toc += '<li><a href="#' + slug + '">' + h3.text () + '</a></li>';
			
			h3.attr ('id', slug);
			h3.text ((i+1) + '. ' + h3.text ());

		});
		
		
		toc += '</ol>';
		
		$(this).find ('hr').eq (0).before (toc);
	});
		
	
	
	$('.toc').find ('a').bind ('click', function (e) {
		e.preventDefault ();	
		$.scrollTo ( $(this).attr ('href') , 1000 );
	});
}

function slugify(text) {
	text = text.replace(/[^-a-zA-Z0-9,&\s]+/ig, '');
	text = text.replace(/-/gi, "_");
	text = text.replace(/\s/gi, "-");
	text = text.toLowerCase ();
	return text;
}

</script>
	
</body>

</html>