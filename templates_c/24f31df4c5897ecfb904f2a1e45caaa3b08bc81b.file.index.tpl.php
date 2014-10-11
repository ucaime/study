<?php /* Smarty version Smarty-3.1.20, created on 2014-10-11 17:20:16
         compiled from "templates\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:174925438e96026b694-32570199%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24f31df4c5897ecfb904f2a1e45caaa3b08bc81b' => 
    array (
      0 => 'templates\\index.tpl',
      1 => 1413019197,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '174925438e96026b694-32570199',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.20',
  'unifunc' => 'content_5438e960369558_38909961',
  'variables' => 
  array (
    'dates' => 0,
    'contect' => 0,
    'V' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5438e960369558_38909961')) {function content_5438e960369558_38909961($_smarty_tpl) {?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $_smarty_tpl->tpl_vars['dates']->value;?>
排行</title>
</head>

<body>
<div>
	<div><h2>微信公众号文章排行榜-<?php echo date("Y年m月d日",strtotime($_smarty_tpl->tpl_vars['dates']->value));?>
</h2></div>
	<div>
		<ul>
			<?php  $_smarty_tpl->tpl_vars['V'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['V']->_loop = false;
 $_smarty_tpl->tpl_vars['K'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['contect']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['V']->key => $_smarty_tpl->tpl_vars['V']->value) {
$_smarty_tpl->tpl_vars['V']->_loop = true;
 $_smarty_tpl->tpl_vars['K']->value = $_smarty_tpl->tpl_vars['V']->key;
?>
			<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['V']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<?php echo $_smarty_tpl->tpl_vars['v']->value;?>

			<?php } ?>
			<?php } ?>
		</ul>
	</div>
</div>
</body>
</html><?php }} ?>
