<?php namespace Snappy\Apps\MailChimp;

use Snappy\Apps\ContactCreatedHandler;
use Snappy\Apps\App as BaseApp;

class App extends BaseApp implements ContactCreatedHandler {

	/**
	 * The name of the application.
	 *
	 * @var string
	 */
	public $name = 'MailChimp';

	/**
	 * The application description.
	 *
	 * @var string
	 */
	public $description = 'Automatically add contacts to a MailChimp list';

	/**
	 * Any notes about this application
	 *
	 * @var string
	 */
	public $notes = '
		<p>To get your API token visit you Account Settings -> Extras -> Api Keys. Or follow the tutorial <a href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key">here</a>.</p>
		<p>To get your list id login to your MailChimp account then go to Lists -> Select List -> Settings -> List Name and Defaults. The
		List ID will be on the right side of this page.</p>
		<p><strong>Important</strong> This integration will only pass the customer email, first name and last name. This means your list should not have any other required fields.</p>
	';

	/**
	 * The application's icon filename.
	 *
	 * @var string
	 */
	public $icon = 'mailchimp.png';

	/**
	 * The application service's main website.
	 *
	 * @var string
	 */
	public $website = 'https://mailchimp.com';

		/**
	 * The application author name.
	 *
	 * @var string
	 */
	public $author = 'UserScape, Inc.';

	/**
	 * The application author e-mail.
	 *
	 * @var string
	 */
	public $email = 'it@userscape.com';

	/**
	 * The settings required by the application.
	 *
	 * @var array
	 */
	public $settings = array(
		array('name' => 'token', 'type' => 'text', 'help' => 'Enter your API Token', 'validate' => 'required'),
		array('name' => 'list', 'type' => 'text', 'help' => 'Enter your Email List ID', 'validate' => 'required'),
	);

	/**
	 * Add the contact into a MailChimp List
	 *
	 * @param  array  $ticket
	 * @param  array  $contact
	 * @return void
	 */
	public function handleContactCreated(array $ticket, array $contact)
	{
		$client = $this->getClient();

		$merge_vars = array(
			'FNAME' => $contact['first_name'],
			'LNAME' => $contact['last_name'],
		);

		$client->lists->subscribe($this->config['list'], array('email' => $contact['value']), $merge_vars, 'html', false);
	}

	/**
	 * Get the client instance
	 *
	 * @return \Mailchimp
	 */
	protected function getClient()
	{
		return new \Mailchimp($this->config['token']);
	}
}
