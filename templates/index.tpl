<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><{if $week eq 1}>微信公众号文章周排行-<{date("m月d日",$datesp)}>-<{date("m月d日",$datesn)}><{else}>微信公众号文章排行榜-<{date("Y年m月d日",strtotime($dates))}><{/if}></title>
<style>

body {
    width: 700px;
    margin: 40px auto;
    font-family: 'trebuchet MS', 'Lucida sans', Arial;
    font-size: 14px;
    color: #444;
}
.bordered td a{
	color: #444;
}
table {
    *border-collapse: collapse; /* IE7 and lower */
    border-spacing: 0;
    width: 100%;    
}

.bordered {
    border: solid #ccc 1px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 0 1px 1px #ccc; 
    -moz-box-shadow: 0 1px 1px #ccc; 
    box-shadow: 0 1px 1px #ccc;         
}

.bordered tr:hover {
    background: #fbf8e9;
    -o-transition: all 0.1s ease-in-out;
    -webkit-transition: all 0.1s ease-in-out;
    -moz-transition: all 0.1s ease-in-out;
    -ms-transition: all 0.1s ease-in-out;
    transition: all 0.1s ease-in-out;     
}    
    
.bordered td, .bordered th {
    border-left: 1px solid #ccc;
    border-top: 1px solid #ccc;
    padding: 10px;
    text-align: left;    
}

.bordered th {
    background-color: #dce9f9;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
    background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:    -moz-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:     -ms-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:      -o-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:         linear-gradient(top, #ebf3fc, #dce9f9);
    -webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset; 
    -moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;  
    box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;        
    border-top: none;
    text-shadow: 0 1px 0 rgba(255,255,255,.5); 
}

.bordered td:first-child, .bordered th:first-child {
    border-left: none;
}

.bordered th:first-child {
    -moz-border-radius: 6px 0 0 0;
    -webkit-border-radius: 6px 0 0 0;
    border-radius: 6px 0 0 0;
}

.bordered th:last-child {
    -moz-border-radius: 0 6px 0 0;
    -webkit-border-radius: 0 6px 0 0;
    border-radius: 0 6px 0 0;
}

.bordered th:only-child{
    -moz-border-radius: 6px 6px 0 0;
    -webkit-border-radius: 6px 6px 0 0;
    border-radius: 6px 6px 0 0;
}

.bordered tr:last-child td:first-child {
    -moz-border-radius: 0 0 0 6px;
    -webkit-border-radius: 0 0 0 6px;
    border-radius: 0 0 0 6px;
}

.bordered tr:last-child td:last-child {
    -moz-border-radius: 0 0 6px 0;
    -webkit-border-radius: 0 0 6px 0;
    border-radius: 0 0 6px 0;
}
</style>
</head>

<body>
<h2><{if $week eq 1}>微信公众号文章周排行-<{date("m月d日",$datesp)}>-<{date("m月d日",$datesn)}><{else}>微信公众号文章排行榜-<{date("Y年m月d日",strtotime($dates))}><{/if}></h2>
<{section name=arr loop=$contect}>
	<table class="bordered">
		<tbody>
			<tr bgcolor="#308598" style="font-size: 23px;">
				<td colspan="2" class="title"><{$contect[arr].type_name}></td>
				<td colspan="4"></td>
			</tr>
			<tr bgcolor="#308598">
				<td>排名</td>
				<td>公众号</td>
				<td>文章标题</td>
				<td>阅读</td>
				<td>点赞</td>
				<td>点赞率</td>
			</tr>
			<{foreach from=$contect[arr].lists key=k item=v}>
			<{if $k%2 eq 0}>
			<tr bgcolor="#DBEDF4">
				<td><{$k}></td>
				<td><{$v.gname}></td>
				<td><a href="<{$v.wzurl}>" target='_blank'><{$v.wztitle}></a></td>
				<td><{$v.wzreads}></td>
				<td><{$v.wzsuports}></td>
				<td><{floor($v.wzsuports/$v.wzreads*10000)/10000*100}>%</td>
			</tr>
			<{elseif $k%2 neq 0}>
			<tr bgcolor="#B6DDE9">
				<td><{$k}></td>
				<td><{$v.gname}></td>
				<td><a href="<{$v.wzurl}>" target='_blank'><{$v.wztitle}></a></td>
				<td><{$v.wzreads}></td>
				<td><{$v.wzsuports}></td>
				<td><{floor($v.wzsuports/$v.wzreads*10000)/10000*100}>%</td>
			</tr>
			<{else}>
			<{/if}>
			<{/foreach}>
		</tbody>
	</table>
	<br>
<{/section}>
</body>
</html>