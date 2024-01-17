<?php
namespace Drupal\kst;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\kst\Annotation\KstPlugin;
/**
 * A plugin manager for kst plugins.
 *
 * The KstPluginManager class extends the DefaultPluginManager to provide
 * a way to manage kst plugins. A plugin manager defines a new plugin type
 * and how instances of any plugin of that type will be discovered, instantiated
 * and more.
 */
class KstPluginManager extends DefaultPluginManager {
  /**
   * Creates the discovery object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    // We replace the $subdir parameter with our own value.
    // This tells the plugin manager to look for kst plugins in the
    // 'src/Plugin/Kst' subdirectory of any enabled modules. This also
    // serves to define the PSR-4 subnamespace in which kst plugins will
    // live. Modules can put a plugin class in their own namespace such as
    // Drupal\{module_name}\Plugin\Kst\MyKstPlugin.
    $subdir = 'Plugin/Kst';
    // The name of the interface that plugins should adhere to. Drupal will
    // enforce this as a requirement. If a plugin does not implement this
    // interface, Drupal will throw an error.
    $plugin_interface = KstPluginInterface::class;
    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = KstPlugin::class;
    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);
    // This allows the plugin definitions to be altered by an alter hook. The
    // parameter defines the name of the hook, thus: hook_kst_info_alter().
    $this->alterInfo('kst_info');
    // This sets the caching method for our plugin definitions.
    $this->setCacheBackend($cache_backend, 'kst_info');
  }
}