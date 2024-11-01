<?php


class Wpgenious_Widgets extends \WP_Widget
{
    /**
     * WidgetController constructor.
     *
     * @param string $id_base
     * @param string $name
     * @param string $description
     */
    public function __construct( $id_base, $name, $description = '' ) {
        parent::__construct( $id_base, $name, array( 'description' => $description ) );
    }


    public static function init() {
        add_action(
            'widgets_init',
            function () {
                $dir = __DIR__ . '/widgets';
                $controllers = scandir( $dir, false );

                foreach ( $controllers as $controller ) {
                    if (is_file($dir . '/' . $controller)) {
                        $class = str_replace(['-', '.php'], ['_', ''], $controller);
                        $class = ucfirst($class);

                        require_once $dir . '/' . $controller;

                        register_widget($class);
                    }
                }
            }
        );
    }
}