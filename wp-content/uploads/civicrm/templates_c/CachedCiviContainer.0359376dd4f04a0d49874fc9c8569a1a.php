<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class CachedCiviContainer extends Container
{
    private $parameters = [];
    private $targetDirs = [];

    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();

        $this->services = [];
        $this->normalizedIds = [
            'bundle.coreresources' => 'bundle.coreResources',
            'bundle.corestyles' => 'bundle.coreStyles',
            'cache.contacttypes' => 'cache.contactTypes',
            'cache.customdata' => 'cache.customData',
            'cache.prevnextcache' => 'cache.prevNextCache',
            'civi.case.statictriggers' => 'civi.case.staticTriggers',
            'civi_api4_event_subscriber_createapi4requestsubscriber' => 'Civi_Api4_Event_Subscriber_CreateApi4RequestSubscriber',
            'civi_api4_event_subscriber_iscurrentsubscriber' => 'Civi_Api4_Event_Subscriber_IsCurrentSubscriber',
            'civi_api4_event_subscriber_permissionchecksubscriber' => 'Civi_Api4_Event_Subscriber_PermissionCheckSubscriber',
            'civi_api4_event_subscriber_validatefieldssubscriber' => 'Civi_Api4_Event_Subscriber_ValidateFieldsSubscriber',
            'civi_api4_service_spec_provider_aclcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider',
            'civi_api4_service_spec_provider_aclentityrolecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider',
            'civi_api4_service_spec_provider_actionschedulecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider',
            'civi_api4_service_spec_provider_activityspecprovider' => 'Civi_Api4_Service_Spec_Provider_ActivitySpecProvider',
            'civi_api4_service_spec_provider_addresscreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider',
            'civi_api4_service_spec_provider_batchcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider',
            'civi_api4_service_spec_provider_campaigncreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider',
            'civi_api4_service_spec_provider_casecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider',
            'civi_api4_service_spec_provider_casetypegetspecprovider' => 'Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider',
            'civi_api4_service_spec_provider_contactcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider',
            'civi_api4_service_spec_provider_contactgetspecprovider' => 'Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider',
            'civi_api4_service_spec_provider_contacttypecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider',
            'civi_api4_service_spec_provider_contributioncreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider',
            'civi_api4_service_spec_provider_contributionrecurcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider',
            'civi_api4_service_spec_provider_customfieldcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider',
            'civi_api4_service_spec_provider_customgroupcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider',
            'civi_api4_service_spec_provider_customvaluespecprovider' => 'Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider',
            'civi_api4_service_spec_provider_defaultlocationtypeprovider' => 'Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider',
            'civi_api4_service_spec_provider_domaincreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider',
            'civi_api4_service_spec_provider_emailcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider',
            'civi_api4_service_spec_provider_entitybatchcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider',
            'civi_api4_service_spec_provider_entitytagcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider',
            'civi_api4_service_spec_provider_entitytagfilterspecprovider' => 'Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider',
            'civi_api4_service_spec_provider_eventcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider',
            'civi_api4_service_spec_provider_fieldcurrencyspecprovider' => 'Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider',
            'civi_api4_service_spec_provider_fielddomainidspecprovider' => 'Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider',
            'civi_api4_service_spec_provider_financialitemcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider',
            'civi_api4_service_spec_provider_getactiondefaultsprovider' => 'Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider',
            'civi_api4_service_spec_provider_groupcontactcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider',
            'civi_api4_service_spec_provider_groupcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider',
            'civi_api4_service_spec_provider_iscurrentfieldspecprovider' => 'Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider',
            'civi_api4_service_spec_provider_managedentityspecprovider' => 'Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider',
            'civi_api4_service_spec_provider_mappingcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider',
            'civi_api4_service_spec_provider_membershipcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider',
            'civi_api4_service_spec_provider_navigationspecprovider' => 'Civi_Api4_Service_Spec_Provider_NavigationSpecProvider',
            'civi_api4_service_spec_provider_notecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider',
            'civi_api4_service_spec_provider_optionvaluecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider',
            'civi_api4_service_spec_provider_paymentprocessorcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider',
            'civi_api4_service_spec_provider_paymentprocessortypecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider',
            'civi_api4_service_spec_provider_phonecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider',
            'civi_api4_service_spec_provider_pricefieldvaluecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider',
            'civi_api4_service_spec_provider_relationshipcachespecprovider' => 'Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider',
            'civi_api4_service_spec_provider_relationshiptypecreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider',
            'civi_api4_service_spec_provider_searchdisplaycreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider',
            'civi_api4_service_spec_provider_tagcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider',
            'civi_api4_service_spec_provider_uffieldcreationspecprovider' => 'Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider',
            'httpclient' => 'httpClient',
            'lockmanager' => 'lockManager',
            'userpermissionclass' => 'userPermissionClass',
            'usersystem' => 'userSystem',
        ];
        $this->syntheticIds = [
            'cache.settings' => true,
            'dispatcher.boot' => true,
            'lockManager' => true,
            'paths' => true,
            'runtime' => true,
            'settings_manager' => true,
            'userPermissionClass' => true,
            'userSystem' => true,
        ];
        $this->methodMap = [
            'Civi_Api4_Event_Subscriber_CreateApi4RequestSubscriber' => 'getCiviApi4EventSubscriberCreateApi4RequestSubscriberService',
            'Civi_Api4_Event_Subscriber_IsCurrentSubscriber' => 'getCiviApi4EventSubscriberIsCurrentSubscriberService',
            'Civi_Api4_Event_Subscriber_PermissionCheckSubscriber' => 'getCiviApi4EventSubscriberPermissionCheckSubscriberService',
            'Civi_Api4_Event_Subscriber_ValidateFieldsSubscriber' => 'getCiviApi4EventSubscriberValidateFieldsSubscriberService',
            'Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderACLCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderACLEntityRoleCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderActionScheduleCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ActivitySpecProvider' => 'getCiviApi4ServiceSpecProviderActivitySpecProviderService',
            'Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderAddressCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderBatchCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderCampaignCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderCaseCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider' => 'getCiviApi4ServiceSpecProviderCaseTypeGetSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderContactCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider' => 'getCiviApi4ServiceSpecProviderContactGetSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderContactTypeCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderContributionCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderContributionRecurCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderCustomFieldCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderCustomGroupCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider' => 'getCiviApi4ServiceSpecProviderCustomValueSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider' => 'getCiviApi4ServiceSpecProviderDefaultLocationTypeProviderService',
            'Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderDomainCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderEmailCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderEntityBatchCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderEntityTagCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider' => 'getCiviApi4ServiceSpecProviderEntityTagFilterSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderEventCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider' => 'getCiviApi4ServiceSpecProviderFieldCurrencySpecProviderService',
            'Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider' => 'getCiviApi4ServiceSpecProviderFieldDomainIdSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderFinancialItemCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider' => 'getCiviApi4ServiceSpecProviderGetActionDefaultsProviderService',
            'Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderGroupContactCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderGroupCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider' => 'getCiviApi4ServiceSpecProviderIsCurrentFieldSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider' => 'getCiviApi4ServiceSpecProviderManagedEntitySpecProviderService',
            'Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderMappingCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderMembershipCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_NavigationSpecProvider' => 'getCiviApi4ServiceSpecProviderNavigationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderNoteCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderOptionValueCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderPaymentProcessorCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderPaymentProcessorTypeCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderPhoneCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderPriceFieldValueCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider' => 'getCiviApi4ServiceSpecProviderRelationshipCacheSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderRelationshipTypeCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderSearchDisplayCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderTagCreationSpecProviderService',
            'Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider' => 'getCiviApi4ServiceSpecProviderUFFieldCreationSpecProviderService',
            'action_object_provider' => 'getActionObjectProviderService',
            'afform_scanner' => 'getAfformScannerService',
            'angular' => 'getAngularService',
            'angularjs.loader' => 'getAngularjs_LoaderService',
            'asset_builder' => 'getAssetBuilderService',
            'bundle.bootstrap3' => 'getBundle_Bootstrap3Service',
            'bundle.coreResources' => 'getBundle_CoreResourcesService',
            'bundle.coreStyles' => 'getBundle_CoreStylesService',
            'cache.checks' => 'getCache_ChecksService',
            'cache.community_messages' => 'getCache_CommunityMessagesService',
            'cache.contactTypes' => 'getCache_ContactTypesService',
            'cache.customData' => 'getCache_CustomDataService',
            'cache.default' => 'getCache_DefaultService',
            'cache.fields' => 'getCache_FieldsService',
            'cache.groups' => 'getCache_GroupsService',
            'cache.js_strings' => 'getCache_JsStringsService',
            'cache.long' => 'getCache_LongService',
            'cache.metadata' => 'getCache_MetadataService',
            'cache.navigation' => 'getCache_NavigationService',
            'cache.prevNextCache' => 'getCache_PrevNextCacheService',
            'cache.session' => 'getCache_SessionService',
            'cache_config' => 'getCacheConfigService',
            'civi.activity.triggers' => 'getCivi_Activity_TriggersService',
            'civi.case.staticTriggers' => 'getCivi_Case_StaticTriggersService',
            'civi.case.triggers' => 'getCivi_Case_TriggersService',
            'civi.pipe' => 'getCivi_PipeService',
            'civi_api_kernel' => 'getCiviApiKernelService',
            'civi_container_factory' => 'getCiviContainerFactoryService',
            'civi_flexmailer_abdicator' => 'getCiviFlexmailerAbdicatorService',
            'civi_flexmailer_api_overrides' => 'getCiviFlexmailerApiOverridesService',
            'civi_flexmailer_attachments' => 'getCiviFlexmailerAttachmentsService',
            'civi_flexmailer_basic_headers' => 'getCiviFlexmailerBasicHeadersService',
            'civi_flexmailer_bounce_tracker' => 'getCiviFlexmailerBounceTrackerService',
            'civi_flexmailer_default_batcher' => 'getCiviFlexmailerDefaultBatcherService',
            'civi_flexmailer_default_composer' => 'getCiviFlexmailerDefaultComposerService',
            'civi_flexmailer_default_sender' => 'getCiviFlexmailerDefaultSenderService',
            'civi_flexmailer_hooks' => 'getCiviFlexmailerHooksService',
            'civi_flexmailer_html_click_tracker' => 'getCiviFlexmailerHtmlClickTrackerService',
            'civi_flexmailer_open_tracker' => 'getCiviFlexmailerOpenTrackerService',
            'civi_flexmailer_required_fields' => 'getCiviFlexmailerRequiredFieldsService',
            'civi_flexmailer_required_tokens' => 'getCiviFlexmailerRequiredTokensService',
            'civi_flexmailer_test_prefix' => 'getCiviFlexmailerTestPrefixService',
            'civi_flexmailer_text_click_tracker' => 'getCiviFlexmailerTextClickTrackerService',
            'civi_flexmailer_to_header' => 'getCiviFlexmailerToHeaderService',
            'civi_token_compat' => 'getCiviTokenCompatService',
            'civi_token_impliedcontext' => 'getCiviTokenImpliedcontextService',
            'crm_activity_tokens' => 'getCrmActivityTokensService',
            'crm_case_tokens' => 'getCrmCaseTokensService',
            'crm_contact_tokens' => 'getCrmContactTokensService',
            'crm_contribute_tokens' => 'getCrmContributeTokensService',
            'crm_contribution_recur_tokens' => 'getCrmContributionRecurTokensService',
            'crm_domain_tokens' => 'getCrmDomainTokensService',
            'crm_event_tokens' => 'getCrmEventTokensService',
            'crm_mailing_action_tokens' => 'getCrmMailingActionTokensService',
            'crm_mailing_tokens' => 'getCrmMailingTokensService',
            'crm_member_tokens' => 'getCrmMemberTokensService',
            'crm_participant_tokens' => 'getCrmParticipantTokensService',
            'crypto.jwt' => 'getCrypto_JwtService',
            'crypto.registry' => 'getCrypto_RegistryService',
            'crypto.token' => 'getCrypto_TokenService',
            'cxn_reg_client' => 'getCxnRegClientService',
            'dispatcher' => 'getDispatcherService',
            'format' => 'getFormatService',
            'httpClient' => 'getHttpClientService',
            'i18n' => 'getI18nService',
            'magic_function_provider' => 'getMagicFunctionProviderService',
            'mosaico_ab_demux' => 'getMosaicoAbDemuxService',
            'mosaico_flexmail_composer' => 'getMosaicoFlexmailComposerService',
            'mosaico_flexmail_url_filter' => 'getMosaicoFlexmailUrlFilterService',
            'mosaico_graphics' => 'getMosaicoGraphicsService',
            'mosaico_image_filter' => 'getMosaicoImageFilterService',
            'mosaico_required_tokens' => 'getMosaicoRequiredTokensService',
            'pear_mail' => 'getPearMailService',
            'prevnext' => 'getPrevnextService',
            'prevnext.driver.redis' => 'getPrevnext_Driver_RedisService',
            'prevnext.driver.sql' => 'getPrevnext_Driver_SqlService',
            'psr_log' => 'getPsrLogService',
            'psr_log_manager' => 'getPsrLogManagerService',
            'resources' => 'getResourcesService',
            'resources.js_strings' => 'getResources_JsStringsService',
            'schema_map_builder' => 'getSchemaMapBuilderService',
            'spec_gatherer' => 'getSpecGathererService',
            'sql_triggers' => 'getSqlTriggersService',
            'themes' => 'getThemesService',
        ];
        $this->privates = [
            'civi_container_factory' => true,
        ];
        $this->aliases = [
            'cache.short' => 'cache.default',
        ];
    }

    public function getRemovedIds()
    {
        return [
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
            'civi_container_factory' => true,
        ];
    }

    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled()
    {
        return true;
    }

    public function isFrozen()
    {
        @trigger_error(sprintf('The %s() method is deprecated since Symfony 3.3 and will be removed in 4.0. Use the isCompiled() method instead.', __METHOD__), E_USER_DEPRECATED);

        return true;
    }

    /**
     * Gets the public 'Civi_Api4_Event_Subscriber_CreateApi4RequestSubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\CreateApi4RequestSubscriber
     */
    protected function getCiviApi4EventSubscriberCreateApi4RequestSubscriberService()
    {
        return $this->services['Civi_Api4_Event_Subscriber_CreateApi4RequestSubscriber'] = new \Civi\Api4\Event\Subscriber\CreateApi4RequestSubscriber();
    }

    /**
     * Gets the public 'Civi_Api4_Event_Subscriber_IsCurrentSubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\IsCurrentSubscriber
     */
    protected function getCiviApi4EventSubscriberIsCurrentSubscriberService()
    {
        return $this->services['Civi_Api4_Event_Subscriber_IsCurrentSubscriber'] = new \Civi\Api4\Event\Subscriber\IsCurrentSubscriber();
    }

    /**
     * Gets the public 'Civi_Api4_Event_Subscriber_PermissionCheckSubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\PermissionCheckSubscriber
     */
    protected function getCiviApi4EventSubscriberPermissionCheckSubscriberService()
    {
        return $this->services['Civi_Api4_Event_Subscriber_PermissionCheckSubscriber'] = new \Civi\Api4\Event\Subscriber\PermissionCheckSubscriber();
    }

    /**
     * Gets the public 'Civi_Api4_Event_Subscriber_ValidateFieldsSubscriber' shared service.
     *
     * @return \Civi\Api4\Event\Subscriber\ValidateFieldsSubscriber
     */
    protected function getCiviApi4EventSubscriberValidateFieldsSubscriberService()
    {
        return $this->services['Civi_Api4_Event_Subscriber_ValidateFieldsSubscriber'] = new \Civi\Api4\Event\Subscriber\ValidateFieldsSubscriber();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ACLCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderACLCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ACLCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ACLEntityRoleCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderACLEntityRoleCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ACLEntityRoleCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ActionScheduleCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderActionScheduleCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ActionScheduleCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ActivitySpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ActivitySpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderActivitySpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ActivitySpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ActivitySpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\AddressCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderAddressCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\AddressCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\BatchCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderBatchCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\BatchCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CampaignCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCampaignCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CampaignCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CaseCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCaseCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CaseCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CaseTypeGetSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCaseTypeGetSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CaseTypeGetSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContactCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContactCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContactCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContactGetSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContactGetSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContactGetSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContactTypeCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContactTypeCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContactTypeCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContributionCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContributionCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContributionCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ContributionRecurCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderContributionRecurCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContributionRecurCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CustomFieldCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCustomFieldCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CustomFieldCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CustomGroupCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCustomGroupCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CustomGroupCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\CustomValueSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderCustomValueSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CustomValueSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\DefaultLocationTypeProvider
     */
    protected function getCiviApi4ServiceSpecProviderDefaultLocationTypeProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider'] = new \Civi\Api4\Service\Spec\Provider\DefaultLocationTypeProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\DomainCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderDomainCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\DomainCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EmailCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEmailCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EmailCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EntityBatchCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEntityBatchCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EntityBatchCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EntityTagCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEntityTagCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EntityTagCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EntityTagFilterSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEntityTagFilterSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EntityTagFilterSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\EventCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderEventCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EventCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\FieldCurrencySpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderFieldCurrencySpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider'] = new \Civi\Api4\Service\Spec\Provider\FieldCurrencySpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\FieldDomainIdSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderFieldDomainIdSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\FieldDomainIdSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\FinancialItemCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderFinancialItemCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\FinancialItemCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\GetActionDefaultsProvider
     */
    protected function getCiviApi4ServiceSpecProviderGetActionDefaultsProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider'] = new \Civi\Api4\Service\Spec\Provider\GetActionDefaultsProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\GroupContactCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderGroupContactCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\GroupContactCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\GroupCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderGroupCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\GroupCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\IsCurrentFieldSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderIsCurrentFieldSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\IsCurrentFieldSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\ManagedEntitySpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderManagedEntitySpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ManagedEntitySpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\MappingCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderMappingCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\MappingCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\MembershipCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderMembershipCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\MembershipCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_NavigationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\NavigationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderNavigationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_NavigationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\NavigationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\NoteCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderNoteCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\NoteCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\OptionValueCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderOptionValueCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\OptionValueCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PaymentProcessorCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPaymentProcessorCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PaymentProcessorCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PaymentProcessorTypeCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPaymentProcessorTypeCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PaymentProcessorTypeCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PhoneCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPhoneCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PhoneCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\PriceFieldValueCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderPriceFieldValueCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PriceFieldValueCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\RelationshipCacheSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderRelationshipCacheSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\RelationshipCacheSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\RelationshipTypeCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderRelationshipTypeCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\RelationshipTypeCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\SearchDisplayCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderSearchDisplayCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\SearchDisplayCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\TagCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderTagCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\TagCreationSpecProvider();
    }

    /**
     * Gets the public 'Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider' shared service.
     *
     * @return \Civi\Api4\Service\Spec\Provider\UFFieldCreationSpecProvider
     */
    protected function getCiviApi4ServiceSpecProviderUFFieldCreationSpecProviderService()
    {
        return $this->services['Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\UFFieldCreationSpecProvider();
    }

    /**
     * Gets the public 'action_object_provider' shared service.
     *
     * @return \Civi\Api4\Provider\ActionObjectProvider
     */
    protected function getActionObjectProviderService()
    {
        return $this->services['action_object_provider'] = new \Civi\Api4\Provider\ActionObjectProvider();
    }

    /**
     * Gets the public 'afform_scanner' shared service.
     *
     * @return \CRM_Afform_AfformScanner
     */
    protected function getAfformScannerService()
    {
        return $this->services['afform_scanner'] = new \CRM_Afform_AfformScanner();
    }

    /**
     * Gets the public 'angular' shared service.
     *
     * @return \Civi\Angular\Manager
     */
    protected function getAngularService()
    {
        return $this->services['angular'] = ${($_ = isset($this->services['civi_container_factory']) ? $this->services['civi_container_factory'] : ($this->services['civi_container_factory'] = new \Civi\Core\Container())) && false ?: '_'}->createAngularManager();
    }

    /**
     * Gets the public 'angularjs.loader' shared service.
     *
     * @return \Civi\Angular\AngularLoader
     */
    protected function getAngularjs_LoaderService()
    {
        return $this->services['angularjs.loader'] = new \Civi\Angular\AngularLoader();
    }

    /**
     * Gets the public 'asset_builder' shared service.
     *
     * @return \Civi\Core\AssetBuilder
     */
    protected function getAssetBuilderService()
    {
        return $this->services['asset_builder'] = new \Civi\Core\AssetBuilder();
    }

    /**
     * Gets the public 'bundle.bootstrap3' shared service.
     *
     * @return \CRM_Core_Resources_Bundle
     */
    protected function getBundle_Bootstrap3Service()
    {
        return $this->services['bundle.bootstrap3'] = \CRM_Core_Resources_Common::createBootstrap3Bundle('bootstrap3');
    }

    /**
     * Gets the public 'bundle.coreResources' shared service.
     *
     * @return \CRM_Core_Resources_Bundle
     */
    protected function getBundle_CoreResourcesService()
    {
        return $this->services['bundle.coreResources'] = \CRM_Core_Resources_Common::createFullBundle('coreResources');
    }

    /**
     * Gets the public 'bundle.coreStyles' shared service.
     *
     * @return \CRM_Core_Resources_Bundle
     */
    protected function getBundle_CoreStylesService()
    {
        return $this->services['bundle.coreStyles'] = \CRM_Core_Resources_Common::createStyleBundle('coreStyles');
    }

    /**
     * Gets the public 'cache.checks' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_ChecksService()
    {
        return $this->services['cache.checks'] = \CRM_Utils_Cache::create(['name' => 'checks', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache']]);
    }

    /**
     * Gets the public 'cache.community_messages' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_CommunityMessagesService()
    {
        return $this->services['cache.community_messages'] = \CRM_Utils_Cache::create(['name' => 'community_messages', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache']]);
    }

    /**
     * Gets the public 'cache.contactTypes' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_ContactTypesService()
    {
        return $this->services['cache.contactTypes'] = \CRM_Utils_Cache::create(['name' => 'contactTypes', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'], 'withArray' => 'fast']);
    }

    /**
     * Gets the public 'cache.customData' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_CustomDataService()
    {
        return $this->services['cache.customData'] = \CRM_Utils_Cache::create(['name' => 'custom data', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'], 'withArray' => 'fast']);
    }

    /**
     * Gets the public 'cache.default' shared service.
     *
     * @return \CRM_Utils_Cache
     */
    protected function getCache_DefaultService()
    {
        return $this->services['cache.default'] = \CRM_Utils_Cache::singleton();
    }

    /**
     * Gets the public 'cache.fields' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_FieldsService()
    {
        return $this->services['cache.fields'] = \CRM_Utils_Cache::create(['name' => 'contact fields', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'], 'withArray' => 'fast']);
    }

    /**
     * Gets the public 'cache.groups' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_GroupsService()
    {
        return $this->services['cache.groups'] = \CRM_Utils_Cache::create(['name' => 'contact groups', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'], 'withArray' => 'fast']);
    }

    /**
     * Gets the public 'cache.js_strings' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_JsStringsService()
    {
        return $this->services['cache.js_strings'] = \CRM_Utils_Cache::create(['name' => 'js_strings', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache']]);
    }

    /**
     * Gets the public 'cache.long' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_LongService()
    {
        return $this->services['cache.long'] = \CRM_Utils_Cache::create(['name' => 'long', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache']]);
    }

    /**
     * Gets the public 'cache.metadata' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_MetadataService()
    {
        return $this->services['cache.metadata'] = \CRM_Utils_Cache::create(['name' => 'metadata_5_49_3', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'], 'withArray' => 'fast']);
    }

    /**
     * Gets the public 'cache.navigation' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_NavigationService()
    {
        return $this->services['cache.navigation'] = \CRM_Utils_Cache::create(['name' => 'navigation', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache'], 'withArray' => 'fast']);
    }

    /**
     * Gets the public 'cache.prevNextCache' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_PrevNextCacheService()
    {
        return $this->services['cache.prevNextCache'] = \CRM_Utils_Cache::create(['name' => 'CiviCRM Search PrevNextCache', 'type' => [0 => 'SqlGroup']]);
    }

    /**
     * Gets the public 'cache.session' shared service.
     *
     * @return \CRM_Utils_Cache_Interface
     */
    protected function getCache_SessionService()
    {
        return $this->services['cache.session'] = \CRM_Utils_Cache::create(['name' => 'CiviCRM Session', 'type' => [0 => '*memory*', 1 => 'SqlGroup', 2 => 'ArrayCache']]);
    }

    /**
     * Gets the public 'cache_config' shared service.
     *
     * @return \ArrayObject
     */
    protected function getCacheConfigService()
    {
        return $this->services['cache_config'] = ${($_ = isset($this->services['civi_container_factory']) ? $this->services['civi_container_factory'] : ($this->services['civi_container_factory'] = new \Civi\Core\Container())) && false ?: '_'}->createCacheConfig();
    }

    /**
     * Gets the public 'civi.activity.triggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\TimestampTriggers
     */
    protected function getCivi_Activity_TriggersService()
    {
        return $this->services['civi.activity.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_activity', 'Activity');
    }

    /**
     * Gets the public 'civi.case.staticTriggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\StaticTriggers
     */
    protected function getCivi_Case_StaticTriggersService()
    {
        return $this->services['civi.case.staticTriggers'] = new \Civi\Core\SqlTrigger\StaticTriggers([0 => ['upgrade_check' => ['table' => 'civicrm_case', 'column' => 'modified_date'], 'table' => 'civicrm_case_activity', 'when' => 'AFTER', 'event' => [0 => 'INSERT'], 'sql' => 'UPDATE civicrm_case SET modified_date = CURRENT_TIMESTAMP WHERE id = NEW.case_id;'], 1 => ['upgrade_check' => ['table' => 'civicrm_case', 'column' => 'modified_date'], 'table' => 'civicrm_activity', 'when' => 'BEFORE', 'event' => [0 => 'UPDATE', 1 => 'DELETE'], 'sql' => 'UPDATE civicrm_case SET modified_date = CURRENT_TIMESTAMP WHERE id IN (SELECT ca.case_id FROM civicrm_case_activity ca WHERE ca.activity_id = OLD.id);']]);
    }

    /**
     * Gets the public 'civi.case.triggers' shared service.
     *
     * @return \Civi\Core\SqlTrigger\TimestampTriggers
     */
    protected function getCivi_Case_TriggersService()
    {
        return $this->services['civi.case.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_case', 'Case');
    }

    /**
     * Gets the public 'civi.pipe' service.
     *
     * @return \Civi\Pipe\PipeSession
     */
    protected function getCivi_PipeService()
    {
        return new \Civi\Pipe\PipeSession();
    }

    /**
     * Gets the public 'civi_api_kernel' shared service.
     *
     * @return \Civi\API\Kernel
     */
    protected function getCiviApiKernelService()
    {
        $this->services['civi_api_kernel'] = $instance = ${($_ = isset($this->services['civi_container_factory']) ? $this->services['civi_container_factory'] : ($this->services['civi_container_factory'] = new \Civi\Core\Container())) && false ?: '_'}->createApiKernel(${($_ = isset($this->services['dispatcher']) ? $this->services['dispatcher'] : $this->getDispatcherService()) && false ?: '_'}, ${($_ = isset($this->services['magic_function_provider']) ? $this->services['magic_function_provider'] : ($this->services['magic_function_provider'] = new \Civi\API\Provider\MagicFunctionProvider())) && false ?: '_'});

        $instance->registerApiProvider(${($_ = isset($this->services['action_object_provider']) ? $this->services['action_object_provider'] : ($this->services['action_object_provider'] = new \Civi\Api4\Provider\ActionObjectProvider())) && false ?: '_'});
        $instance->registerApiProvider(${($_ = isset($this->services['civi_flexmailer_api_overrides']) ? $this->services['civi_flexmailer_api_overrides'] : $this->getCiviFlexmailerApiOverridesService()) && false ?: '_'});

        return $instance;
    }

    /**
     * Gets the public 'civi_flexmailer_abdicator' shared service.
     *
     * @return \Civi\FlexMailer\Listener\Abdicator
     */
    protected function getCiviFlexmailerAbdicatorService()
    {
        return $this->services['civi_flexmailer_abdicator'] = new \Civi\FlexMailer\Listener\Abdicator();
    }

    /**
     * Gets the public 'civi_flexmailer_api_overrides' shared service.
     *
     * @return \Civi\API\Provider\ProviderInterface
     */
    protected function getCiviFlexmailerApiOverridesService()
    {
        return $this->services['civi_flexmailer_api_overrides'] = \Civi\FlexMailer\Services::createApiOverrides();
    }

    /**
     * Gets the public 'civi_flexmailer_attachments' shared service.
     *
     * @return \Civi\FlexMailer\Listener\Attachments
     */
    protected function getCiviFlexmailerAttachmentsService()
    {
        return $this->services['civi_flexmailer_attachments'] = new \Civi\FlexMailer\Listener\Attachments();
    }

    /**
     * Gets the public 'civi_flexmailer_basic_headers' shared service.
     *
     * @return \Civi\FlexMailer\Listener\BasicHeaders
     */
    protected function getCiviFlexmailerBasicHeadersService()
    {
        return $this->services['civi_flexmailer_basic_headers'] = new \Civi\FlexMailer\Listener\BasicHeaders();
    }

    /**
     * Gets the public 'civi_flexmailer_bounce_tracker' shared service.
     *
     * @return \Civi\FlexMailer\Listener\BounceTracker
     */
    protected function getCiviFlexmailerBounceTrackerService()
    {
        return $this->services['civi_flexmailer_bounce_tracker'] = new \Civi\FlexMailer\Listener\BounceTracker();
    }

    /**
     * Gets the public 'civi_flexmailer_default_batcher' shared service.
     *
     * @return \Civi\FlexMailer\Listener\DefaultBatcher
     */
    protected function getCiviFlexmailerDefaultBatcherService()
    {
        return $this->services['civi_flexmailer_default_batcher'] = new \Civi\FlexMailer\Listener\DefaultBatcher();
    }

    /**
     * Gets the public 'civi_flexmailer_default_composer' shared service.
     *
     * @return \Civi\FlexMailer\Listener\DefaultComposer
     */
    protected function getCiviFlexmailerDefaultComposerService()
    {
        return $this->services['civi_flexmailer_default_composer'] = new \Civi\FlexMailer\Listener\DefaultComposer();
    }

    /**
     * Gets the public 'civi_flexmailer_default_sender' shared service.
     *
     * @return \Civi\FlexMailer\Listener\DefaultSender
     */
    protected function getCiviFlexmailerDefaultSenderService()
    {
        return $this->services['civi_flexmailer_default_sender'] = new \Civi\FlexMailer\Listener\DefaultSender();
    }

    /**
     * Gets the public 'civi_flexmailer_hooks' shared service.
     *
     * @return \Civi\FlexMailer\Listener\HookAdapter
     */
    protected function getCiviFlexmailerHooksService()
    {
        return $this->services['civi_flexmailer_hooks'] = new \Civi\FlexMailer\Listener\HookAdapter();
    }

    /**
     * Gets the public 'civi_flexmailer_html_click_tracker' shared service.
     *
     * @return \Civi\FlexMailer\ClickTracker\HtmlClickTracker
     */
    protected function getCiviFlexmailerHtmlClickTrackerService()
    {
        return $this->services['civi_flexmailer_html_click_tracker'] = new \Civi\FlexMailer\ClickTracker\HtmlClickTracker();
    }

    /**
     * Gets the public 'civi_flexmailer_open_tracker' shared service.
     *
     * @return \Civi\FlexMailer\Listener\OpenTracker
     */
    protected function getCiviFlexmailerOpenTrackerService()
    {
        return $this->services['civi_flexmailer_open_tracker'] = new \Civi\FlexMailer\Listener\OpenTracker();
    }

    /**
     * Gets the public 'civi_flexmailer_required_fields' shared service.
     *
     * @return \Civi\FlexMailer\Listener\RequiredFields
     */
    protected function getCiviFlexmailerRequiredFieldsService()
    {
        return $this->services['civi_flexmailer_required_fields'] = new \Civi\FlexMailer\Listener\RequiredFields([0 => 'subject', 1 => 'name', 2 => 'from_name', 3 => 'from_email', 4 => '(body_html|body_text)']);
    }

    /**
     * Gets the public 'civi_flexmailer_required_tokens' shared service.
     *
     * @return \Civi\FlexMailer\Listener\RequiredTokens
     */
    protected function getCiviFlexmailerRequiredTokensService()
    {
        return $this->services['civi_flexmailer_required_tokens'] = new \Civi\FlexMailer\Listener\RequiredTokens([0 => 'traditional'], ['domain.address' => 'Domain address - displays your organization\'s postal address.', 'action.optOutUrl or action.unsubscribeUrl' => ['action.optOut' => '\'Opt out via email\' - displays an email address for recipients to opt out of receiving emails from your organization.', 'action.optOutUrl' => '\'Opt out via web page\' - creates a link for recipients to click if they want to opt out of receiving emails from your organization. Alternatively, you can include the \'Opt out via email\' token.', 'action.unsubscribe' => '\'Unsubscribe via email\' - displays an email address for recipients to unsubscribe from the specific mailing list used to send this message.', 'action.unsubscribeUrl' => '\'Unsubscribe via web page\' - creates a link for recipients to unsubscribe from the specific mailing list used to send this message. Alternatively, you can include the \'Unsubscribe via email\' token or one of the Opt-out tokens.']]);
    }

    /**
     * Gets the public 'civi_flexmailer_test_prefix' shared service.
     *
     * @return \Civi\FlexMailer\Listener\TestPrefix
     */
    protected function getCiviFlexmailerTestPrefixService()
    {
        return $this->services['civi_flexmailer_test_prefix'] = new \Civi\FlexMailer\Listener\TestPrefix();
    }

    /**
     * Gets the public 'civi_flexmailer_text_click_tracker' shared service.
     *
     * @return \Civi\FlexMailer\ClickTracker\TextClickTracker
     */
    protected function getCiviFlexmailerTextClickTrackerService()
    {
        return $this->services['civi_flexmailer_text_click_tracker'] = new \Civi\FlexMailer\ClickTracker\TextClickTracker();
    }

    /**
     * Gets the public 'civi_flexmailer_to_header' shared service.
     *
     * @return \Civi\FlexMailer\Listener\ToHeader
     */
    protected function getCiviFlexmailerToHeaderService()
    {
        return $this->services['civi_flexmailer_to_header'] = new \Civi\FlexMailer\Listener\ToHeader();
    }

    /**
     * Gets the public 'civi_token_compat' shared service.
     *
     * @return \Civi\Token\TokenCompatSubscriber
     */
    protected function getCiviTokenCompatService()
    {
        return $this->services['civi_token_compat'] = new \Civi\Token\TokenCompatSubscriber();
    }

    /**
     * Gets the public 'civi_token_impliedcontext' shared service.
     *
     * @return \Civi\Token\ImpliedContextSubscriber
     */
    protected function getCiviTokenImpliedcontextService()
    {
        return $this->services['civi_token_impliedcontext'] = new \Civi\Token\ImpliedContextSubscriber();
    }

    /**
     * Gets the public 'crm_activity_tokens' shared service.
     *
     * @return \CRM_Activity_Tokens
     */
    protected function getCrmActivityTokensService()
    {
        return $this->services['crm_activity_tokens'] = new \CRM_Activity_Tokens();
    }

    /**
     * Gets the public 'crm_case_tokens' shared service.
     *
     * @return \CRM_Case_Tokens
     */
    protected function getCrmCaseTokensService()
    {
        return $this->services['crm_case_tokens'] = new \CRM_Case_Tokens();
    }

    /**
     * Gets the public 'crm_contact_tokens' shared service.
     *
     * @return \CRM_Contact_Tokens
     */
    protected function getCrmContactTokensService()
    {
        return $this->services['crm_contact_tokens'] = new \CRM_Contact_Tokens();
    }

    /**
     * Gets the public 'crm_contribute_tokens' shared service.
     *
     * @return \CRM_Contribute_Tokens
     */
    protected function getCrmContributeTokensService()
    {
        return $this->services['crm_contribute_tokens'] = new \CRM_Contribute_Tokens();
    }

    /**
     * Gets the public 'crm_contribution_recur_tokens' shared service.
     *
     * @return \CRM_Contribute_RecurTokens
     */
    protected function getCrmContributionRecurTokensService()
    {
        return $this->services['crm_contribution_recur_tokens'] = new \CRM_Contribute_RecurTokens();
    }

    /**
     * Gets the public 'crm_domain_tokens' shared service.
     *
     * @return \CRM_Core_DomainTokens
     */
    protected function getCrmDomainTokensService()
    {
        return $this->services['crm_domain_tokens'] = new \CRM_Core_DomainTokens();
    }

    /**
     * Gets the public 'crm_event_tokens' shared service.
     *
     * @return \CRM_Event_Tokens
     */
    protected function getCrmEventTokensService()
    {
        return $this->services['crm_event_tokens'] = new \CRM_Event_Tokens();
    }

    /**
     * Gets the public 'crm_mailing_action_tokens' shared service.
     *
     * @return \CRM_Mailing_ActionTokens
     */
    protected function getCrmMailingActionTokensService()
    {
        return $this->services['crm_mailing_action_tokens'] = new \CRM_Mailing_ActionTokens();
    }

    /**
     * Gets the public 'crm_mailing_tokens' shared service.
     *
     * @return \CRM_Mailing_Tokens
     */
    protected function getCrmMailingTokensService()
    {
        return $this->services['crm_mailing_tokens'] = new \CRM_Mailing_Tokens();
    }

    /**
     * Gets the public 'crm_member_tokens' shared service.
     *
     * @return \CRM_Member_Tokens
     */
    protected function getCrmMemberTokensService()
    {
        return $this->services['crm_member_tokens'] = new \CRM_Member_Tokens();
    }

    /**
     * Gets the public 'crm_participant_tokens' shared service.
     *
     * @return \CRM_Event_ParticipantTokens
     */
    protected function getCrmParticipantTokensService()
    {
        return $this->services['crm_participant_tokens'] = new \CRM_Event_ParticipantTokens();
    }

    /**
     * Gets the public 'crypto.jwt' shared service.
     *
     * @return \Civi\Crypto\CryptoJwt
     */
    protected function getCrypto_JwtService()
    {
        return $this->services['crypto.jwt'] = new \Civi\Crypto\CryptoJwt();
    }

    /**
     * Gets the public 'crypto.registry' shared service.
     *
     * @return \Civi\Crypto\CryptoService
     */
    protected function getCrypto_RegistryService()
    {
        return $this->services['crypto.registry'] = \Civi\Crypto\CryptoRegistry::createDefaultRegistry();
    }

    /**
     * Gets the public 'crypto.token' shared service.
     *
     * @return \Civi\Crypto\CryptoToken
     */
    protected function getCrypto_TokenService()
    {
        return $this->services['crypto.token'] = new \Civi\Crypto\CryptoToken();
    }

    /**
     * Gets the public 'cxn_reg_client' shared service.
     *
     * @return \Civi\Cxn\Rpc\RegistrationClient
     */
    protected function getCxnRegClientService()
    {
        return $this->services['cxn_reg_client'] = \CRM_Cxn_BAO_Cxn::createRegistrationClient();
    }

    /**
     * Gets the public 'dispatcher' shared service.
     *
     * @return \Civi\Core\CiviEventDispatcher
     */
    protected function getDispatcherService()
    {
        $this->services['dispatcher'] = $instance = ${($_ = isset($this->services['civi_container_factory']) ? $this->services['civi_container_factory'] : ($this->services['civi_container_factory'] = new \Civi\Core\Container())) && false ?: '_'}->createEventDispatcher();

        $instance->addListenerMap('CRM_Core_BAO_LocationType', ['hook_civicrm_pre::LocationType' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_Managed', ['hook_civicrm_post' => [0 => [0 => 'on_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_Mapping', ['hook_civicrm_pre' => [0 => [0 => 'on_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_MessageTemplate', ['hook_civicrm_pre::MessageTemplate' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_OptionGroup', ['hook_civicrm_pre::OptionGroup' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_Translation', ['civi.api4.validate::Translation' => [0 => [0 => 'self_civi_api4_validate', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_RecurringEntity', ['civi.dao.postInsert' => [0 => [0 => 'triggerInsert']], 'civi.dao.postUpdate' => [0 => [0 => 'triggerUpdate']], 'civi.dao.postDelete' => [0 => [0 => 'triggerDelete']]]);
        $instance->addListenerMap('CRM_ACL_BAO_ACL', ['hook_civicrm_pre::ACL' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Contact_BAO_Contact', ['hook_civicrm_post' => [0 => [0 => 'on_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Contact_BAO_RelationshipType', ['hook_civicrm_pre::RelationshipType' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Contact_BAO_ContactType', ['hook_civicrm_pre::ContactType' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]], 'hook_civicrm_post::ContactType' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Financial_BAO_FinancialAccount', ['hook_civicrm_pre::FinancialAccount' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]], 'hook_civicrm_post::FinancialAccount' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Financial_BAO_PaymentProcessorType', ['hook_civicrm_pre::PaymentProcessorType' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Financial_BAO_FinancialType', ['hook_civicrm_pre::FinancialType' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]], 'hook_civicrm_post::FinancialType' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Member_BAO_MembershipStatus', ['hook_civicrm_pre::MembershipStatus' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Campaign_BAO_Survey', ['hook_civicrm_pre::Survey' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Case_BAO_CaseType', ['hook_civicrm_pre::CaseType' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]], 'hook_civicrm_post::CaseType' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_CustomGroup', ['hook_civicrm_post::CustomGroup' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_Email', ['hook_civicrm_post::Email' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_Note', ['hook_civicrm_pre::Note' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Core_BAO_WordReplacement', ['hook_civicrm_post::WordReplacement' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Financial_BAO_PaymentProcessor', ['hook_civicrm_post::PaymentProcessor' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Member_BAO_MembershipType', ['hook_civicrm_pre' => [0 => [0 => 'on_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Report_BAO_ReportInstance', ['hook_civicrm_pre::ReportInstance' => [0 => [0 => 'self_hook_civicrm_pre', 1 => 0]]]);
        $instance->addListenerMap('CRM_Contact_BAO_RelationshipCache', ['hook_civicrm_triggerInfo' => [0 => [0 => 'on_hook_civicrm_triggerInfo', 1 => 0]]]);
        $instance->addListenerMap('CRM_Contact_BAO_GroupContact', ['hook_civicrm_post::GroupContact' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addListenerMap('CRM_Contribute_BAO_Contribution', ['hook_civicrm_post::Contribution' => [0 => [0 => 'self_hook_civicrm_post', 1 => 0]]]);
        $instance->addSubscriberServiceMap('action_object_provider', ['civi.api.resolve' => [0 => [0 => 'onApiResolve', 1 => 100]]]);
        $instance->addSubscriberServiceMap('Civi_Api4_Event_Subscriber_CreateApi4RequestSubscriber', ['civi.api4.createRequest' => [0 => [0 => 'onApiRequestCreate', 1 => -100]]]);
        $instance->addSubscriberServiceMap('Civi_Api4_Event_Subscriber_IsCurrentSubscriber', ['civi.api.prepare' => [0 => [0 => 'onApiPrepare']]]);
        $instance->addSubscriberServiceMap('Civi_Api4_Event_Subscriber_PermissionCheckSubscriber', ['civi.api.authorize' => [0 => [0 => 'onApiAuthorize', 1 => -100]]]);
        $instance->addSubscriberServiceMap('Civi_Api4_Event_Subscriber_ValidateFieldsSubscriber', ['civi.api.prepare' => [0 => [0 => 'onApiPrepare']]]);
        $instance->addListener('civi.api4.authorizeRecord::Contribution', '_financialacls_civi_api4_authorizeContribution');
        $instance->addListenerService('civi.flexmailer.checkSendable', [0 => 'civi_flexmailer_abdicator', 1 => 'onCheckSendable'], 2000);
        $instance->addListenerService('civi.flexmailer.checkSendable', [0 => 'civi_flexmailer_required_fields', 1 => 'onCheckSendable'], 0);
        $instance->addListenerService('civi.flexmailer.checkSendable', [0 => 'civi_flexmailer_required_tokens', 1 => 'onCheckSendable'], 0);
        $instance->addListenerService('civi.flexmailer.run', [0 => 'civi_flexmailer_default_composer', 1 => 'onRun'], 0);
        $instance->addListenerService('civi.flexmailer.run', [0 => 'civi_flexmailer_abdicator', 1 => 'onRun'], -2000);
        $instance->addListenerService('civi.flexmailer.walk', [0 => 'civi_flexmailer_default_batcher', 1 => 'onWalk'], -2000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_basic_headers', 1 => 'onCompose'], 1000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_to_header', 1 => 'onCompose'], 1000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_bounce_tracker', 1 => 'onCompose'], 1000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_default_composer', 1 => 'onCompose'], -100);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_attachments', 1 => 'onCompose'], -1000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_open_tracker', 1 => 'onCompose'], -1000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_test_prefix', 1 => 'onCompose'], -1000);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'civi_flexmailer_hooks', 1 => 'onCompose'], -1100);
        $instance->addListenerService('civi.flexmailer.send', [0 => 'civi_flexmailer_default_sender', 1 => 'onSend'], -2000);
        $instance->addListener('civi.api4.authorizeRecord::SavedSearch', [0 => 'CRM_Search_BAO_SearchDisplay', 1 => 'savedSearchCheckAccessByDisplay']);
        $instance->addListenerService('civi.flexmailer.checkSendable', [0 => 'mosaico_required_tokens', 1 => 'onCheckSendable'], 0);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'mosaico_flexmail_composer', 1 => 'onCompose'], 0);
        $instance->addListenerService('civi.flexmailer.compose', [0 => 'mosaico_flexmail_url_filter', 1 => 'onCompose'], -1100);
        $instance->addListenerService('hook_civicrm_alterMailContent', [0 => 'mosaico_image_filter', 1 => 'alterMailContent']);
        $instance->addListener('civi.api.prepare', 'mosaico_wrapMailingApi', -100);
        $instance->addListener('hook_civicrm_triggerInfo', [0 => function () {
            return ${($_ = isset($this->services['civi.activity.triggers']) ? $this->services['civi.activity.triggers'] : ($this->services['civi.activity.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_activity', 'Activity'))) && false ?: '_'};
        }, 1 => 'onTriggerInfo'], 0);
        $instance->addListener('hook_civicrm_triggerInfo', [0 => function () {
            return ${($_ = isset($this->services['civi.case.triggers']) ? $this->services['civi.case.triggers'] : ($this->services['civi.case.triggers'] = new \Civi\Core\SqlTrigger\TimestampTriggers('civicrm_case', 'Case'))) && false ?: '_'};
        }, 1 => 'onTriggerInfo'], 0);
        $instance->addListener('hook_civicrm_triggerInfo', [0 => function () {
            return ${($_ = isset($this->services['civi.case.staticTriggers']) ? $this->services['civi.case.staticTriggers'] : $this->getCivi_Case_StaticTriggersService()) && false ?: '_'};
        }, 1 => 'onTriggerInfo'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['civi_token_compat']) ? $this->services['civi_token_compat'] : ($this->services['civi_token_compat'] = new \Civi\Token\TokenCompatSubscriber())) && false ?: '_'};
        }, 1 => 'setupSmartyAliases'], 1000);
        $instance->addListener('civi.token.render', [0 => function () {
            return ${($_ = isset($this->services['civi_token_compat']) ? $this->services['civi_token_compat'] : ($this->services['civi_token_compat'] = new \Civi\Token\TokenCompatSubscriber())) && false ?: '_'};
        }, 1 => 'onRender'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_mailing_action_tokens']) ? $this->services['crm_mailing_action_tokens'] : ($this->services['crm_mailing_action_tokens'] = new \CRM_Mailing_ActionTokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_mailing_action_tokens']) ? $this->services['crm_mailing_action_tokens'] : ($this->services['crm_mailing_action_tokens'] = new \CRM_Mailing_ActionTokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_mailing_action_tokens']) ? $this->services['crm_mailing_action_tokens'] : ($this->services['crm_mailing_action_tokens'] = new \CRM_Mailing_ActionTokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_activity_tokens']) ? $this->services['crm_activity_tokens'] : ($this->services['crm_activity_tokens'] = new \CRM_Activity_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_activity_tokens']) ? $this->services['crm_activity_tokens'] : ($this->services['crm_activity_tokens'] = new \CRM_Activity_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_activity_tokens']) ? $this->services['crm_activity_tokens'] : ($this->services['crm_activity_tokens'] = new \CRM_Activity_Tokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_contact_tokens']) ? $this->services['crm_contact_tokens'] : ($this->services['crm_contact_tokens'] = new \CRM_Contact_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateLegacyHookTokens'], 500);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_contact_tokens']) ? $this->services['crm_contact_tokens'] : ($this->services['crm_contact_tokens'] = new \CRM_Contact_Tokens())) && false ?: '_'};
        }, 1 => 'onEvaluate'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_contact_tokens']) ? $this->services['crm_contact_tokens'] : ($this->services['crm_contact_tokens'] = new \CRM_Contact_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_contribute_tokens']) ? $this->services['crm_contribute_tokens'] : ($this->services['crm_contribute_tokens'] = new \CRM_Contribute_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_contribute_tokens']) ? $this->services['crm_contribute_tokens'] : ($this->services['crm_contribute_tokens'] = new \CRM_Contribute_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_contribute_tokens']) ? $this->services['crm_contribute_tokens'] : ($this->services['crm_contribute_tokens'] = new \CRM_Contribute_Tokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_event_tokens']) ? $this->services['crm_event_tokens'] : ($this->services['crm_event_tokens'] = new \CRM_Event_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_event_tokens']) ? $this->services['crm_event_tokens'] : ($this->services['crm_event_tokens'] = new \CRM_Event_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_event_tokens']) ? $this->services['crm_event_tokens'] : ($this->services['crm_event_tokens'] = new \CRM_Event_Tokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_mailing_tokens']) ? $this->services['crm_mailing_tokens'] : ($this->services['crm_mailing_tokens'] = new \CRM_Mailing_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_mailing_tokens']) ? $this->services['crm_mailing_tokens'] : ($this->services['crm_mailing_tokens'] = new \CRM_Mailing_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_mailing_tokens']) ? $this->services['crm_mailing_tokens'] : ($this->services['crm_mailing_tokens'] = new \CRM_Mailing_Tokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_member_tokens']) ? $this->services['crm_member_tokens'] : ($this->services['crm_member_tokens'] = new \CRM_Member_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_member_tokens']) ? $this->services['crm_member_tokens'] : ($this->services['crm_member_tokens'] = new \CRM_Member_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_member_tokens']) ? $this->services['crm_member_tokens'] : ($this->services['crm_member_tokens'] = new \CRM_Member_Tokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_case_tokens']) ? $this->services['crm_case_tokens'] : ($this->services['crm_case_tokens'] = new \CRM_Case_Tokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_case_tokens']) ? $this->services['crm_case_tokens'] : ($this->services['crm_case_tokens'] = new \CRM_Case_Tokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_case_tokens']) ? $this->services['crm_case_tokens'] : ($this->services['crm_case_tokens'] = new \CRM_Case_Tokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['civi_token_impliedcontext']) ? $this->services['civi_token_impliedcontext'] : ($this->services['civi_token_impliedcontext'] = new \Civi\Token\ImpliedContextSubscriber())) && false ?: '_'};
        }, 1 => 'onRegisterTokens'], 1000);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['civi_token_impliedcontext']) ? $this->services['civi_token_impliedcontext'] : ($this->services['civi_token_impliedcontext'] = new \Civi\Token\ImpliedContextSubscriber())) && false ?: '_'};
        }, 1 => 'onEvaluateTokens'], 1000);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_participant_tokens']) ? $this->services['crm_participant_tokens'] : ($this->services['crm_participant_tokens'] = new \CRM_Event_ParticipantTokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_participant_tokens']) ? $this->services['crm_participant_tokens'] : ($this->services['crm_participant_tokens'] = new \CRM_Event_ParticipantTokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_participant_tokens']) ? $this->services['crm_participant_tokens'] : ($this->services['crm_participant_tokens'] = new \CRM_Event_ParticipantTokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_contribution_recur_tokens']) ? $this->services['crm_contribution_recur_tokens'] : ($this->services['crm_contribution_recur_tokens'] = new \CRM_Contribute_RecurTokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_contribution_recur_tokens']) ? $this->services['crm_contribution_recur_tokens'] : ($this->services['crm_contribution_recur_tokens'] = new \CRM_Contribute_RecurTokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_contribution_recur_tokens']) ? $this->services['crm_contribution_recur_tokens'] : ($this->services['crm_contribution_recur_tokens'] = new \CRM_Contribute_RecurTokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);
        $instance->addListener('civi.token.list', [0 => function () {
            return ${($_ = isset($this->services['crm_domain_tokens']) ? $this->services['crm_domain_tokens'] : ($this->services['crm_domain_tokens'] = new \CRM_Core_DomainTokens())) && false ?: '_'};
        }, 1 => 'registerTokens'], 0);
        $instance->addListener('civi.token.eval', [0 => function () {
            return ${($_ = isset($this->services['crm_domain_tokens']) ? $this->services['crm_domain_tokens'] : ($this->services['crm_domain_tokens'] = new \CRM_Core_DomainTokens())) && false ?: '_'};
        }, 1 => 'evaluateTokens'], 0);
        $instance->addListener('civi.actionSchedule.prepareMailingQuery', [0 => function () {
            return ${($_ = isset($this->services['crm_domain_tokens']) ? $this->services['crm_domain_tokens'] : ($this->services['crm_domain_tokens'] = new \CRM_Core_DomainTokens())) && false ?: '_'};
        }, 1 => 'alterActionScheduleQuery'], 0);

        return $instance;
    }

    /**
     * Gets the public 'format' shared service.
     *
     * @return \Civi\Core\Format
     */
    protected function getFormatService()
    {
        return $this->services['format'] = new \Civi\Core\Format();
    }

    /**
     * Gets the public 'httpClient' shared service.
     *
     * @return \CRM_Utils_HttpClient
     */
    protected function getHttpClientService()
    {
        return $this->services['httpClient'] = \CRM_Utils_HttpClient::singleton();
    }

    /**
     * Gets the public 'i18n' shared service.
     *
     * @return \CRM_Core_I18n
     */
    protected function getI18nService()
    {
        return $this->services['i18n'] = \CRM_Core_I18n::singleton();
    }

    /**
     * Gets the public 'magic_function_provider' shared service.
     *
     * @return \Civi\API\Provider\MagicFunctionProvider
     */
    protected function getMagicFunctionProviderService()
    {
        return $this->services['magic_function_provider'] = new \Civi\API\Provider\MagicFunctionProvider();
    }

    /**
     * Gets the public 'mosaico_ab_demux' shared service.
     *
     * @return \CRM_Mosaico_AbDemux
     */
    protected function getMosaicoAbDemuxService()
    {
        return $this->services['mosaico_ab_demux'] = new \CRM_Mosaico_AbDemux();
    }

    /**
     * Gets the public 'mosaico_flexmail_composer' shared service.
     *
     * @return \CRM_Mosaico_MosaicoComposer
     */
    protected function getMosaicoFlexmailComposerService()
    {
        return $this->services['mosaico_flexmail_composer'] = new \CRM_Mosaico_MosaicoComposer();
    }

    /**
     * Gets the public 'mosaico_flexmail_url_filter' shared service.
     *
     * @return \CRM_Mosaico_UrlFilter
     */
    protected function getMosaicoFlexmailUrlFilterService()
    {
        return $this->services['mosaico_flexmail_url_filter'] = new \CRM_Mosaico_UrlFilter();
    }

    /**
     * Gets the public 'mosaico_graphics' shared service.
     *
     * @return \CRM_Mosaico_Graphics_Interface
     */
    protected function getMosaicoGraphicsService()
    {
        return $this->services['mosaico_graphics'] = \CRM_Mosaico_Services::createGraphics();
    }

    /**
     * Gets the public 'mosaico_image_filter' shared service.
     *
     * @return \CRM_Mosaico_ImageFilter
     */
    protected function getMosaicoImageFilterService()
    {
        return $this->services['mosaico_image_filter'] = new \CRM_Mosaico_ImageFilter();
    }

    /**
     * Gets the public 'mosaico_required_tokens' shared service.
     *
     * @return \CRM_Mosaico_MosaicoRequiredTokens
     */
    protected function getMosaicoRequiredTokensService()
    {
        return $this->services['mosaico_required_tokens'] = new \CRM_Mosaico_MosaicoRequiredTokens();
    }

    /**
     * Gets the public 'pear_mail' shared service.
     *
     * @return \Mail
     */
    protected function getPearMailService()
    {
        return $this->services['pear_mail'] = \CRM_Utils_Mail::createMailer();
    }

    /**
     * Gets the public 'prevnext' shared service.
     *
     * @return \CRM_Core_PrevNextCache_Interface
     */
    protected function getPrevnextService()
    {
        return $this->services['prevnext'] = ${($_ = isset($this->services['civi_container_factory']) ? $this->services['civi_container_factory'] : ($this->services['civi_container_factory'] = new \Civi\Core\Container())) && false ?: '_'}->createPrevNextCache($this);
    }

    /**
     * Gets the public 'prevnext.driver.redis' shared service.
     *
     * @return \CRM_Core_PrevNextCache_Redis
     */
    protected function getPrevnext_Driver_RedisService()
    {
        return $this->services['prevnext.driver.redis'] = new \CRM_Core_PrevNextCache_Redis(${($_ = isset($this->services['cache_config']) ? $this->services['cache_config'] : $this->getCacheConfigService()) && false ?: '_'});
    }

    /**
     * Gets the public 'prevnext.driver.sql' shared service.
     *
     * @return \CRM_Core_PrevNextCache_Sql
     */
    protected function getPrevnext_Driver_SqlService()
    {
        return $this->services['prevnext.driver.sql'] = new \CRM_Core_PrevNextCache_Sql();
    }

    /**
     * Gets the public 'psr_log' shared service.
     *
     * @return \CRM_Core_Error_Log
     */
    protected function getPsrLogService()
    {
        return $this->services['psr_log'] = new \CRM_Core_Error_Log();
    }

    /**
     * Gets the public 'psr_log_manager' shared service.
     *
     * @return \Civi\Core\LogManager
     */
    protected function getPsrLogManagerService()
    {
        return $this->services['psr_log_manager'] = new \Civi\Core\LogManager();
    }

    /**
     * Gets the public 'resources' shared service.
     *
     * @return \CRM_Core_Resources
     */
    protected function getResourcesService()
    {
        return $this->services['resources'] = ${($_ = isset($this->services['civi_container_factory']) ? $this->services['civi_container_factory'] : ($this->services['civi_container_factory'] = new \Civi\Core\Container())) && false ?: '_'}->createResources($this);
    }

    /**
     * Gets the public 'resources.js_strings' shared service.
     *
     * @return \CRM_Core_Resources_Strings
     */
    protected function getResources_JsStringsService()
    {
        return $this->services['resources.js_strings'] = new \CRM_Core_Resources_Strings(${($_ = isset($this->services['cache.js_strings']) ? $this->services['cache.js_strings'] : $this->getCache_JsStringsService()) && false ?: '_'});
    }

    /**
     * Gets the public 'schema_map_builder' shared service.
     *
     * @return \Civi\Api4\Service\Schema\SchemaMapBuilder
     */
    protected function getSchemaMapBuilderService()
    {
        return $this->services['schema_map_builder'] = new \Civi\Api4\Service\Schema\SchemaMapBuilder(${($_ = isset($this->services['dispatcher']) ? $this->services['dispatcher'] : $this->getDispatcherService()) && false ?: '_'});
    }

    /**
     * Gets the public 'spec_gatherer' shared service.
     *
     * @return \Civi\Api4\Service\Spec\SpecGatherer
     */
    protected function getSpecGathererService()
    {
        $this->services['spec_gatherer'] = $instance = new \Civi\Api4\Service\Spec\SpecGatherer();

        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ACLCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ACLCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ACLEntityRoleCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ACLEntityRoleCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ActionScheduleCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ActionScheduleCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ActivitySpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ActivitySpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ActivitySpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ActivitySpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_AddressCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\AddressCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_BatchCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\BatchCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_CampaignCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CampaignCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_CaseCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CaseCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_CaseTypeGetSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CaseTypeGetSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ContactCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContactCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ContactGetSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContactGetSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ContactTypeCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContactTypeCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ContributionCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContributionCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ContributionRecurCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ContributionRecurCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_CustomFieldCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CustomFieldCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_CustomGroupCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CustomGroupCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_CustomValueSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\CustomValueSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_DefaultLocationTypeProvider'] = new \Civi\Api4\Service\Spec\Provider\DefaultLocationTypeProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_DomainCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\DomainCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_EmailCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EmailCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_EntityBatchCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EntityBatchCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_EntityTagCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EntityTagCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_EntityTagFilterSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EntityTagFilterSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_EventCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\EventCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_FieldCurrencySpecProvider'] = new \Civi\Api4\Service\Spec\Provider\FieldCurrencySpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_FieldDomainIdSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\FieldDomainIdSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_FinancialItemCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\FinancialItemCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_GetActionDefaultsProvider'] = new \Civi\Api4\Service\Spec\Provider\GetActionDefaultsProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_GroupContactCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\GroupContactCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_GroupCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\GroupCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_IsCurrentFieldSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\IsCurrentFieldSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_ManagedEntitySpecProvider'] = new \Civi\Api4\Service\Spec\Provider\ManagedEntitySpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_MappingCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\MappingCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_MembershipCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\MembershipCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_NavigationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_NavigationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_NavigationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\NavigationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_NoteCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\NoteCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_OptionValueCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\OptionValueCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PaymentProcessorCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_PaymentProcessorTypeCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PaymentProcessorTypeCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_PhoneCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PhoneCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_PriceFieldValueCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\PriceFieldValueCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_RelationshipCacheSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\RelationshipCacheSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_RelationshipTypeCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\RelationshipTypeCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_TagCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\TagCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_UFFieldCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\UFFieldCreationSpecProvider())) && false ?: '_'});
        $instance->addSpecProvider(${($_ = isset($this->services['Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider']) ? $this->services['Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider'] : ($this->services['Civi_Api4_Service_Spec_Provider_SearchDisplayCreationSpecProvider'] = new \Civi\Api4\Service\Spec\Provider\SearchDisplayCreationSpecProvider())) && false ?: '_'});

        return $instance;
    }

    /**
     * Gets the public 'sql_triggers' shared service.
     *
     * @return \Civi\Core\SqlTriggers
     */
    protected function getSqlTriggersService()
    {
        return $this->services['sql_triggers'] = new \Civi\Core\SqlTriggers();
    }

    /**
     * Gets the public 'themes' shared service.
     *
     * @return \Civi\Core\Themes
     */
    protected function getThemesService()
    {
        return $this->services['themes'] = new \Civi\Core\Themes();
    }

    /**
     * Gets the private 'civi_container_factory' shared service.
     *
     * @return \Civi\Core\Container
     */
    protected function getCiviContainerFactoryService()
    {
        return $this->services['civi_container_factory'] = new \Civi\Core\Container();
    }

    public function getParameter($name)
    {
        $name = (string) $name;
        if (!(isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters))) {
            $name = $this->normalizeParameterName($name);

            if (!(isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters))) {
                throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
            }
        }
        if (isset($this->loadedDynamicParameters[$name])) {
            return $this->loadedDynamicParameters[$name] ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
        }

        return $this->parameters[$name];
    }

    public function hasParameter($name)
    {
        $name = (string) $name;
        $name = $this->normalizeParameterName($name);

        return isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters);
    }

    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $parameters = $this->parameters;
            foreach ($this->loadedDynamicParameters as $name => $loaded) {
                $parameters[$name] = $loaded ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
            }
            $this->parameterBag = new FrozenParameterBag($parameters);
        }

        return $this->parameterBag;
    }

    private $loadedDynamicParameters = [];
    private $dynamicParameters = [];

    /**
     * Computes a dynamic parameter.
     *
     * @param string $name The name of the dynamic parameter to load
     *
     * @return mixed The value of the dynamic parameter
     *
     * @throws InvalidArgumentException When the dynamic parameter does not exist
     */
    private function getDynamicParameter($name)
    {
        throw new InvalidArgumentException(sprintf('The dynamic parameter "%s" must be defined.', $name));
    }

    private $normalizedParameterNames = [];

    private function normalizeParameterName($name)
    {
        if (isset($this->normalizedParameterNames[$normalizedName = strtolower($name)]) || isset($this->parameters[$normalizedName]) || array_key_exists($normalizedName, $this->parameters)) {
            $normalizedName = isset($this->normalizedParameterNames[$normalizedName]) ? $this->normalizedParameterNames[$normalizedName] : $normalizedName;
            if ((string) $name !== $normalizedName) {
                @trigger_error(sprintf('Parameter names will be made case sensitive in Symfony 4.0. Using "%s" instead of "%s" is deprecated since Symfony 3.4.', $name, $normalizedName), E_USER_DEPRECATED);
            }
        } else {
            $normalizedName = $this->normalizedParameterNames[$normalizedName] = (string) $name;
        }

        return $normalizedName;
    }

    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return [
            'civicrm_base_path' => '/var/www/obiaa.jmaconsulting.biz/htdocs/wp-content/plugins/civicrm/civicrm',
        ];
    }
}
