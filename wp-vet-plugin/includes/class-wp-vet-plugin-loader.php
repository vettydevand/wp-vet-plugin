<?php

/**
 * Register all actions, filters, and shortcodes for the plugin.
 */
class WPVetPlugin_Loader {

    protected $actions;
    protected $filters;
    protected $shortcodes;

    /**
     * Initialize the collections used to maintain the actions, filters, and shortcodes.
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add_hook($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add_hook($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new shortcode to the collection to be registered with WordPress.
     *
     * @param string $tag              The name of the shortcode tag.
     * @param object $component        A reference to the instance of the object on which the shortcode is defined.
     * @param string $callback         The name of the function definition on the $component.
     */
    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes[] = array(
            'tag'       => $tag,
            'component' => $component,
            'callback'  => $callback,
        );
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
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
