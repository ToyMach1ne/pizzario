<?php
/**
 * File containing WPShowCase's functions
 */
if ( !function_exists( 'value' ) ) {

    /**
     * WPShowCase's function for getting data from arrays
     */
    function value( $array, $key, $default = '', $ignore_empty_string = false ) {
        if ( isset( $array[ $key ] ) ) {
            if ( !$ignore_empty_string || $array[ $key ] != '' ) {
                return $array[ $key ];
            }
        }
        return $default;
    }

}

if ( !function_exists( 'wpshowcase_div' ) ) {

    function wpshowcase_div( $values, $parameters ) {
        print '<div ';
        wpshowcase_display_parameters( $parameters );
        print '>';
        wpshowcase_print_items( $values, $parameters );
        print '</div>';
    }

}

if ( !function_exists( 'wpshowcase_select' ) ) {

    /**
     * WPShowCase's function for displaying a select
     */
    function wpshowcase_select( $options, $value, $label, $name,
            $params = array() ) {
        if ( !empty( $options ) ) {
            print '<label>' . $label . '';
            print '<select name="' . $name . '"';
            wpshowcase_display_parameters( $params );
            print ' >';
            if ( !empty( $options ) ) {
                foreach ( $options as $name => $option ) {
                    print '<option value="' . $name . '"';
                    if ( $name == $value ) {
                        print 'selected ';
                    }
                    print '>' . $option . '</option>';
                }
            }
            print '</select></label>';
        }
    }

}

if ( !function_exists( 'wpshowcase_kses_post' ) ) {

    /**
     * Sanitize data
     */
    function wpshowcase_kses_post( $data, $remove_ids = false ) {
        if ( is_array( $data ) ) {
            foreach ( $data as $key => $value ) {
                if ( $remove_ids && !is_numeric( $key ) && strpos( $key, 'new_' )
                        === 0 && strpos( $key, '_id' ) !== false ) {
                    unset( $data[ $key ] );
                    continue;
                }
                $data[ $key ] = wpshowcase_kses_post( $value, $remove_ids );
            }
            return $data;
        }
        return wp_kses_post( $data );
    }

}

if ( !function_exists( 'wpshowcase_select_and_display_suboptions' ) ) {

    /**
     * A dropdown which displays suboptions depedning on what option is selected
     */
    function wpshowcase_select_and_display_suboptions( $values, $parameters ) {
        $options = value( $parameters, 'items' );
        if ( empty( $options ) ) {
            return;
        }
        print '<div class="select-and-display-suboptions-wrapper">';
        ob_start();
        $options_for_select = array();
        foreach ( $options as $option_id => $option ) {
            $option_values = value( $values, $option_id, array() );
            $options_for_select[ $option_id ] = value( $option, 'label' );
            $class = str_replace( '_', '-', $option_id );
            $name = value( $parameters, 'name' ) . '[' . $option_id . ']';
            $option[ 'name' ] = $name;
            print '<div class="select-and-display-suboptions-suboption select-and-display-suboptions-' . $class . '">';
            wpshowcase_print_items( $option_values, $option );
            print '</div>';
        }
        $suboption_html = ob_get_clean();
        if ( empty( $parameters[ 'class' ] ) ) {
            $parameters[ 'class' ] = 'select-and-display-suboptions';
        } else {
            $parameters[ 'class' ].=' select-and-display-suboptions';
        }
        $dropdown_index = array_keys( value( $parameters, 'dropdown', array() ) )[ 0 ];
        $dropdown_name = $parameters[ 'name' ] . '[' . $dropdown_index . ']';
        $dropdown_params = value( value( $parameters, 'dropdown', array() ),
                $dropdown_index );
        $dropdown_parameters = array_merge( $dropdown_params,
                array( 'options' => $options_for_select, 'name' => $dropdown_name,
            'class' => 'select-and-display-suboptions' ) );
        wpshowcase_print_html( value( $values, $dropdown_index ),
                $dropdown_parameters );
        print $suboption_html;
        print '</div>';
        wp_enqueue_script( 'jquery' );
        wp_enqueue_style( 'wpshowcase-functions',
                plugins_url( '../wpshowcase/wpshowcase-functions.css', __FILE__ ) );
        wp_enqueue_script( 'wpshowcase-functions',
                plugins_url( '../wpshowcase/wpshowcase-functions.js', __FILE__ ) );
    }

}

if ( !function_exists( 'wpshowcase_display_parameters' ) ) {

    /**
     * html parameters
     */
    function wpshowcase_display_parameters( $parameters ) {
        $html_parameters = array(
            'title' => 'title',
            'type' => 'type',
            'value' => 'value',
            'onclick' => 'onclick',
            'name' => 'name',
            'value' => 'value',
            'class' => 'class',
            'id' => 'id',
            'checked' => 'checked',
            'min' => 'min',
            'max' => 'max',
            'selected' => 'selected',
            'disabled' => 'disabled',
            'maxlength' => 'maxlength',
            'pattern' => 'pattern',
            'readonly' => 'readonly',
            'required' => 'required',
            'size' => 'size',
            'step' => 'step',
            'rows' => 'rows',
            'cols' => 'cols',
        );
        foreach ( $parameters as $id => $value ) {
            if ( !empty( $html_parameters[ $id ] ) ) {
                print " $id=\"$value\" ";
            }
        }
    }

}

