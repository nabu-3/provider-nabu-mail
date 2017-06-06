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

namespace providers\nabu\mail\templates\renders;
use nabu\core\CNabuObject;
use nabu\messaging\interfaces\INabuMessagingTemplateRenderInterface;
use providers\nabu\mail\CNabuMailProviderManager;

/**
 * Class to use Nabu Mail as a Template Render Interface for Messaging.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0
 * @version 3.0.0
 * @package \providers\nabu\mail\templates\renders
 */
class CNabuMailTemplateRenderInterface extends CNabuObject implements INabuMessagingTemplateRenderInterface
{
    /** @var CNabuMailProviderManager Manager instance associated with this interface. */
    private $nabu_mail_manager = null;

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
}
