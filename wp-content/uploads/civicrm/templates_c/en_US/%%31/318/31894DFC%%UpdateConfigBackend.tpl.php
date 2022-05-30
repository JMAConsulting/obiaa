<?php /* Smarty version 2.6.31, created on 2022-05-30 04:23:45
         compiled from CRM/Admin/Form/Setting/UpdateConfigBackend.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Admin/Form/Setting/UpdateConfigBackend.tpl', 1, false),array('block', 'ts', 'CRM/Admin/Form/Setting/UpdateConfigBackend.tpl', 12, false),array('function', 'crmURL', 'CRM/Admin/Form/Setting/UpdateConfigBackend.tpl', 15, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div class="help">
  <p>
    <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>When migrating a site to a new server, the paths and URLs of your CiviCRM installation may change. <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  </p>
  <p>
    <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => "civicrm/admin/setting/path",'q' => "reset=1"), $this);?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('pathsURL', ob_get_contents());ob_end_clean(); ?>
    <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => "civicrm/admin/setting/url",'q' => "reset=1"), $this);?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('urlsURL', ob_get_contents());ob_end_clean(); ?>
    <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['pathsURL'],'2' => $this->_tpl_vars['urlsURL'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>The old paths and URLs may be retained in some database records. Use this form to clear caches or to reset paths to their defaults. If you need further customizations, then update the <a href="%1">Directories</a> and <a href="%2">Resource URLs</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  </p>
</div>
<div class="crm-block crm-form-block crm-config-backend-form-block">
  <div><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/formButtons.tpl", 'smarty_include_vars' => array('location' => 'bottom')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
</div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>