if ( !function_exists( 'wpshowcase_combobox' ) ) {

    /**
     * Displays a combobox
     */
    function wpshowcase_combobox( $values, $parameters ) {
        if ( !empty( $parameters[ 'class' ] ) ) {
            $parameters[ 'class' ].=' combobox';
        } else {
            $parameters[ 'class' ] = 'combobox';
        }
        wpshowcase_dropdown( $values, $parameters );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-tooltip' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_script( 'jquery-ui-button' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-position' );
        wp_enqueue_script( 'jquery-ui-menu' );
        wp_enqueue_script( 'wp-a11y' );
        wp_enqueue_style( 'jquery-ui',
                plugins_url() . '/combobox/assets/jquery-ui/jquery-ui.css' );
    }

}

if ( !function_exists( 'wpshowcase_dropdown' ) ) {

    /**
     * Displays a dropdown
     */
    function wpshowcase_dropdown( $values, $parameters ) {
        print value( $parameters, 'label' ) . '<br>';
        $values_array = array();
        $multiple = value( $parameters, 'multiple' );
        if ( !is_array( $values ) ) {
            $values = array( $values );
        }
        if ( !empty( $values ) ) {
            foreach ( $values as $selected_term ) {
                if ( !is_array( $selected_term ) ) {
                    $values_array[ $selected_term ] = $selected_term;
                }
            }
        }
        print '<select ';
        wpshowcase_display_parameters( $parameters );
        if ( !empty( $multiple ) ) {
            print '[]" multiple ';
        } else {
            print '" ';
        }
        print '>';
        $options = value( $parameters, 'options' );
        foreach ( $options as $option_id => $option_label ) {
            print '<option value="' . $option_id . '"';
            if ( isset( $values_array[ $option_id ] ) ) {
                print ' selected ';
            }
            print '>' . $option_label . '</option>';
        }
        print '</select>';
    }

}

if ( !function_exists( 'wpshowcase_textarea' ) ) {

    /**
     * Displays a text area
     */
    function wpshowcase_textarea( $values, $parameters ) {
        print value( $parameters, 'label' ) . '<br>';
        if ( empty( $values ) ) {
            $values = '';
        } elseif ( is_array( $values ) ) {
            $values = array_pop( $values );
        }
        ?>
        <textarea <?php wpshowcase_display_parameters( $parameters ); ?>><?php print $values; ?></textarea>
        <?php
    }

}

if ( !function_exists( 'wpshowcase_editor' ) ) {

    /**
     * Displays a tinymce editor
     */
    function wpshowcase_editor( $values, $parameters ) {
        $id = value( $parameters, 'id' );
        if ( empty( $id ) ) {
            $id = 'editor' . sanitize_title( microtime() );
        }
        $editor_parameters = array(
            'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ),
        );
        if ( !empty( $parameters[ 'name' ] ) ) {
            $editor_parameters[ 'textarea_name' ] = $parameters[ 'name' ];
        }
        if ( !empty( $parameters[ 'class' ] ) ) {
            $editor_parameters[ 'class' ] = $parameters[ 'class' ];
        }
        wp_editor( $values, $id, $editor_parameters );
    }

}

if ( !function_exists( 'wpshowcase_input' ) ) {

    /**
     * Text input, number input, etc
     */
    function wpshowcase_input( $values, $parameters ) {
        if ( empty( $values ) ) {
            $values = '';
        }
        if ( is_array( $values ) && !empty( $values[ 0 ] ) ) {
            $values = $values[ 0 ];
        }
        if ( value( $parameters, 'type' ) == 'checkbox' ) {
            print '<label class="' . value( $parameters, 'label-class' ) . '">' . value( $parameters,
                            'label_before' ) . '<input ';
            wpshowcase_display_parameters( $parameters );
            print ' value="yes"';
            if ( $values == 'yes' ) {
                print 'checked="checked" ';
            }
            print '/>' . value( $parameters, 'label' ) . value( $parameters,
                            'label_after' ) . '</label>';
        } else {
            print '<label class="' . value( $parameters, 'label-class' ) . '">' . value( $parameters,
                            'label' ) . '<input ';
            wpshowcase_display_parameters( $parameters );
            print ' value="' . $values . '" />' . value( $parameters,
                            'label_after' ) . '</label>';
        }
    }

}

