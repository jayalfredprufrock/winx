<!DOCTYPE html>

<html lang="<?= $this->language->get() ?>" class="no-js">

	<head>
        
		<meta charset="utf-8">
		
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    
		<title><?= lang('page_title') ?></title>
		<meta name="description" content="<?= lang('page_description')?>">
		
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta http-equiv="cleartype" content="on">
		
		<link rel="shortcut icon" href="assets/images/icons/favicon.ico">
	    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/icons/apple-touch-icon-144x144-precomposed.png">
	    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/icons/apple-touch-icon-114x114-precomposed.png">
	    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/icons/apple-touch-icon-72x72-precomposed.png">
	    <link rel="apple-touch-icon-precomposed" href="assets/images/icons/apple-touch-icon-57x57-precomposed.png">
	    <link rel="shortcut icon" href="assets/images/icons/apple-touch-icon.png">
	
	   {{css}}
	   
	   {{js_head}}
	
	</head>
	
	<body>
		
		{{css_page|div}}
		
		{{layout|div}}
		
		{{modal|div}}
	
		{{js|div}}
		
		{{js_page|div}}

    </body>
	    
</html>

