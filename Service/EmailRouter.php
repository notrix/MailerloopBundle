<?php

namespace Notrix\MailerloopBundle\Service;

use Notrix\MailerloopBundle\Exception\MailerloopRouterException;

/**
 * Notrix\MailerloopBundle\Service\EmailRouter
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class EmailRouter
{
    /**
     * @var array
     */
    protected $emailMap = array();

    /**
     * Class constructor
     *
     * @param array|null $emailMap
     */
    public function __construct($emailMap = null)
    {
        if ($emailMap) {
            $this->emailMap = $emailMap;
        }
    }

    /**
     * Adds email template to map
     *
     * @param int    $templateId
     * @param string $slug
     * @param string $locale
     */
    public function addTemplate($templateId, $slug, $locale = null)
    {
        $node = $locale ? array($locale => $templateId) : $templateId;
        if (isset($this->emailMap[$slug]) && $locale) {
            $this->emailMap[$slug] = array_merge($this->emailMap[$slug], $node);
        } else {
            $this->emailMap[$slug] = $node;
        }
    }

    /**
     * Gets email template id by configured slug
     *
     * @param string $slug
     * @param string $locale
     *
     * @return int
     *
     * @throws \Notrix\MailerloopBundle\Exception\MailerloopRouterException
     */
    public function getTemplate($slug, $locale = null)
    {
        if (!isset($this->emailMap[$slug])) {
            throw new MailerloopRouterException(sprintf('Template for slug "%s" is not configured', $slug));
        }
        if (is_array($this->emailMap[$slug])) {
            if (!isset($this->emailMap[$slug][$locale])) {
                throw new MailerloopRouterException(
                    sprintf('Template for slug "%s" and locale "%s" is not configured', $slug, $locale)
                );
            }
            return $this->emailMap[$slug][$locale];
        } else {
            return $this->emailMap[$slug];
        }
    }
}
