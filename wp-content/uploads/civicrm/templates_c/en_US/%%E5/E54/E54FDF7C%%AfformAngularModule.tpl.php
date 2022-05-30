<?php /* Smarty version 2.6.31, created on 2022-05-30 08:23:48
         compiled from afform/AfformAngularModule.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'afform/AfformAngularModule.tpl', 1, false),array('modifier', 'json', 'afform/AfformAngularModule.tpl', 11, false),array('modifier', 'json_encode', 'afform/AfformAngularModule.tpl', 11, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo '
(function(angular, $, _) {
  angular.module(\''; ?>
<?php echo $this->_tpl_vars['afform']['camel']; ?>
<?php echo '\', CRM.angRequires(\''; ?>
<?php echo $this->_tpl_vars['afform']['camel']; ?>
<?php echo '\'));
  angular.module(\''; ?>
<?php echo $this->_tpl_vars['afform']['camel']; ?>
<?php echo '\').directive(\''; ?>
<?php echo $this->_tpl_vars['afform']['camel']; ?>
<?php echo '\', function(afCoreDirective) {
    return afCoreDirective('; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['afform']['camel'])) ? $this->_run_mod_handler('json', true, $_tmp) : smarty_modifier_json($_tmp)); ?>
, <?php echo json_encode($this->_tpl_vars['afform']['meta']); ?>
<?php echo ', {
      templateUrl: '; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['afform']['templateUrl'])) ? $this->_run_mod_handler('json', true, $_tmp) : smarty_modifier_json($_tmp)); ?>
<?php echo '
    });
  });
})(angular, CRM.$, CRM._);
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>