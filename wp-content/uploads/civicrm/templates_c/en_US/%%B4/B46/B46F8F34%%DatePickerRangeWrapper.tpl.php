<?php /* Smarty version 2.6.31, created on 2022-05-30 04:27:44
         compiled from CRM/Core/DatePickerRangeWrapper.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Core/DatePickerRangeWrapper.tpl', 1, false),array('modifier', 'default', 'CRM/Core/DatePickerRangeWrapper.tpl', 12, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><td <?php if (! empty ( $this->_tpl_vars['colspan'] )): ?> colspan="<?php echo $this->_tpl_vars['colspan']; ?>
" <?php else: ?> colspan="2" <?php endif; ?> <?php if (! empty ( $this->_tpl_vars['class'] )): ?> class="<?php echo $this->_tpl_vars['class']; ?>
" <?php endif; ?>>
  <?php $this->assign('from', ((is_array($_tmp=@$this->_tpl_vars['from'])) ? $this->_run_mod_handler('default', true, $_tmp, '_low') : smarty_modifier_default($_tmp, '_low'))); ?>
  <?php $this->assign('to', ((is_array($_tmp=@$this->_tpl_vars['to'])) ? $this->_run_mod_handler('default', true, $_tmp, '_high') : smarty_modifier_default($_tmp, '_high'))); ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Core/DatePickerRange.tpl", 'smarty_include_vars' => array('fieldName' => $this->_tpl_vars['fieldName'],'hideRelativeLabel' => $this->_tpl_vars['hideRelativeLabel'],'to' => $this->_tpl_vars['to'],'from' => $this->_tpl_vars['from'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>