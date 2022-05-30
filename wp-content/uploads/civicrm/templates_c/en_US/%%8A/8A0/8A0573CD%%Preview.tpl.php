<?php /* Smarty version 2.6.31, created on 2022-05-30 04:28:44
         compiled from CRM/UF/Form/Preview.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/UF/Form/Preview.tpl', 1, false),array('block', 'ts', 'CRM/UF/Form/Preview.tpl', 11, false),array('modifier', 'truncate', 'CRM/UF/Form/Preview.tpl', 21, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if ($this->_tpl_vars['previewField']): ?>
  <?php ob_start(); ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profile Field Preview<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('infoTitle', ob_get_contents());ob_end_clean(); ?>
<?php else: ?>
  <?php ob_start(); ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Profile Preview<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('infoTitle', ob_get_contents());ob_end_clean(); ?>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/info.tpl", 'smarty_include_vars' => array('infoType' => "no-popup profile-preview-msg",'infoMessage' => ' ')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="crm-form-block">

<?php if (! empty ( $this->_tpl_vars['fields'] )): ?>
  <?php if ($this->_tpl_vars['viewOnly']): ?>
      <div id="crm-container-inner" lang="<?php echo ((is_array($_tmp=$this->_tpl_vars['config']->lcMessages)) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, "", true) : smarty_modifier_truncate($_tmp, 2, "", true)); ?>
" xml:lang="<?php echo ((is_array($_tmp=$this->_tpl_vars['config']->lcMessages)) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, "", true) : smarty_modifier_truncate($_tmp, 2, "", true)); ?>
">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/CMSUser.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php echo ''; ?><?php if ($this->_tpl_vars['help_pre'] && $this->_tpl_vars['action'] != 4): ?><?php echo '<div class="messages help">'; ?><?php echo $this->_tpl_vars['help_pre']; ?><?php echo '</div>'; ?><?php endif; ?><?php echo ''; ?><?php $this->assign('zeroField', 'Initial Non Existent Fieldset'); ?><?php echo ''; ?><?php $this->assign('fieldset', $this->_tpl_vars['zeroField']); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/UF/Form/Fields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php if ($this->_tpl_vars['addCAPTCHA']): ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'CRM/common/ReCAPTCHA.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['field']['groupHelpPost']): ?><?php echo '<div class="messages help">'; ?><?php echo $this->_tpl_vars['field']['groupHelpPost']; ?><?php echo '</div>'; ?><?php endif; ?><?php echo ''; ?>

    </div>   <?php else: ?>
    <?php ob_start(); ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This CiviCRM profile field is view only.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('infoMessage', ob_get_contents());ob_end_clean(); ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/info.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
<?php endif; ?> 
  <div class="crm-submit-buttons">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/formButtons.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
</div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>