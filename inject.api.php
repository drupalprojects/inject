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
 * @deprecated Configures the container after boot.
 *
 * This hook is triggered at the end of the boot process, just before hook_init
 * is about to start.
 *
 * Because this hook can be triggered in a bootstrap session, it is recommended
 * to dump the configuration on disk to avoid calling it on each page request.
 *
 * This hook will be removed in favor of hook_inject_build(ContainerBuilder) in
 * the stable 2.0 release.
 *
 * @see hook_inject_build().
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
 * @deprecated Configures the container after init.
 *
 * This hook is triggered at the end of the init process, it is recommended to
 * dump the configuration on disk to avoid calling it on each page request.
 *
 * This hook will be removed in favor of hook_inject_build(ContainerBuilder) in
 * the stable 2.0 release.
 *
 * @see hook_inject_build().
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
 * Configures the container.
 *
 * It is only ever called once when the cache is empty.
 *
 * This hook can be implemented in a custom module.inject.inc file to register
 * compilation passes,services, other extensions, ...
 *
 * @param ContainerBuilder $container
 */
function hook_inject_build(ContainerBuilder $container) {
  $loader = new XmlFileLoader(
    $container,
    new FileLocator(__DIR__.'/../config')
  );

  $loader->load('services.xml');

  if ($config['advanced']) {
    $loader->load('advanced.xml');
  }

  $container->setParameter('mailer.transport', 'sendmail');
  $container->register('mailer', 'Mailer')
    ->addArgument('%mailer.transport%');

  $container->register('newsletter_manager', 'NewletterManager')
    ->addArgument(new Reference('mailer'));
}

/**
 * @} End of "addtogroup hooks".
 */
