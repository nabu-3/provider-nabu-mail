<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace providers\nabu\mail\templates\renders;
use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\lang\CNabuLanguage;
use nabu\data\messaging\CNabuMessagingTemplate;
use nabu\data\messaging\CNabuMessagingTemplateLanguage;
use nabu\messaging\exceptions\ENabuMessagingException;
use nabu\messaging\interfaces\INabuMessagingTemplateRenderInterface;
use providers\nabu\mail\CNabuMailProviderManager;

/**
 * Class to use Nabu Mail as a Template Render Interface for Messaging.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0
 * @version 3.0.0
 * @package \providers\nabu\mail\templates\renders
 */
class CNabuMailTemplateBasicRenderInterface extends CNabuObject implements INabuMessagingTemplateRenderInterface
{
    /** @var CNabuMailProviderManager Manager instance associated with this interface. */
    private $nabu_mail_manager = null;
    /** @var CNabuMessagingTemplate Template instance to render contents.*/
    private $nb_messaging_template = null;
    /** @var CNabuLanguage Language instance to render contents. */
    private $nb_language = null;

    /**
     * Default constructor.
     * @param CNabuMailProviderManager $nabu_mail_manager Nabu Mail Manager that owns this instance.
     */
    public function __construct(CNabuMailProviderManager $nabu_mail_manager)
    {
        $this->nabu_mail_manager = $nabu_mail_manager;
    }

    public function init()
    {
        return true;
    }

    public function finish()
    {
    }

    public function setTemplate(CNabuMessagingTemplate $nb_messaging_template): INabuMessagingTemplateRenderInterface
    {
        $this->nb_messaging_template = $nb_messaging_template;

        return $this;
    }

    public function setLanguage(CNabuLanguage $nb_language) : INabuMessagingTemplateRenderInterface
    {
        $this->nb_language = $nb_language;

        return $this;
    }

    public function createSubject(array $params = null) : string
    {
        if ($this->nb_messaging_template instanceof CNabuMessagingTemplate) {
            if ($this->nb_language instanceof CNabuLanguage &&
                ($nb_translation = $this->nb_messaging_template->getTranslation($this->nb_language)) instanceof CNabuMessagingTemplateLanguage
            ) {
                return $this->applyTemplate($nb_translation->getSubject(), $params);
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_LANGUAGE_REQUIRED);
            }
        } else {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_TEMPLATE_REQUIRED);
        }
    }

    public function createBodyHTML(array $params = null) : string
    {
        if ($this->nb_messaging_template instanceof CNabuMessagingTemplate) {
            if ($this->nb_language instanceof CNabuLanguage &&
                ($nb_translation = $this->nb_messaging_template->getTranslation($this->nb_language)) instanceof CNabuMessagingTemplateLanguage
            ) {
                return is_string($html = $nb_translation->getHTML()) ? $this->applyTemplate($html, $params) : '';
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_LANGUAGE_REQUIRED);
            }
        } else {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_TEMPLATE_REQUIRED);
        }
    }

    public function createBodyText(array $params = null) : string
    {
        if ($this->nb_messaging_template instanceof CNabuMessagingTemplate) {
            if ($this->nb_language instanceof CNabuLanguage &&
                ($nb_translation = $this->nb_messaging_template->getTranslation($this->nb_language)) instanceof CNabuMessagingTemplateLanguage
            ) {
                return is_string($html = $nb_translation->getText()) ? $this->applyTemplate($html, $params) : '';
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_LANGUAGE_REQUIRED);
            }
        } else {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_TEMPLATE_REQUIRED);
        }
    }

    /**
     * Applies a pattern with a set of optional parameters replacing macros for their values.
     * @param string $pattern Pattern string to be applied.
     * @param array|null $params Optional parameters to use inside the pattern.
     * @return string Returns the formed string after combine pattern and params.
     */
    private function applyTemplate(string $pattern, array $params = null)
    {
        $retval = $pattern;

        if (count($params) > 0) {
            $regexpr = '/(\$\$[a-zA-Z][a-zA-Z0-9_]*\$\$)/';
            $macros = preg_grep($regexpr, preg_split($regexpr, $pattern, 0, PREG_SPLIT_DELIM_CAPTURE));
            if (count($macros) > 0) {
                array_walk($macros, function(&$item, $key) {
                    $item = substr($item, 2, strlen($item) - 4);
                });
                $macros = array_intersect(array_unique($macros), array_keys($params));
                foreach ($macros as $macro) {
                    $value = $params[$macro];
                    $retval = str_replace("\$\$$macro\$\$", $value, $retval);
                }
            }
        }

        return $retval;
    }
}
