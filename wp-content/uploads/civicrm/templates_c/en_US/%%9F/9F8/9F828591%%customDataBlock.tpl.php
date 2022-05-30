<?php /* Smarty version 2.6.31, created on 2022-05-30 04:31:34
         compiled from CRM/common/customDataBlock.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/customDataBlock.tpl', 1, false),array('modifier', 'default', 'CRM/common/customDataBlock.tpl', 5, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if (! empty ( $this->_tpl_vars['customDataType'] )): ?>
  <div id="customData"></div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/customData.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php $this->assign('cid', ((is_array($_tmp=@$this->_tpl_vars['cid'])) ? $this->_run_mod_handler('default', true, $_tmp, 'false') : smarty_modifier_default($_tmp, 'false'))); ?>
  <?php echo '
  <script type="text/javascript">
    CRM.$(function($) {
      '; ?>

      <?php if (! empty ( $this->_tpl_vars['customDataSubType'] )): ?>
        CRM.buildCustomData('<?php echo $this->_tpl_vars['customDataType']; ?>
', <?php echo $this->_tpl_vars['customDataSubType']; ?>
, false, false, false, false, false, <?php echo $this->_tpl_vars['cid']; ?>
);
      <?php else: ?>
        CRM.buildCustomData('<?php echo $this->_tpl_vars['customDataType']; ?>
', false, false, false, false, false, false, <?php echo $this->_tpl_vars['cid']; ?>
);
      <?php endif; ?>
      <?php echo '
    });
  </script>
  '; ?>

<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>