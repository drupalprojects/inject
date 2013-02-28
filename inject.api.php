<?php

/**
 * @file
 * Hooks provided by the inject system for container configuration support.
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Configures the container after boot.
 *
 * This hook is triggered at the end of the boot process, just before hook_init
 * is about to start.
 *
 * Because this hook can is triggered in a bootstrap session, it is recommended
 * to dump the configuration on disk. Building the container through this hook
 * is a very expensive operation and can take down your web-site if used in
 * production.
 *
 * @param ContainerBuilder $container
 *   The container builder object.
 */
function hook_inject_boot(ContainerBuilder $container) {
  $container->setParameter('mailer.transport', 'sendmail');
  $container->register('mailer', 'Mailer')
    ->addArgument('%mailer.transport%');

  $container->register('newsletter_manager', 'NewletterManager')
    ->addArgument(new Reference('mailer'));
}

/**
 * Configures the container after init.
 *
 * This hook is triggered at the end of the init process.
 *
 * @param ContainerBuilder $container
 *   The container builder object.
 */
function hook_inject_init(ContainerBuilder $container) {
  $loader = new XmlFileLoader(
    $container,
    new FileLocator(__DIR__.'/../config')
  );

  $loader->load('services.xml');

  if ($config['advanced']) {
    $loader->load('advanced.xml');
  }
}

/**
 * @} End of "addtogroup hooks".
 */
