<?php
/**
 * Class for CiviRule Action send sms
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_Smsapi_CivirulesAction extends CRM_CivirulesActions_Generic_Api {

  /**
   * Method to get the api entity to process in this CiviRule action
   *
   * @access protected
   * @abstract
   */
  protected function getApiEntity() {
    return 'Sms';
  }

  /**
   * Method to get the api action to process in this CiviRule action
   *
   * @access protected
   * @abstract
   */
  protected function getApiAction() {
    return 'send';
  }

  /**
   * Returns an array with parameters used for processing an action
   *
   * @param array $parameters
   * @param CRM_Civirules_EventData_EventData $eventData
   * @return array
   * @access protected
   */
  protected function alterApiParameters($parameters, CRM_Civirules_EventData_EventData $eventData) {
    //this method could be overridden in subclasses to alter parameters to meet certain criteria
    $parameters['contact_id'] = $eventData->getContactId();
    return $parameters;
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a action
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleActionId
   * @return bool|string
   * $access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return CRM_Utils_System::url('civicrm/civirules/actions/smsapi', 'rule_action_id='.$ruleActionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    $template = 'unknown template';
    $params = $this->getActionParameters();
    $version = CRM_Core_BAO_Domain::version();
    // Compatibility with CiviCRM > 4.3
    if($version >= 4.4) {
      $messageTemplates = new CRM_Core_DAO_MessageTemplate();
    } else {
      $messageTemplates = new CRM_Core_DAO_MessageTemplates();
    }
    $messageTemplates->id = $params['template_id'];
    $messageTemplates->is_active = true;
    if ($messageTemplates->find(TRUE)) {
      $template = $messageTemplates->msg_title;
    }
    $providerInfo = CRM_SMS_BAO_Provider::getProviderInfo($params['provider_id']);
    return ts('Send SMS with provider "%1" with template "%2"', array(
        1=>$providerInfo['title'],
        2=>$template
    ));
  }
}