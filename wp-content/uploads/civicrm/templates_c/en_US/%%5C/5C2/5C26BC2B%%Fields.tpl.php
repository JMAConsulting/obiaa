<?php /* Smarty version 2.6.31, created on 2022-05-30 04:28:44
         compiled from CRM/UF/Form/Fields.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/UF/Form/Fields.tpl', 1, false),array('modifier', 'substr', 'CRM/UF/Form/Fields.tpl', 87, false),array('modifier', 'crmDate', 'CRM/UF/Form/Fields.tpl', 98, false),array('modifier', 'replace', 'CRM/UF/Form/Fields.tpl', 114, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldName'] => $this->_tpl_vars['field']):
?>
  <?php if ($this->_tpl_vars['field']['skipDisplay']): ?>
    <?php continue; ?>
  <?php endif; ?>

  <?php $this->assign('profileFieldName', $this->_tpl_vars['field']['name']); ?>
  <?php if ($this->_tpl_vars['prefix']): ?>
    <?php $this->assign('formElement', $this->_tpl_vars['form'][$this->_tpl_vars['prefix']][$this->_tpl_vars['profileFieldName']]); ?>
    <?php $this->assign('rowIdentifier', $this->_tpl_vars['form'][$this->_tpl_vars['prefix']][$this->_tpl_vars['profileFieldName']]['id']); ?>
  <?php else: ?>
    <?php $this->assign('formElement', $this->_tpl_vars['form'][$this->_tpl_vars['profileFieldName']]); ?>
    <?php $this->assign('rowIdentifier', $this->_tpl_vars['field']['name']); ?>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['field']['groupTitle'] != $this->_tpl_vars['fieldset']): ?>
    <?php if ($this->_tpl_vars['fieldset'] != $this->_tpl_vars['zeroField']): ?>
      <?php if ($this->_tpl_vars['groupHelpPost'] && $this->_tpl_vars['action'] != 4): ?>
        <div class="messages help"><?php echo $this->_tpl_vars['groupHelpPost']; ?>
</div>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['mode'] != 8): ?>
        </fieldset>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['mode'] != 8 && $this->_tpl_vars['action'] != 1028 && $this->_tpl_vars['action'] != 4 && ! $this->_tpl_vars['hideFieldset']): ?>
      <fieldset class="crm-profile crm-profile-id-<?php echo $this->_tpl_vars['field']['group_id']; ?>
 crm-profile-name-<?php echo $this->_tpl_vars['field']['groupName']; ?>
"><legend><?php echo $this->_tpl_vars['field']['groupDisplayTitle']; ?>
</legend>
    <?php endif; ?>

    <?php if (( $this->_tpl_vars['form']['formName'] == 'Confirm' || $this->_tpl_vars['form']['formName'] == 'ThankYou' ) && $this->_tpl_vars['prefix'] != 'honor'): ?>
      <div class="header-dark"><?php echo $this->_tpl_vars['field']['groupDisplayTitle']; ?>
 </div>
    <?php endif; ?>
    <?php $this->assign('fieldset', ($this->_tpl_vars['field']['groupTitle'])); ?>
    <?php $this->assign('groupHelpPost', ($this->_tpl_vars['field']['groupHelpPost'])); ?>
    <?php if ($this->_tpl_vars['field']['groupHelpPre'] && $this->_tpl_vars['action'] != 4 && $this->_tpl_vars['action'] != 1028): ?>
      <div class="messages help"><?php echo $this->_tpl_vars['field']['groupHelpPre']; ?>
</div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['field']['field_type'] == 'Formatting'): ?>
    <?php if ($this->_tpl_vars['action'] != 4 && $this->_tpl_vars['action'] != 1028): ?>
      <?php echo $this->_tpl_vars['field']['help_pre']; ?>

    <?php endif; ?>
  <?php elseif ($this->_tpl_vars['profileFieldName']): ?>
        <?php if ($this->_tpl_vars['field']['help_pre'] && $this->_tpl_vars['action'] != 4 && $this->_tpl_vars['action'] != 1028): ?>
      <div class="crm-section helprow-<?php echo $this->_tpl_vars['profileFieldName']; ?>
-section helprow-pre" id="helprow-<?php echo $this->_tpl_vars['rowIdentifier']; ?>
">
        <div class="content description"><?php echo $this->_tpl_vars['field']['help_pre']; ?>
</div>
      </div>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['field']['options_per_line'] != 0): ?>
      <div class="crm-section editrow_<?php echo $this->_tpl_vars['profileFieldName']; ?>
-section form-item" id="editrow-<?php echo $this->_tpl_vars['rowIdentifier']; ?>
">
        <div class="label option-label"><?php echo $this->_tpl_vars['formElement']['label']; ?>
</div>
        <div class="content 3">

          <?php $this->assign('count', '1'); ?>
          <?php echo '<table class="form-layout-compressed"><tr>'; ?><?php echo ''; ?><?php $this->assign('index', '1'); ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['formElement']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['outer']['iteration']++;
?><?php echo ''; ?><?php if ($this->_tpl_vars['index'] < 10): ?><?php echo ''; ?><?php $this->assign('index', ($this->_tpl_vars['index']+1)); ?><?php echo ''; ?><?php else: ?><?php echo '<td class="labels font-light">'; ?><?php echo $this->_tpl_vars['formElement'][$this->_tpl_vars['key']]['html']; ?><?php echo '</td>'; ?><?php if ($this->_tpl_vars['count'] == $this->_tpl_vars['field']['options_per_line']): ?><?php echo '</tr><tr>'; ?><?php $this->assign('count', '1'); ?><?php echo ''; ?><?php else: ?><?php echo ''; ?><?php $this->assign('count', ($this->_tpl_vars['count']+1)); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo '</tr></table>'; ?>

        </div>
        <div class="clear"></div>
      </div>
    <?php else: ?>
      <div class="crm-section editrow_<?php echo $this->_tpl_vars['profileFieldName']; ?>
-section form-item" id="editrow-<?php echo $this->_tpl_vars['rowIdentifier']; ?>
">
        <div class="label">
          <?php echo $this->_tpl_vars['formElement']['label']; ?>

        </div>
        <div class="content">
          <?php if (((is_array($_tmp=$this->_tpl_vars['profileFieldName'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) == 'im-'): ?>
            <?php $this->assign('provider', "profileFieldNamen|cat:\"-provider_id\""); ?>
            <?php echo $this->_tpl_vars['form'][$this->_tpl_vars['provider']]['html']; ?>
&nbsp;
          <?php endif; ?>

          <?php if ($this->_tpl_vars['profileFieldName'] == 'email_greeting' || $this->_tpl_vars['profileFieldName'] == 'postal_greeting' || $this->_tpl_vars['profileFieldName'] == 'addressee'): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Profile/Form/GreetingType.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          <?php elseif (( $this->_tpl_vars['profileFieldName'] == 'group' && $this->_tpl_vars['form']['group'] ) || ( $this->_tpl_vars['profileFieldName'] == 'tag' && $this->_tpl_vars['form']['tag'] )): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Contact/Form/Edit/TagsAndGroups.tpl", 'smarty_include_vars' => array('type' => $this->_tpl_vars['profileFieldName'],'title' => null,'context' => 'profile')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          <?php elseif ($this->_tpl_vars['field']['is_datetime_field'] && $this->_tpl_vars['action'] & 4): ?>
            <span class="crm-frozen-field">
              <?php echo ((is_array($_tmp=$this->_tpl_vars['formElement']['value'])) ? $this->_run_mod_handler('crmDate', true, $_tmp, $this->_tpl_vars['field']['smarty_view_format']) : smarty_modifier_crmDate($_tmp, $this->_tpl_vars['field']['smarty_view_format'])); ?>

              <input type="hidden"
               name="<?php echo $this->_tpl_vars['formElement']['name']; ?>
"
               value="<?php echo $this->_tpl_vars['formElement']['value']; ?>
" id="<?php echo $this->_tpl_vars['formElement']['name']; ?>
"
              >
            </span>
          <?php elseif (( $this->_tpl_vars['profileFieldName'] == 'image_URL' )): ?>
            <?php echo $this->_tpl_vars['formElement']['html']; ?>

            <?php if (! empty ( $this->_tpl_vars['imageURL'] )): ?>
              <div class="crm-section contact_image-section">
                <div class="content">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Contact/Page/ContactImage.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
              </div>
            <?php endif; ?>
          <?php elseif (((is_array($_tmp=$this->_tpl_vars['profileFieldName'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 5) : substr($_tmp, 0, 5)) == 'phone'): ?>
            <?php $this->assign('phone_ext_field', ((is_array($_tmp=$this->_tpl_vars['profileFieldName'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'phone', 'phone_ext') : smarty_modifier_replace($_tmp, 'phone', 'phone_ext'))); ?>
            <?php echo $this->_tpl_vars['formElement']['html']; ?>

            <?php if ($this->_tpl_vars['form'][$this->_tpl_vars['phone_ext_field']]['html']): ?>
              &nbsp;<?php echo $this->_tpl_vars['form'][$this->_tpl_vars['phone_ext_field']]['html']; ?>

            <?php endif; ?>
          <?php else: ?>
            <?php if ($this->_tpl_vars['prefix']): ?>
              <?php if ($this->_tpl_vars['profileFieldName'] == 'organization_name' && ! empty ( $this->_tpl_vars['form']['onbehalfof_id'] )): ?>
                <?php echo $this->_tpl_vars['form']['onbehalfof_id']['html']; ?>

              <?php endif; ?>
              <?php if ($this->_tpl_vars['field']['html_type'] == 'File' && $this->_tpl_vars['viewOnlyPrefixFileValues']): ?>
                <?php echo $this->_tpl_vars['viewOnlyPrefixFileValues'][$this->_tpl_vars['prefix']][$this->_tpl_vars['profileFieldName']]; ?>

              <?php else: ?>
                <?php echo $this->_tpl_vars['formElement']['html']; ?>

              <?php endif; ?>
            <?php elseif ($this->_tpl_vars['field']['html_type'] == 'File' && $this->_tpl_vars['viewOnlyFileValues']): ?>
              <?php echo $this->_tpl_vars['viewOnlyFileValues'][$this->_tpl_vars['profileFieldName']]; ?>

            <?php else: ?>
              <?php echo $this->_tpl_vars['formElement']['html']; ?>

            <?php endif; ?>
          <?php endif; ?>

                    <?php if ($this->_tpl_vars['field']['html_type'] == 'Autocomplete-Select'): ?>
            <?php if ($this->_tpl_vars['field']['data_type'] == 'ContactReference'): ?>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Custom/Form/ContactReference.tpl", 'smarty_include_vars' => array('element_name' => $this->_tpl_vars['profileFieldName'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <div class="clear"></div>
      </div>
    <?php endif; ?>
        <?php if ($this->_tpl_vars['field']['help_post'] && $this->_tpl_vars['action'] != 4 && $this->_tpl_vars['action'] != 1028): ?>
      <div class="crm-section helprow-<?php echo $this->_tpl_vars['profileFieldName']; ?>
-section helprow-post" id="helprow-<?php echo $this->_tpl_vars['rowIdentifier']; ?>
">
        <div class="content description"><?php echo $this->_tpl_vars['field']['help_post']; ?>
</div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>