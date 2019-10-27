<?php
/**
 * @author Daniel Berthereau
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2.1-en.txt
 * @copyright Daniel Berthereau, 2018-2019
 *
 * Copyright 2018-2019 Daniel Berthereau
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software. You can use, modify and/or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software’s author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user’s attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software’s suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */
namespace Verovio;

if (!class_exists(\Generic\AbstractModule::class)) {
    require file_exists(dirname(__DIR__) . '/Generic/AbstractModule.php')
        ? dirname(__DIR__) . '/Generic/AbstractModule.php'
        : __DIR__ . '/src/Generic/AbstractModule.php';
}

use Generic\AbstractModule;
use Omeka\Module\Exception\ModuleCannotInstallException;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module extends AbstractModule
{
    const NAMESPACE = __NAMESPACE__;

    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);

        $js = __DIR__ . '/asset/vendor/verovio/js/verovio-toolkit.js';
        if (!file_exists($js)) {
            $t = $serviceLocator->get('MvcTranslator');
            throw new ModuleCannotInstallException(
                $t->translate('The Verovio library should be installed.') // @translate
                    . ' ' . $t->translate('See module’s installation documentation.') // @translate
            );
        }

        parent::install($serviceLocator);
        $this->updateWhitelists();
    }

    protected function updateWhitelist()
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');

        $whitelist = $settings->get('media_type_whitelist', []);
        $keys = [
            'application/vnd.mei+xml',
            'application/vnd.recordare.musicxml',
            'image/svg+xml',
            'text/xml',
        ];
        foreach ($keys as $key) {
            if (!in_array($key, $whitelist)) {
                $whitelist[] = $key;
            }
        }
        $settings->set('media_type_whitelist', $whitelist);

        $whitelist = $settings->get('extension_whitelist', []);
        $keys = [
            'mei',
            'musicxml',
            'mxl',
            'svg',
            'xml',
        ];
        foreach ($keys as $key) {
            if (!in_array($key, $whitelist)) {
                $whitelist[] = $key;
            }
        }
        $settings->set('extension_whitelist', $whitelist);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            \Omeka\Form\SettingForm::class,
            'form.add_elements',
            [$this, 'handleMainSettings']
        );
        $sharedEventManager->attach(
            \Omeka\Form\SettingForm::class,
            'form.add_input_filters',
            [$this, 'handleMainSettingsFilters']
        );

        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_elements',
            [$this, 'handleSiteSettings']
        );
        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_input_filters',
            [$this, 'handleSiteSettingsFilters']
        );
    }

    public function handleMainSettingsFilters(Event $event)
    {
        $inputFilter = $event->getParam('inputFilter');
        $inputFilter->get('verovio')
            ->add([
                'name' => 'verovio_source_property',
                'required' => false,
            ]);
    }

    public function handleSiteSettingsFilters(Event $event)
    {
        $inputFilter = $event->getParam('inputFilter');
        $inputFilter->get('verovio')
            ->add([
                'name' => 'verovio_template',
                'required' => false,
            ]);
    }
}
