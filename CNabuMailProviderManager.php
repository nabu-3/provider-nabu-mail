<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
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

namespace providers\nabu\mail;
use nabu\core\CNabuEngine;
use nabu\core\interfaces\INabuApplication;
use nabu\messaging\descriptors\CNabuMessagingTemplateRenderInterfaceDescriptor;
use nabu\messaging\adapters\CNabuMessagingModuleManagerAdapter;
use nabu\messaging\exceptions\ENabuMessagingException;

/**
 * Class to manage nabu-mail library
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0
 * @version 3.0.0
 * @package \providers\nabu\mail
 */
class CNabuMailProviderManager extends CNabuMessagingModuleManagerAdapter
{
    /** @var CNabuMessagingTemplateRenderInterfaceDescriptor Messaging Account descriptor. */
    private $nb_messaging_template_render_descriptor = null;

    /**
     * Default constructor.
     */
    public function __construct()
    {
        parent::__construct(NABU_MAIL_VENDOR_KEY, NABU_MAIL_MODULE_KEY);
    }

    public function enableManager()
    {
        $nb_engine = CNabuEngine::getEngine();

        $this->nb_messaging_template_render_descriptor = new CNabuMessagingTemplateRenderInterfaceDescriptor(
            $this,
            'NabuMailTemplateRender',
            'Nabu Mail Template Render'
        );

        $nb_engine->registerProviderInterface($this->nb_messaging_template_render_descriptor);

        return true;
    }

    public function registerApplication(INabuApplication $nb_application)
    {
        return $this;
    }

    public function createServiceInterface(string $name)
    {
    }

    public function createTemplateRenderInterface(string $name)
    {
        $nb_engine = CNabuEngine::getEngine();
        $fullname = __NAMESPACE__ . "\\templates\\renders\\$name";

        if ($nb_engine->preloadClass($fullname)) {
            $interface = new $fullname($this);
            if ($this->registerTemplateRenderInterface($interface)) {
                return $interface;
            } else {
                throw new ENabuMessagingException(
                    ENabuMessagingException::ERROR_TEMPLATE_RENDER_CANNOT_BE_INSTANTIATED,
                    array($name)
                );
            }
        } else {
            throw new ENabuMessagingException(
                ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_CLASS_NAME,
                array($name)
            );
        }
    }
}
