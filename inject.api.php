<?php

/**
 * @file
 * Hooks provided by the inject system for container configuration support.
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Configures the container.
 *
 * This hook is triggered at the end of the boot process, just before hook_init
 * is about to start.
 *
 * Because this hook can be triggered in a bootstrap session, it is recommanded
 * to dump the configuration on disk. Building the container through hooks is a
 * very expensive operation and can take down your web-site if used in production.
 *
 * @param ContainerBuilder $container
 *   The container builder object.
 */
function hook_container(ContainerBuilder $container) {
  $container->setParameter('mailer.transport', 'sendmail');
  $container->register('mailer', 'Mailer')
    ->addArgument('%mailer.transport%');

  $container->register('newsletter_manager', 'NewletterManager')
    ->addArgument(new Reference('mailer'));
}

/**
 * @} End of "addtogroup hooks".
 */
