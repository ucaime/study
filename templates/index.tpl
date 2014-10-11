<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><{$dates}>排行</title>
</head>

<body>
<div>
	<div><h2>微信公众号文章排行榜-<{date("Y年m月d日",strtotime($dates))}></h2></div>
	<div>
		<ul>
			<{foreach from=$contect key=K item=V}>
			<{foreach from=$V key=k item=v}>
			<{$v}>
			<{/foreach}>
			<{/foreach}>
		</ul>
	</div>
</div>
</body>
</html>