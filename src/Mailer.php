<?php
/**
 * mailer server
 *
 * @category php
 * @package xb.mailer
 * @author enze.wei <[enzewei@gmail.com]>
 * @copyright 2017 xbsoft
 * @license http://xbsoft.net/licenses/mit.php MIT License
 * @version Stable 1.0.0
 * @link http://mailer.xbsoft.net
 */
namespace xb\mailer;

/**
 * mailer server
 *
 * $mail = new xb\mailer\Mailer;
 * $mail->smtp = 'smtp.your.com';
 * $mail->port = '465';
 * $mail->ssl = true;
 * $mail->username = 'you@your.com';
 * $mail->password = '';
 * $mail->charset = 'utf-8';
 * $mail->mailFrom = ['you@your.com' => 'you'];
 * $mail->mailTo = ['other@other.com' => 'other'];
 * $mail->title = '测试邮件';
 * $mail->message = '亲爱的xxx，这是一封测试邮件，请忽略！！';
 *
 * try {
 *   $mail->send();
 * } catch (\Exception $e) {
 *	 do sth.
 * }
 *
 */
class Mailer {
	
	/*
	 * smtp mailer config
	 */
	public $smtp = '';
	public $port = '';
	public $ssl = true;
	
	/*
	 * smtp account
	 */
	public $username = '';
	public $password = '';

	/*
	 * content encode type
	 */
	public $charset = '';
	
	/*
	 * sender [email => nickname]
	 */
	public $mailFrom = [];
	
	/*
	 * receiver [email => nickname]
	 */
	public $mailTo = [];
	
	/*
	 * subject
	 */
	public $title = '';
	
	/*
	 * body
	 */
	public $message = '';
	
	/*
	 * mail body content type
	 */
	public $contentType = 'text/html';
	
	/*
	 * object of swiftmailer
	 */
	private $_swift = [];
	
	/*
	 * ReflectionClass object
	 */
	private $_reflection = null;

	public function __construct() {
		/*
		 * get reflectionProperty
		 */
		$reflection = new \ReflectionClass($this);
		$this->_reflection = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
	}
	
	/**
	 * init swift mailer
	 *
	 * @return object [$this]
	 */
	public function init() {
		$this->_swift['transport'] = \Swift_SmtpTransport::newInstance($this->smtp, $this->port, true === $this->ssl ? 'ssl' : '');
		$this->_swift['transport']->setUsername($this->username);
		$this->_swift['transport']->setPassword($this->password);
		
		$this->_swift['mailer'] = \Swift_mailer::newInstance($this->_swift['transport']);
		
		$this->_swift['message'] = \Swift_message::newInstance();
		
		return $this;
	}
	
	/**
	 * set sender
	 *
	 * @return object [$this]
	 */
	public function setFrom() {
		$this->_swift['message']->setFrom($this->mailFrom);
		
		return $this;
	}
	
	/**
	 * set receiver
	 *
	 * @return object [$this]
	 */
	public function setTo() {
		$this->_swift['message']->setTo($this->mailTo);
		
		return $this;
	}

	/**
	 * set mail subject
	 *
	 * @return object [$this]
	 */
	public function setTitle() {
		$this->_swift['message']->setSubject($this->title);
		
		return $this;
	}
	
	/**
	 * set mail body
	 *
	 * @return object [$this]
	 */
	public function setBody() {
		$this->_swift['message']->setBody($this->message, $this->contentType, $this->charset);
		
		return $this;
	}
	
	/**
	 * send mail
	 *
	 * @return void
	 *
	 * @throws Exception $e
	 */
	public function send() {
		try {
			$this->init()->setFrom()->setTo()->setTitle()->setBody();
			$this->_swift['mailer']->send($this->_swift['message']);
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * get property if exists
	 *
	 */
	public function __get($proporty) {
		foreach ($this->_reflection as $props) {
			if ($proporty == $props->getName()) {
				return $props->getValue();
			}
		}
	}
	
	/**
	 * set property if exists
	 *
	 *
	 */
	public function __set($proporty, $data) {
		foreach ($this->_reflection as $props) {
			if ($proporty == $props->getName()) {
				$props->setValue($data);
				break;
			}
		}
	}
}