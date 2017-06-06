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

use providers\nabu\mail\CNabuMailProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 0.0.1
 * @version 0.0.1
 * @package \providers\phpmailer\phpmailer
 */

define ('NABU_MAIL_VENDOR_KEY', 'NabuMail');
define ('NABU_MAIL_MODULE_KEY', 'NabuMail');
define ('NABU_MAIL_MANAGER_KEY', 'CNabuMailProviderManager');
define ('NABU_MAIL_PROVIDER_PATH', dirname(__FILE__));

$nb_engine->registerProviderManager(new CNabuMailProviderManager());