if ( !function_exists( 'wpshowcase_print_html' ) ) {

    /**
     * Prints an element
     */
    function wpshowcase_print_html( $values, $parameters ) {
        if ( !empty( $parameters[ 'html_before' ] ) ) {
            print $parameters[ 'html_before' ];
        }
        if ( empty( $values ) && !empty( $parameters[ 'default' ] ) ) {
            $values = $parameters[ 'default' ];
        }
        $element_type = 'wpshowcase_' . value( $parameters, 'element_type' );
        if ( function_exists( $element_type ) ) {
            $element_type( $values, $parameters );
        }
    }

}

$wpshowcase_form_items = array(
    'input' => 'input', 'select' => 'select', 'dropdown' => 'dropdown', 'textarea' => 'textarea',
);

if ( !function_exists( 'wpshowcase_print_items' ) ) {

    /**
     * Prints an array's items
     */
    function wpshowcase_print_items( $values, $parameters_with_items ) {
        global $wpshowcase_form_items;
        $items = value( $parameters_with_items, 'items' );
        if ( !empty( $items ) ) {
            foreach ( $items as $item_id => $item_value ) {
                $value = value( $values, $item_id, array() );
                if ( !empty( $item_value[ 'element_type' ] ) && !empty( $wpshowcase_form_items[ $item_value[ 'element_type' ] ] ) ) {
                    $item_value[ 'name' ] = value( $parameters_with_items,
                                    'name' ) . '[' . $item_id . ']';
                }
                wpshowcase_print_html( $value, $item_value );
            }
        }
    }

}

if ( !function_exists( 'wpshowcase_set_option' ) ) {

    /**
     * Sets an option
     */
    function wpshowcase_set_option( $option_name, $option ) {
        update_option( $option_name, $option );
    }

}

if ( !function_exists( 'wpshowcase_set_settings' ) ) {

    /**
     * Gets an option
     */
    function wpshowcase_set_settings( $option_name, $option ) {
        update_option( 'wpshowcase_' . $option_name, $option );
    }

}

if ( !function_exists( 'wpshowcase_get_settings' ) ) {

    /**
     * Gets settings
     */
    function wpshowcase_get_settings( $option_name ) {
        return get_option( 'wpshowcase_' . $option_name );
    }

}



if ( !function_exists( 'wpshowcase_get_default_values' ) ) {

    /**
     * Gets default values from settings
     */
    function wpshowcase_get_default_values( $default_array, $settings ) {
        $default_found = false;
        if ( !empty( $settings[ 'default' ] ) ) {
            return $settings[ 'default' ];
        }
        if ( !empty( $settings[ 'items' ] ) ) {
            foreach ( $settings[ 'items' ] as $item_id => $item ) {
                if ( !empty( $item[ 'default' ] ) ) {
                    $default_array[ $item_id ] = $item[ 'default' ];
                    $default_found = true;
                } else {
                    $default = wpshowcase_get_default_values( array(), $item );
                    if ( !empty( $default ) ) {
                        $default_array[ $item_id ] = $default;
                        $default_found = true;
                    }
                }
            }
        }
        if ( !empty( $settings[ 'dropdown' ] ) ) {
            foreach ( $settings[ 'dropdown' ] as $item_id => $item ) {
                if ( !empty( $item[ 'default' ] ) ) {
                    $default_array[ $item_id ] = $item[ 'default' ];
                    $default_found = true;
                } else {
                    $default = wpshowcase_get_default_value( $default, $item,
                            value( $item, 'dropdown', array() ) );
                    if ( !empty( $default ) ) {
                        $default_array[ $item_id ] = $default;
                        $default_found = true;
                    }
                }
            }
        }
        if ( !$default_found ) {
            return array();
        }
        return $default_array;
    }

}

if ( !function_exists( 'wpshowcase_array_merge_recursive' ) ) {

    /**
     * Merges two arrays, the second array overwrites the first array
     */
    function wpshowcase_array_merge_recursive( $array1, $array2 ) {
        if ( !is_array( $array2 ) || !is_array( $array1 ) ) {
            return $array2;
        }
        if ( empty( $array2 ) ) {
            return $array1;
        }
        foreach ( $array2 as $key => $value ) {
            if ( is_numeric( $key ) ) {
                $i = $key;
                while ( isset( $array1[ $key ] ) ) {
                    $i = $i + 1;
                }
                $array1[ $key ] = $value;
            } elseif ( isset( $array1[ $key ] ) ) {
                $array1[ $key ] = wpshowcase_array_merge_recursive( $array1[ $key ],
                        $array2[ $key ] );
            } else {
                $array1[ $key ] = $array2[ $key ];
            }
        }
        return $array1;
    }

}

if ( !function_exists( 'wpshowcase_get_option' ) ) {

    /**
     * Gets an option using the default values of the settings
     */
    function wpshowcase_get_option( $option_name ) {
        $option = get_option( $option_name, array() );
        $settings = get_option( 'wpshowcase_' . $option_name );
        $default_array = wpshowcase_get_default_values( array(), $settings );
        return wpshowcase_array_merge_recursive( $default_array, $option );
    }

}