<?php /* Smarty version 2.6.31, created on 2022-05-30 04:31:34
         compiled from CRM/Activity/Form/FollowUp.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Activity/Form/FollowUp.tpl', 1, false),array('block', 'ts', 'CRM/Activity/Form/FollowUp.tpl', 3, false),array('block', 'edit', 'CRM/Activity/Form/FollowUp.tpl', 21, false),array('modifier', 'crmAddClass', 'CRM/Activity/Form/FollowUp.tpl', 16, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div id="follow-up" class="crm-accordion-wrapper collapsed">
  <div class="crm-accordion-header">
     <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Schedule Follow-up<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  </div><!-- /.crm-accordion-header -->
  <div class="crm-accordion-body">
    <table class="form-layout-compressed">
      <tr class="crm-<?php echo $this->_tpl_vars['type']; ?>
activity-form-block-followup_activity_type_id">
	<td class="label"><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Schedule Follow-up Activity<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
        <td>
          <?php echo $this->_tpl_vars['form']['followup_activity_type_id']['html']; ?>
&nbsp;&nbsp;
          <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>on<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo $this->_tpl_vars['form']['followup_date']['html']; ?>

        </td>
      </tr>
      <tr class="crm-<?php echo $this->_tpl_vars['type']; ?>
activity-form-block-followup_activity_subject">
        <td class="label"><?php echo $this->_tpl_vars['form']['followup_activity_subject']['label']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['form']['followup_activity_subject']['html'])) ? $this->_run_mod_handler('crmAddClass', true, $_tmp, 'huge') : smarty_modifier_crmAddClass($_tmp, 'huge')); ?>
</td>
      </tr>
      <tr class="crm-<?php echo $this->_tpl_vars['type']; ?>
activity-form-block-followup_assignee_contact_id">
        <td class="label">
        <?php echo $this->_tpl_vars['form']['followup_assignee_contact_id']['label']; ?>

        <?php $this->_tag_stack[] = array('edit', array()); $_block_repeat=true;smarty_block_edit($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_edit($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td>
          <?php echo $this->_tpl_vars['form']['followup_assignee_contact_id']['html']; ?>

        </td>
      </tr>
    </table>
  </div><!-- /.crm-accordion-body -->
</div><!-- /.crm-accordion-wrapper -->
     
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>