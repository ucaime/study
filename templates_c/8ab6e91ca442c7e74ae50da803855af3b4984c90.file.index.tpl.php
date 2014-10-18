<?php /* Smarty version Smarty-3.1.20, created on 2014-10-17 11:29:37
         compiled from "templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:153270021654408c60de0a12-07062095%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8ab6e91ca442c7e74ae50da803855af3b4984c90' => 
    array (
      0 => 'templates/index.tpl',
      1 => 1413516572,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '153270021654408c60de0a12-07062095',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.20',
  'unifunc' => 'content_54408c60ea11e6_38912515',
  'variables' => 
  array (
    'week' => 0,
    'datesp' => 0,
    'datesn' => 0,
    'dates' => 0,
    'contect' => 0,
    'k' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54408c60ea11e6_38912515')) {function content_54408c60ea11e6_38912515($_smarty_tpl) {?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php if ($_smarty_tpl->tpl_vars['week']->value==1) {?>微信文章周排行-<?php echo date("m月d日",$_smarty_tpl->tpl_vars['datesp']->value);?>
-<?php echo date("m月d日",$_smarty_tpl->tpl_vars['datesn']->value);?>
<?php } else { ?>微信文章排行榜-<?php echo date("Y年m月d日",strtotime($_smarty_tpl->tpl_vars['dates']->value));?>
<?php }?></title>
<style>

body {
    width: 900px;
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
<h2><?php if ($_smarty_tpl->tpl_vars['week']->value==1) {?>微信文章周排行-<?php echo date("m月d日",$_smarty_tpl->tpl_vars['datesp']->value);?>
-<?php echo date("m月d日",$_smarty_tpl->tpl_vars['datesn']->value);?>
<?php } else { ?>微信文章排行榜-<?php echo date("Y年m月d日",strtotime($_smarty_tpl->tpl_vars['dates']->value));?>
<?php }?></h2>
<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['arr'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['name'] = 'arr';
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['contect']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['arr']['total']);
?>
	<table class="bordered">
		<tbody>
			<tr bgcolor="#308598" style="font-size: 23px;">
				<td colspan="2" class="title"><?php echo $_smarty_tpl->tpl_vars['contect']->value[$_smarty_tpl->getVariable('smarty')->value['section']['arr']['index']]['type_name'];?>
</td>
				<td colspan="5"></td>
			</tr>
			<tr bgcolor="#308598">
				<td>排名</td>
				<td>公众号</td>
				<td>文章标题</td>
				<td>阅读</td>
				<td>点赞</td>
				<td>更新频次</td>
                <td>综合排名</td>
			</tr>
			<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['contect']->value[$_smarty_tpl->getVariable('smarty')->value['section']['arr']['index']]['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<?php if ($_smarty_tpl->tpl_vars['k']->value%2==0) {?>
			<tr bgcolor="#DBEDF4">
				<td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['gname'];?>
</td>
				<td><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['wzurl'];?>
" target='_blank'><?php echo $_smarty_tpl->tpl_vars['v']->value['wztitle'];?>
</a></td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['wzreads'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['wzsuports'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['pinci'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['v']->value['totals'];?>
</td>
			</tr>
			<?php } elseif ($_smarty_tpl->tpl_vars['k']->value%2!=0) {?>
			<tr bgcolor="#B6DDE9">
				<td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['gname'];?>
</td>
				<td><a href="<?php echo $_smarty_tpl->tpl_vars['v']->value['wzurl'];?>
" target='_blank'><?php echo $_smarty_tpl->tpl_vars['v']->value['wztitle'];?>
</a></td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['wzreads'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['wzsuports'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['v']->value['pinci'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['v']->value['totals'];?>
</td>
			</tr>
			<?php } else { ?>
			<?php }?>
			<?php } ?>
		</tbody>
	</table>
	<br>
<?php endfor; endif; ?>
</body>
</html><?php }} ?>
