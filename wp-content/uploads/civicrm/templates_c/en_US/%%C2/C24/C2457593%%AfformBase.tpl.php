<?php /* Smarty version 2.6.31, created on 2022-05-30 04:23:48
         compiled from CRM/Afform/Page/AfformBase.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Afform/Page/AfformBase.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><crm-angular-js modules="afformStandalone">
  <form id="bootstrap-theme" ng-controller="AfformStandalonePageCtrl">
    <?php echo '
      <h1 style="display: none" crm-page-title ng-if="afformTitle">{{ afformTitle }}</h1>
    '; ?>

    <<?php echo $this->_tpl_vars['directive']; ?>
></<?php echo $this->_tpl_vars['directive']; ?>
>
  </form>
</crm-angular-js>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>