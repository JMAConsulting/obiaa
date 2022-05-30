<?php /* Smarty version 2.6.31, created on 2022-05-30 04:24:09
         compiled from CRM/common/enableDisableApi.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/enableDisableApi.tpl', 1, false),array('block', 'ts', 'CRM/common/enableDisableApi.tpl', 18, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo '
<script type="text/javascript">
  CRM.$(function($) {
    var $a, $row, info, enabled, fieldLabel;

    function successMsg() {
      '; ?>
       var msg = enabled ? '<?php $this->_tag_stack[] = array('ts', array('escape' => 'js','1' => "%1")); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>%1 Disabled<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>' : '<?php $this->_tag_stack[] = array('ts', array('escape' => 'js','1' => "%1")); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>%1 Enabled<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>'<?php echo ';
      return ts(msg, {1: fieldLabel});
    }

    function refresh() {
      // the opposite of the current status based on row class
      var newStatus = $row.hasClass(\'disabled\');
      $a.trigger(\'crmPopupFormSuccess\', {
        \'entity\': info.entity,
        \'id\': info.id,
        \'enabled\': newStatus
      });
      CRM.refreshParent($row);
    }

    function save() {
      $row.closest(\'table\').block();
      var params = {id: info.id};
      if (info.action == \'setvalue\') {
        params.field = \'is_active\';
        params.value = enabled ? 0 : 1;
      } else {
        params.is_active = enabled ? 0 : 1;
      }
      CRM.api3(info.entity, info.action, params, {success: successMsg}).done(refresh);
    }

    function checkResponse(e, response) {
      if (response.illegal) {
        $(this).dialog(\'option\', \'buttons\', [
          {text: '; ?>
'<?php $this->_tag_stack[] = array('ts', array('escape' => 'js')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Close<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>'<?php echo ', click: function() {$(this).dialog(\'close\');}, icons: {primary: \'fa-times\'}}
        ]);
      }
    }

    function enableDisable() {
      $row = $a.closest(\'.crm-entity\');
      info = $a.crmEditableEntity();
      fieldLabel = info.label || info.title || info.display_name || info.name || '; ?>
'<?php $this->_tag_stack[] = array('ts', array('escape' => 'js')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Record<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>'<?php echo ';
      enabled = !$row.hasClass(\'disabled\');
      if (enabled) {
        CRM.confirm({
          url: CRM.url(\'civicrm/ajax/statusmsg\', {entity: info.entity, id: info.id}),
          title: ts(\''; ?>
<?php $this->_tag_stack[] = array('ts', array('escape' => 'js','1' => '%1')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Disable %1<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '\', {1: fieldLabel}),
          options: {'; ?>
yes: '<?php $this->_tag_stack[] = array('ts', array('escape' => 'js')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Yes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>', no: '<?php $this->_tag_stack[] = array('ts', array('escape' => 'js')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>'<?php echo '},
          width: 300,
          height: \'auto\'
        })
          .on(\'crmLoad\', checkResponse)
          .on(\'crmConfirm:yes\', save);
      } else {
        save();
      }
    }

    // Because this is an inline script it may get added to the document more than once, so remove handler before adding
    $(\'body\')
      .off(\'.crmEnableDisable\')
      .on(\'click.crmEnableDisable\', \'.action-item.crm-enable-disable\', function(e) {
        e.preventDefault();
        $a = $(this);
        CRM.loadScript(CRM.config.resourceBase + \'js/jquery/jquery.crmEditable.js\').done(enableDisable);
      });
  });
</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>