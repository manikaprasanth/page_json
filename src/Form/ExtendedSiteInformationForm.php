<?php

namespace Drupal\page_json\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Configure the site settings for this site.
 *
 * @internal
 */

class ExtendedSiteInformationForm extends SiteInformationForm {

	/**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$site_config = $this->config('system.site');
		$form =  parent::buildForm($form, $form_state);
		$form['site_information']['siteapikey'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
			'#description' => t("Custom field to set the API Key"),
		];
		//Change the button text in Site settings form.
		$form['actions']['submit']['#value'] = t('Update Configuration');
		return $form;
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->config('system.site')
		->set('siteapikey', $form_state->getValue('siteapikey'))
		->save();
		parent::submitForm($form, $form_state);
		//Removed the default staus message.
		\Drupal::messenger()->deleteAll();
		//Added custom status message.
		\Drupal::messenger()->addMessage(t('The Site API Key %api_key has been saved', ['%api_key' => $form_state->getValue('siteapikey')]));
	}
}
