<?php

/**
 * Register all actions, filters, and shortcodes for the plugin.
 *
 * This class maintains three collections of hooks: actions, filters, and shortcodes.
 * It provides methods to add new hooks to these collections and a method to register
 * them all with WordPress.
 *
 * @since      1.0.0
 * @package    WPVetPlugin
 * @subpackage WPVetPlugin/includes
 * @author     Gemini
 */
class WPVetPlugin_Loader {

    /**
     * The array of actions registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    /**
     * The array of shortcodes registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $shortcodes    The shortcodes registered with WordPress to fire when the plugin loads.
     */
    protected $shortcodes;

    /**
     * Initialize the collections used to maintain the actions, filters, and shortcodes.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @since    1.0.0
     * @param    string    $hook             The name of the WordPress action that is being registered.
     * @param    object    $component        A reference to the instance of the object on which the action is defined.
     * @param    string    $callback         The name of the function definition on the $component.
     * @param    int       $priority         Optional. The priority at which the function should be fired. Default is 10.
     * @param    int       $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add_hook($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @since    1.0.0
     * @param    string    $hook             The name of the WordPress filter that is being registered.
     * @param    object    $component        A reference to the instance of the object on which the filter is defined.
     * @param    string    $callback         The name of the function definition on the $component.
     * @param    int       $priority         Optional. The priority at which the function should be fired. Default is 10.
     * @param    int       $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add_hook($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }
    
    /**
     * Add a new shortcode to the collection to be registered with WordPress.
     *
     * @since    1.0.0
     * @param    string    $tag              The name of the shortcode tag.
     * @param    object    $component        A reference to the instance of the object on which the shortcode is defined.
     * @param    string    $callback         The name of the function definition on the $component.
     */
    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes[] = array(
            'tag'       => $tag,
            'component' => $component,
            'callback'  => $callback,
        );
    }

    /**
     * A utility function that is used to register the actions and filters into a single
     * collection.
     *
     * @since    1.0.0
     * @access   private
     * @param    array     $hooks            The collection of hooks (actions or filters).
     * @param    string    $hook             The name of the WordPress hook that is being registered.
     * @param    object    $component        A reference to the instance of the object on which the hook is defined.
     * @param    string    $callback         The name of the function definition on the $component.
     * @param    int       $priority         The priority at which the function should be fired.
     * @param    int       $accepted_args    The number of arguments that should be passed to the $callback.
     * @return   array    The collection of hooks.
     */
    private function add_hook($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );
        return $hooks;
    }

    /**
     * Register the filters, actions, and shortcodes with WordPress.
     *
     * This function iterates over the actions, filters, and shortcodes collections
     * and registers each one with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->shortcodes as $shortcode) {
            add_shortcode($shortcode['tag'], array($shortcode['component'], $shortcode['callback']));
        }
    }
}
