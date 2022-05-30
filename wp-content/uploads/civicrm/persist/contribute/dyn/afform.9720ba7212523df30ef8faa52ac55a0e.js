
(function(angular, $, _) {
  angular.module('afformInquiry', CRM.angRequires('afformInquiry'));
  angular.module('afformInquiry').directive('afformInquiry', function(afCoreDirective) {
    return afCoreDirective("afformInquiry", {"name":"afformInquiry","title":"Inquiry","redirect":null}, {
      templateUrl: "~\/afformInquiry\/afformInquiry.aff.html"
    });
  });
})(angular, CRM.$, CRM._);

