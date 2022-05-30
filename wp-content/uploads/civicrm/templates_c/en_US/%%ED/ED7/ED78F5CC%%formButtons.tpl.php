<?php /* Smarty version 2.6.31, created on 2022-05-30 04:23:45
         compiled from CRM/common/formButtons.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/formButtons.tpl', 1, false),array('block', 'crmRegion', 'CRM/common/formButtons.tpl', 11, false),array('function', 'crmURL', 'CRM/common/formButtons.tpl', 27, false),array('modifier', 'substring', 'CRM/common/formButtons.tpl', 32, false),array('modifier', 'crmReplace', 'CRM/common/formButtons.tpl', 34, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('crmRegion', array('name' => 'form-buttons')); $_block_repeat=true;smarty_block_crmRegion($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if ($this->_tpl_vars['linkButtons']): ?>
  <?php $_from = $this->_tpl_vars['linkButtons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['linkButton']):
?>
    <?php if ($this->_tpl_vars['linkButton']['accessKey']): ?>
      <?php ob_start(); ?>accesskey="<?php echo $this->_tpl_vars['linkButton']['accessKey']; ?>
"<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('accessKey', ob_get_contents());ob_end_clean(); ?>
    <?php else: ?><?php $this->assign('accessKey', ""); ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['linkButton']['icon']): ?>
      <?php ob_start(); ?><i class="crm-i <?php echo $this->_tpl_vars['linkButton']['icon']; ?>
" aria-hidden="true"></i> <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('icon', ob_get_contents());ob_end_clean(); ?>
    <?php else: ?><?php $this->assign('icon', ""); ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['linkButton']['ref']): ?>
      <?php ob_start(); ?>name="<?php echo $this->_tpl_vars['linkButton']['ref']; ?>
"<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('linkname', ob_get_contents());ob_end_clean(); ?>
    <?php else: ?><?php ob_start(); ?>name="<?php echo $this->_tpl_vars['linkButton']['name']; ?>
"<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('linkname', ob_get_contents());ob_end_clean(); ?>
    <?php endif; ?>
    <a class="button<?php if (array_key_exists ( 'class' , $this->_tpl_vars['linkButton'] )): ?> <?php echo $this->_tpl_vars['linkButton']['class']; ?>
<?php endif; ?>" <?php echo $this->_tpl_vars['linkname']; ?>
 href="<?php echo CRM_Utils_System::crmURL(array('p' => $this->_tpl_vars['linkButton']['url'],'q' => $this->_tpl_vars['linkButton']['qs']), $this);?>
" <?php echo $this->_tpl_vars['accessKey']; ?>
 <?php echo $this->_tpl_vars['linkButton']['extra']; ?>
><span><?php echo $this->_tpl_vars['icon']; ?>
<?php echo $this->_tpl_vars['linkButton']['title']; ?>
</span></a>
  <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['form']['buttons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['btns'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['btns']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['button']):
        $this->_foreach['btns']['iteration']++;
?>
  <?php if (((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('substring', true, $_tmp, 0, 4) : smarty_modifier_substring($_tmp, 0, 4)) == '_qf_'): ?>
    <?php if (! empty ( $this->_tpl_vars['location'] )): ?>
      <?php echo ((is_array($_tmp=$this->_tpl_vars['form']['buttons'][$this->_tpl_vars['key']]['html'])) ? $this->_run_mod_handler('crmReplace', true, $_tmp, 'id', ($this->_tpl_vars['key'])."-".($this->_tpl_vars['location'])) : smarty_modifier_crmReplace($_tmp, 'id', ($this->_tpl_vars['key'])."-".($this->_tpl_vars['location']))); ?>

    <?php else: ?>
      <?php echo $this->_tpl_vars['form']['buttons'][$this->_tpl_vars['key']]['html']; ?>

    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmRegion($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>