<?php

trait MailTracking {

	/**
     * Delivered emails.
     */
	protected $emails = [];

	/**
     * Register a listener for new emails.
     *
     * @before
     */
	public function setUpMailTracking() {
		parent::setUp();

		Mail::getSwiftMailer()
			->registerPlugin(new TestingMailEventListener($this));
	}

	/**
     * Store a new swift message.
     *
     * @param Swift_Message $email
     */
	public function addEmail(Swift_Message $email) {
		$this->emails[] = $email;
	}

	/**
     * Retrieve the mostly recently sent swift message.
     */
    protected function lastEmail() {
        return end($this->emails);
    }

	/**
     * Retrieve the appropriate swift message.
     *
     * @param Swift_Message $message
     */
	protected function getEmail(Swift_Message $message = null) {
		$this->seeEmailWasSent();

        return $message ?: $this->lastEmail();
    }

	/**
     * Assert that at least one email was sent.
	 * 
	 * @return PHPUnit_Framework_TestCase $this
     */
	protected function seeEmailWasSent() {
		$this->assertNotEmpty(
			$this->emails, 
			"No emails have been sent."
		);
		return $this;
	}

	/**
     * Assert that the given number of emails were sent.
     *
     * @param integer $count
	 * @return PHPUnit_Framework_TestCase $this
     */
	protected function seeEmailsSent($count) {
		$emailsSent = count($this->emails);

		$this->assertCount(
			$count, 
			$this->emails,
			"Expected $count emails to have been sent, but $emailsSent were."
		);
		return $this;
	}

	/**
     * Assert that the last email's subject matches the given string.
     *
     * @param string        $subject
     * @param Swift_Message $message
	 * @return PHPUnit_Framework_TestCase $this
     */
    protected function seeEmailSubject($subject, Swift_Message $message = null) {
        $this->assertEquals(
            $subject, $this->getEmail($message)->getSubject(),
            "No email with a subject of $subject was found."
        );
        return $this;
	}
	
	/**
     * Assert that the last email was sent to the given recipient.
     *
     * @param string        $recipient
     * @param Swift_Message $message
	 * @return PHPUnit_Framework_TestCase $this
     */
    protected function seeEmailTo($recipient, Swift_Message $message = null) {
        $this->assertArrayHasKey(
            $recipient, (array) $this->getEmail($message)->getTo(),
            "No email was sent to $recipient."
        );
        return $this;
	}
	
	/**
     * Assert that the last email's body equals the given text.
     *
     * @param string        $body
     * @param Swift_Message $message
	 * @return PHPUnit_Framework_TestCase $this
     */
    protected function seeEmailEquals($body, Swift_Message $message = null) {
        $this->assertEquals(
            $body, $this->getEmail($message)->getBody(),
            "No email with the provided body was sent."
        );
        return $this;
	}
	
	/**
     * Assert that the last email's body contains the given text.
     *
     * @param string        $excerpt
     * @param Swift_Message $message
	 * @return PHPUnit_Framework_TestCase $this
     */
    protected function seeEmailContains($excerpt, Swift_Message $message = null) {
        $this->assertContains(
            $excerpt, $this->getEmail($message)->getBody(),
            "No email containing the provided body was found."
        );
        return $this;
    }
}

class TestingMailEventListener implements Swift_Events_EventListener
{
    protected $test;

    public function __construct($test) {
        $this->test = $test;
    }

    public function beforeSendPerformed($event) {
        $this->test->addEmail($event->getMessage());
    }
}